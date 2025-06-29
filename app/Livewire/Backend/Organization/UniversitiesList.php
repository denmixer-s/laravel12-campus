<?php

namespace App\Livewire\Backend\Organization;

use App\Models\University;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.dashboard')]
#[Title('Manage Universities')]
class UniversitiesList extends Component
{
    use WithPagination, AuthorizesRequests;

    // Component properties
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Modal properties
    public $confirmingUniversityDeletion = false;
    public $universityToDelete = null;
    public $universityToDeleteName = '';

    // Real-time listeners
    protected $listeners = [
        'universityCreated' => 'handleUniversityCreated',
        'universityUpdated' => 'handleUniversityUpdated',
        'universityDeleted' => 'handleUniversityDeleted',
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
    public function handleUniversityCreated()
    {
        $this->resetPage();
        session()->flash('success', 'University created successfully!');
    }

    public function handleUniversityUpdated()
    {
        $this->resetPage();
        session()->flash('success', 'University updated successfully!');
    }

    public function handleUniversityDeleted()
    {
        $this->resetPage();
        session()->flash('success', 'University deleted successfully!');
    }

    // Navigation methods
    public function createUniversity()
    {
        // Check permission
        if (!auth()->user()->can('organizations.universities.create') &&
            !auth()->user()->can('organizations.universities.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to create universities.');
            return;
        }

        return $this->redirect(route('administrator.organization.universities.create'), navigate: true);
    }

    public function viewUniversity($universityId)
    {
        // Check permission
        if (!auth()->user()->can('organizations.universities.view') &&
            !auth()->user()->can('organizations.view-all') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to view universities.');
            return;
        }

        return $this->redirect(route('administrator.organization.universities.show', $universityId), navigate: true);
    }

    public function editUniversity($universityId)
    {
        // Check permission
        if (!auth()->user()->can('organizations.universities.edit') &&
            !auth()->user()->can('organizations.universities.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to edit universities.');
            return;
        }

        return $this->redirect(route('administrator.organization.universities.edit', $universityId), navigate: true);
    }

    // Delete functionality
    public function confirmDelete($universityId)
    {
        Log::info('confirmDelete called with universityId: ' . $universityId);

        try {
            $university = University::findOrFail($universityId);
            Log::info('University found: ' . $university->name);

            // Set the properties for the modal
            $this->universityToDelete = $universityId;
            $this->universityToDeleteName = $university->name;
            $this->confirmingUniversityDeletion = true;

            Log::info('Modal should open now', [
                'universityToDelete' => $this->universityToDelete,
                'universityToDeleteName' => $this->universityToDeleteName,
                'confirmingUniversityDeletion' => $this->confirmingUniversityDeletion
            ]);

        } catch (\Exception $e) {
            Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function deleteUniversity()
    {
        Log::info('deleteUniversity method called');

        // Check permission before delete
        if (!auth()->user()->can('organizations.universities.delete') &&
            !auth()->user()->can('organizations.universities.manage') &&
            !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You do not have permission to delete universities.');
            $this->cancelDelete();
            return;
        }

        if (!$this->universityToDelete) {
            Log::error('No university selected for deletion');
            session()->flash('error', 'No university selected for deletion.');
            $this->cancelDelete();
            return;
        }

        try {
            $university = University::findOrFail($this->universityToDelete);
            $universityName = $university->name;

            Log::info('Attempting to delete university: ' . $universityName);

            // Check if university has faculties before deleting
            $facultiesCount = $university->faculties()->count();
            if ($facultiesCount > 0) {
                session()->flash('error', "Cannot delete university '{$universityName}' because it has {$facultiesCount} faculties. Please remove all faculties first.");
                $this->cancelDelete();
                return;
            }

            // Delete the university with transaction for safety
            DB::transaction(function () use ($university) {
                $university->delete();
            });

            Log::info('University deleted successfully: ' . $universityName);

            session()->flash('success', "University '{$universityName}' has been deleted successfully.");

            $this->resetPage();
            $this->cancelDelete();

            // Dispatch event for real-time updates
            $this->dispatch('universityDeleted');

        } catch (\Exception $e) {
            Log::error('Error deleting university: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete university: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function cancelDelete()
    {
        Log::info('cancelDelete called');
        $this->confirmingUniversityDeletion = false;
        $this->universityToDelete = null;
        $this->universityToDeleteName = '';
    }

    // Clear all filters
    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    // Helper method to check if user can delete university
    public function canUserDeleteUniversity($university)
    {
        // Check if user has proper permissions based on the ComprehensivePermissionsSeeder
        return auth()->user()->hasRole('Super Admin') ||
               auth()->user()->can('organizations.universities.delete') ||
               auth()->user()->can('organizations.universities.manage');
    }

    // Data fetching
    public function getUniversitiesProperty()
    {
        try {
            return University::withCount(['faculties', 'activeFaculties'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('code', 'like', '%' . $this->search . '%')
                          ->orWhere('description', 'like', '%' . $this->search . '%')
                          ->orWhere('address', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
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
            Log::error('Error loading universities', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error loading universities. Please try again.');
            return collect()->paginate($this->perPage);
        }
    }

    // Get filter options
    public function getStatusFilterOptionsProperty()
    {
        return [
            '' => 'All Universities',
            'active' => 'Active Only',
            'inactive' => 'Inactive Only',
        ];
    }

    // Get statistics
    public function getStatsProperty()
    {
        try {
            $totalUniversities = University::count();
            $activeUniversities = University::where('is_active', true)->count();
            $inactiveUniversities = University::where('is_active', false)->count();
            $universitiesWithFaculties = University::has('faculties')->count();

            return [
                'total' => $totalUniversities,
                'active' => $activeUniversities,
                'inactive' => $inactiveUniversities,
                'with_faculties' => $universitiesWithFaculties,
            ];
        } catch (\Exception $e) {
            Log::error('Error loading stats', ['error' => $e->getMessage()]);
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'with_faculties' => 0,
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

    // Format university URL or identifier
    public function getUniversityIdentifier($university)
    {
        return $university->code ? $university->code : 'UNIV-' . $university->id;
    }

    // Get university status badge
    public function getStatusBadge($isActive)
    {
        return $isActive
            ? ['class' => 'bg-green-100 text-green-800', 'text' => 'Active']
            : ['class' => 'bg-red-100 text-red-800', 'text' => 'Inactive'];
    }

    public function render()
    {
        return view('livewire.backend.organization.universities-list', [
            'universities' => $this->universities,
            'statusFilterOptions' => $this->statusFilterOptions,
            'stats' => $this->stats,
        ]);
    }
}
