<?php
namespace App\Livewire\Backend\Document;

use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.dashboard')]
#[Title('ถังขยะเอกสาร')]
class DocumentsTrash extends Component
{
    use WithPagination;

    // Search and filters
    public $search           = '';
    public $categoryFilter   = '';
    public $typeFilter       = '';
    public $departmentFilter = '';
    public $sortBy           = 'deleted_at';
    public $sortDirection    = 'desc';
    public $perPage          = 10;

    // UI state
    public $confirmingRestore         = false;
    public $confirmingPermanentDelete = false;
    public $confirmingEmptyTrash      = false;
    public $documentToRestore         = null;
    public $documentToDelete          = null;
    public $selectedDocuments         = [];
    public $selectAll                 = false;

    // Statistics
    public $totalTrashedDocuments = 0;
    public $trashedThisWeek       = 0;
    public $trashedThisMonth      = 0;
    public $totalTrashedSize      = 0;

    // Available data for filters
    public $availableCategories;
    public $availableTypes;
    public $availableDepartments;

    protected $queryString = [
        'search'           => ['except' => ''],
        'categoryFilter'   => ['except' => ''],
        'typeFilter'       => ['except' => ''],
        'departmentFilter' => ['except' => ''],
        'sortBy'           => ['except' => 'deleted_at'],
        'sortDirection'    => ['except' => 'desc'],
        'perPage'          => ['except' => 10],
        'page'             => ['except' => 1],
    ];

