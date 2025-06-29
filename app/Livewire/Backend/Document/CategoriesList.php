<?php

namespace App\Livewire\Backend\Document;

use App\Models\DocumentCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
class CategoriesList extends Component
{
    use WithPagination;

    public $search = '';
    public $showOnlyParents = false;
    public $statusFilter = 'all'; // all, active, inactive
    public $sortBy = 'sort_order';
    public $sortDirection = 'asc';

    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form data
    public $categoryId = null;
    public $name = '';
    public $description = '';
    public $parent_id = null;
    public $sort_order = 0;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'parent_id' => 'nullable|exists:document_categories,id',
        'sort_order' => 'integer|min:0',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'ชื่อหมวดหมู่จำเป็นต้องระบุ',
        'name.max' => 'ชื่อหมวดหมู่ต้องไม่เกิน 255 ตัวอักษร',
        'parent_id.exists' => 'หมวดหมู่หลักที่เลือกไม่ถูกต้อง',
        'sort_order.integer' => 'ลำดับต้องเป็นตัวเลข',
        'sort_order.min' => 'ลำดับต้องไม่น้อยกว่า 0',
    ];

    public function mount()
    {
        // Check permission
        if (!auth()->user()->can('document-categories.view')) {
            abort(403, 'ไม่มีสิทธิ์เข้าถึงหน้านี้');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingShowOnlyParents()
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

    public function openCreateModal()
    {
        if (!auth()->user()->can('document-categories.create')) {
            session()->flash('error', 'ไม่มีสิทธิ์สร้างหมวดหมู่');
            return;
        }

        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($categoryId)
    {
        if (!auth()->user()->can('document-categories.edit')) {
            session()->flash('error', 'ไม่มีสิทธิ์แก้ไขหมวดหมู่');
            return;
        }

        $category = DocumentCategory::findOrFail($categoryId);

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->sort_order = $category->sort_order;
        $this->is_active = $category->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($categoryId)
    {
        if (!auth()->user()->can('document-categories.delete')) {
            session()->flash('error', 'ไม่มีสิทธิ์ลบหมวดหมู่');
            return;
        }

        $this->categoryId = $categoryId;
        $this->showDeleteModal = true;
    }

    public function createCategory()
    {
        if (!auth()->user()->can('document-categories.create')) {
            session()->flash('error', 'ไม่มีสิทธิ์สร้างหมวดหมู่');
            return;
        }

        $this->validate();

        // Check for circular reference
        if ($this->parent_id && $this->wouldCreateCircularReference($this->parent_id)) {
            $this->addError('parent_id', 'ไม่สามารถเลือกหมวดหมู่นี้เป็นหมวดหมู่หลักได้ เนื่องจากจะสร้างการอ้างอิงแบบวงกลม');
            return;
        }

        DocumentCategory::create([
            'name' => $this->name,
            'slug' => str()->slug($this->name),
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('message', 'สร้างหมวดหมู่เรียบร้อยแล้ว');

        $this->dispatch('categoryCreated');
    }

    public function updateCategory()
    {
        if (!auth()->user()->can('document-categories.edit')) {
            session()->flash('error', 'ไม่มีสิทธิ์แก้ไขหมวดหมู่');
            return;
        }

        $this->validate();

        $category = DocumentCategory::findOrFail($this->categoryId);

        // Check for circular reference (excluding self)
        if ($this->parent_id &&
            $this->parent_id != $category->id &&
            $this->wouldCreateCircularReference($this->parent_id, $category->id)) {
            $this->addError('parent_id', 'ไม่สามารถเลือกหมวดหมู่นี้เป็นหมวดหมู่หลักได้ เนื่องจากจะสร้างการอ้างอิงแบบวงกลม');
            return;
        }

        $category->update([
            'name' => $this->name,
            'slug' => str()->slug($this->name),
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ]);

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('message', 'แก้ไขหมวดหมู่เรียบร้อยแล้ว');

        $this->dispatch('categoryUpdated');
    }

    public function deleteCategory()
    {
        if (!auth()->user()->can('document-categories.delete')) {
            session()->flash('error', 'ไม่มีสิทธิ์ลบหมวดหมู่');
            return;
        }

        $category = DocumentCategory::findOrFail($this->categoryId);

        // Check if category has children
        if ($category->children()->count() > 0) {
            session()->flash('error', 'ไม่สามารถลบหมวดหมู่ที่มีหมวดหมู่ย่อยได้');
            $this->showDeleteModal = false;
            return;
        }

        // Check if category has documents
        if ($category->documents()->count() > 0) {
            session()->flash('error', 'ไม่สามารถลบหมวดหมู่ที่มีเอกสารได้');
            $this->showDeleteModal = false;
            return;
        }

        $category->delete();

        $this->showDeleteModal = false;
        $this->categoryId = null;
        session()->flash('message', 'ลบหมวดหมู่เรียบร้อยแล้ว');

        $this->dispatch('categoryDeleted');
    }

    public function toggleStatus($categoryId)
    {
        if (!auth()->user()->can('document-categories.edit')) {
            session()->flash('error', 'ไม่มีสิทธิ์แก้ไขสถานะหมวดหมู่');
            return;
        }

        $category = DocumentCategory::findOrFail($categoryId);
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        session()->flash('message', "เปลี่ยนสถานะหมวดหมู่เป็น{$status}แล้ว");
    }

    public function moveUp($categoryId)
    {
        if (!auth()->user()->can('document-categories.edit')) {
            return;
        }

        $category = DocumentCategory::findOrFail($categoryId);
        $previousCategory = DocumentCategory::where('parent_id', $category->parent_id)
            ->where('sort_order', '<', $category->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previousCategory) {
            $tempOrder = $category->sort_order;
            $category->update(['sort_order' => $previousCategory->sort_order]);
            $previousCategory->update(['sort_order' => $tempOrder]);
        }
    }

    public function moveDown($categoryId)
    {
        if (!auth()->user()->can('document-categories.edit')) {
            return;
        }

        $category = DocumentCategory::findOrFail($categoryId);
        $nextCategory = DocumentCategory::where('parent_id', $category->parent_id)
            ->where('sort_order', '>', $category->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($nextCategory) {
            $tempOrder = $category->sort_order;
            $category->update(['sort_order' => $nextCategory->sort_order]);
            $nextCategory->update(['sort_order' => $tempOrder]);
        }
    }

    private function wouldCreateCircularReference($parentId, $excludeId = null)
    {
        $current = DocumentCategory::find($parentId);

        while ($current) {
            if ($current->id === $excludeId) {
                return true;
            }
            $current = $current->parent;
        }

        return false;
    }

    private function resetForm()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->description = '';
        $this->parent_id = null;
        $this->sort_order = 0;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    #[On('categoryCreated')]
    #[On('categoryUpdated')]
    #[On('categoryDeleted')]
    public function refreshComponent()
    {
        // This method will be called when any category CRUD operation is completed
        // to refresh the component if needed
    }

    public function render()
    {
        $query = DocumentCategory::query()
            ->with(['parent', 'children'])
            ->withCount(['documents', 'children']);

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // Parent filter
        if ($this->showOnlyParents) {
            $query->whereNull('parent_id');
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        // Secondary sort for consistent ordering
        if ($this->sortBy !== 'sort_order') {
            $query->orderBy('sort_order', 'asc');
        }
        if ($this->sortBy !== 'name') {
            $query->orderBy('name', 'asc');
        }

        $categories = $query->paginate(15);

        // Get parent categories for the form dropdown
        $parentCategories = DocumentCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('livewire.backend.document.categories-list', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
        ]);
    }
}
