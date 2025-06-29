<?php

namespace App\Livewire\Backend\Organization;

use App\Models\Department;
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
#[Title('Manage Departments')]
class DepartmentsList extends Component
{
    use WithPagination, AuthorizesRequests;

    // Component properties
    public $search = '';
    public $universityFilter = '';
    public $facultyFilter = '';
    public $divisionFilter = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortField = 'sort_order';
    public $sortDirection = 'asc';

    // Modal properties
    public $confirmingDepartmentDeletion = false;
    public $departmentToDelete = null;
    public $departmentToDeleteName = '';

    // Real-time listeners
    protected $listeners = [
        'departmentCreated' => 'handleDepartmentCreated',
        'departmentUpdated' => 'handleDepartmentUpdated',
        'departmentDeleted' => 'handleDepartmentDeleted',
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
        // Reset dependent filters when university changes
        $this->facultyFilter = '';
        $this->divisionFilter = '';
        $this->resetPage();
    }

    public function updatedFacultyFilter()
    {
        // Reset division filter when faculty changes
        $this->divisionFilter = '';
        $this->resetPage();
    }

    public function updatedDivisionFilter()
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
    public function handleDepartmentCreated()
    {
        $this->resetPage();
        session()->flash('success', 'Department created successfully!');
    }

    public function handleDepartmentUpdated()
    {
        $this->resetPage();
        session()->flash('success', 'Department updated successfully!');
    }

    public function handleDepartmentDeleted()
    {
        $this->resetPage();
        session()->flash('success', 'Department deleted successfully!');
    }

    // Navigation methods
    public function createDepartment()
    {
        // Check permission
        if (!auth()->user()->can('organizations.departments.create') &&
            !auth()->user()->can('organizations.departments.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to create departments.');
            return;
        }

        return $this->redirect(route('administrator.organization.departments.create'), navigate: true);
    }

    public function viewDepartment($departmentId)
    {
        // Check permission
        if (!auth()->user()->can('organizations.departments.view') &&
            !auth()->user()->can('organizations.view-all') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to view departments.');
            return;
        }

        return $this->redirect(route('administrator.organization.departments.show', $departmentId), navigate: true);
    }

    public function editDepartment($departmentId)
    {
        // Check permission
        if (!auth()->user()->can('organizations.departments.edit') &&
            !auth()->user()->can('organizations.departments.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to edit departments.');
            return;
        }

        return $this->redirect(route('administrator.organization.departments.edit', $departmentId), navigate: true);
    }

