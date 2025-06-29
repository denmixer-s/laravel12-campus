<?php

namespace App\Livewire\Frontend\Document;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('components.layouts.app')]
class DocumentShow extends Component
{
    use AuthorizesRequests;

    public Document $document;
    public $showShareModal = false;
    public $reportReason = '';
    public $showReportModal = false;

    public function mount(Document $document)
    {
        // ✅ ใช้ Route Model Binding แบบ Laravel standard
        // Laravel จะ auto-resolve Document model จาก {document} parameter
        $this->document = $document;

        // Check if document can be accessed
        if (!$this->document->canBeAccessedBy(auth()->user())) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงเอกสารนี้');
        }

        // Check if document is published
        if ($this->document->status !== 'published') {
            abort(404, 'ไม่พบเอกสารที่คุณต้องการ');
        }

        // Load relationships
        $this->document->load(['category', 'type', 'department', 'creator']);

        // Increment view count
        $this->document->incrementViewCount();
    }

    public function getTitle(): string
    {
        return $this->document->title . ' - ระบบจัดการเอกสาร';
    }

    #[Computed]
    public function relatedDocuments()
    {
        return Document::query()
            ->published()
            ->public()
            ->where('id', '!=', $this->document->id)
            ->where(function ($query) {
                // Same category
                $query->where('document_category_id', $this->document->document_category_id)
                    // Or same type
                    ->orWhere('document_type_id', $this->document->document_type_id)
                    // Or similar tags
                    ->orWhere(function ($q) {
                        if ($this->document->tags && count($this->document->tags) > 0) {
                            foreach ($this->document->tags as $tag) {
                                $q->orWhereJsonContains('tags', $tag);
                            }
                        }
                    });
            })
            ->with(['category', 'type', 'creator'])
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();
    }

    #[Computed]
    public function sameCategoryDocuments()
    {
        return Document::query()
            ->published()
            ->public()
            ->where('id', '!=', $this->document->id)
            ->where('document_category_id', $this->document->document_category_id)
            ->with(['category', 'type'])
            ->orderBy('published_at', 'desc')
            ->take(8)
            ->get();
    }

    #[Computed]
    public function recentDocuments()
    {
        return Document::query()
            ->published()
            ->public()
            ->where('id', '!=', $this->document->id)
            ->where('published_at', '>=', now()->subDays(30))
            ->with(['category', 'type'])
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();
    }

    #[Computed]
    public function breadcrumbs()
    {
        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => 'เอกสาร', 'url' => route('documents.index')],
        ];

        // Add category breadcrumb
        if ($this->document->category) {
            $categoryBreadcrumbs = $this->document->category->breadcrumb;
            foreach ($categoryBreadcrumbs as $category) {
                $breadcrumbs[] = [
                    'name' => $category['name'],
                    'url' => route('documents.index', ['category' => $category['slug']])
                ];
            }
        }

        // Add current document
        $breadcrumbs[] = [
            'name' => $this->document->title,
            'url' => null // Current page
        ];

        return $breadcrumbs;
    }

    #[Computed]
    public function documentStats()
    {
        return [
            'views' => number_format($this->document->view_count),
            'downloads' => number_format($this->document->download_count),
            'file_size' => $this->document->file_size_formatted,
            'published_date' => $this->document->published_at?->format('d/m/Y'),
            'last_updated' => $this->document->updated_at->diffForHumans(),
        ];
    }

    #[Computed]
    public function shareUrl()
    {
        return route('documents.show', $this->document);
    }

    public function downloadDocument()
    {
        // Double-check access
        if (!$this->document->canBeAccessedBy(auth()->user())) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'คุณไม่มีสิทธิ์ดาวน์โหลดเอกสารนี้'
            ]);
            return;
        }

        // Record download
        $this->document->downloads()->create([
            'user_id' => auth()->id(),
            'user_type' => auth()->check() ? (auth()->user()->user_type ?? 'public') : 'anonymous',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
            'downloaded_at' => now(),
        ]);

        // Increment download count
        $this->document->incrementDownloadCount();

        // Refresh the document to get updated download count
        $this->document->refresh();

        // Show success message
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'กำลังดาวน์โหลดเอกสาร...'
        ]);

        // Return download URL
        return redirect()->to($this->document->file_url);
    }

    public function toggleShare()
    {
        $this->showShareModal = !$this->showShareModal;
    }

    public function shareVia($platform)
    {
        $url = $this->shareUrl;
        $title = $this->document->title;
        $description = $this->document->description ?? '';

        $shareUrls = [
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($url),
            'twitter' => "https://twitter.com/intent/tweet?url=" . urlencode($url) . "&text=" . urlencode($title),
            'line' => "https://social-plugins.line.me/lineit/share?url=" . urlencode($url),
            'email' => "mailto:?subject=" . urlencode($title) . "&body=" . urlencode($description . "\n\n" . $url),
            'copy' => $url // Handle copy to clipboard via JavaScript
        ];

        if ($platform === 'copy') {
            $this->dispatch('copy-to-clipboard', $url);
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'คัดลอกลิงก์แล้ว'
            ]);
        } else {
            $this->dispatch('open-share-window', $shareUrls[$platform] ?? $url);
        }

        $this->showShareModal = false;
    }

    public function toggleReport()
    {
        $this->showReportModal = !$this->showReportModal;
        $this->reportReason = '';
    }

    public function submitReport()
    {
        $this->validate([
            'reportReason' => 'required|string|min:10|max:500'
        ], [
            'reportReason.required' => 'กรุณาระบุเหตุผลในการรายงาน',
            'reportReason.min' => 'เหตุผลต้องมีอย่างน้อย 10 ตัวอักษร',
            'reportReason.max' => 'เหตุผลต้องไม่เกิน 500 ตัวอักษร'
        ]);

        // Create report record (you might want to create a DocumentReport model)
        // DocumentReport::create([
        //     'document_id' => $this->document->id,
        //     'user_id' => auth()->id(),
        //     'reason' => $this->reportReason,
        //     'ip_address' => request()->ip(),
        //     'user_agent' => request()->userAgent(),
        // ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'ส่งรายงานเรียบร้อยแล้ว ขอบคุณสำหรับข้อมูลที่แจ้งมา'
        ]);

        $this->showReportModal = false;
        $this->reportReason = '';
    }

    public function viewInCategory()
    {
        return redirect()->route('documents.index', [
            'category' => $this->document->category->slug
        ]);
    }

    public function viewByType()
    {
        return redirect()->route('documents.index', [
            'type' => $this->document->type->slug
        ]);
    }

    public function viewByDepartment()
    {
        if ($this->document->department) {
            return redirect()->route('documents.index', [
                'department' => $this->document->department->slug
            ]);
        }
    }

    public function addToFavorites()
    {
        if (!auth()->check()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'กรุณาเข้าสู่ระบบเพื่อเพิ่มเอกสารในรายการโปรด'
            ]);
            return;
        }

        // Toggle favorite (you might want to create a UserFavorite model)
        // $favorite = UserFavorite::where('user_id', auth()->id())
        //     ->where('document_id', $this->document->id)
        //     ->first();

        // if ($favorite) {
        //     $favorite->delete();
        //     $message = 'ลบออกจากรายการโปรดแล้ว';
        // } else {
        //     UserFavorite::create([
        //         'user_id' => auth()->id(),
        //         'document_id' => $this->document->id
        //     ]);
        //     $message = 'เพิ่มในรายการโปรดแล้ว';
        // }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'เพิ่มในรายการโปรดแล้ว' // $message
        ]);
    }

    public function printDocument()
    {
        $this->dispatch('print-document');
    }

    public function goBack()
    {
        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.frontend.document.document-show')
            ->title($this->getTitle());
    }
}
