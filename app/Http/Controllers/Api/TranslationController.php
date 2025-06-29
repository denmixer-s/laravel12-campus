<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslationController extends Controller
{
    public function batchTranslate(Request $request)
    {
        try {
            // Debug log
            Log::info('Translation request received:', [
                'texts_count'     => count($request->input('texts', [])),
                'target_language' => $request->input('target_language'),
                'source_language' => $request->input('source_language'),
                'first_text'      => $request->input('texts.0', 'N/A')
            ]);

            // Validation
            $validated = $request->validate([
                'texts'           => 'required|array|min:1|max:100',
                'texts.*'         => 'required|string|max:5000',
                'target_language' => 'required|string|in:en,th,ja,ko,zh',
                'source_language' => 'nullable|string|in:en,th,ja,ko,zh',
            ]);

            $texts      = $validated['texts'];
            $targetLang = $validated['target_language'];
            $sourceLang = $validated['source_language'] ?? 'auto';

            // ถ้าเป็นภาษาเดียวกัน
            if ($sourceLang === $targetLang) {
                return response()->json([
                    'success'      => true,
                    'translations' => $texts,
                    'usage'        => $this->getUsageInfo(),
                ]);
            }

            // ตรวจสอบ Google Translate API Key
            $apiKey = config('services.google.translate_api_key');

            Log::info('Translation API Key Check', [
                'api_key_present' => ! empty($apiKey),
                'api_key_length'  => $apiKey ? strlen($apiKey) : 0,
                'config_path'     => 'services.google.translate_api_key',
            ]);

            $translations = [];
            $batchTexts   = array_chunk($texts, 25); // แบ่งเป็น batch ย่อย

            foreach ($batchTexts as $batch) {
                // ใช้ Google API เป็นอันดับแรก ถ้าไม่ได้ใช้ Stichoza
                Log::info('Batch translation decision', [
                    'has_api_key' => ! empty($apiKey),
                    'will_use'    => ! empty($apiKey) ? 'Google API' : 'Stichoza',
                ]);

                if (! empty($apiKey)) {
                    $batchTranslations = $this->translateBatch_Google($batch, $targetLang, $sourceLang, $apiKey);
                } else {
                    $batchTranslations = $this->translateBatch_Stichoza($batch, $targetLang, $sourceLang);
                }
                $translations = array_merge($translations, $batchTranslations);
            }

            return response()->json([
                'success'      => true,
                'translations' => $translations,
                'count'        => count($translations),
                'usage'        => $this->getUsageInfo(),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Translation API Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Translation service unavailable: ' . $e->getMessage(),
            ], 500);
        }
    }

    // 🚀 NEW: Performance Optimized Method สำหรับ Frontend ใหม่
    public function translate(Request $request)
    {
        try {
            // Debug log
            Log::info('Optimized translation request received:', [
                'texts_count'     => count($request->input('texts', [])),
                'target_language' => $request->input('target_language'),
                'source_language' => $request->input('source_language'),
                'first_text'      => $request->input('texts.0', 'N/A')
            ]);

            // ⚡ OPTIMIZED Validation: ลดขนาดและจำนวน
            $validated = $request->validate([
                'texts'           => 'required|array|min:1|max:20', // ลดจาก 100 → 20
                'texts.*'         => 'required|string|max:500',     // ลดจาก 5000 → 500
                'target_language' => 'required|string|in:en,th',
                'source_language' => 'nullable|string|in:en,th',
            ]);

            $texts      = $validated['texts'];
            $targetLang = $validated['target_language'];
            $sourceLang = $validated['source_language'] ?? 'th';

            // ถ้าเป็นภาษาเดียวกัน
            if ($sourceLang === $targetLang) {
                return response()->json([
                    'success'          => true,
                    'translations'     => $texts,
                    'cached_count'     => count($texts),
                    'translated_count' => 0,
                ]);
            }

            // 💾 OPTIMIZED: ตรวจสอบ cache ก่อน
            $translations     = [];
            $textsToTranslate = [];

            foreach ($texts as $index => $text) {
                $cacheKey = $this->getCacheKey($text, $sourceLang, $targetLang);
                $cached   = Cache::get($cacheKey);

                if ($cached) {
                    $translations[$index] = $cached;
                } else {
                    $textsToTranslate[$index] = $text;
                }
            }

            // แปลเฉพาะที่ไม่มี cache
            if (! empty($textsToTranslate)) {
                $batchTranslations = $this->translateBatchOptimized(
                    array_values($textsToTranslate),
                    $targetLang,
                    $sourceLang
                );

                // รวมผลลัพธ์และเก็บ cache
                $valueIndex = 0;
                foreach ($textsToTranslate as $originalIndex => $text) {
                    if (isset($batchTranslations[$valueIndex])) {
                        $translation                  = $batchTranslations[$valueIndex];
                        $translations[$originalIndex] = $translation;

                        // 📈 OPTIMIZED: เก็บ cache นานขึ้น 4 ชั่วโมง
                        $cacheKey = $this->getCacheKey($text, $sourceLang, $targetLang);
                        Cache::put($cacheKey, $translation, 14400);
                    }
                    $valueIndex++;
                }
            }

            // 📊 Log สำหรับ monitoring
            Log::info('Optimized translation completed', [
                'total_texts'     => count($texts),
                'cached'          => count($texts) - count($textsToTranslate),
                'translated'      => count($textsToTranslate),
                'target_language' => $targetLang,
                'user_ip'         => $request->ip(),
            ]);

            return response()->json([
                'success'          => true,
                'translations'     => $translations,
                'cached_count'     => count($texts) - count($textsToTranslate),
                'translated_count' => count($textsToTranslate),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Optimized translation error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการแปลภาษา กรุณาลองใหม่อีกครั้ง',
            ], 500);
        }
    }

    // 🚀 OPTIMIZED: การแปลที่เร็วขึ้น
    private function translateBatchOptimized($texts, $targetLang, $sourceLang)
    {
        // ตรวจสอบ Google Translate API Key
        $apiKey = config('services.google.translate_api_key');

        Log::info('Optimized translation method selection', [
            'has_api_key' => ! empty($apiKey),
            'texts_count' => count($texts),
            'method'      => ! empty($apiKey) ? 'Google API' : 'Stichoza Optimized',
        ]);

        // ใช้ Google API เป็นอันดับแรก ถ้าไม่ได้ใช้ Stichoza
        if (! empty($apiKey)) {
            return $this->translateBatch_Google($texts, $targetLang, $sourceLang, $apiKey);
        } else {
            return $this->translateBatch_SticozaOptimized($texts, $targetLang, $sourceLang);
        }
    }

    // 🔧 Helper method สำหรับ cache key
    private function getCacheKey($text, $sourceLang, $targetLang)
    {
        return 'translate_opt_' . md5($text . '_' . $sourceLang . '_' . $targetLang);
    }

    // ⚡ OPTIMIZED Stichoza method
    private function translateBatch_SticozaOptimized($texts, $targetLang, $sourceLang)
    {
        try {
            Log::info('Using optimized Stichoza translation', [
                'texts_count' => count($texts),
                'target_lang' => $targetLang,
                'source_lang' => $sourceLang,
            ]);

            // สร้าง Google Translate instance
            $tr = new GoogleTranslate();

            if ($sourceLang !== 'auto') {
                $tr->setSource($this->mapLanguageCode($sourceLang));
            }
            $tr->setTarget($this->mapLanguageCode($targetLang));

            $translations = [];

            foreach ($texts as $index => $text) {
                if (empty(trim($text))) {
                    $translations[] = $text;
                    continue;
                }

                try {
                    $translated     = $tr->translate($text);
                    $translations[] = $translated;

                    Log::debug("Optimized translation {$index}: " . substr($text, 0, 30) . " -> " . substr($translated, 0, 30));

                                    // ⚡ OPTIMIZED: ลดหน่วงเวลา 0.2 → 0.1 วินาที
                    usleep(100000); // 0.1 วินาที

                } catch (\Exception $e) {
                    Log::warning('Optimized single translation failed', [
                        'index' => $index,
                        'text'  => substr($text, 0, 30),
                        'error' => $e->getMessage(),
                    ]);

                    // ใช้ mock สำหรับข้อความนี้
                    $translations[] = $this->mockSingleTranslation($text, $targetLang);
                }
            }

            Log::info('Optimized Stichoza translation completed', [
                'input_count'   => count($texts),
                'output_count'  => count($translations),
                'sample_input'  => $texts[0] ?? 'N/A',
                'sample_output' => $translations[0] ?? 'N/A',
            ]);

            return $translations;

        } catch (\Exception $e) {
            Log::error('Optimized Stichoza translation failed', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            // ใช้ Mock Translation เป็น fallback
            Log::warning('Using mock translation as final fallback');
            return $this->mockTranslation($texts, $targetLang);
        }
    }

    private function translateBatch_Google($texts, $targetLang, $sourceLang, $apiKey)
    {
        try {
            Log::info('Using Google Translate API', [
                'texts_count'     => count($texts),
                'target_lang'     => $targetLang,
                'source_lang'     => $sourceLang,
                'api_key_present' => ! empty($apiKey),
                'api_key_length'  => $apiKey ? strlen($apiKey) : 0,
            ]);

            // สร้าง cache key
            $cacheKey = 'translate_google_' . md5(json_encode($texts) . $targetLang . $sourceLang);

            // ตรวจสอบ cache ก่อน
            if (Cache::has($cacheKey)) {
                Log::info('Using cached translation (Google API)');
                return Cache::get($cacheKey);
            }

            // เตรียม parameters สำหรับ API
            $params = [
                'key'    => $apiKey,
                'target' => $this->mapLanguageCode($targetLang),
                'format' => 'text',
            ];

            if ($sourceLang !== 'auto') {
                $params['source'] = $this->mapLanguageCode($sourceLang);
            }

            // เพิ่ม q parameters สำหรับแต่ละ text
            foreach ($texts as $text) {
                $params['q'][] = $text;
            }

            // เตรียม parameters สำหรับ GET request
            $params = [
                'key'    => $apiKey,
                'target' => $this->mapLanguageCode($targetLang),
                'format' => 'text',
            ];

            if ($sourceLang !== 'auto') {
                $params['source'] = $this->mapLanguageCode($sourceLang);
            }

            // เพิ่ม q parameters สำหรับแต่ละ text
            foreach ($texts as $text) {
                $params['q'][] = $text;
            }

            $url = 'https://translation.googleapis.com/language/translate/v2?' . http_build_query($params);

            Log::info('Google API request params', [
                'method'  => 'GET',
                'target'  => $params['target'],
                'source'  => $params['source'] ?? 'auto',
                'q_count' => count($params['q']),
            ]);

            // ใช้ cURL GET
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
            ]);

            $response  = curl_exec($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                throw new \Exception('cURL Error: ' . $curlError);
            }

            if ($httpCode !== 200) {
                Log::error('Google API HTTP Error', [
                    'code'     => $httpCode,
                    'response' => substr($response, 0, 500),
                    'endpoint' => 'https://translation.googleapis.com/language/translate/v2',
                ]);
                throw new \Exception('HTTP Error: ' . $httpCode);
            }

            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON decode error: ' . json_last_error_msg());
            }

            if (! isset($data['data']['translations'])) {
                throw new \Exception('Invalid response structure from Google API');
            }

            $translations = array_map(function ($translation) {
                return $translation['translatedText'];
            }, $data['data']['translations']);

            Log::info('Google API translation successful', [
                'input_count'  => count($texts),
                'output_count' => count($translations),
            ]);

            // เก็บใน cache 1 ชั่วโมง
            Cache::put($cacheKey, $translations, 3600);

            return $translations;

        } catch (\Exception $e) {
            Log::warning('Google API failed, falling back to Stichoza', [
                'error' => $e->getMessage(),
            ]);

            // Fallback ไปใช้ Stichoza
            return $this->translateBatch_Stichoza($texts, $targetLang, $sourceLang);
        }
    }

    private function translateBatch_Stichoza($texts, $targetLang, $sourceLang)
    {
        try {
            // สร้าง cache key
            $cacheKey = 'translate_stichoza_' . md5(json_encode($texts) . $targetLang . $sourceLang);

            // ตรวจสอบ cache ก่อน
            if (Cache::has($cacheKey)) {
                Log::info('Using cached translation (stichoza)');
                return Cache::get($cacheKey);
            }

            Log::info('Translating with stichoza/google-translate-php', [
                'texts_count' => count($texts),
                'target_lang' => $targetLang,
                'source_lang' => $sourceLang,
            ]);

            // สร้าง Google Translate instance
            $tr = new GoogleTranslate();

            if ($sourceLang !== 'auto') {
                $tr->setSource($this->mapLanguageCode($sourceLang));
            }
            $tr->setTarget($this->mapLanguageCode($targetLang));

            $translations = [];

            foreach ($texts as $index => $text) {
                if (empty(trim($text))) {
                    $translations[] = $text;
                    continue;
                }

                try {
                    $translated     = $tr->translate($text);
                    $translations[] = $translated;

                    Log::debug("Translation {$index}: " . substr($text, 0, 50) . " -> " . substr($translated, 0, 50));

                                    // เพิ่มหน่วงเวลาเล็กน้อยเพื่อป้องกัน rate limit
                    usleep(200000); // 0.2 วินาที

                } catch (\Exception $e) {
                    Log::warning('Single translation failed', [
                        'index' => $index,
                        'text'  => substr($text, 0, 50),
                        'error' => $e->getMessage(),
                    ]);

                    // ใช้ mock สำหรับข้อความนี้
                    $translations[] = $this->mockSingleTranslation($text, $targetLang);
                }
            }

            Log::info('Stichoza translation completed', [
                'input_count'   => count($texts),
                'output_count'  => count($translations),
                'sample_input'  => $texts[0] ?? 'N/A',
                'sample_output' => $translations[0] ?? 'N/A',
            ]);

            // เก็บใน cache 1 ชั่วโมง
            Cache::put($cacheKey, $translations, 3600);

            return $translations;

        } catch (\Exception $e) {
            Log::error('Stichoza translation failed', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            // ใช้ Mock Translation เป็น fallback
            Log::warning('Using mock translation as final fallback');
            return $this->mockTranslation($texts, $targetLang);
        }
    }

    private function mockSingleTranslation($text, $targetLang)
    {
        $mockDict = [
            'th' => [
                'มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน' => 'Rajamangala University of Technology Isan',
                'วิทยาเขตสกลนคร'                   => 'Sakon Nakhon Campus',
                'ประวัติความเป็นมา'                => 'History',
                'ผู้บริหาร'                        => 'Management',
                'สวัสดี'                           => 'Hello',
                'ขอบคุณ'                           => 'Thank you',
                'ยินดีต้อนรับ'                     => 'Welcome',
            ],
        ];

        $cleanText = trim($text);

        if ($targetLang === 'en' && isset($mockDict['th'][$cleanText])) {
            return $mockDict['th'][$cleanText];
        }

        return $this->simpleTranslate($cleanText);
    }

    private function mockTranslation($texts, $targetLang)
    {
        // Mock translations สำหรับทดสอบ - เพิ่มคำศัพท์ให้ครบขึ้น
        $mockDict = [
            'th' => [
                // หน้าหลัก
                'ประวัติความเป็นมา'                => 'History',
                'ผู้บริหาร'                        => 'Management',
                'โครงสร้าง'                        => 'Structure',
                'งานวิจัยและนวัตกรรม'              => 'Research and Innovation',
                'ศูนย์และหน่วยงานวิจัย'            => 'Research Centers and Units',
                'เมนูหลัก'                         => 'Main Menu',
                'หน้าแรก'                          => 'Home',
                'เกี่ยวกับเรา'                     => 'About Us',
                'บริการ'                           => 'Services',
                'ติดต่อเรา'                        => 'Contact Us',
                'ข่าวสาร'                          => 'News',
                'กิจกรรม'                          => 'Activities',
                'ยินดีต้อนรับ'                     => 'Welcome',

                // คำพื้นฐาน
                'สวัสดี'                           => 'Hello',
                'ขอบคุณ'                           => 'Thank you',
                'ขอโทษ'                            => 'Sorry',
                'ลาก่อน'                           => 'Goodbye',
                'ใช่'                              => 'Yes',
                'ไม่'                              => 'No',
                'ดี'                               => 'Good',
                'ไม่ดี'                            => 'Bad',
                'สวย'                              => 'Beautiful',
                'เก่ง'                             => 'Good',
                'เก็บ'                             => 'Keep',
                'ใหม่'                             => 'New',
                'เก่า'                             => 'Old',
                'ใหญ่'                             => 'Big',
                'เล็ก'                             => 'Small',
                'สูง'                              => 'High',
                'ต่ำ'                              => 'Low',

                // การศึกษา
                'มหาวิทยาลัย'                      => 'University',
                'มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน' => 'Rajamangala University of Technology Isan',
                'วิทยาเขตสกลนคร'                   => 'Sakon Nakhon Campus',
                'คณะ'                              => 'Faculty',
                'สาขา'                             => 'Department',
                'หลักสูตร'                         => 'Program',
                'นักศึกษา'                         => 'Students',
                'อาจารย์'                          => 'Faculty',
                'บุคลากร'                          => 'Staff',
            ],
        ];

        $translations = [];
        foreach ($texts as $text) {
            $cleanText = trim($text);

            if ($targetLang === 'en' && isset($mockDict['th'][$cleanText])) {
                $translations[] = $mockDict['th'][$cleanText];
            } else if ($targetLang === 'en') {
                // ใช้ simple rule-based translation สำหรับคำที่ไม่มีในพจนานุกรม
                $translation    = $this->simpleTranslate($cleanText);
                $translations[] = $translation;
            } else {
                $translations[] = $text; // คืนค่าเดิมถ้าไม่ใช่ภาษาอังกฤษ
            }
        }

        Log::info('Using mock translation', [
            'target_lang'   => $targetLang,
            'texts_count'   => count($texts),
            'sample_input'  => array_slice($texts, 0, 3),
            'sample_output' => array_slice($translations, 0, 3),
        ]);

        return $translations;
    }

    private function simpleTranslate($text)
    {
        // Simple rules สำหรับคำที่ไม่มีในพจนานุกรม
        $patterns = [
            // ชื่อเฉพาะและคำเฉพาะ
            '/มหาวิทยาลัยเทคโนโลยีราชมงคลอีสาน/' => 'Rajamangala University of Technology Isan',
            '/วิทยาเขตสกลนคร/'                   => 'Sakon Nakhon Campus',
            '/เทคโนโลยี/'                        => 'Technology',
            '/ราชมงคล/'                          => 'Rajamangala',
            '/สกลนคร/'                           => 'Sakon Nakhon',
            '/วิทยาเขต/'                         => 'Campus',

            // รูปแบบคำ
            '/^ข้อมูล(.*)/'                      => 'Information$1',
            '/^ระบบ(.*)/'                        => 'System$1',
            '/^การ(.*)/'                         => '$1 Management',
            '/^แผน(.*)/'                         => '$1 Plan',
            '/^โครงการ(.*)/'                     => '$1 Project',
            '/^ศูนย์(.*)/'                       => '$1 Center',
            '/^สำนัก(.*)/'                       => '$1 Office',
            '/^กอง(.*)/'                         => '$1 Division',
            '/^ฝ่าย(.*)/'                        => '$1 Department',
            '/^หน่วย(.*)/'                       => '$1 Unit',
        ];

        foreach ($patterns as $pattern => $replacement) {
            if (preg_match($pattern, $text)) {
                $result = preg_replace($pattern, $replacement, $text);
                return $result;
            }
        }

        // ตรวจสอบคำที่เป็นภาษาอังกฤษอยู่แล้ว
        if (preg_match('/^[a-zA-Z\s\-_]+$/', trim($text))) {
            return trim($text); // คืนค่าเดิมถ้าเป็นภาษาอังกฤษแล้ว
        }

        // ถ้าไม่ตรงกับ pattern ไหน ใส่ [EN] prefix
        return "[EN] " . $text;
    }

    private function mapLanguageCode($code)
    {
        $mapping = [
            'th'   => 'th',
            'en'   => 'en',
            'ja'   => 'ja',
            'ko'   => 'ko',
            'zh'   => 'zh-CN',
            'auto' => 'auto',
        ];

        return $mapping[$code] ?? $code;
    }

    private function getUsageInfo()
    {
        $today = now()->format('Y-m-d');
        $month = now()->format('Y-m');

        $dailyUsed   = Cache::get('translate_quota_daily_' . $today, 0);
        $monthlyUsed = Cache::get('translate_quota_monthly_' . $month, 0);

        $dailyQuota   = 999999; // ไม่จำกัด
        $monthlyQuota = 999999; // ไม่จำกัด

        return [
            'daily'    => [
                'used'      => $dailyUsed,
                'quota'     => $dailyQuota,
                'remaining' => $dailyQuota - $dailyUsed,
            ],
            'monthly'  => [
                'used'      => $monthlyUsed,
                'quota'     => $monthlyQuota,
                'remaining' => $monthlyQuota - $monthlyUsed,
            ],
            'provider' => ! empty($apiKey) ? 'Google Translate API' : 'stichoza/google-translate-php',
        ];
    }

    // Method สำหรับดู usage
    public function usage()
    {
        return response()->json($this->getUsageInfo());
    }

    // Method สำหรับทดสอบ cURL โดยตรง
    public function testCurl(Request $request)
    {
        try {
            $text   = $request->input('text', 'สวัสดี');
            $apiKey = config('services.google.translate_api_key');

            Log::info('Direct cURL test', ['text' => $text, 'has_api_key' => ! empty($apiKey)]);

            if (! $apiKey) {
                return response()->json(['error' => 'No API key configured']);
            }

            $url = 'https://translation.googleapis.com/language/translate/v2?' . http_build_query([
                'key'    => $apiKey,
                'q'      => $text,
                'target' => 'en',
                'source' => 'th',
                'format' => 'text',
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response  = curl_exec($ch);
            $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                return response()->json(['error' => 'cURL Error: ' . $curlError]);
            }

            if ($httpCode !== 200) {
                return response()->json(['error' => 'HTTP ' . $httpCode, 'response' => $response]);
            }

            $data = json_decode($response, true);

            return response()->json([
                'success'      => true,
                'input'        => $text,
                'output'       => $data['data']['translations'][0]['translatedText'] ?? 'N/A',
                'raw_response' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
        }
    }

    // Method สำหรับทดสอบ
    public function test()
    {
        $apiKey = config('services.google.translate_api_key');

        return response()->json([
            'message'            => 'Translation API is working',
            'timestamp'          => now(),
            'api_key_configured' => ! empty($apiKey),
            'api_key_length'     => $apiKey ? strlen($apiKey) : 0,
            'stichoza_available' => class_exists('Stichoza\GoogleTranslate\GoogleTranslate'),
            'config_test'        => [
                'services.google.translate_api_key' => $apiKey ? 'Found' : 'Not found',
                'env_direct'                        => env('GOOGLE_TRANSLATE_API_KEY') ? 'Found' : 'Not found',
            ],
        ]);
    }
}
