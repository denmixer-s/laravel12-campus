<?php

namespace App\Livewire\Backend\Organization;

use App\Models\Faculty;
use App\Models\University;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.dashboard')]
#[Title('Manage Faculties')]
class FacultiesList extends Component
{
    use WithPagination, AuthorizesRequests;

    // Component properties
    public $search = '';
    public $universityFilter = '';
    public $typeFilter = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortField = 'sort_order';
    public $sortDirection = 'asc';

    // Modal properties
    public $confirmingFacultyDeletion = false;
    public $facultyToDelete = null;
    public $facultyToDeleteName = '';

    // Real-time listeners
    protected $listeners = [
        'facultyCreated' => 'handleFacultyCreated',
        'facultyUpdated' => 'handleFacultyUpdated',
        'facultyDeleted' => 'handleFacultyDeleted',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    // Search and filter updates
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedUniversityFilter()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
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

    // Sorting functionality
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    // Real-time event handlers
    public function handleFacultyCreated()
    {
        $this->resetPage();
        session()->flash('success', 'Faculty created successfully!');
    }

    public function handleFacultyUpdated()
    {
        $this->resetPage();
        session()->flash('success', 'Faculty updated successfully!');
    }

    public function handleFacultyDeleted()
    {
        $this->resetPage();
        session()->flash('success', 'Faculty deleted successfully!');
    }

    // Navigation methods
    public function createFaculty()
    {
        // Check permission
        if (!auth()->user()->can('organizations.faculties.create') &&
            !auth()->user()->can('organizations.faculties.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to create faculties.');
            return;
        }

        return $this->redirect(route('administrator.organization.faculties.create'), navigate: true);
    }

    public function viewFaculty($facultyId)
    {
        // Check permission
        if (!auth()->user()->can('organizations.faculties.view') &&
            !auth()->user()->can('organizations.view-all') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to view faculties.');
            return;
        }

        return $this->redirect(route('administrator.organization.faculties.show', $facultyId), navigate: true);
    }

    public function editFaculty($facultyId)
    {
        // Check permission
        if (!auth()->user()->can('organizations.faculties.edit') &&
            !auth()->user()->can('organizations.faculties.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to edit faculties.');
            return;
        }

        return $this->redirect(route('administrator.organization.faculties.edit', $facultyId), navigate: true);
    }

    // Delete functionality
    public function confirmDelete($facultyId)
    {
        Log::info('confirmDelete called with facultyId: ' . $facultyId);

        try {
            $faculty = Faculty::findOrFail($facultyId);
            Log::info('Faculty found: ' . $faculty->name);

            // Set the properties for the modal
            $this->facultyToDelete = $facultyId;
            $this->facultyToDeleteName = $faculty->name;
            $this->confirmingFacultyDeletion = true;

            Log::info('Modal should open now', [
                'facultyToDelete' => $this->facultyToDelete,
                'facultyToDeleteName' => $this->facultyToDeleteName,
                'confirmingFacultyDeletion' => $this->confirmingFacultyDeletion
            ]);

        } catch (\Exception $e) {
            Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function deleteFaculty()
    {
        Log::info('deleteFaculty method called');

        // Check permission before delete
        if (!auth()->user()->can('organizations.faculties.delete') &&
            !auth()->user()->can('organizations.faculties.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to delete faculties.');
            $this->cancelDelete();
            return;
        }

        if (!$this->facultyToDelete) {
            Log::error('No faculty selected for deletion');
            session()->flash('error', 'No faculty selected for deletion.');
            $this->cancelDelete();
            return;
        }

        try {
            $faculty = Faculty::findOrFail($this->facultyToDelete);
            $facultyName = $faculty->name;

            Log::info('Attempting to delete faculty: ' . $facultyName);

            // Check if faculty has divisions before deleting
            $divisionsCount = $faculty->divisions()->count();
            if ($divisionsCount > 0) {
                session()->flash('error', "Cannot delete faculty '{$facultyName}' because it has {$divisionsCount} divisions. Please remove all divisions first.");
                $this->cancelDelete();
                return;
            }

            // Delete the faculty with transaction for safety
            DB::transaction(function () use ($faculty) {
                $faculty->delete();
            });

            Log::info('Faculty deleted successfully: ' . $facultyName);

            session()->flash('success', "Faculty '{$facultyName}' has been deleted successfully.");

            $this->resetPage();
            $this->cancelDelete();

            // Dispatch event for real-time updates
            $this->dispatch('facultyDeleted');

        } catch (\Exception $e) {
            Log::error('Error deleting faculty: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete faculty: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function cancelDelete()
    {
        Log::info('cancelDelete called');
        $this->confirmingFacultyDeletion = false;
        $this->facultyToDelete = null;
        $this->facultyToDeleteName = '';
    }

    // Clear all filters
    public function clearFilters()
    {
        $this->search = '';
        $this->universityFilter = '';
        $this->typeFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    // Helper method to check if user can delete faculty
    public function canUserDeleteFaculty($faculty)
    {
        // Check if user has proper permissions based on the ComprehensivePermissionsSeeder
        return auth()->user()->hasRole('Super Admin') ||
               auth()->user()->can('organizations.faculties.delete') ||
               auth()->user()->can('organizations.faculties.manage');
    }

    // Data fetching
    public function getFacultiesProperty()
    {
        try {
            return Faculty::with(['university', 'divisions'])
                ->withCount(['divisions', 'activeDivisions'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('code', 'like', '%' . $this->search . '%')
                          ->orWhere('description', 'like', '%' . $this->search . '%')
                          ->orWhereHas('university', function($qu) {
                              $qu->where('name', 'like', '%' . $this->search . '%');
                          });
                    });
                })
                ->when($this->universityFilter, function ($query) {
                    $query->where('university_id', $this->universityFilter);
                })
                ->when($this->typeFilter, function ($query) {
                    $query->where('type', $this->typeFilter);
                })
                ->when($this->statusFilter === 'active', function ($query) {
                    $query->where('is_active', true);
                })
                ->when($this->statusFilter === 'inactive', function ($query) {
                    $query->where('is_active', false);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        } catch (\Exception $e) {
            Log::error('Error loading faculties', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error loading faculties. Please try again.');
            return collect()->paginate($this->perPage);
        }
    }

    // Get filter options
    public function getUniversityFilterOptionsProperty()
    {
        try {
            $universities = University::select('id', 'name')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            $options = ['' => 'All Universities'];
            foreach ($universities as $university) {
                $options[$university->id] = $university->name;
            }
            return $options;
        } catch (\Exception $e) {
            Log::error('Error loading university filter options', ['error' => $e->getMessage()]);
            return ['' => 'All Universities'];
        }
    }

    public function getTypeFilterOptionsProperty()
    {
        return [
            '' => 'All Types',
            'faculty' => 'Faculty',
            'office' => 'Office',
        ];
    }

    public function getStatusFilterOptionsProperty()
    {
        return [
            '' => 'All Statuses',
            'active' => 'Active Only',
            'inactive' => 'Inactive Only',
        ];
    }

    // Get statistics
    public function getStatsProperty()
    {
        try {
            $totalFaculties = Faculty::count();
            $activeFaculties = Faculty::where('is_active', true)->count();
            $inactiveFaculties = Faculty::where('is_active', false)->count();
            $facultiesOnly = Faculty::where('type', 'faculty')->count();
            $officesOnly = Faculty::where('type', 'office')->count();
            $facultiesWithDivisions = Faculty::has('divisions')->count();

            return [
                'total' => $totalFaculties,
                'active' => $activeFaculties,
                'inactive' => $inactiveFaculties,
                'faculties' => $facultiesOnly,
                'offices' => $officesOnly,
                'with_divisions' => $facultiesWithDivisions,
            ];
        } catch (\Exception $e) {
            Log::error('Error loading stats', ['error' => $e->getMessage()]);
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'faculties' => 0,
                'offices' => 0,
                'with_divisions' => 0,
            ];
        }
    }

    // Get sort icon for table headers
    public function getSortIcon($field)
    {
        if ($this->sortField !== $field) {
            return 'M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4'; // Sort icon
        }

        return $this->sortDirection === 'asc'
            ? 'M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12' // Sort up
            : 'M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4'; // Sort down
    }

    // Preview content
    public function getContentPreview($content, $limit = 100)
    {
        if (!$content) return 'No description available';

        $stripped = strip_tags($content);
        return strlen($stripped) > $limit ? substr($stripped, 0, $limit) . '...' : $stripped;
    }

    // Get faculty type badge
    public function getTypeBadge($type)
    {
        return $type === 'faculty'
            ? ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Faculty']
            : ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Office'];
    }

    // Get faculty status badge
    public function getStatusBadge($isActive)
    {
        return $isActive
            ? ['class' => 'bg-green-100 text-green-800', 'text' => 'Active']
            : ['class' => 'bg-red-100 text-red-800', 'text' => 'Inactive'];
    }

    public function render()
    {
        return view('livewire.backend.organization.faculties-list', [
            'faculties' => $this->faculties,
            'universityFilterOptions' => $this->universityFilterOptions,
            'typeFilterOptions' => $this->typeFilterOptions,
            'statusFilterOptions' => $this->statusFilterOptions,
            'stats' => $this->stats,
        ]);
    }
}
