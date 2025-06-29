<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LanguageSwitcher extends Component
{
    public $currentLang;
    public $isOpen = false;
    public $translating = false;

    public $languages = [
        'th' => [
            'name' => 'ไทย',
            'flag' => 'flag-th'
        ],
        'en' => [
            'name' => 'English',
            'flag' => 'flag-en'
        ]
    ];

    public function mount()
    {
        $this->currentLang = Session::get('language', 'th');
    }

    public function toggle()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function switchLanguage($locale)
    {
        if ($this->currentLang === $locale) {
            $this->close();
            return;
        }

        $this->translating = true;
        $this->close();

        try {
            // เก็บภาษาใน session
            Session::put('language', $locale);
            $oldLang = $this->currentLang;
            $this->currentLang = $locale;

            // ส่ง event ไปยัง JavaScript เพื่อแปลเนื้อหาในหน้า
            $this->dispatch('language-changed',
                locale: $locale,
                from: $oldLang
            );

            // แจ้งเตือนสำเร็จ
            $this->dispatch('notify',
                message: $locale === 'th' ? 'เปลี่ยนเป็นภาษาไทยแล้ว' : 'Changed to English',
                type: 'success'
            );

        } catch (\Exception $e) {
            Log::error('Language switch error: ' . $e->getMessage());

            $this->dispatch('notify',
                message: 'เกิดข้อผิดพลาดในการเปลี่ยนภาษา',
                type: 'error'
            );
        } finally {
            $this->translating = false;
        }
    }

    public function getCurrentFlag()
    {
        return $this->languages[$this->currentLang]['flag'] ?? 'flag-th';
    }

    public function getCurrentName()
    {
        return $this->languages[$this->currentLang]['name'] ?? 'ไทย';
    }

    public function render()
    {
        return view('livewire.frontend.language-switcher');
    }
}
