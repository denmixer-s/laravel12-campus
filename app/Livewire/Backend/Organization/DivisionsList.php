<?php

namespace App\Livewire\Backend\Organization;

use App\Models\Division;
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
#[Title('Manage Divisions')]
class DivisionsList extends Component
{
    use WithPagination, AuthorizesRequests;

    // Component properties
    public $search = '';
    public $universityFilter = '';
    public $facultyFilter = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortField = 'sort_order';
    public $sortDirection = 'asc';

    // Modal properties
    public $confirmingDivisionDeletion = false;
    public $divisionToDelete = null;
    public $divisionToDeleteName = '';

    // Real-time listeners
    protected $listeners = [
        'divisionCreated' => 'handleDivisionCreated',
        'divisionUpdated' => 'handleDivisionUpdated',
        'divisionDeleted' => 'handleDivisionDeleted',
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
        $this->facultyFilter = ''; // Reset faculty filter when university changes
        $this->resetPage();
    }

    public function updatedFacultyFilter()
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
    public function handleDivisionCreated()
    {
        $this->resetPage();
        session()->flash('success', 'Division created successfully!');
    }

    public function handleDivisionUpdated()
    {
        $this->resetPage();
        session()->flash('success', 'Division updated successfully!');
    }

    public function handleDivisionDeleted()
    {
        $this->resetPage();
        session()->flash('success', 'Division deleted successfully!');
    }

    // Navigation methods
    public function createDivision()
    {
        // Check permission
        if (!auth()->user()->can('organizations.divisions.create') &&
            !auth()->user()->can('organizations.divisions.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to create divisions.');
            return;
        }

        return $this->redirect(route('administrator.organization.divisions.create'), navigate: true);
    }

    public function viewDivision($divisionId)
    {
        // Check permission
        if (!auth()->user()->can('organizations.divisions.view') &&
            !auth()->user()->can('organizations.view-all') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to view divisions.');
            return;
        }

        return $this->redirect(route('administrator.organization.divisions.show', $divisionId), navigate: true);
    }

    public function editDivision($divisionId)
    {
        // Check permission
        if (!auth()->user()->can('organizations.divisions.edit') &&
            !auth()->user()->can('organizations.divisions.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to edit divisions.');
            return;
        }

        return $this->redirect(route('administrator.organization.divisions.edit', $divisionId), navigate: true);
    }

