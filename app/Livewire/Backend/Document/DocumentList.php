<?php
namespace App\Livewire\Backend\Document;

use Livewire\Component;
use App\Models\Document;
use App\Models\Department;
use App\Models\DocumentType;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Builder;


class DocumentList extends Component
{
    use WithPagination;

    #[Url( as : 'search')]
    public $search = '';

    #[Url( as : 'status')]
    public $selectedStatus = '';

    #[Url( as : 'category')]
    public $selectedCategory = '';

    #[Url( as : 'type')]
    public $selectedType = '';

    #[Url( as : 'department')]
    public $selectedDepartment = '';

    #[Url( as : 'access')]
    public $selectedAccessLevel = '';

    #[Url( as : 'sort')]
    public $sortBy = 'created_at';

    #[Url( as : 'direction')]
    public $sortDirection = 'desc';

    public $perPage = 10;
    public $selectedDocuments = [];
    public $selectAll = false;

    protected $queryString = [
        'search'              => ['except' => ''],
        'selectedStatus'      => ['except' => ''],
        'selectedCategory'    => ['except' => ''],
        'selectedType'        => ['except' => ''],
        'selectedDepartment'  => ['except' => ''],
        'selectedAccessLevel' => ['except' => ''],
        'sortBy'              => ['except' => 'created_at'],
        'sortDirection'       => ['except' => 'desc'],
        'page'                => ['except' => 1],
    ];

    public function mount()
    {
        $this->authorize('documents.view');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedStatus()
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

    public function updatedSelectedAccessLevel()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedDocuments = $this->documents->pluck('id')->toArray();
        } else {
            $this->selectedDocuments = [];
        }
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy        = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function getDocumentsProperty()
    {
        return Document::query()
            ->with(['category', 'type', 'creator', 'department'])
            ->when($this->search, function (Builder $query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('document_number', 'like', "%{$search}%")
                        ->orWhere('keywords', 'like', "%{$search}%");
                });
            })
            ->when($this->selectedStatus, function (Builder $query, $status) {
                $query->where('status', $status);
            })
            ->when($this->selectedCategory, function (Builder $query, $categoryId) {
                $query->where('document_category_id', $categoryId);
            })
            ->when($this->selectedType, function (Builder $query, $typeId) {
                $query->where('document_type_id', $typeId);
            })
            ->when($this->selectedDepartment, function (Builder $query, $departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->when($this->selectedAccessLevel, function (Builder $query, $accessLevel) {
                $query->where('access_level', $accessLevel);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getCategoriesProperty()
    {
        return DocumentCategory::active()
            ->orderBy('name')
            ->get();
    }

    public function getTypesProperty()
    {
        return DocumentType::active()
            ->orderBy('name')
            ->get();
    }

    public function getDepartmentsProperty()
    {
        return Department::active()
            ->orderBy('name')
            ->get();
    }

    public function getStatusOptionsProperty()
    {
        return [
            'draft'     => 'แบบร่าง',
            'published' => 'เผยแพร่',
            'archived'  => 'เก็บถาวร',
        ];
    }

    public function getAccessLevelOptionsProperty()
    {
        return [
            'public'     => 'สาธารณะ',
            'registered' => 'สมาชิก',
        ];
    }

    public function clearFilters()
    {
        $this->reset([
            'search',
            'selectedStatus',
            'selectedCategory',
            'selectedType',
            'selectedDepartment',
            'selectedAccessLevel',
        ]);
        $this->resetPage();
    }

    public function bulkDelete()
    {
        $this->authorize('documents.delete');

        if (empty($this->selectedDocuments)) {
            $this->addError('bulk', 'กรุณาเลือกเอกสารที่ต้องการลบ');
            return;
        }

        $deletedCount = Document::whereIn('id', $this->selectedDocuments)->delete();

        $this->selectedDocuments = [];
        $this->selectAll         = false;

        session()->flash('success', "ลบเอกสาร {$deletedCount} รายการเรียบร้อยแล้ว");
        $this->resetPage();
    }

    public function bulkPublish()
    {
        $this->authorize('documents.publish');

        if (empty($this->selectedDocuments)) {
            $this->addError('bulk', 'กรุณาเลือกเอกสารที่ต้องการเผยแพร่');
            return;
        }

        $publishedCount = Document::whereIn('id', $this->selectedDocuments)
            ->where('status', '!=', 'published')
            ->update([
                'status'       => 'published',
                'published_at' => now(),
            ]);

        $this->selectedDocuments = [];
        $this->selectAll         = false;

        session()->flash('success', "เผยแพร่เอกสาร {$publishedCount} รายการเรียบร้อยแล้ว");
    }

    public function bulkArchive()
    {
        $this->authorize('documents.archive');

        if (empty($this->selectedDocuments)) {
            $this->addError('bulk', 'กรุณาเลือกเอกสารที่ต้องการเก็บถาวร');
            return;
        }

        $archivedCount = Document::whereIn('id', $this->selectedDocuments)
            ->where('status', '!=', 'archived')
            ->update(['status' => 'archived']);

        $this->selectedDocuments = [];
        $this->selectAll         = false;

        session()->flash('success', "เก็บถาวรเอกสาร {$archivedCount} รายการเรียบร้อยแล้ว");
    }

    public function deleteDocument($documentId)
    {
        $this->authorize('documents.delete');

        $document = Document::findOrFail($documentId);
        $document->delete();

        session()->flash('success', "ลบเอกสาร '{$document->title}' เรียบร้อยแล้ว");
    }

    public function toggleDocumentStatus($documentId)
    {
        $document = Document::findOrFail($documentId);

        if ($document->status === 'published') {
            $this->authorize('documents.archive');
            $document->update(['status' => 'archived']);
            session()->flash('success', "เก็บถาวรเอกสาร '{$document->title}' เรียบร้อยแล้ว");
        } else {
            $this->authorize('documents.publish');
            $document->update([
                'status'       => 'published',
                'published_at' => now(),
            ]);
            session()->flash('success', "เผยแพร่เอกสาร '{$document->title}' เรียบร้อยแล้ว");
        }
    }

    public function toggleFeatured($documentId)
    {
        $this->authorize('documents.feature');

        $document = Document::findOrFail($documentId);
        $document->update(['is_featured' => ! $document->is_featured]);

        $status = $document->is_featured ? 'เพิ่มเป็นเอกสารแนะนำ' : 'ยกเลิกเอกสารแนะนำ';
        session()->flash('success', "{$status} '{$document->title}' เรียบร้อยแล้ว");
    }

    #[Layout('components.layouts.dashboard')]
    public function render()
    {
        return view('livewire.backend.document.document-list', [
            'documents'          => $this->documents,
            'categories'         => $this->categories,
            'types'              => $this->types,
            'departments'        => $this->departments,
            'statusOptions'      => $this->statusOptions,
            'accessLevelOptions' => $this->accessLevelOptions,
        ]);
    }
}
