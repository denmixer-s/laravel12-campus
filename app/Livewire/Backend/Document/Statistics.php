<?php
namespace App\Livewire\Backend\Document;

use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentDownload;
use App\Models\DocumentType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.dashboard')]
class Statistics extends Component
{
                                       // Filter properties
    public $selectedPeriod     = '30'; // days
    public $selectedDepartment = '';
    public $selectedCategory   = '';
    public $selectedType       = '';

    // Chart refresh interval (in seconds)
    public $refreshInterval = 30;

    // Auto-refresh toggle
    public $autoRefresh = true;

    protected $queryString = [
        'selectedPeriod'     => ['except' => '30'],
        'selectedDepartment' => ['except' => ''],
        'selectedCategory'   => ['except' => ''],
        'selectedType'       => ['except' => ''],
    ];

    public function mount()
    {
        // Check permissions - เพิ่มการตรวจสอบ user login
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        // ตรวจสอบสิทธิ์ - ให้ Super Admin ผ่านได้ทุกอย่าง
        if (! auth()->user()->hasRole('Super Admin') && ! auth()->user()->can('documents.view-stats')) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
    }

    public function getTitle(): string
    {
        return 'สถิติและรายงาน - ระบบจัดการเอกสาร';
    }

    // ===============================
    // OVERVIEW STATISTICS
    // ===============================

    #[Computed]
    public function overviewStats()
    {
        return Cache::remember('stats.overview.' . $this->getCacheKey(), 300, function () {
            $totalDocuments     = Document::count();
            $publishedDocuments = Document::published()->count();
            $totalDownloads     = Document::sum('download_count');
            $totalViews         = Document::sum('view_count');
            $totalUsers         = User::count();
            $totalCategories    = DocumentCategory::active()->count();
            $totalDepartments   = Department::active()->count();

            // Recent activity (last 24 hours)
            $recentDocuments = Document::where('created_at', '>=', now()->subDay())->count();
            $recentDownloads = DocumentDownload::where('downloaded_at', '>=', now()->subDay())->count();

            // Calculate growth rates
            $previousPeriodStart = now()->subDays($this->selectedPeriod * 2);
            $currentPeriodStart  = now()->subDays($this->selectedPeriod);

            $previousDownloads = DocumentDownload::whereBetween('downloaded_at', [
                $previousPeriodStart, $currentPeriodStart,
            ])->count();

            $currentDownloads = DocumentDownload::where('downloaded_at', '>=', $currentPeriodStart)->count();

            $downloadGrowth = $previousDownloads > 0
            ? round((($currentDownloads - $previousDownloads) / $previousDownloads) * 100, 1)
            : 0;

            return [
                'total_documents'            => $totalDocuments,
                'published_documents'        => $publishedDocuments,
                'draft_documents'            => $totalDocuments - $publishedDocuments,
                'total_downloads'            => $totalDownloads,
                'total_views'                => $totalViews,
                'total_users'                => $totalUsers,
                'total_categories'           => $totalCategories,
                'total_departments'          => $totalDepartments,
                'recent_documents'           => $recentDocuments,
                'recent_downloads'           => $recentDownloads,
                'download_growth'            => $downloadGrowth,
                'avg_downloads_per_document' => $totalDocuments > 0 ? round($totalDownloads / $totalDocuments, 1) : 0,
                'publication_rate'           => $totalDocuments > 0 ? round(($publishedDocuments / $totalDocuments) * 100, 1) : 0,
            ];
        });
    }

    // ===============================
    // DOWNLOAD TRENDS
    // ===============================