    // Delete functionality
    public function confirmDelete($departmentId)
    {
        Log::info('confirmDelete called with departmentId: ' . $departmentId);

        try {
            $department = Department::findOrFail($departmentId);
            Log::info('Department found: ' . $department->name);

            // Set the properties for the modal
            $this->departmentToDelete = $departmentId;
            $this->departmentToDeleteName = $department->name;
            $this->confirmingDepartmentDeletion = true;

            Log::info('Modal should open now', [
                'departmentToDelete' => $this->departmentToDelete,
                'departmentToDeleteName' => $this->departmentToDeleteName,
                'confirmingDepartmentDeletion' => $this->confirmingDepartmentDeletion
            ]);

        } catch (\Exception $e) {
            Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function deleteDepartment()
    {
        Log::info('deleteDepartment method called');

        // Check permission before delete
        if (!auth()->user()->can('organizations.departments.delete') &&
            !auth()->user()->can('organizations.departments.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to delete departments.');
            $this->cancelDelete();
            return;
        }

        if (!$this->departmentToDelete) {
            Log::error('No department selected for deletion');
            session()->flash('error', 'No department selected for deletion.');
            $this->cancelDelete();
            return;
        }

        try {
            $department = Department::findOrFail($this->departmentToDelete);
            $departmentName = $department->name;

            Log::info('Attempting to delete department: ' . $departmentName);

            // Check if department has users before deleting
            $usersCount = $department->users()->count();
            if ($usersCount > 0) {
                session()->flash('error', "Cannot delete department '{$departmentName}' because it has {$usersCount} users. Please reassign or remove all users first.");
                $this->cancelDelete();
                return;
            }

            // Check if department has documents before deleting
            $documentsCount = $department->documents()->count();
            if ($documentsCount > 0) {
                session()->flash('error', "Cannot delete department '{$departmentName}' because it has {$documentsCount} documents. Please remove all documents first.");
                $this->cancelDelete();
                return;
            }

            // Delete the department with transaction for safety
            DB::transaction(function () use ($department) {
                $department->delete();
            });

            Log::info('Department deleted successfully: ' . $departmentName);

            session()->flash('success', "Department '{$departmentName}' has been deleted successfully.");

            $this->resetPage();
            $this->cancelDelete();

            // Dispatch event for real-time updates
            $this->dispatch('departmentDeleted');

        } catch (\Exception $e) {
            Log::error('Error deleting department: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete department: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function cancelDelete()
    {
        Log::info('cancelDelete called');
        $this->confirmingDepartmentDeletion = false;
        $this->departmentToDelete = null;
        $this->departmentToDeleteName = '';
    }

    // Clear all filters
    public function clearFilters()
    {
        $this->search = '';
        $this->universityFilter = '';
        $this->facultyFilter = '';
        $this->divisionFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    // Helper method to check if user can delete department
    public function canUserDeleteDepartment($department)
    {
        // Check if user has proper permissions based on the ComprehensivePermissionsSeeder
        return auth()->user()->hasRole('Super Admin') ||
               auth()->user()->can('organizations.departments.delete') ||
               auth()->user()->can('organizations.departments.manage');
    }

    // Data fetching
    public function getDepartmentsProperty()
    {
        try {
            return Department::with([
                'division.faculty.university',
                'users' => function ($query) {
                    $query->select('id', 'department_id', 'name', 'status');
                }
            ])
                ->withCount(['users', 'activeUsers', 'documents'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('code', 'like', '%' . $this->search . '%')
                          ->orWhere('description', 'like', '%' . $this->search . '%')
                          ->orWhereHas('division', function($qu) {
                              $qu->where('name', 'like', '%' . $this->search . '%');
                          })
                          ->orWhereHas('division.faculty', function($qu) {
                              $qu->where('name', 'like', '%' . $this->search . '%');
                          })
                          ->orWhereHas('division.faculty.university', function($qu) {
                              $qu->where('name', 'like', '%' . $this->search . '%');
                          });
                    });
                })
                ->when($this->universityFilter, function ($query) {
                    $query->whereHas('division.faculty.university', function ($q) {
                        $q->where('id', $this->universityFilter);
                    });
                })
                ->when($this->facultyFilter, function ($query) {
                    $query->whereHas('division.faculty', function ($q) {
                        $q->where('id', $this->facultyFilter);
                    });
                })
                ->when($this->divisionFilter, function ($query) {
                    $query->where('division_id', $this->divisionFilter);
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
            Log::error('Error loading departments', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error loading departments. Please try again.');
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

    public function getDivisionFilterOptionsProperty()
    {
        try {
            $query = Division::select('id', 'name', 'faculty_id')
                ->where('is_active', true)
                ->orderBy('name');

            if ($this->facultyFilter) {
                $query->where('faculty_id', $this->facultyFilter);
            } elseif ($this->universityFilter) {
                $query->whereHas('faculty', function ($q) {
                    $q->where('university_id', $this->universityFilter);
                });
            }

            $divisions = $query->get();

            $options = ['' => 'All Divisions'];
            foreach ($divisions as $division) {
                $options[$division->id] = $division->name;
            }
            return $options;
        } catch (\Exception $e) {
            Log::error('Error loading division filter options', ['error' => $e->getMessage()]);
            return ['' => 'All Divisions'];
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
            $totalDepartments = Department::count();
            $activeDepartments = Department::where('is_active', true)->count();
            $inactiveDepartments = Department::where('is_active', false)->count();
            $departmentsWithUsers = Department::has('users')->count();
            $departmentsWithDocuments = Department::has('documents')->count();
            $totalUsers = Department::withCount('users')->get()->sum('users_count');

            return [
                'total' => $totalDepartments,
                'active' => $activeDepartments,
                'inactive' => $inactiveDepartments,
                'with_users' => $departmentsWithUsers,
                'with_documents' => $departmentsWithDocuments,
                'total_users' => $totalUsers,
            ];
        } catch (\Exception $e) {
            Log::error('Error loading stats', ['error' => $e->getMessage()]);
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'with_users' => 0,
                'with_documents' => 0,
                'total_users' => 0,
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

    // Get department status badge
    public function getStatusBadge($isActive)
    {
        return $isActive
            ? ['class' => 'bg-green-100 text-green-800', 'text' => 'Active']
            : ['class' => 'bg-red-100 text-red-800', 'text' => 'Inactive'];
    }

    // Get hierarchy path for department
    public function getHierarchyPath($department)
    {
        try {
            return $department->division->faculty->university->name . ' > ' .
                   $department->division->faculty->name . ' > ' .
                   $department->division->name . ' > ' .
                   $department->name;
        } catch (\Exception $e) {
            return $department->name;
        }
    }

    public function render()
    {
        return view('livewire.backend.organization.departments-list', [
            'departments' => $this->departments,
            'universityFilterOptions' => $this->universityFilterOptions,
            'facultyFilterOptions' => $this->facultyFilterOptions,
            'divisionFilterOptions' => $this->divisionFilterOptions,
            'statusFilterOptions' => $this->statusFilterOptions,
            'stats' => $this->stats,
        ]);
    }
}
