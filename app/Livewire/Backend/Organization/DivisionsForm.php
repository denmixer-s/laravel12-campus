<?php

namespace App\Livewire\Backend\Organization;

use App\Models\Division;
use App\Models\Faculty;
use App\Models\University;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

#[Layout('components.layouts.dashboard')]
class DivisionsForm extends Component
{
    use AuthorizesRequests;

    public ?Division $division = null;
    public bool $isEdit = false;

    // Form fields
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255')]
    public string $slug = '';

    #[Validate('nullable|string|max:20')]
    public string $code = '';

    #[Validate('nullable|string|max:1000')]
    public string $description = '';

    #[Validate('required|exists:faculties,id')]
    public $faculty_id = '';

    #[Validate('required|integer|min:0')]
    public int $sort_order = 0;

    #[Validate('required|boolean')]
    public bool $is_active = true;

    // UI state
    public bool $autoGenerateSlug = true;
    public bool $isSaving = false;

    // Helper properties for display
    public $selectedUniversity = null;
    public $selectedFaculty = null;

    protected $listeners = [
        'divisionUpdated' => 'refreshForm',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if we have a division from route model binding
        if (request()->route('division')) {
            $this->division = request()->route('division');
            $this->isEdit = true;
            $this->loadDivisionData();
        } else {
            $this->division = new Division();
            $this->isEdit = false;
            $this->setDefaults();
        }
    }

    protected function loadDivisionData(): void
    {
        $this->name = $this->division->name;
        $this->slug = $this->division->slug;
        $this->code = $this->division->code ?? '';
        $this->description = $this->division->description ?? '';
        $this->faculty_id = $this->division->faculty_id;
        $this->sort_order = $this->division->sort_order ?? 0;
        $this->is_active = $this->division->is_active ?? true;

        // Load related data for display
        $this->loadSelectedFaculty();

        // Don't auto-generate slug when editing
        $this->autoGenerateSlug = false;
    }

    protected function setDefaults(): void
    {
        $this->name = '';
        $this->slug = '';
        $this->code = '';
        $this->description = '';
        $this->faculty_id = '';
        $this->sort_order = $this->getNextSortOrder();
        $this->is_active = true;
        $this->autoGenerateSlug = true;
    }

    protected function loadSelectedFaculty(): void
    {
        if ($this->faculty_id) {
            try {
                $this->selectedFaculty = Faculty::with('university')->find($this->faculty_id);
                $this->selectedUniversity = $this->selectedFaculty?->university;
            } catch (\Exception $e) {
                Log::error('Error loading selected faculty: ' . $e->getMessage());
                $this->selectedFaculty = null;
                $this->selectedUniversity = null;
            }
        } else {
            $this->selectedFaculty = null;
            $this->selectedUniversity = null;
        }
    }

