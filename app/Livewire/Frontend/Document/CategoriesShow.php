<?php

namespace App\Livewire\Frontend\Document;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

#[Layout('components.layouts.app')]
class CategoriesShow extends Component
{
    use WithPagination;

    // Category
    public DocumentCategory $category;

    // Filters
    #[Url(as: 'search')]
    public $search = '';

    #[Url(as: 'type')]
    public $selectedType = '';

    #[Url(as: 'department')]
    public $selectedDepartment = '';

    #[Url(as: 'sort')]
    public $sortBy = 'latest';

    #[Url(as: 'view')]
    public $viewMode = 'list'; // list, grid

    #[Url(as: 'featured')]
    public $showFeatured = false;

    #[Url(as: 'new')]
    public $showNew = false;

    // Display Options
    public $perPage = 15;
    public $showFilters = true;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'search'],
        'selectedType' => ['except' => '', 'as' => 'type'],
        'selectedDepartment' => ['except' => '', 'as' => 'department'],
        'sortBy' => ['except' => 'latest', 'as' => 'sort'],
        'viewMode' => ['except' => 'list', 'as' => 'view'],
        'showFeatured' => ['except' => false, 'as' => 'featured'],
        'showNew' => ['except' => false, 'as' => 'new'],
        'page' => ['except' => 1],
    ];

    public function mount(DocumentCategory $category)
    {
        $this->category = $category;

        // Check if category is active
        if (!$this->category->is_active) {
            abort(404, 'หมวดหมู่ไม่พบหรือไม่ได้เปิดใช้งาน');
        }

        // Load relationships
        $this->category->load(['parent', 'children']);
    }

    public function getTitle(): string
    {
        return $this->category->name . ' - ระบบจัดการเอกสาร';
    }

    #[Computed]
    public function documents()
    {
        $query = Document::query()
            ->with(['category', 'type', 'department', 'creator'])
            ->published()
            ->public();

        // Get all descendant category IDs including current category
        $categoryIds = [$this->category->id];
        $descendants = $this->category->getAllDescendants();
        $categoryIds = array_merge($categoryIds, $descendants->pluck('id')->toArray());

        // Filter by category and subcategories
        $query->whereIn('document_category_id', $categoryIds);

        // Apply search
        if ($this->search) {
            $query->search($this->search);
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
    public function types()
    {
        // Get types that have documents in this category and its descendants
        $categoryIds = [$this->category->id];
        $descendants = $this->category->getAllDescendants();
        $categoryIds = array_merge($categoryIds, $descendants->pluck('id')->toArray());

        return DocumentType::active()
            ->whereHas('documents', function ($query) use ($categoryIds) {
                $query->published()->public()->whereIn('document_category_id', $categoryIds);
            })
            ->withCount(['documents' => function ($query) use ($categoryIds) {
                $query->published()->public()->whereIn('document_category_id', $categoryIds);
            }])
            ->get();
    }

    #[Computed]
    public function departments()
    {
        // Get departments that have documents in this category and its descendants
        $categoryIds = [$this->category->id];
        $descendants = $this->category->getAllDescendants();
        $categoryIds = array_merge($categoryIds, $descendants->pluck('id')->toArray());

        return Department::active()
            ->whereHas('documents', function ($query) use ($categoryIds) {
                $query->published()->public()->whereIn('document_category_id', $categoryIds);
            })
            ->withCount(['documents' => function ($query) use ($categoryIds) {
                $query->published()->public()->whereIn('document_category_id', $categoryIds);
            }])
            ->get();
    }

    #[Computed]
    public function subcategories()
    {
        return $this->category->children()
            ->active()
            ->ordered()
            ->withCount(['documents' => function ($query) {
                $query->published()->public();
            }])
            ->get();
    }

    #[Computed]
    public function featuredDocuments()
    {
        $categoryIds = [$this->category->id];
        $descendants = $this->category->getAllDescendants();
        $categoryIds = array_merge($categoryIds, $descendants->pluck('id')->toArray());

        return Document::query()
            ->published()
            ->public()
            ->featured()
            ->whereIn('document_category_id', $categoryIds)
            ->with(['category', 'type'])
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();
    }

    #[Computed]
    public function recentDocuments()
    {
        $categoryIds = [$this->category->id];
        $descendants = $this->category->getAllDescendants();
        $categoryIds = array_merge($categoryIds, $descendants->pluck('id')->toArray());

        return Document::query()
            ->published()
            ->public()
            ->whereIn('document_category_id', $categoryIds)
            ->where('published_at', '>=', now()->subDays(30))
            ->with(['category', 'type'])
            ->orderBy('published_at', 'desc')
            ->take(8)
            ->get();
    }

    #[Computed]
    public function popularDocuments()
    {
        $categoryIds = [$this->category->id];
        $descendants = $this->category->getAllDescendants();
        $categoryIds = array_merge($categoryIds, $descendants->pluck('id')->toArray());

        return Document::query()
            ->published()
            ->public()
            ->whereIn('document_category_id', $categoryIds)
            ->where('download_count', '>', 0)
            ->with(['category', 'type'])
            ->orderBy('download_count', 'desc')
            ->take(8)
            ->get();
    }

    #[Computed]
    public function totalDocuments()
    {
        $categoryIds = [$this->category->id];
        $descendants = $this->category->getAllDescendants();
        $categoryIds = array_merge($categoryIds, $descendants->pluck('id')->toArray());

        return Document::published()
            ->public()
            ->whereIn('document_category_id', $categoryIds)
            ->count();
    }

    #[Computed]
    public function breadcrumbs()
    {
        $breadcrumbs = [
            ['name' => 'หน้าแรก', 'url' => route('home')],
            ['name' => 'เอกสาร', 'url' => route('documents.index')],
        ];

        // Add category breadcrumbs
        $categoryBreadcrumbs = $this->category->breadcrumb;
        foreach ($categoryBreadcrumbs as $category) {
            $breadcrumbs[] = [
                'name' => $category['name'],
                'url' => $category['id'] === $this->category->id
                    ? null
                    : route('documents.categories.show', $category['slug'])
            ];
        }

        return $breadcrumbs;
    }

    #[Computed]
    public function activeFiltersCount()
    {
        $count = 0;
        if ($this->search) $count++;
        if ($this->selectedType) $count++;
        if ($this->selectedDepartment) $count++;
        if ($this->showFeatured) $count++;
        if ($this->showNew) $count++;
        return $count;
    }

    // Event Handlers
    public function updatedSearch()
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

    // Actions
    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
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
            'selectedType',
            'selectedDepartment',
            'showFeatured',
            'showNew'
        ]);
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 15;
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

    public function viewSubcategory($subcategorySlug)
    {
        return redirect()->route('documents.categories.show', $subcategorySlug);
    }

    // Helper methods
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
        return view('livewire.frontend.document.categories-show', [
            'documents' => $this->documents,
            'types' => $this->types,
            'departments' => $this->departments,
            'subcategories' => $this->subcategories,
            'featuredDocuments' => $this->featuredDocuments,
            'recentDocuments' => $this->recentDocuments,
            'popularDocuments' => $this->popularDocuments,
            'totalDocuments' => $this->totalDocuments,
            'breadcrumbs' => $this->breadcrumbs,
            'activeFiltersCount' => $this->activeFiltersCount,
            'sortOptions' => $this->getSortOptions(),
        ])
        ->title($this->getTitle());
    }
}