    // Search and filter methods
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedDepartmentFilter()
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
            $this->sortBy        = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search           = '';
        $this->categoryFilter   = '';
        $this->typeFilter       = '';
        $this->departmentFilter = '';
        $this->sortBy           = 'deleted_at';
        $this->sortDirection    = 'desc';
        $this->resetPage();
    }

    // Selection methods
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedDocuments = $this->getTrashedDocuments()->pluck('id')->toArray();
        } else {
            $this->selectedDocuments = [];
        }
    }

    public function toggleDocumentSelection($documentId)
    {
        if (in_array($documentId, $this->selectedDocuments)) {
            $this->selectedDocuments = array_diff($this->selectedDocuments, [$documentId]);
        } else {
            $this->selectedDocuments[] = $documentId;
        }

        $this->selectAll = count($this->selectedDocuments) === $this->getTrashedDocuments()->count();
    }

    // Single document actions
    public function confirmRestore($documentId)
    {
        Log::info('confirmRestore called', ['document_id' => $documentId]);

        $this->documentToRestore = Document::onlyTrashed()->find($documentId);

        if (! $this->documentToRestore) {
            Log::error('Document not found');
            session()->flash('error', 'ไม่พบเอกสาร');
            return;
        }

        Log::info('Setting confirmingRestore to true');
        $this->confirmingRestore = true;

        // Debug: ดูว่า property เปลี่ยนไหม
        Log::info('confirmingRestore value', ['value' => $this->confirmingRestore]);

        session()->flash('success', 'Modal should appear now!');
    }

    public function directRestore($documentId)
    {
        $document = Document::onlyTrashed()->find($documentId);

        if (! $document) {
            session()->flash('error', 'ไม่พบเอกสาร');
            return;
        }

        try {
            $document->restore();
            session()->flash('success', "กู้คืนเอกสาร '{$document->title}' เรียบร้อยแล้ว");
            $this->loadStatistics();
        } catch (\Exception $e) {
            session()->flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function directDelete($documentId)
    {
        $document = Document::onlyTrashed()->find($documentId);

        if (! $document) {
            session()->flash('error', 'ไม่พบเอกสาร');
            return;
        }

        try {
            $document->clearMediaCollection('documents');
            $title = $document->title;
            $document->forceDelete();
            session()->flash('success', "ลบเอกสาร '{$title}' ถาวรเรียบร้อยแล้ว");
            $this->loadStatistics();
        } catch (\Exception $e) {
            session()->flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
    public function directEmptyTrash()
    {
        try {
            Log::info('Direct empty trash called');

            $trashedDocuments = Document::onlyTrashed()->get();
            $count            = $trashedDocuments->count();

            if ($count === 0) {
                session()->flash('info', 'ถังขยะว่างเปล่าอยู่แล้ว');
                return;
            }

            foreach ($trashedDocuments as $document) {
                // Delete associated media files
                try {
                    $document->clearMediaCollection('documents');
                } catch (\Exception $e) {
                    Log::warning('Failed to clear media for document', [
                        'document_id' => $document->id,
                        'error'       => $e->getMessage(),
                    ]);
                }

                $document->forceDelete();
            }

            Log::info('Empty trash completed', ['count' => $count]);

            session()->flash('success', "ถังขยะถูกล้างเรียบร้อยแล้ว (ลบ {$count} เอกสาร)");

            $this->loadStatistics();

        } catch (\Exception $e) {
            Log::error('Failed to empty trash', ['error' => $e->getMessage()]);
            session()->flash('error', 'เกิดข้อผิดพลาดในการล้างถังขยะ: ' . $e->getMessage());
        }
    }

    public function confirmPermanentDelete($documentId)
    {
        $this->selectedDocument           = Document::onlyTrashed()->find($documentId);
        $this->showConfirmPermanentDelete = true;
    }

    public function restoreDocument()
    {
        if (! $this->selectedDocument) {
            return;
        }

        try {
            // ตรวจสอบสิทธิ์แบบง่าย - ถ้าเป็นเจ้าของหรือ admin
            if (! auth()->user()->can('documents.manage-all-departments') &&
                $this->selectedDocument->created_by !== auth()->id()) {
                session()->flash('error', 'คุณไม่มีสิทธิ์กู้คืนเอกสารนี้');
                return;
            }

            $this->selectedDocument->restore();

            Log::info('Document restored from trash', [
                'document_id' => $this->selectedDocument->id,
                'title'       => $this->selectedDocument->title,
                'restored_by' => auth()->id(),
            ]);

            session()->flash('success', "เอกสาร '{$this->selectedDocument->title}' ถูกกู้คืนเรียบร้อยแล้ว");

            $this->closeModals();
            $this->loadStatistics();

        } catch (\Exception $e) {
            Log::error('Failed to restore document', [
                'document_id' => $this->selectedDocument->id,
                'error'       => $e->getMessage(),
            ]);

            session()->flash('error', 'เกิดข้อผิดพลาดในการกู้คืนเอกสาร: ' . $e->getMessage());
        }
    }

    public function permanentDeleteDocument()
    {
        if (! $this->selectedDocument) {
            return;
        }

        try {
            // ตรวจสอบสิทธิ์แบบง่าย - ถ้าเป็นเจ้าของหรือ admin
            if (! auth()->user()->can('documents.manage-all-departments') &&
                $this->selectedDocument->created_by !== auth()->id()) {
                session()->flash('error', 'คุณไม่มีสิทธิ์ลบเอกสารนี้');
                return;
            }

            // Delete associated media files
            $this->selectedDocument->clearMediaCollection('documents');

            $title = $this->selectedDocument->title;
            $this->selectedDocument->forceDelete();

            Log::info('Document permanently deleted', [
                'document_id' => $this->selectedDocument->id,
                'title'       => $title,
                'deleted_by'  => auth()->id(),
            ]);

            session()->flash('success', "เอกสาร '{$title}' ถูกลบถาวรเรียบร้อยแล้ว");

            $this->closeModals();
            $this->loadStatistics();

        } catch (\Exception $e) {
            Log::error('Failed to permanently delete document', [
                'document_id' => $this->selectedDocument->id,
                'error'       => $e->getMessage(),
            ]);

            session()->flash('error', 'เกิดข้อผิดพลาดในการลบเอกสารถาวร: ' . $e->getMessage());
        }
    }

    // Bulk actions
    public function confirmEmptyTrash()
    {
        $this->showConfirmEmptyTrash = true;
    }

    public function emptyTrash()
    {
        try {
            // ตรวจสอบสิทธิ์ - เฉพาะ admin เท่านั้น
            if (! auth()->user()->can('documents.manage-all-departments')) {
                session()->flash('error', 'คุณไม่มีสิทธิ์ล้างถังขยะ');
                return;
            }

            $trashedDocuments = Document::onlyTrashed()->get();
            $count            = $trashedDocuments->count();

            foreach ($trashedDocuments as $document) {
                // Delete associated media files
                $document->clearMediaCollection('documents');
                $document->forceDelete();
            }

            Log::info('Trash emptied', [
                'documents_count' => $count,
                'emptied_by'      => auth()->id(),
            ]);

            session()->flash('success', "ถังขยะถูกล้างเรียบร้อยแล้ว (ลบ {$count} เอกสาร)");

            $this->closeModals();
            $this->loadStatistics();

        } catch (\Exception $e) {
            Log::error('Failed to empty trash', [
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'เกิดข้อผิดพลาดในการล้างถังขยะ: ' . $e->getMessage());
        }
    }

    public function restoreSelected()
    {
        if (empty($this->selectedDocuments)) {
            session()->flash('warning', 'กรุณาเลือกเอกสารที่ต้องการกู้คืน');
            return;
        }

        try {
            $documents     = Document::onlyTrashed()->whereIn('id', $this->selectedDocuments)->get();
            $restoredCount = 0;

            foreach ($documents as $document) {
                // ตรวจสอบสิทธิ์ - ถ้าเป็นเจ้าของหรือ admin
                if (auth()->user()->can('documents.manage-all-departments') ||
                    $document->created_by === auth()->id()) {
                    $document->restore();
                    $restoredCount++;
                }
            }

            Log::info('Bulk documents restored', [
                'documents_count' => $restoredCount,
                'document_ids'    => $this->selectedDocuments,
                'restored_by'     => auth()->id(),
            ]);

            session()->flash('success', "กู้คืนเอกสาร {$restoredCount} รายการเรียบร้อยแล้ว");

            $this->selectedDocuments = [];
            $this->selectAll         = false;
            $this->loadStatistics();

        } catch (\Exception $e) {
            Log::error('Failed to restore selected documents', [
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'เกิดข้อผิดพลาดในการกู้คืนเอกสาร: ' . $e->getMessage());
        }
    }

    public function deleteSelectedPermanently()
    {
        if (empty($this->selectedDocuments)) {
            session()->flash('warning', 'กรุณาเลือกเอกสารที่ต้องการลบถาวร');
            return;
        }

        try {
            $documents    = Document::onlyTrashed()->whereIn('id', $this->selectedDocuments)->get();
            $deletedCount = 0;

            foreach ($documents as $document) {
                // ตรวจสอบสิทธิ์ - ถ้าเป็นเจ้าของหรือ admin
                if (auth()->user()->can('documents.manage-all-departments') ||
                    $document->created_by === auth()->id()) {
                    $document->clearMediaCollection('documents');
                    $document->forceDelete();
                    $deletedCount++;
                }
            }

            Log::info('Bulk documents permanently deleted', [
                'documents_count' => $deletedCount,
                'document_ids'    => $this->selectedDocuments,
                'deleted_by'      => auth()->id(),
            ]);

            session()->flash('success', "ลบเอกสารถาวร {$deletedCount} รายการเรียบร้อยแล้ว");

            $this->selectedDocuments = [];
            $this->selectAll         = false;
            $this->loadStatistics();

        } catch (\Exception $e) {
            Log::error('Failed to permanently delete selected documents', [
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'เกิดข้อผิดพลาดในการลบเอกสารถาวร: ' . $e->getMessage());
        }
    }

    // Helper methods
    public function closeModals()
    {
        $this->confirmingRestore         = false;
        $this->confirmingPermanentDelete = false;
        $this->confirmingEmptyTrash      = false;
        $this->documentToRestore         = null;
        $this->documentToDelete          = null;
    }

    public function getTrashedDocuments()
    {
        $query = Document::onlyTrashed()
            ->with(['category', 'type', 'department', 'creator']);

        // Apply search
        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'LIKE', "%{$this->search}%")
                    ->orWhere('description', 'LIKE', "%{$this->search}%")
                    ->orWhere('document_number', 'LIKE', "%{$this->search}%")
                    ->orWhere('keywords', 'LIKE', "%{$this->search}%");
            });
        }

        // Apply filters
        if (! empty($this->categoryFilter)) {
            $query->where('document_category_id', $this->categoryFilter);
        }

        if (! empty($this->typeFilter)) {
            $query->where('document_type_id', $this->typeFilter);
        }

        if (! empty($this->departmentFilter)) {
            $query->where('department_id', $this->departmentFilter);
        }

        // Apply user department restriction for non-admin users
        if (! auth()->user()->can('documents.manage-all-departments')) {
            $query->where('department_id', auth()->user()->department_id);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function loadStatistics()
    {
        $baseQuery = Document::onlyTrashed();

        // Apply user department restriction for non-admin users
        if (! auth()->user()->can('documents.manage-all-departments')) {
            $baseQuery->where('department_id', auth()->user()->department_id);
        }

        $this->totalTrashedDocuments = $baseQuery->count();
        $this->trashedThisWeek       = $baseQuery->where('deleted_at', '>=', now()->subWeek())->count();
        $this->trashedThisMonth      = $baseQuery->where('deleted_at', '>=', now()->subMonth())->count();

        // Calculate total size (approximate)
        $this->totalTrashedSize = $baseQuery->sum('file_size') ?? 0;
    }

    private function loadFilterData()
    {
        $this->availableCategories  = DocumentCategory::active()->ordered()->get();
        $this->availableTypes       = DocumentType::active()->orderBy('name')->get();
        $this->availableDepartments = Department::active()->orderBy('name')->get();
    }

    // Computed properties
    public function getHasSelectedDocumentsProperty()
    {
        return ! empty($this->selectedDocuments);
    }

    public function getSelectedCountProperty()
    {
        return count($this->selectedDocuments);
    }

    public function getFormattedTotalSizeProperty()
    {
        return $this->formatFileSize($this->totalTrashedSize);
    }

    private function formatFileSize($bytes)
    {
        if ($bytes === 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    // Lifecycle methods
    public function mount()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        // ตรวจสอบสิทธิ์ขั้นพื้นฐาน - ใครก็เข้าได้แต่จะเห็นแค่เอกสารของตัวเอง
        // เว้นแต่จะเป็น admin จะเห็นทั้งหมด

        $this->loadFilterData();
        $this->loadStatistics();
    }

    public function testMethod()
    {
        Log::info('Test method called');
        session()->flash('success', 'ปุ่มทำงาน!');
        dd('Method called successfully!');
    }

    public function render()
    {
        return view('livewire.backend.document.documents-trash', [
            'documents'   => $this->getTrashedDocuments(),
            'categories'  => $this->availableCategories,
            'types'       => $this->availableTypes,
            'departments' => $this->availableDepartments,
        ]);
    }
}
