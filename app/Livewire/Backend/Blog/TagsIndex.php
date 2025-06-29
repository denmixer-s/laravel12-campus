<?php

namespace App\Livewire\Backend\Blog;

use App\Models\BlogTag;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

#[Layout('components.layouts.dashboard')]
#[Title('จัดการแท็ก - Blog Management')]
class TagsIndex extends Component
{
    use WithPagination;

    // Search & Filter Properties
    public $search = '';
    public $statusFilter = 'all'; // all, active, inactive
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 15;

    // Bulk Actions
    public $selectedTags = [];
    public $selectAll = false;
    public $bulkAction = '';

    // Quick Edit
    public $editingTag = null;
    public $editForm = [
        'name' => '',
        'description' => '',
        'color' => '#10B981',
        'is_active' => true
    ];

    // Stats
    public $totalTags = 0;
    public $activeTags = 0;
    public $inactiveTags = 0;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 15]
    ];

    public function mount()
    {
        $this->loadStats();
    }


    private function getTagsQuery()
    {
        $query = BlogTag::query();

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedTags = $this->getTagsQuery()->pluck('id')->toArray();
        } else {
            $this->selectedTags = [];
        }
    }

    public function loadStats()
    {
        $this->totalTags = BlogTag::count();
        $this->activeTags = BlogTag::where('is_active', true)->count();
        $this->inactiveTags = BlogTag::where('is_active', false)->count();
    }

    public function toggleStatus($tagId)
    {
        $tag = BlogTag::findOrFail($tagId);
        $tag->update(['is_active' => !$tag->is_active]);

        $this->loadStats();
        $this->dispatch('tag-updated', ['message' => 'สถานะแท็กได้รับการอัปเดตแล้ว']);
    }

    public function deleteTag($tagId)
    {
        $tag = BlogTag::findOrFail($tagId);

        // Check if tag has posts
        if ($tag->posts_count > 0) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'ไม่สามารถลบแท็กที่มีโพสต์ได้ กรุณาย้ายโพสต์ไปยังแท็กอื่นก่อน'
            ]);
            return;
        }

        $tag->delete();

        $this->loadStats();
        $this->dispatch('tag-deleted', ['message' => 'ลบแท็กเรียบร้อยแล้ว']);
    }

    public function startEdit($tagId)
    {
        $tag = BlogTag::findOrFail($tagId);
        $this->editingTag = $tagId;
        $this->editForm = [
            'name' => $tag->name,
            'description' => $tag->description ?? '',
            'color' => $tag->color,
            'is_active' => $tag->is_active
        ];
    }

    public function saveEdit()
    {
        $this->validate([
            'editForm.name' => 'required|string|max:255',
            'editForm.description' => 'nullable|string',
            'editForm.color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'editForm.is_active' => 'boolean'
        ]);

        $tag = BlogTag::findOrFail($this->editingTag);

        // Generate slug if name changed
        $updateData = $this->editForm;
        if ($tag->name !== $this->editForm['name']) {
            $updateData['slug'] = \Str::slug($this->editForm['name']);
        }

        $tag->update($updateData);

        $this->cancelEdit();
        $this->loadStats();
        $this->dispatch('tag-updated', ['message' => 'อัปเดตแท็กเรียบร้อยแล้ว']);
    }

    public function cancelEdit()
    {
        $this->editingTag = null;
        $this->editForm = [
            'name' => '',
            'description' => '',
            'color' => '#10B981',
            'is_active' => true
        ];
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedTags) || empty($this->bulkAction)) {
            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => 'กรุณาเลือกแท็กและการดำเนินการ'
            ]);
            return;
        }

        switch ($this->bulkAction) {
            case 'activate':
                BlogTag::whereIn('id', $this->selectedTags)->update(['is_active' => true]);
                $message = 'เปิดใช้งานแท็กที่เลือกเรียบร้อยแล้ว';
                break;

            case 'deactivate':
                BlogTag::whereIn('id', $this->selectedTags)->update(['is_active' => false]);
                $message = 'ปิดใช้งานแท็กที่เลือกเรียบร้อยแล้ว';
                break;

            case 'delete':
                $tagsWithPosts = BlogTag::whereIn('id', $this->selectedTags)
                    ->where('posts_count', '>', 0)
                    ->count();

                if ($tagsWithPosts > 0) {
                    $this->dispatch('show-alert', [
                        'type' => 'error',
                        'message' => 'ไม่สามารถลบแท็กที่มีโพสต์ได้'
                    ]);
                    return;
                }

                BlogTag::whereIn('id', $this->selectedTags)->delete();
                $message = 'ลบแท็กที่เลือกเรียบร้อยแล้ว';
                break;

            default:
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'การดำเนินการไม่ถูกต้อง'
                ]);
                return;
        }

        $this->selectedTags = [];
        $this->selectAll = false;
        $this->bulkAction = '';
        $this->loadStats();

        $this->dispatch('bulk-action-completed', ['message' => $message]);
    }

    public function refreshStats()
    {
        $this->loadStats();
    }

    #[On('tag-created')]
    public function handleTagCreated()
    {
        $this->loadStats();
    }

    public function render()
    {
        $tags = $this->getTagsQuery();

        return view('livewire.backend.blog.tags-index', [
            'tags' => $tags
        ]);
    }
}