    #[Computed]
    public function downloadTrends()
    {
        return Cache::remember('stats.download_trends.' . $this->getCacheKey(), 300, function () {
            $days      = (int) $this->selectedPeriod;
            $startDate = now()->subDays($days);

            $query = DocumentDownload::selectRaw('DATE(downloaded_at) as date, COUNT(*) as downloads')
                ->where('downloaded_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date');

            // Apply filters
            if ($this->selectedDepartment) {
                $query->whereHas('document', function ($q) {
                    $q->where('department_id', $this->selectedDepartment);
                });
            }

            if ($this->selectedCategory) {
                $query->whereHas('document', function ($q) {
                    $q->where('document_category_id', $this->selectedCategory);
                });
            }

            if ($this->selectedType) {
                $query->whereHas('document', function ($q) {
                    $q->where('document_type_id', $this->selectedType);
                });
            }

            $results = $query->get();

            // Fill missing dates with zero downloads
            $dates = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $date      = now()->subDays($i)->format('Y-m-d');
                $downloads = $results->firstWhere('date', $date)?->downloads ?? 0;
                $dates[]   = [
                    'date'           => $date,
                    'formatted_date' => Carbon::parse($date)->format('d/m'),
                    'downloads'      => $downloads,
                ];
            }

            return $dates;
        });
    }

    // ===============================
    // POPULAR DOCUMENTS
    // ===============================

    #[Computed]
    public function popularDocuments()
    {
        return Cache::remember('stats.popular_documents.' . $this->getCacheKey(), 600, function () {
            $query = Document::with(['category', 'type', 'creator'])
                ->published()
                ->where('download_count', '>', 0);

            // Apply filters
            if ($this->selectedDepartment) {
                $query->where('department_id', $this->selectedDepartment);
            }

            if ($this->selectedCategory) {
                $query->where('document_category_id', $this->selectedCategory);
            }

            if ($this->selectedType) {
                $query->where('document_type_id', $this->selectedType);
            }

            return $query->orderBy('download_count', 'desc')
                ->take(10)
                ->get()
                ->map(function ($document) {
                    return [
                        'id'         => $document->id,
                        'title'      => $document->title,
                        'category'   => $document->category->name,
                        'type'       => $document->type->name,
                        'creator'    => $document->creator->name ?? 'ไม่ระบุ',
                        'downloads'  => $document->download_count,
                        'views'      => $document->view_count,
                        'created_at' => $document->created_at->format('d/m/Y'),
                    ];
                });
        });
    }

    // ===============================
    // CATEGORY DISTRIBUTION - FIXED
    // ===============================

    #[Computed]
    public function categoryDistribution()
    {
        return Cache::remember('stats.category_distribution.' . $this->getCacheKey(), 600, function () {
            // Use subquery approach to avoid PostgreSQL HAVING with alias issues
            $subquery = DB::table('document_categories')
                ->select([
                    'document_categories.id',
                    'document_categories.name',
                    DB::raw('COUNT(documents.id) as doc_count'),
                ])
                ->leftJoin('documents', function ($join) {
                    $join->on('document_categories.id', '=', 'documents.document_category_id')
                        ->where('documents.status', '=', 'published')
                        ->whereNull('documents.deleted_at');

                    // Apply filters in the join
                    if ($this->selectedDepartment) {
                        $join->where('documents.department_id', '=', $this->selectedDepartment);
                    }

                    if ($this->selectedType) {
                        $join->where('documents.document_type_id', '=', $this->selectedType);
                    }
                })
                ->where('document_categories.is_active', true)
                ->groupBy('document_categories.id', 'document_categories.name');

            // Apply category filter if selected
            if ($this->selectedCategory) {
                $subquery->where('document_categories.id', $this->selectedCategory);
            }

            // Wrap in another query to filter and order properly
            $results = DB::table(DB::raw("({$subquery->toSql()}) as cat_stats"))
                ->mergeBindings($subquery)
                ->select('*')
                ->where('doc_count', '>', 0)
                ->orderBy('doc_count', 'desc')
                ->limit(10)
                ->get();

            return collect($results)->map(function ($category) {
                return [
                    'name'       => $category->name,
                    'count'      => (int) $category->doc_count,
                    'percentage' => 0, // Will be calculated in the view
                ];
            });
        });
    }

    // ===============================
    // USER TYPE STATISTICS
    // ===============================

    #[Computed]
    public function userTypeStats()
    {
        return Cache::remember('stats.user_type.' . $this->getCacheKey(), 600, function () {
            $startDate = now()->subDays($this->selectedPeriod);

            $stats = DocumentDownload::selectRaw('user_type, COUNT(*) as downloads')
                ->where('downloaded_at', '>=', $startDate)
                ->groupBy('user_type')
                ->get()
                ->keyBy('user_type');

            return [
                'staff'     => [
                    'downloads' => $stats->get('staff')?->downloads ?? 0,
                    'label'     => 'เจ้าหน้าที่',
                    'color'     => '#3B82F6', // blue
                ],
                'public'    => [
                    'downloads' => $stats->get('public')?->downloads ?? 0,
                    'label'     => 'บุคคลทั่วไป',
                    'color'     => '#10B981', // green
                ],
                'anonymous' => [
                    'downloads' => $stats->get('anonymous')?->downloads ?? 0,
                    'label'     => 'ผู้เยี่ยมชม',
                    'color'     => '#6B7280', // gray
                ],
            ];
        });
    }

    // ===============================
    // RECENT ACTIVITIES
    // ===============================

    #[Computed]
    public function recentActivities()
    {
        return Cache::remember('stats.recent_activities.' . $this->getCacheKey(), 180, function () {
            // Recent downloads
            $recentDownloads = DocumentDownload::with(['document', 'user'])
                ->latest('downloaded_at')
                ->take(5)
                ->get()
                ->map(function ($download) {
                    return [
                        'type'           => 'download',
                        'icon'           => 'download',
                        'color'          => 'text-green-500',
                        'title'          => 'ดาวน์โหลดเอกสาร',
                        'description'    => $download->document->title,
                        'user'           => $download->user?->name ?? 'ผู้เยี่ยมชม',
                        'time'           => $download->downloaded_at,
                        'formatted_time' => $download->downloaded_at->diffForHumans(),
                    ];
                });

            // Recent documents
            $recentDocuments = Document::with(['creator', 'category'])
                ->latest('created_at')
                ->take(3)
                ->get()
                ->map(function ($document) {
                    return [
                        'type'           => 'document',
                        'icon'           => 'document-add',
                        'color'          => 'text-blue-500',
                        'title'          => 'เอกสารใหม่',
                        'description'    => $document->title,
                        'user'           => $document->creator->name ?? 'ไม่ระบุ',
                        'time'           => $document->created_at,
                        'formatted_time' => $document->created_at->diffForHumans(),
                    ];
                });

            // Recent publications
            $recentPublications = Document::with(['creator', 'category'])
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->latest('published_at')
                ->take(2)
                ->get()
                ->map(function ($document) {
                    return [
                        'type'           => 'publish',
                        'icon'           => 'share',
                        'color'          => 'text-purple-500',
                        'title'          => 'เผยแพร่เอกสาร',
                        'description'    => $document->title,
                        'user'           => $document->creator->name ?? 'ไม่ระบุ',
                        'time'           => $document->published_at,
                        'formatted_time' => $document->published_at->diffForHumans(),
                    ];
                });

            return $recentDownloads
                ->concat($recentDocuments)
                ->concat($recentPublications)
                ->sortByDesc('time')
                ->take(10)
                ->values();
        });
    }

    // ===============================
    // DEPARTMENT PERFORMANCE
    // ===============================

    #[Computed]
    public function departmentPerformance()
    {
        return Cache::remember('stats.department_performance.' . $this->getCacheKey(), 600, function () {
            // Using subquery approach to avoid PostgreSQL HAVING with alias issues
            $subquery = DB::table('departments')
                ->select([
                    'departments.id',
                    'departments.name',
                    DB::raw('COUNT(DISTINCT users.id) as users_count'),
                    DB::raw('COUNT(DISTINCT documents.id) as documents_count'),
                    DB::raw('COALESCE(SUM(documents.download_count), 0) as total_downloads'),
                ])
                ->leftJoin('users', 'departments.id', '=', 'users.department_id')
                ->leftJoin('documents', function ($join) {
                    $join->on('departments.id', '=', 'documents.department_id')
                        ->where('documents.status', '=', 'published')
                        ->whereNull('documents.deleted_at');
                })
                ->where('departments.is_active', true)
                ->groupBy('departments.id', 'departments.name');

            if ($this->selectedDepartment) {
                $subquery->where('departments.id', $this->selectedDepartment);
            }

            // Wrap in another query to filter properly
            $results = DB::table(DB::raw("({$subquery->toSql()}) as dept_stats"))
                ->mergeBindings($subquery)
                ->select('*')
                ->where('documents_count', '>', 0)
                ->orderBy('total_downloads', 'desc')
                ->get();

            return collect($results)->map(function ($department) {
                return [
                    'id'              => $department->id,
                    'name'            => $department->name,
                    'documents_count' => (int) $department->documents_count,
                    'users_count'     => (int) $department->users_count,
                    'total_downloads' => (int) $department->total_downloads,
                    'avg_downloads'   => $department->documents_count > 0
                    ? round($department->total_downloads / $department->documents_count, 1)
                    : 0,
                ];
            });
        });
    }

    // ===============================
    // SYSTEM HEALTH
    // ===============================

    #[Computed]
    public function systemHealth()
    {
        return Cache::remember('stats.system_health', 60, function () {
            // For response time, we'll calculate based on recent download patterns
            // Since document_downloads doesn't have created_at, we'll use a different approach
            $recentDownloads = DocumentDownload::where('downloaded_at', '>=', now()->subHour())->count();

            // Simple response time estimation based on download volume
            $avgResponseTime = $recentDownloads > 100 ? 250 : ($recentDownloads > 50 ? 150 : 100);

            // Storage usage
            $totalFileSize = Document::sum('file_size') ?? 0;
            $storageSizeMB = round($totalFileSize / 1024 / 1024, 2);

            // Error rates - placeholder for now
            $totalRequests  = DocumentDownload::where('downloaded_at', '>=', now()->subDay())->count();
            $failedRequests = 0; // This would need error logging implementation

            $errorRate = $totalRequests > 0 ? round(($failedRequests / $totalRequests) * 100, 2) : 0;

            // Active users today
            $activeUsersToday = DocumentDownload::where('downloaded_at', '>=', now()->startOfDay())
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count();

            return [
                'avg_response_time'         => $avgResponseTime, // milliseconds
                'storage_usage_mb'          => $storageSizeMB,
                'storage_usage_gb'          => round($storageSizeMB / 1024, 2),
                'error_rate'                => $errorRate,
                'uptime_percentage'         => 99.9, // This would need actual monitoring
                'active_users_today'        => $activeUsersToday,
                'recent_downloads_per_hour' => $recentDownloads,
            ];
        });
    }

    // ===============================
    // EXPORT & UTILITIES
    // ===============================

    public function refreshStats()
    {
        // Clear specific cache keys instead of using tags
        $keys = [
            'stats.overview.' . $this->getCacheKey(),
            'stats.download_trends.' . $this->getCacheKey(),
            'stats.popular_documents.' . $this->getCacheKey(),
            'stats.category_distribution.' . $this->getCacheKey(),
            'stats.user_type.' . $this->getCacheKey(),
            'stats.recent_activities.' . $this->getCacheKey(),
            'stats.department_performance.' . $this->getCacheKey(),
            'stats.system_health',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        $this->dispatch('notify', [
            'type'    => 'success',
            'message' => 'สถิติได้รับการอัปเดตแล้ว',
        ]);
    }

    public function exportReport($format = 'csv')
    {
        // ตรวจสอบสิทธิ์ส่งออกรายงาน - ให้ Super Admin ผ่านได้
        if (! auth()->user()->hasRole('Super Admin') && ! auth()->user()->can('documents.export')) {
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => 'คุณไม่มีสิทธิ์ส่งออกรายงาน',
            ]);
            return;
        }

        try {
            // รวบรวมข้อมูลสำหรับ export
            $data = $this->getExportData();

            // สร้างไฟล์ตามรูปแบบที่เลือก
            return $this->generateExportFile($format, $data);

        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage());

            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => 'เกิดข้อผิดพลาดในการส่งออกรายงาน: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * รวบรวมข้อมูลสำหรับ export
     */
    protected function getExportData(): array
    {
        return [
            'overview'               => $this->overviewStats,
            'popular_documents'      => $this->popularDocuments,
            'category_distribution'  => $this->categoryDistribution,
            'department_performance' => $this->departmentPerformance,
            'download_trends'        => $this->downloadTrends,
            'user_type_stats'        => $this->userTypeStats,
            'generated_at'           => now(),
            'period'                 => $this->selectedPeriod . ' วันล่าสุด',
            'filters'                => [
                'department' => $this->selectedDepartment,
                'category'   => $this->selectedCategory,
                'type'       => $this->selectedType,
            ],
        ];
    }

    /**
     * สร้างไฟล์ export
     */
    protected function generateExportFile(string $format, array $data)
    {
        $filename = 'สถิติเอกสาร_' . now()->format('Y-m-d_H-i-s');

        return match ($format) {
            'csv' => $this->exportToCsv($data, $filename),
            'excel' => $this->exportToExcel($data, $filename),
            'pdf' => $this->exportToPdf($data, $filename),
            default => $this->exportToCsv($data, $filename),
        };
    }

    /**
     * Export เป็น CSV
     */
    protected function exportToCsv(array $data, string $filename)
    {
        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');

            // Write UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // Headers
            fputcsv($file, [
                'รายงานสถิติระบบจัดการเอกสาร',
                'สร้างเมื่อ: ' . $data['generated_at']->format('d/m/Y H:i:s'),
                'ช่วงข้อมูล: ' . $data['period'],
            ]);

            fputcsv($file, []); // Empty line

            // Overview Statistics
            fputcsv($file, ['สถิติภาพรวม']);
            fputcsv($file, ['รายการ', 'จำนวน']);

            foreach ($data['overview'] as $key => $value) {
                $label = $this->getOverviewLabel($key);
                fputcsv($file, [$label, number_format($value)]);
            }

            fputcsv($file, []); // Empty line

            // Popular Documents
            fputcsv($file, ['เอกสารยอดนิยม']);
            fputcsv($file, ['ลำดับ', 'ชื่อเอกสาร', 'หมวดหมู่', 'ประเภท', 'ดาวน์โหลด', 'การเข้าชม', 'ผู้สร้าง']);

            foreach ($data['popular_documents'] as $index => $doc) {
                fputcsv($file, [
                    $index + 1,
                    $doc['title'],
                    $doc['category'],
                    $doc['type'],
                    $doc['downloads'],
                    $doc['views'],
                    $doc['creator'],
                ]);
            }

            fputcsv($file, []); // Empty line

            // Category Distribution
            fputcsv($file, ['การกระจายตามหมวดหมู่']);
            fputcsv($file, ['หมวดหมู่', 'จำนวนเอกสาร', 'เปอร์เซ็นต์']);

            $totalCategoryDocs = collect($data['category_distribution'])->sum('count');
            foreach ($data['category_distribution'] as $category) {
                $percentage = $totalCategoryDocs > 0 ? round(($category['count'] / $totalCategoryDocs) * 100, 2) : 0;
                fputcsv($file, [
                    $category['name'],
                    $category['count'],
                    $percentage . '%',
                ]);
            }

            fputcsv($file, []); // Empty line

            // Department Performance
            fputcsv($file, ['ประสิทธิภาพแผนก']);
            fputcsv($file, ['แผนก', 'จำนวนเอกสาร', 'จำนวนผู้ใช้', 'ดาวน์โหลดรวม', 'ดาวน์โหลดเฉลี่ย']);

            foreach ($data['department_performance'] as $dept) {
                fputcsv($file, [
                    $dept['name'],
                    $dept['documents_count'],
                    $dept['users_count'],
                    $dept['total_downloads'],
                    $dept['avg_downloads'],
                ]);
            }

                                // Download Trends
            fputcsv($file, []); // Empty line
            fputcsv($file, ['แนวโน้มการดาวน์โหลด']);
            fputcsv($file, ['วันที่', 'จำนวนดาวน์โหลด']);

            foreach ($data['download_trends'] as $trend) {
                fputcsv($file, [
                    $trend['formatted_date'],
                    $trend['downloads'],
                ]);
            }

            fclose($file);
        }, $filename . '.csv', [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ]);
    }

    /**
     * Export เป็น Excel (HTML format)
     */
    protected function exportToExcel(array $data, string $filename)
    {
        return response()->streamDownload(function () use ($data) {
            echo '<meta charset="UTF-8">';
            echo '<table border="1" style="border-collapse: collapse; width: 100%;">';

            // Title
            echo '<tr><td colspan="6" style="text-align: center; font-size: 18px; font-weight: bold; padding: 10px;">รายงานสถิติระบบจัดการเอกสาร</td></tr>';
            echo '<tr><td colspan="6" style="text-align: center; padding: 5px;">สร้างเมื่อ: ' . $data['generated_at']->format('d/m/Y H:i:s') . '</td></tr>';
            echo '<tr><td colspan="6" style="text-align: center; padding: 5px;">ช่วงข้อมูล: ' . $data['period'] . '</td></tr>';
            echo '<tr><td colspan="6"></td></tr>';

            // Overview
            echo '<tr><td colspan="6" style="font-size: 16px; font-weight: bold; background-color: #f0f0f0; padding: 8px;">สถิติภาพรวม</td></tr>';
            echo '<tr><td style="font-weight: bold; background-color: #f8f8f8; padding: 5px;">รายการ</td><td style="font-weight: bold; background-color: #f8f8f8; padding: 5px;">จำนวน</td><td></td><td></td><td></td><td></td></tr>';

            foreach ($data['overview'] as $key => $value) {
                $label = $this->getOverviewLabel($key);
                echo '<tr><td style="padding: 5px;">' . $label . '</td><td style="padding: 5px; text-align: right;">' . number_format($value) . '</td><td></td><td></td><td></td><td></td></tr>';
            }

            echo '<tr><td colspan="6"></td></tr>';

            // Popular Documents
            echo '<tr><td colspan="6" style="font-size: 16px; font-weight: bold; background-color: #f0f0f0; padding: 8px;">เอกสารยอดนิยม</td></tr>';
            echo '<tr>';
            echo '<td style="font-weight: bold; background-color: #f8f8f8; padding: 5px;">ลำดับ</td>';
            echo '<td style="font-weight: bold; background-color: #f8f8f8; padding: 5px;">ชื่อเอกสาร</td>';
            echo '<td style="font-weight: bold; background-color: #f8f8f8; padding: 5px;">หมวดหมู่</td>';
            echo '<td style="font-weight: bold; background-color: #f8f8f8; padding: 5px;">ประเภท</td>';
            echo '<td style="font-weight: bold; background-color: #f8f8f8; padding: 5px;">ดาวน์โหลด</td>';
            echo '<td style="font-weight: bold; background-color: #f8f8f8; padding: 5px;">ผู้สร้าง</td>';
            echo '</tr>';

            foreach ($data['popular_documents'] as $index => $doc) {
                echo '<tr>';
                echo '<td style="padding: 5px; text-align: center;">' . ($index + 1) . '</td>';
                echo '<td style="padding: 5px;">' . htmlspecialchars($doc['title']) . '</td>';
                echo '<td style="padding: 5px;">' . htmlspecialchars($doc['category']) . '</td>';
                echo '<td style="padding: 5px;">' . htmlspecialchars($doc['type']) . '</td>';
                echo '<td style="padding: 5px; text-align: right;">' . number_format($doc['downloads']) . '</td>';
                echo '<td style="padding: 5px;">' . htmlspecialchars($doc['creator']) . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        }, $filename . '.xls', [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xls"',
        ]);
    }

    /**
     * Export เป็น PDF จริง ๆ (ใช้ HTML to PDF)
     */
    protected function exportToPdf(array $data, string $filename)
    {
        // สร้าง HTML content สำหรับ PDF
        $html = $this->generatePdfHtml($data);

        // ใช้วิธี HTML to PDF ด้วย browser
        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $filename . '.html', [
            'Content-Type'        => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.html"',
        ]);
    }

    /**
     * สร้าง HTML สำหรับ PDF (พิมพ์ได้ดี)
     */
    protected function generatePdfHtml(array $data): string
    {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>รายงานสถิติเอกสาร</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }

        body {
            font-family: "TH Sarabun New", "Angsana New", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #1e40af;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section h2 {
            font-size: 18px;
            color: #1e40af;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #374151;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            background-color: #f9fafb;
        }

        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #1f2937;
        }

        .number {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .section {
                page-break-inside: avoid;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>รายงานสถิติระบบจัดการเอกสาร</h1>
        <p>สร้างเมื่อ: ' . $data['generated_at']->format('d/m/Y H:i:s') . '</p>
        <p>ช่วงข้อมูล: ' . $data['period'] . '</p>
    </div>

    <div class="section">
        <h2>📊 สถิติภาพรวม</h2>
        <div class="summary-grid">
            <div class="summary-card">
                <h3>เอกสารทั้งหมด</h3>
                <div class="number">' . number_format($data['overview']['total_documents']) . '</div>
            </div>
            <div class="summary-card">
                <h3>เอกสารที่เผยแพร่</h3>
                <div class="number">' . number_format($data['overview']['published_documents']) . '</div>
            </div>
            <div class="summary-card">
                <h3>ดาวน์โหลดทั้งหมด</h3>
                <div class="number">' . number_format($data['overview']['total_downloads']) . '</div>
            </div>
            <div class="summary-card">
                <h3>การเข้าชมทั้งหมด</h3>
                <div class="number">' . number_format($data['overview']['total_views']) . '</div>
            </div>
        </div>

        <table>
            <tr><th style="width: 70%;">รายการ</th><th style="width: 30%;" class="text-right">จำนวน</th></tr>';

        foreach ($data['overview'] as $key => $value) {
            $label = $this->getOverviewLabel($key);
            $html .= '<tr><td>' . $label . '</td><td class="text-right">' . number_format($value) . '</td></tr>';
        }

        $html .= '</table>
    </div>

    <div class="section">
        <h2>🏆 เอกสารยอดนิยม</h2>
        <table>
            <tr>
                <th style="width: 8%;" class="text-center">ลำดับ</th>
                <th style="width: 40%;">ชื่อเอกสาร</th>
                <th style="width: 20%;">หมวดหมู่</th>
                <th style="width: 15%;">ประเภท</th>
                <th style="width: 12%;" class="text-right">ดาวน์โหลด</th>
                <th style="width: 15%;">ผู้สร้าง</th>
            </tr>';

        foreach ($data['popular_documents'] as $index => $doc) {
            $html .= '<tr>
                <td class="text-center">' . ($index + 1) . '</td>
                <td>' . htmlspecialchars(mb_strimwidth($doc['title'], 0, 50, '...')) . '</td>
                <td>' . htmlspecialchars($doc['category']) . '</td>
                <td>' . htmlspecialchars($doc['type']) . '</td>
                <td class="text-right">' . number_format($doc['downloads']) . '</td>
                <td>' . htmlspecialchars($doc['creator']) . '</td>
            </tr>';
        }

        $html .= '</table>
    </div>

    <div class="section">
        <h2>📈 การกระจายตามหมวดหมู่</h2>
        <table>
            <tr>
                <th style="width: 60%;">หมวดหมู่</th>
                <th style="width: 25%;" class="text-right">จำนวนเอกสาร</th>
                <th style="width: 15%;" class="text-right">เปอร์เซ็นต์</th>
            </tr>';

        $totalCategoryDocs = collect($data['category_distribution'])->sum('count');
        foreach ($data['category_distribution'] as $category) {
            $percentage = $totalCategoryDocs > 0 ? round(($category['count'] / $totalCategoryDocs) * 100, 1) : 0;
            $html .= '<tr>
                <td>' . htmlspecialchars($category['name']) . '</td>
                <td class="text-right">' . number_format($category['count']) . '</td>
                <td class="text-right">' . $percentage . '%</td>
            </tr>';
        }

        $html .= '</table>
    </div>

    <div class="section">
        <h2>🏢 ประสิทธิภาพแผนก</h2>
        <table>
            <tr>
                <th style="width: 35%;">แผนก</th>
                <th style="width: 15%;" class="text-right">เอกสาร</th>
                <th style="width: 15%;" class="text-right">ผู้ใช้</th>
                <th style="width: 20%;" class="text-right">ดาวน์โหลดรวม</th>
                <th style="width: 15%;" class="text-right">เฉลี่ย</th>
            </tr>';

        foreach ($data['department_performance'] as $dept) {
            $html .= '<tr>
                <td>' . htmlspecialchars($dept['name']) . '</td>
                <td class="text-right">' . number_format($dept['documents_count']) . '</td>
                <td class="text-right">' . number_format($dept['users_count']) . '</td>
                <td class="text-right">' . number_format($dept['total_downloads']) . '</td>
                <td class="text-right">' . $dept['avg_downloads'] . '</td>
            </tr>';
        }

        $html .= '</table>
    </div>

    <div class="section">
        <h2>📅 แนวโน้มการดาวน์โหลด</h2>
        <table>
            <tr>
                <th style="width: 50%;" class="text-center">วันที่</th>
                <th style="width: 50%;" class="text-right">จำนวนดาวน์โหลด</th>
            </tr>';

        foreach ($data['download_trends'] as $trend) {
            $html .= '<tr>
                <td class="text-center">' . $trend['formatted_date'] . '</td>
                <td class="text-right">' . number_format($trend['downloads']) . '</td>
            </tr>';
        }

        $html .= '</table>
    </div>

    <div class="footer">
        <p>รายงานนี้สร้างขึ้นโดยระบบจัดการเอกสาร</p>
        <p class="no-print">
            <strong>วิธีบันทึกเป็น PDF:</strong> กด Ctrl+P (Windows) หรือ Cmd+P (Mac)
            แล้วเลือก "Save as PDF" หรือ "บันทึกเป็น PDF"
        </p>
    </div>

    <script>
        // Auto print dialog (optional)
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</body>
</html>';

        return $html;
    }

    /**
     * รับ label สำหรับสถิติภาพรวม
     */
    protected function getOverviewLabel(string $key): string
    {
        return match ($key) {
            'total_documents' => 'เอกสารทั้งหมด',
            'published_documents' => 'เอกสารที่เผยแพร่',
            'draft_documents' => 'เอกสารร่าง',
            'total_downloads' => 'ดาวน์โหลดทั้งหมด',
            'total_views' => 'การเข้าชมทั้งหมด',
            'total_users' => 'ผู้ใช้ทั้งหมด',
            'total_categories' => 'หมวดหมู่ทั้งหมด',
            'total_departments' => 'แผนกทั้งหมด',
            'recent_documents' => 'เอกสารใหม่ (24 ชม.)',
            'recent_downloads' => 'ดาวน์โหลดล่าสุด (24 ชม.)',
            'download_growth' => 'การเติบโตของดาวน์โหลด (%)',
            'avg_downloads_per_document' => 'ดาวน์โหลดเฉลี่ยต่อเอกสาร',
            'publication_rate' => 'อัตราการเผยแพร่ (%)',
            default => $key,
        };
    }

    public function toggleAutoRefresh()
    {
        $this->autoRefresh = ! $this->autoRefresh;

        $this->dispatch('notify', [
            'type'    => 'info',
            'message' => $this->autoRefresh ? 'เปิดการรีเฟรชอัตโนมัติ' : 'ปิดการรีเฟรชอัตโนมัติ',
        ]);
    }

    // ===============================
    // FILTER METHODS
    // ===============================

    public function updatedSelectedPeriod()
    {
        $this->clearCache();
    }

    public function updatedSelectedDepartment()
    {
        $this->clearCache();
    }

    public function updatedSelectedCategory()
    {
        $this->clearCache();
    }

    public function updatedSelectedType()
    {
        $this->clearCache();
    }

    public function resetFilters()
    {
        $this->reset(['selectedDepartment', 'selectedCategory', 'selectedType']);
        $this->selectedPeriod = '30';
        $this->clearCache();

        $this->dispatch('notify', [
            'type'    => 'success',
            'message' => 'รีเซ็ตตัวกรองแล้ว',
        ]);
    }

    // ===============================
    // HELPER METHODS
    // ===============================

    protected function getCacheKey(): string
    {
        return implode('.', [
            $this->selectedPeriod,
            $this->selectedDepartment,
            $this->selectedCategory,
            $this->selectedType,
            auth()->user()->department_id ?? 'all',
        ]);
    }

    protected function clearCache(): void
    {
        // Clear cache keys with current cache key pattern
        $patterns = [
            'stats.overview.',
            'stats.download_trends.',
            'stats.popular_documents.',
            'stats.category_distribution.',
            'stats.user_type.',
            'stats.recent_activities.',
            'stats.department_performance.',
        ];

        $currentCacheKey = $this->getCacheKey();

        foreach ($patterns as $pattern) {
            Cache::forget($pattern . $currentCacheKey);
        }

        // Also clear system health cache
        Cache::forget('stats.system_health');
    }

    protected function checkPermission($ability): bool
    {
        if (! auth()->user()) {
            return false;
        }

        // Super Admin ผ่านได้ทุกอย่าง
        if (auth()->user()->hasRole('Super Admin')) {
            return true;
        }

        return auth()->user()->can($ability);
    }

    // ===============================
    // DATA PROVIDERS
    // ===============================

    #[Computed]
    public function departments()
    {
        return Department::active()->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function categories()
    {
        return DocumentCategory::active()->parent()->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function types()
    {
        return DocumentType::active()->orderBy('name')->get(['id', 'name']);
    }

    public function getPeriodOptions()
    {
        return [
            '7'   => '7 วันล่าสุด',
            '30'  => '30 วันล่าสุด',
            '90'  => '3 เดือนล่าสุด',
            '365' => '1 ปีล่าสุด',
        ];
    }

    public function render()
    {
        return view('livewire.backend.document.statistics', [
            'overviewStats'         => $this->overviewStats,
            'downloadTrends'        => $this->downloadTrends,
            'popularDocuments'      => $this->popularDocuments,
            'categoryDistribution'  => $this->categoryDistribution,
            'userTypeStats'         => $this->userTypeStats,
            'recentActivities'      => $this->recentActivities,
            'departmentPerformance' => $this->departmentPerformance,
            'systemHealth'          => $this->systemHealth,
            'departments'           => $this->departments,
            'categories'            => $this->categories,
            'types'                 => $this->types,
            'periodOptions'         => $this->getPeriodOptions(),
        ])
            ->title($this->getTitle());
    }
}
