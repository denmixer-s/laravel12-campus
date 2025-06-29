<?php

namespace App\Livewire\Frontend\Document;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class DocumentSearch extends Component
{
    use WithPagination;

    // Search Parameters
    #[Url(as: 'q')]
    public $query = '';

    #[Url(as: 'cat')]
    public $category_id = '';

    #[Url(as: 'type')]
    public $type_id = '';

    #[Url(as: 'dept')]
    public $department_id = '';

    #[Url(as: 'from')]
    public $date_from = '';

    #[Url(as: 'to')]
    public $date_to = '';

    #[Url(as: 'access')]
    public $access_level = '';

    #[Url(as: 'sort')]
    public $sort_by = 'created_at';

    #[Url(as: 'dir')]
    public $sort_direction = 'desc';

    #[Url(as: 'view')]
    public $view_mode = 'list';

    // Display Options
    public $per_page = 20;
    public $show_filters = true;
    public $tags = [];
    public $tag_input = '';

    // Data Collections
    public $categories = [];
    public $types = [];
    public $departments = [];

    // Results
    public $total_results = 0;
    public $search_time = 0;
    public $recent_searches = [];

    // UI State
    public $loading = false;
    public $show_suggestions = false;
    public $suggestions = [];

    protected $queryString = [
        'query' => ['except' => '', 'as' => 'q'],
        'category_id' => ['except' => '', 'as' => 'cat'],
        'type_id' => ['except' => '', 'as' => 'type'],
        'department_id' => ['except' => '', 'as' => 'dept'],
        'date_from' => ['except' => '', 'as' => 'from'],
        'date_to' => ['except' => '', 'as' => 'to'],
        'access_level' => ['except' => '', 'as' => 'access'],
        'sort_by' => ['except' => 'created_at', 'as' => 'sort'],
        'sort_direction' => ['except' => 'desc', 'as' => 'dir'],
        'view_mode' => ['except' => 'list', 'as' => 'view'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        // Debug log
        \Log::info('DocumentSearch mount called', [
            'url' => request()->url(),
            'path' => request()->path(),
            'route' => request()->route()?->getName()
        ]);

        $this->loadSearchData();
        $this->loadRecentSearches();

        // Auto-search if query exists
        if ($this->query) {
            $this->search();
        }
    }

    public function loadSearchData()
    {
        $this->categories = DocumentCategory::active()
            ->orderBy('name')
            ->get(['id', 'name', 'parent_id']);

        $this->types = DocumentType::active()
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->departments = Department::active()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function search()
    {
        $this->loading = true;
        $this->resetPage();

        // Save to recent searches
        $this->saveRecentSearch();

        $this->loading = false;
    }

    public function clearSearch()
    {
        $this->reset([
            'query', 'category_id', 'type_id', 'department_id',
            'date_from', 'date_to', 'access_level', 'tags'
        ]);

        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->show_filters = !$this->show_filters;
    }

    public function setSortBy($field)
    {
        if ($this->sort_by === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $field;
            $this->sort_direction = 'desc';
        }

        $this->resetPage();
    }

    public function setViewMode($mode)
    {
        $this->view_mode = $mode;
    }

    public function addTag()
    {
        if (empty($this->tag_input)) return;

        $tag = trim($this->tag_input);
        if (!in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
        }

        $this->tag_input = '';
        $this->resetPage();
    }

    public function removeTag($index)
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
        $this->resetPage();
    }

    public function updatedQuery()
    {
        $this->loadSuggestions();
    }

    public function loadSuggestions()
    {
        if (strlen($this->query) < 2) {
            $this->suggestions = [];
            $this->show_suggestions = false;
            return;
        }

        $this->suggestions = Document::published()
            ->public()
            ->where('title', 'LIKE', "%{$this->query}%")
            ->limit(5)
            ->pluck('title')
            ->toArray();

        $this->show_suggestions = count($this->suggestions) > 0;
    }

    public function selectSuggestion($suggestion)
    {
        $this->query = $suggestion;
        $this->show_suggestions = false;
        $this->search();
    }

    public function loadRecentSearches()
    {
        $this->recent_searches = session('recent_document_searches', []);
    }

    public function saveRecentSearch()
    {
        if (empty($this->query)) return;

        $searches = session('recent_document_searches', []);

        // Remove if already exists
        $searches = array_filter($searches, fn($s) => $s !== $this->query);

        // Add to beginning
        array_unshift($searches, $this->query);

        // Keep only last 10
        $searches = array_slice($searches, 0, 10);

        session(['recent_document_searches' => $searches]);
        $this->recent_searches = $searches;
    }

    public function useRecentSearch($search)
    {
        $this->query = $search;
        $this->search();
    }

    public function render()
    {
        $start_time = microtime(true);

        $documents = Document::published()
            ->public()
            ->when($this->query, function ($query) {
                $query->search($this->query);
            })
            ->when($this->category_id, function ($query) {
                $query->byCategory($this->category_id);
            })
            ->when($this->type_id, function ($query) {
                $query->byType($this->type_id);
            })
            ->when($this->department_id, function ($query) {
                $query->byDepartment($this->department_id);
            })
            ->when($this->date_from, function ($query) {
                $query->whereDate('document_date', '>=', $this->date_from);
            })
            ->when($this->date_to, function ($query) {
                $query->whereDate('document_date', '<=', $this->date_to);
            })
            ->when($this->access_level, function ($query) {
                $query->where('access_level', $this->access_level);
            })
            ->when(!empty($this->tags), function ($query) {
                foreach ($this->tags as $tag) {
                    $query->whereJsonContains('tags', $tag);
                }
            })
            ->with(['category', 'type', 'department', 'creator'])
            ->orderBy($this->sort_by, $this->sort_direction)
            ->paginate($this->per_page);

        $this->total_results = $documents->total();
        $this->search_time = round((microtime(true) - $start_time) * 1000, 2);

        return view('livewire.frontend.document.document-search', [
            'documents' => $documents,
            'hierarchical_categories' => $this->getHierarchicalCategories(),
        ]);
    }

    public function getHierarchicalCategories()
    {
        $categories = collect();

        // Get root categories first
        $roots = $this->categories->where('parent_id', null);

        foreach ($roots as $root) {
            $categories->push($root);

            // Get children
            $children = $this->categories->where('parent_id', $root->id);
            foreach ($children as $child) {
                $child->name = '-- ' . $child->name;
                $categories->push($child);
            }
        }

        return $categories;
    }

    public function getSortOptions()
    {
        return [
            'created_at' => 'วันที่สร้าง',
            'title' => 'ชื่อเอกสาร',
            'document_date' => 'วันที่เอกสาร',
            'download_count' => 'ยอดดาวน์โหลด',
            'view_count' => 'ยอดเข้าชม',
        ];
    }

    public function getAccessLevelOptions()
    {
        return [
            '' => 'ทั้งหมด',
            'public' => 'สาธารณะ',
            'registered' => 'สมาชิก',
        ];
    }

    // Helper methods for view
    public function hasActiveFilters()
    {
        return !empty($this->category_id) ||
               !empty($this->type_id) ||
               !empty($this->department_id) ||
               !empty($this->date_from) ||
               !empty($this->date_to) ||
               !empty($this->access_level) ||
               !empty($this->tags);
    }

    public function getActiveFiltersCount()
    {
        $count = 0;
        if (!empty($this->category_id)) $count++;
        if (!empty($this->type_id)) $count++;
        if (!empty($this->department_id)) $count++;
        if (!empty($this->date_from)) $count++;
        if (!empty($this->date_to)) $count++;
        if (!empty($this->access_level)) $count++;
        if (!empty($this->tags)) $count += count($this->tags);

        return $count;
    }

    public function getSearchSummary()
    {
        $summary = [];

        if ($this->query) {
            $summary[] = "คำค้นหา: \"{$this->query}\"";
        }

        if ($this->category_id) {
            $category = $this->categories->firstWhere('id', $this->category_id);
            $summary[] = "หมวดหมู่: {$category->name}";
        }

        if ($this->type_id) {
            $type = $this->types->firstWhere('id', $this->type_id);
            $summary[] = "ประเภท: {$type->name}";
        }

        if ($this->department_id) {
            $dept = $this->departments->firstWhere('id', $this->department_id);
            $summary[] = "แผนก: {$dept->name}";
        }

        if ($this->date_from && $this->date_to) {
            $summary[] = "ช่วงวันที่: {$this->date_from} ถึง {$this->date_to}";
        } elseif ($this->date_from) {
            $summary[] = "ตั้งแต่: {$this->date_from}";
        } elseif ($this->date_to) {
            $summary[] = "จนถึง: {$this->date_to}";
        }

        if (!empty($this->tags)) {
            $summary[] = "แท็ก: " . implode(', ', $this->tags);
        }

        return implode(' | ', $summary);
    }
}