    public function updatedName(): void
    {
        if ($this->autoGenerateSlug && !$this->isEdit) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function updatedSlug(): void
    {
        // Only clean slug, don't disable auto-generate automatically
        $this->slug = Str::slug($this->slug);
    }

    public function updatedFacultyId(): void
    {
        // Load selected faculty and university
        $this->loadSelectedFaculty();

        // Update sort order when faculty changes
        if (!$this->isEdit) {
            $this->sort_order = $this->getNextSortOrder();
        }
    }

    public function toggleAutoSlug(): void
    {
        $this->autoGenerateSlug = !$this->autoGenerateSlug;

        if ($this->autoGenerateSlug && !empty($this->name) && !$this->isEdit) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function generateSlug(): void
    {
        if ($this->name) {
            $this->slug = Str::slug($this->name);
            $this->autoGenerateSlug = true;
        }
    }

    public function toggleActive(): void
    {
        $this->is_active = !$this->is_active;
    }

    private function getNextSortOrder(): int
    {
        if (!$this->faculty_id) {
            return 0;
        }

        $maxOrder = Division::where('faculty_id', $this->faculty_id)
            ->max('sort_order');

        return ($maxOrder ?? 0) + 1;
    }

    public function save()
    {
        // Check permissions
        if ($this->isEdit) {
            if (!auth()->user()->can('organizations.divisions.edit') &&
                !auth()->user()->can('organizations.divisions.manage') &&
                !auth()->user()->hasRole('Super Admin')) {
                session()->flash('error', 'You do not have permission to edit divisions.');
                return;
            }
        } else {
            if (!auth()->user()->can('organizations.divisions.create') &&
                !auth()->user()->can('organizations.divisions.manage') &&
                !auth()->user()->hasRole('Super Admin')) {
                session()->flash('error', 'You do not have permission to create divisions.');
                return;
            }
        }

        $this->isSaving = true;

        try {
            // Dynamic validation rules based on edit/create mode
            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'code' => 'nullable|string|max:20',
                'faculty_id' => 'required|exists:faculties,id',
                'sort_order' => 'required|integer|min:0',
                'is_active' => 'required|boolean',
            ];

            // Slug validation - different for create/edit and unique per faculty
            if ($this->isEdit && $this->division->exists) {
                $rules['slug'] = 'nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:divisions,slug,' . $this->division->id . ',id,faculty_id,' . $this->faculty_id;
                $rules['code'] = 'nullable|string|max:20|unique:divisions,code,' . $this->division->id . ',id,faculty_id,' . $this->faculty_id;
            } else {
                $rules['slug'] = 'nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:divisions,slug,NULL,id,faculty_id,' . $this->faculty_id;
                $rules['code'] = 'nullable|string|max:20|unique:divisions,code,NULL,id,faculty_id,' . $this->faculty_id;
            }

            $this->validate($rules);

            $data = [
                'name' => $this->name,
                'slug' => $this->slug ?: Str::slug($this->name),
                'code' => $this->code ?: null,
                'description' => $this->description ?: null,
                'faculty_id' => $this->faculty_id,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
            ];

            DB::transaction(function () use ($data) {
                if ($this->isEdit) {
                    $this->division->update($data);
                    Log::info('Division updated: ' . $this->division->name);
                } else {
                    $this->division = Division::create($data);
                    Log::info('Division created: ' . $this->division->name);
                }
            });

            $message = $this->isEdit
                ? "Division '{$this->division->name}' updated successfully!"
                : "Division '{$this->division->name}' created successfully!";

            session()->flash('success', $message);

            // Dispatch events for real-time updates
            if ($this->isEdit) {
                $this->dispatch('divisionUpdated');
            } else {
                $this->dispatch('divisionCreated');
            }

            // Redirect to index after successful save
            return redirect()->route('administrator.organization.divisions.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let Livewire handle validation errors
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error saving division: ' . $e->getMessage());
            session()->flash('error', 'Failed to save division. Please try again.');
        } finally {
            $this->isSaving = false;
        }
    }

    public function cancel()
    {
        return redirect()->route('administrator.organization.divisions.index');
    }

    public function resetForm(): void
    {
        if ($this->isEdit) {
            $this->loadDivisionData();
        } else {
            $this->setDefaults();
        }
        $this->resetValidation();
    }

    public function getFormTitleProperty(): string
    {
        return $this->isEdit ? 'Edit Division' : 'Create New Division';
    }

    public function getBreadcrumbsProperty(): array
    {
        return [
            ['name' => 'Dashboard', 'url' => route('administrator.dashboard')],
            ['name' => 'Organization', 'url' => '#'],
            ['name' => 'Divisions', 'url' => route('administrator.organization.divisions.index')],
            ['name' => $this->formTitle, 'url' => null],
        ];
    }

    // Check if form has changes
    public function getHasChangesProperty(): bool
    {
        if (!$this->isEdit || !$this->division->exists) {
            return !empty($this->name) || !empty($this->slug) || !empty($this->code) ||
                   !empty($this->description) || !empty($this->faculty_id);
        }

        return $this->name !== $this->division->name ||
               $this->slug !== $this->division->slug ||
               $this->code !== ($this->division->code ?? '') ||
               $this->description !== ($this->division->description ?? '') ||
               $this->faculty_id !== $this->division->faculty_id ||
               $this->sort_order !== $this->division->sort_order ||
               $this->is_active !== $this->division->is_active;
    }

    // Get form statistics
    public function getFormStatsProperty(): array
    {
        $filledFields = 0;
        $totalFields = 7; // Total number of form fields

        $fields = [
            $this->name,
            $this->slug,
            $this->code,
            $this->description,
            $this->faculty_id,
            $this->sort_order,
        ];

        foreach ($fields as $field) {
            if (!empty(trim((string)$field))) {
                $filledFields++;
            }
        }

        // Always count is_active as filled since it has a default
        $filledFields++;

        return [
            'filled' => $filledFields,
            'total' => $totalFields,
            'percentage' => round(($filledFields / $totalFields) * 100)
        ];
    }

    // Get available faculties
    public function getFacultiesProperty()
    {
        try {
            return Faculty::with('university')
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
                ->groupBy('university.name');
        } catch (\Exception $e) {
            Log::error('Error loading faculties', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    // Get all universities (for display purposes)
    public function getUniversitiesProperty()
    {
        try {
            return University::where('is_active', true)
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error loading universities', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    // Get hierarchy path
    public function getHierarchyPathProperty(): string
    {
        if (!$this->selectedFaculty || !$this->selectedUniversity) {
            return 'No faculty selected';
        }

        try {
            return $this->selectedUniversity->name . ' > ' . $this->selectedFaculty->name;
        } catch (\Exception $e) {
            return 'Error loading hierarchy';
        }
    }

    // Get full hierarchy path including division name
    public function getFullHierarchyPathProperty(): string
    {
        $basePath = $this->hierarchyPath;

        if ($basePath === 'No faculty selected' || $basePath === 'Error loading hierarchy') {
            return $basePath;
        }

        $divisionName = $this->name ?: 'New Division';
        return $basePath . ' > ' . $divisionName;
    }

    // Get faculty type badge info
    public function getFacultyTypeBadgeProperty(): array
    {
        if (!$this->selectedFaculty) {
            return ['class' => 'bg-gray-100 text-gray-800', 'text' => 'No Faculty'];
        }

        return $this->selectedFaculty->type === 'faculty'
            ? ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Faculty']
            : ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Office'];
    }

    // Get university info
    public function getUniversityInfoProperty(): ?array
    {
        if (!$this->selectedUniversity) {
            return null;
        }

        return [
            'name' => $this->selectedUniversity->name,
            'code' => $this->selectedUniversity->code,
            'full_name' => $this->selectedUniversity->full_name,
        ];
    }

    // Get faculty info
    public function getFacultyInfoProperty(): ?array
    {
        if (!$this->selectedFaculty) {
            return null;
        }

        return [
            'name' => $this->selectedFaculty->name,
            'code' => $this->selectedFaculty->code,
            'type' => $this->selectedFaculty->type,
            'full_name' => $this->selectedFaculty->full_name,
        ];
    }

    // Get faculty statistics
    public function getFacultyStatsProperty(): array
    {
        if (!$this->faculty_id) {
            return [
                'total_divisions' => 0,
                'active_divisions' => 0,
                'my_sort_order' => 0,
            ];
        }

        try {
            $totalDivisions = Division::where('faculty_id', $this->faculty_id)->count();
            $activeDivisions = Division::where('faculty_id', $this->faculty_id)
                ->where('is_active', true)
                ->count();

            $mySortOrder = $this->sort_order;

            return [
                'total_divisions' => $totalDivisions,
                'active_divisions' => $activeDivisions,
                'my_sort_order' => $mySortOrder,
            ];
        } catch (\Exception $e) {
            return [
                'total_divisions' => 0,
                'active_divisions' => 0,
                'my_sort_order' => 0,
            ];
        }
    }

    public function refreshForm()
    {
        if ($this->isEdit && $this->division->exists) {
            $this->loadDivisionData();
        }
    }

    // Validation messages
    protected function getMessages(): array
    {
        return [
            'name.required' => 'Division name is required.',
            'name.max' => 'Division name cannot exceed 255 characters.',
            'slug.unique' => 'This URL slug is already taken for this faculty.',
            'slug.regex' => 'Slug must contain only lowercase letters, numbers, and hyphens.',
            'code.unique' => 'This division code is already taken for this faculty.',
            'code.max' => 'Division code cannot exceed 20 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'faculty_id.required' => 'Please select a faculty.',
            'faculty_id.exists' => 'Selected faculty does not exist.',
            'sort_order.required' => 'Sort order is required.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order must be at least 0.',
        ];
    }

    public function render()
    {
        return view('livewire.backend.organization.divisions-form', [
            'breadcrumbs' => $this->breadcrumbs,
            'hasChanges' => $this->hasChanges,
            'formStats' => $this->formStats,
            'faculties' => $this->faculties,
            'universities' => $this->universities,
            'hierarchyPath' => $this->hierarchyPath,
            'fullHierarchyPath' => $this->fullHierarchyPath,
            'facultyTypeBadge' => $this->facultyTypeBadge,
            'universityInfo' => $this->universityInfo,
            'facultyInfo' => $this->facultyInfo,
            'facultyStats' => $this->facultyStats,
        ]);
    }
}
