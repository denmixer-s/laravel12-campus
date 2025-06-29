<?php

namespace App\Livewire\Frontend\Document;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

class FrontDocumentList extends Component
{
    use WithPagination;

    #[Url(as: 'search')]
    public $search = '';

    #[Url(as: 'category')]
    public $selectedCategory = '';

    #[Url(as: 'type')]
    public $selectedType = '';

    #[Url(as: 'department')]
    public $selectedDepartment = '';

    #[Url(as: 'sort')]
    public $sortBy = 'latest';

    #[Url(as: 'view')]
    public $viewMode = 'grid'; // grid, list

    public $perPage = 12;

    // Filter toggles
    public $showFilters = false;
    public $showFeatured = false;
    public $showNew = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
        'selectedType' => ['except' => ''],
        'selectedDepartment' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
        'viewMode' => ['except' => 'grid'],
        'showFeatured' => ['except' => false],
        'showNew' => ['except' => false],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        // Initialize component
    }

    #[Computed]
    public function documents()
    {
        $query = Document::query()
            ->with(['category', 'type', 'department', 'creator'])
            ->published()
            ->public();

        // Apply search
        if ($this->search) {
            $query->search($this->search);
        }

        // Apply category filter
        if ($this->selectedCategory) {
            $category = DocumentCategory::where('slug', $this->selectedCategory)->first();
            if ($category) {
                // Include subcategories
                $categoryIds = [$category->id];
                $subcategories = $category->getAllDescendants();
                $categoryIds = array_merge($categoryIds, $subcategories->pluck('id')->toArray());

                $query->whereIn('document_category_id', $categoryIds);
            }
        }

        // Apply type filter
        if ($this->selectedType) {
            $type = DocumentType::where('slug', $this->selectedType)->first();
            if ($type) {
                $query->where('document_type_id', $type->id);
            }
        }

        // Apply department filter
        if ($this->selectedDepartment) {
            $department = Department::where('slug', $this->selectedDepartment)->first();
            if ($department) {
                $query->where('department_id', $department->id);
            }
        }

        // Apply featured filter
        if ($this->showFeatured) {
            $query->featured();
        }

        // Apply new filter
        if ($this->showNew) {
            $query->new();
        }

        // Apply sorting
        match ($this->sortBy) {
            'latest' => $query->orderBy('published_at', 'desc'),
            'oldest' => $query->orderBy('published_at', 'asc'),
            'title_asc' => $query->orderBy('title', 'asc'),
            'title_desc' => $query->orderBy('title', 'desc'),
            'popular' => $query->orderBy('download_count', 'desc'),
            'most_viewed' => $query->orderBy('view_count', 'desc'),
            default => $query->orderBy('published_at', 'desc'),
        };

        return $query->paginate($this->perPage);
    }

    #[Computed]
    public function categories()
    {
        return DocumentCategory::active()
            ->parent()
            ->ordered()
            ->withCount(['documents' => function ($query) {
                $query->published()->public();
            }])
            ->get();
    }

    #[Computed]
    public function types()
    {
        return DocumentType::active()
            ->withCount(['documents' => function ($query) {
                $query->published()->public();
            }])
            ->get();
    }

    #[Computed]
    public function departments()
    {
        return Department::active()
            ->withCount(['documents' => function ($query) {
                $query->published()->public();
            }])
            ->get();
    }

    #[Computed]
    public function featuredDocuments()
    {
        return Document::query()
            ->published()
            ->public()
            ->featured()
            ->with(['category', 'type'])
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();
    }

    #[Computed]
    public function recentDocuments()
    {
        return Document::query()
            ->published()
            ->public()
            ->where('published_at', '>=', now()->subDays(30))
            ->with(['category', 'type'])
            ->orderBy('published_at', 'desc')
            ->take(8)
            ->get();
    }

    #[Computed]
    public function popularDocuments()
    {
        return Document::query()
            ->published()
            ->public()
            ->where('download_count', '>', 0)
            ->with(['category', 'type'])
            ->orderBy('download_count', 'desc')
            ->take(8)
            ->get();
    }

    #[Computed]
    public function totalDocuments()
    {
        return Document::published()->public()->count();
    }

    #[Computed]
    public function activeFiltersCount()
    {
        $count = 0;
        if ($this->search) $count++;
        if ($this->selectedCategory) $count++;
        if ($this->selectedType) $count++;
        if ($this->selectedDepartment) $count++;
        if ($this->showFeatured) $count++;
        if ($this->showNew) $count++;
        return $count;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedSelectedType()
    {
        $this->resetPage();
    }

    public function updatedSelectedDepartment()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function updatedShowFeatured()
    {
        $this->resetPage();
    }

    public function updatedShowNew()
    {
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function setCategory($categorySlug)
    {
        $this->selectedCategory = $categorySlug;
        $this->resetPage();
    }

    public function setType($typeSlug)
    {
        $this->selectedType = $typeSlug;
        $this->resetPage();
    }

    public function setDepartment($departmentSlug)
    {
        $this->selectedDepartment = $departmentSlug;
        $this->resetPage();
    }

    public function setSortBy($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function clearFilters()
    {
        $this->reset([
            'search',
            'selectedCategory',
            'selectedType',
            'selectedDepartment',
            'showFeatured',
            'showNew'
        ]);
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function downloadDocument($documentId)
    {
        $document = Document::findOrFail($documentId);

        if (!$document->canBeAccessedBy(auth()->user())) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'คุณไม่มีสิทธิ์เข้าถึงเอกสารนี้'
            ]);
            return;
        }

        // Record download
        $document->downloads()->create([
            'user_id' => auth()->id(),
            'user_type' => auth()->check() ? (auth()->user()->user_type ?? 'public') : 'anonymous',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
        ]);

        // Increment download count
        $document->incrementDownloadCount();

        // Redirect to download
        return redirect()->to($document->file_url);
    }

    public function viewDocument($documentId)
    {
        $document = Document::findOrFail($documentId);

        if (!$document->canBeAccessedBy(auth()->user())) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'คุณไม่มีสิทธิ์เข้าถึงเอกสารนี้'
            ]);
            return;
        }

        // Increment view count
        $document->incrementViewCount();

        // Redirect to document detail page
        return redirect()->route('documents.show', $document);
    }

    public function getSortOptions()
    {
        return [
            'latest' => 'ใหม่ล่าสุด',
            'oldest' => 'เก่าสุด',
            'title_asc' => 'ชื่อ A-Z',
            'title_desc' => 'ชื่อ Z-A',
            'popular' => 'ดาวน์โหลดมากสุด',
            'most_viewed' => 'เปิดดูมากสุด',
        ];
    }

    public function render()
    {
        return view('livewire.frontend.document.front-document-list', [
            'documents' => $this->documents,
            'categories' => $this->categories,
            'types' => $this->types,
            'departments' => $this->departments,
            'featuredDocuments' => $this->featuredDocuments,
            'recentDocuments' => $this->recentDocuments,
            'popularDocuments' => $this->popularDocuments,
            'totalDocuments' => $this->totalDocuments,
            'activeFiltersCount' => $this->activeFiltersCount,
            'sortOptions' => $this->getSortOptions(),
        ]);
    }
}