    // Delete functionality
    public function confirmDelete($divisionId)
    {
        Log::info('confirmDelete called with divisionId: ' . $divisionId);

        try {
            $division = Division::findOrFail($divisionId);
            Log::info('Division found: ' . $division->name);

            // Set the properties for the modal
            $this->divisionToDelete = $divisionId;
            $this->divisionToDeleteName = $division->name;
            $this->confirmingDivisionDeletion = true;

            Log::info('Modal should open now', [
                'divisionToDelete' => $this->divisionToDelete,
                'divisionToDeleteName' => $this->divisionToDeleteName,
                'confirmingDivisionDeletion' => $this->confirmingDivisionDeletion
            ]);

        } catch (\Exception $e) {
            Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function deleteDivision()
    {
        Log::info('deleteDivision method called');

        // Check permission before delete
        if (!auth()->user()->can('organizations.divisions.delete') &&
            !auth()->user()->can('organizations.divisions.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to delete divisions.');
            $this->cancelDelete();
            return;
        }

        if (!$this->divisionToDelete) {
            Log::error('No division selected for deletion');
            session()->flash('error', 'No division selected for deletion.');
            $this->cancelDelete();
            return;
        }

        try {
            $division = Division::findOrFail($this->divisionToDelete);
            $divisionName = $division->name;

            Log::info('Attempting to delete division: ' . $divisionName);

            // Check if division has departments before deleting
            $departmentsCount = $division->departments()->count();
            if ($departmentsCount > 0) {
                session()->flash('error', "Cannot delete division '{$divisionName}' because it has {$departmentsCount} departments. Please remove all departments first.");
                $this->cancelDelete();
                return;
            }

            // Delete the division with transaction for safety
            DB::transaction(function () use ($division) {
                $division->delete();
            });

            Log::info('Division deleted successfully: ' . $divisionName);

            session()->flash('success', "Division '{$divisionName}' has been deleted successfully.");

            $this->resetPage();
            $this->cancelDelete();

            // Dispatch event for real-time updates
            $this->dispatch('divisionDeleted');

        } catch (\Exception $e) {
            Log::error('Error deleting division: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete division: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function cancelDelete()
    {
        Log::info('cancelDelete called');
        $this->confirmingDivisionDeletion = false;
        $this->divisionToDelete = null;
        $this->divisionToDeleteName = '';
    }

    // Clear all filters
    public function clearFilters()
    {
        $this->search = '';
        $this->universityFilter = '';
        $this->facultyFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    // Helper method to check if user can delete division
    public function canUserDeleteDivision($division)
    {
        // Check if user has proper permissions based on the ComprehensivePermissionsSeeder
        return auth()->user()->hasRole('Super Admin') ||
               auth()->user()->can('organizations.divisions.delete') ||
               auth()->user()->can('organizations.divisions.manage');
    }

    // Data fetching
    public function getDivisionsProperty()
    {
        try {
            return Division::with(['faculty.university', 'departments'])
                ->withCount(['departments', 'activeDepartments'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('code', 'like', '%' . $this->search . '%')
                          ->orWhere('description', 'like', '%' . $this->search . '%')
                          ->orWhereHas('faculty', function($qf) {
                              $qf->where('name', 'like', '%' . $this->search . '%')
                                ->orWhereHas('university', function($qu) {
                                    $qu->where('name', 'like', '%' . $this->search . '%');
                                });
                          });
                    });
                })
                ->when($this->universityFilter, function ($query) {
                    $query->whereHas('faculty', function($q) {
                        $q->where('university_id', $this->universityFilter);
                    });
                })
                ->when($this->facultyFilter, function ($query) {
                    $query->where('faculty_id', $this->facultyFilter);
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
            Log::error('Error loading divisions', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error loading divisions. Please try again.');
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

    public function getFacultyFilterOptionsProperty()
    {
        try {
            $query = Faculty::select('id', 'name', 'university_id')
                ->where('is_active', true)
                ->orderBy('name');

            // Filter faculties by selected university
            if ($this->universityFilter) {
                $query->where('university_id', $this->universityFilter);
            }

            $faculties = $query->get();

            $options = ['' => 'All Faculties'];
            foreach ($faculties as $faculty) {
                $options[$faculty->id] = $faculty->name;
            }
            return $options;
        } catch (\Exception $e) {
            Log::error('Error loading faculty filter options', ['error' => $e->getMessage()]);
            return ['' => 'All Faculties'];
        }
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
            $totalDivisions = Division::count();
            $activeDivisions = Division::where('is_active', true)->count();
            $inactiveDivisions = Division::where('is_active', false)->count();
            $divisionsWithDepartments = Division::has('departments')->count();
            $divisionsWithActiveDepartments = Division::has('activeDepartments')->count();
            $totalDepartments = Division::withCount('departments')->get()->sum('departments_count');

            return [
                'total' => $totalDivisions,
                'active' => $activeDivisions,
                'inactive' => $inactiveDivisions,
                'with_departments' => $divisionsWithDepartments,
                'with_active_departments' => $divisionsWithActiveDepartments,
                'total_departments' => $totalDepartments,
            ];
        } catch (\Exception $e) {
            Log::error('Error loading stats', ['error' => $e->getMessage()]);
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'with_departments' => 0,
                'with_active_departments' => 0,
                'total_departments' => 0,
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

    // Get division status badge
    public function getStatusBadge($isActive)
    {
        return $isActive
            ? ['class' => 'bg-green-100 text-green-800', 'text' => 'Active']
            : ['class' => 'bg-red-100 text-red-800', 'text' => 'Inactive'];
    }

    // Get hierarchy path for division
    public function getHierarchyPath($division)
    {
        try {
            return $division->faculty->university->name . ' > ' . $division->faculty->name . ' > ' . $division->name;
        } catch (\Exception $e) {
            return 'Unknown hierarchy';
        }
    }

    // Get university name from division
    public function getUniversityName($division)
    {
        try {
            return $division->faculty->university->name ?? 'Unknown University';
        } catch (\Exception $e) {
            return 'Unknown University';
        }
    }

    // Get faculty name from division
    public function getFacultyName($division)
    {
        try {
            return $division->faculty->name ?? 'Unknown Faculty';
        } catch (\Exception $e) {
            return 'Unknown Faculty';
        }
    }

    // Get faculty type from division
    public function getFacultyType($division)
    {
        try {
            return $division->faculty->type ?? 'faculty';
        } catch (\Exception $e) {
            return 'faculty';
        }
    }

    // Get faculty type badge
    public function getFacultyTypeBadge($division)
    {
        $type = $this->getFacultyType($division);
        return $type === 'faculty'
            ? ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Faculty']
            : ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Office'];
    }

    public function render()
    {
        return view('livewire.backend.organization.divisions-list', [
            'divisions' => $this->divisions,
            'universityFilterOptions' => $this->universityFilterOptions,
            'facultyFilterOptions' => $this->facultyFilterOptions,
            'statusFilterOptions' => $this->statusFilterOptions,
            'stats' => $this->stats,
        ]);
    }
}
