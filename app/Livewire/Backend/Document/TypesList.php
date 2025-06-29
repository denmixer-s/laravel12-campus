<?php

namespace App\Livewire\Backend\Document;

use App\Models\DocumentType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
class TypesList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all'; // all, active, inactive
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form data
    public $typeId = null;
    public $name = '';
    public $description = '';
    public $allowed_extensions = [];
    public $is_active = true;

    // Available extensions
    public $availableExtensions = [
        'pdf' => 'PDF',
        'doc' => 'Word Document',
        'docx' => 'Word Document (DOCX)',
        'xls' => 'Excel Spreadsheet',
        'xlsx' => 'Excel Spreadsheet (XLSX)',
        'ppt' => 'PowerPoint Presentation',
        'pptx' => 'PowerPoint Presentation (PPTX)',
        'jpg' => 'JPEG Image',
        'jpeg' => 'JPEG Image',
        'png' => 'PNG Image',
        'gif' => 'GIF Image',
        'txt' => 'Text File',
        'rtf' => 'Rich Text Format',
        'zip' => 'ZIP Archive',
        'rar' => 'RAR Archive',
        'mp4' => 'MP4 Video',
        'avi' => 'AVI Video',
        'mp3' => 'MP3 Audio',
        'wav' => 'WAV Audio',
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'allowed_extensions' => 'nullable|array',
        'allowed_extensions.*' => 'string|max:10',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'ชื่อประเภทเอกสารจำเป็นต้องระบุ',
        'name.max' => 'ชื่อประเภทเอกสารต้องไม่เกิน 255 ตัวอักษร',
        'allowed_extensions.array' => 'รูปแบบไฟล์ต้องเป็นอาเรย์',
        'allowed_extensions.*.string' => 'รูปแบบไฟล์ต้องเป็นข้อความ',
        'allowed_extensions.*.max' => 'รูปแบบไฟล์ต้องไม่เกิน 10 ตัวอักษร',
    ];

    public function mount()
    {
        // Check permission
        if (!auth()->user()->can('document-types.view')) {
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

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    public function openCreateModal()
    {
        if (!auth()->user()->can('document-types.create')) {
            session()->flash('error', 'ไม่มีสิทธิ์สร้างประเภทเอกสาร');
            return;
        }

        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($typeId)
    {
        if (!auth()->user()->can('document-types.edit')) {
            session()->flash('error', 'ไม่มีสิทธิ์แก้ไขประเภทเอกสาร');
            return;
        }

        $type = DocumentType::findOrFail($typeId);

        $this->typeId = $type->id;
        $this->name = $type->name;
        $this->description = $type->description;
        $this->allowed_extensions = $type->allowed_extensions ?? [];
        $this->is_active = $type->is_active;

        $this->showEditModal = true;
    }

    public function openDeleteModal($typeId)
    {
        if (!auth()->user()->can('document-types.delete')) {
            session()->flash('error', 'ไม่มีสิทธิ์ลบประเภทเอกสาร');
            return;
        }

        $this->typeId = $typeId;
        $this->showDeleteModal = true;
    }

    public function createType()
    {
        if (!auth()->user()->can('document-types.create')) {
            session()->flash('error', 'ไม่มีสิทธิ์สร้างประเภทเอกสาร');
            return;
        }

        $this->validate();

        DocumentType::create([
            'name' => $this->name,
            'slug' => str()->slug($this->name),
            'description' => $this->description,
            'allowed_extensions' => $this->allowed_extensions,
            'is_active' => $this->is_active,
        ]);

        $this->showCreateModal = false;
        $this->resetForm();
        session()->flash('message', 'สร้างประเภทเอกสารเรียบร้อยแล้ว');

        $this->dispatch('typeCreated');
    }

    public function updateType()
    {
        if (!auth()->user()->can('document-types.edit')) {
            session()->flash('error', 'ไม่มีสิทธิ์แก้ไขประเภทเอกสาร');
            return;
        }

        $this->validate();

        $type = DocumentType::findOrFail($this->typeId);

        $type->update([
            'name' => $this->name,
            'slug' => str()->slug($this->name),
            'description' => $this->description,
            'allowed_extensions' => $this->allowed_extensions,
            'is_active' => $this->is_active,
        ]);

        $this->showEditModal = false;
        $this->resetForm();
        session()->flash('message', 'แก้ไขประเภทเอกสารเรียบร้อยแล้ว');

        $this->dispatch('typeUpdated');
    }

    public function deleteType()
    {
        if (!auth()->user()->can('document-types.delete')) {
            session()->flash('error', 'ไม่มีสิทธิ์ลบประเภทเอกสาร');
            return;
        }

        $type = DocumentType::findOrFail($this->typeId);

        // Check if type has documents
        if ($type->documents()->count() > 0) {
            session()->flash('error', 'ไม่สามารถลบประเภทเอกสารที่มีเอกสารได้');
            $this->showDeleteModal = false;
            return;
        }

        $type->delete();

        $this->showDeleteModal = false;
        $this->typeId = null;
        session()->flash('message', 'ลบประเภทเอกสารเรียบร้อยแล้ว');

        $this->dispatch('typeDeleted');
    }

    public function toggleStatus($typeId)
    {
        if (!auth()->user()->can('document-types.edit')) {
            session()->flash('error', 'ไม่มีสิทธิ์แก้ไขสถานะประเภทเอกสาร');
            return;
        }

        $type = DocumentType::findOrFail($typeId);
        $type->update(['is_active' => !$type->is_active]);

        $status = $type->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';
        session()->flash('message', "เปลี่ยนสถานะประเภทเอกสารเป็น{$status}แล้ว");
    }

    public function toggleExtension($extension)
    {
        if (in_array($extension, $this->allowed_extensions)) {
            $this->allowed_extensions = array_values(array_diff($this->allowed_extensions, [$extension]));
        } else {
            $this->allowed_extensions[] = $extension;
        }
    }

    private function resetForm()
    {
        $this->typeId = null;
        $this->name = '';
        $this->description = '';
        $this->allowed_extensions = [];
        $this->is_active = true;
        $this->resetErrorBag();
    }

    #[On('typeCreated')]
    #[On('typeUpdated')]
    #[On('typeDeleted')]
    public function refreshComponent()
    {
        // This method will be called when any type CRUD operation is completed
        // to refresh the component if needed
    }

    public function render()
    {
        $query = DocumentType::query()
            ->withCount('documents');

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        // Secondary sort for consistent ordering
        if ($this->sortBy !== 'name') {
            $query->orderBy('name', 'asc');
        }

        $types = $query->paginate(15);

        return view('livewire.backend.document.types-list', [
            'types' => $types,
        ]);
    }
}
