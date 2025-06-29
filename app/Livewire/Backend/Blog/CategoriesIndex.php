<?php

namespace App\Livewire\Backend\Blog;

use App\Models\BlogCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoriesIndex extends Component
{
    use WithPagination, AuthorizesRequests;

    public $search = '';
    public $sortBy = 'sort_order';
    public $sortDirection = 'asc';
    public $perPage = 15;
    public $selectedCategories = [];
    public $selectAll = false;
    public $showBulkActions = false;
    public $showDeleteModal = false;
    public $showMergeModal = false;
    public $targetCategoryId = null;
    public $deleteConfirmation = '';

    // Bulk action properties
    public $bulkAction = '';
    public $newParentId = null;
    public $mergeIntoCategoryId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'sort_order'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 15],
    ];

    protected $listeners = [
        'categoryDeleted' => 'refreshCategories',
        'categoryUpdated' => 'refreshCategories',
    ];

    public function mount()
    {
        $this->authorize('blog.categories.view');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedCategories = $this->getCategories()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedCategories = [];
        }

        $this->updateBulkActionsVisibility();
    }

    public function updatedSelectedCategories()
    {
        $this->updateBulkActionsVisibility();

        if (empty($this->selectedCategories)) {
            $this->selectAll = false;
        } elseif (count($this->selectedCategories) === $this->getCategories()->count()) {
            $this->selectAll = true;
        }
    }

    private function updateBulkActionsVisibility()
    {
        $this->showBulkActions = count($this->selectedCategories) > 0;
    }

    public function sortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function getCategories()
    {
        return BlogCategory::query()
            ->with(['parent', 'children', 'media'])
            ->withCount(['posts', 'children'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%')
                      ->orWhere('meta_title', 'like', '%' . $this->search . '%')
                      ->orWhere('meta_description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->sortBy === 'posts_count', function ($query) {
                $query->orderBy('posts_count', $this->sortDirection);
            }, function ($query) {
                if ($this->sortBy === 'sort_order') {
                    $query->orderBy('sort_order', $this->sortDirection)
                          ->orderBy('name', 'asc');
                } else {
                    $query->orderBy($this->sortBy, $this->sortDirection);
                }
            })
            ->paginate($this->perPage);
    }

    public function performBulkAction()
    {
        if (empty($this->selectedCategories) || empty($this->bulkAction)) {
            return;
        }

        $this->authorize('blog.categories.edit');

        switch ($this->bulkAction) {
            case 'delete':
                $this->showDeleteModal = true;
                break;
            case 'change_parent':
                $this->changeParentCategory();
                break;
            case 'merge':
                $this->showMergeModal = true;
                break;
            case 'activate':
                $this->toggleCategoriesStatus(true);
                break;
            case 'deactivate':
                $this->toggleCategoriesStatus(false);
                break;
        }
    }

    public function confirmBulkDelete()
    {
        if ($this->deleteConfirmation !== 'DELETE') {
            session()->flash('error', 'กรุณาพิมพ์ "DELETE" เพื่อยืนยันการลบ');
            return;
        }

        $this->authorize('blog.categories.delete');

        $deletedCount = 0;
        foreach ($this->selectedCategories as $categoryId) {
            $category = BlogCategory::find($categoryId);
            if ($category && $category->posts()->count() === 0) {
                $category->delete();
                $deletedCount++;
            }
        }

        $this->resetBulkActions();
        session()->flash('success', "ลบหมวดหมู่สำเร็จ {$deletedCount} รายการ");
        $this->refreshCategories();
    }

    public function changeParentCategory()
    {
        if (empty($this->newParentId)) {
            return;
        }

        $updatedCount = 0;
        foreach ($this->selectedCategories as $categoryId) {
            $category = BlogCategory::find($categoryId);
            if ($category && $categoryId != $this->newParentId) {
                $category->update(['parent_id' => $this->newParentId === 'null' ? null : $this->newParentId]);
                $updatedCount++;
            }
        }

        $this->resetBulkActions();
        session()->flash('success', "อัปเดตหมวดหมู่แม่สำเร็จ {$updatedCount} รายการ");
        $this->refreshCategories();
    }

    public function mergeCategories()
    {
        if (empty($this->mergeIntoCategoryId)) {
            return;
        }

        $targetCategory = BlogCategory::find($this->mergeIntoCategoryId);
        if (!$targetCategory) {
            return;
        }

        $mergedCount = 0;
        foreach ($this->selectedCategories as $categoryId) {
            if ($categoryId == $this->mergeIntoCategoryId) {
                continue;
            }

            $category = BlogCategory::find($categoryId);
            if ($category) {
                // Move all posts to target category
                $category->posts()->update(['category_id' => $this->mergeIntoCategoryId]);

                // Move child categories to target category
                $category->children()->update(['parent_id' => $this->mergeIntoCategoryId]);

                $category->delete();
                $mergedCount++;
            }
        }

        $this->resetBulkActions();
        session()->flash('success', "รวมหมวดหมู่สำเร็จ {$mergedCount} รายการ เข้ากับ \"{$targetCategory->name}\"");
        $this->refreshCategories();
    }

    public function toggleCategoriesStatus($isActive)
    {
        $updatedCount = 0;
        foreach ($this->selectedCategories as $categoryId) {
            $category = BlogCategory::find($categoryId);
            if ($category) {
                $category->update(['is_active' => $isActive]);
                $updatedCount++;
            }
        }

        $status = $isActive ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        $this->resetBulkActions();
        session()->flash('success', "{$status}หมวดหมู่สำเร็จ {$updatedCount} รายการ");
        $this->refreshCategories();
    }

    public function resetBulkActions()
    {
        $this->selectedCategories = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
        $this->showDeleteModal = false;
        $this->showMergeModal = false;
        $this->bulkAction = '';
        $this->newParentId = null;
        $this->mergeIntoCategoryId = null;
        $this->deleteConfirmation = '';
    }

    public function refreshCategories()
    {
        $this->resetBulkActions();
        $this->resetPage();
    }

    public function getAvailableParentCategoriesProperty()
    {
        return BlogCategory::whereNotIn('id', $this->selectedCategories)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();
    }

    public function getAvailableMergeCategoriesProperty()
    {
        return BlogCategory::whereNotIn('id', $this->selectedCategories)
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.backend.blog.categories-index', [
            'categories' => $this->getCategories(),
        ])->layout('components.layouts.dashboard');
    }
}
