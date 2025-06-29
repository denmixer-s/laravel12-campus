<?php

namespace App\Livewire\Backend\Organization;

use App\Models\Department;
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
class DepartmentsForm extends Component
{
    use AuthorizesRequests;

    public ?Department $department = null;
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

    #[Validate('required|exists:divisions,id')]
    public $division_id = '';

    #[Validate('required|integer|min:0')]
    public int $sort_order = 0;

    #[Validate('required|boolean')]
    public bool $is_active = true;

    // Cascading selects
    public $university_id = '';
    public $faculty_id = '';

    // UI state
    public bool $autoGenerateSlug = true;
    public bool $isSaving = false;

    protected $listeners = [
        'departmentUpdated' => 'refreshForm',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if we have a department from route model binding
        if (request()->route('department')) {
            $this->department = request()->route('department');
            $this->isEdit = true;
            $this->loadDepartmentData();
        } else {
            $this->department = new Department();
            $this->isEdit = false;
            $this->setDefaults();
        }
    }

    protected function loadDepartmentData(): void
    {
        $this->name = $this->department->name;
        $this->slug = $this->department->slug;
        $this->code = $this->department->code ?? '';
        $this->description = $this->department->description ?? '';
        $this->division_id = $this->department->division_id;
        $this->sort_order = $this->department->sort_order ?? 0;
        $this->is_active = $this->department->is_active ?? true;

        // Load cascading values
        if ($this->department->division) {
            $this->faculty_id = $this->department->division->faculty_id;
            if ($this->department->division->faculty) {
                $this->university_id = $this->department->division->faculty->university_id;
            }
        }

        // Don't auto-generate slug when editing
        $this->autoGenerateSlug = false;
    }

    protected function setDefaults(): void
    {
        $this->name = '';
        $this->slug = '';
        $this->code = '';
        $this->description = '';
        $this->university_id = '';
        $this->faculty_id = '';
        $this->division_id = '';
        $this->sort_order = $this->getNextSortOrder();
        $this->is_active = true;
        $this->autoGenerateSlug = true;
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

    public function updatedUniversityId(): void
    {
        // Reset dependent fields when university changes
        $this->faculty_id = '';
        $this->division_id = '';
        if (!$this->isEdit) {
            $this->sort_order = $this->getNextSortOrder();
        }
    }

    public function updatedFacultyId(): void
    {
        // Reset division when faculty changes
        $this->division_id = '';
        if (!$this->isEdit) {
            $this->sort_order = $this->getNextSortOrder();
        }
    }

    public function updatedDivisionId(): void
    {
        // Update sort order when division changes
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
        if (!$this->division_id) {
            return 0;
        }

        $maxOrder = Department::where('division_id', $this->division_id)
            ->max('sort_order');

        return ($maxOrder ?? 0) + 1;
    }

    public function save()
    {
        // Check permissions
        if (!auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'Only Super Admin can manage departments.');
            return;
        }

        $this->isSaving = true;

        try {
            // Dynamic validation rules based on edit/create mode
            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'code' => 'nullable|string|max:20',
                'division_id' => 'required|exists:divisions,id',
                'sort_order' => 'required|integer|min:0',
                'is_active' => 'required|boolean',
            ];

            // Slug validation - different for create/edit and unique per division
            if ($this->isEdit && $this->department->exists) {
                $rules['slug'] = 'nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:departments,slug,' . $this->department->id . ',id,division_id,' . $this->division_id;
                $rules['code'] = 'nullable|string|max:20|unique:departments,code,' . $this->department->id . ',id,division_id,' . $this->division_id;
            } else {
                $rules['slug'] = 'nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:departments,slug,NULL,id,division_id,' . $this->division_id;
                $rules['code'] = 'nullable|string|max:20|unique:departments,code,NULL,id,division_id,' . $this->division_id;
            }

            $this->validate($rules);

            $data = [
                'name' => $this->name,
                'slug' => $this->slug ?: Str::slug($this->name),
                'code' => $this->code ?: null,
                'description' => $this->description ?: null,
                'division_id' => $this->division_id,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
            ];

            DB::transaction(function () use ($data) {
                if ($this->isEdit) {
                    $this->department->update($data);
                    Log::info('Department updated: ' . $this->department->name);
                } else {
                    $this->department = Department::create($data);
                    Log::info('Department created: ' . $this->department->name);
                }
            });

            $message = $this->isEdit
                ? "Department '{$this->department->name}' updated successfully!"
                : "Department '{$this->department->name}' created successfully!";

            session()->flash('success', $message);

            // Dispatch events for real-time updates
            if ($this->isEdit) {
                $this->dispatch('departmentUpdated');
            } else {
                $this->dispatch('departmentCreated');
            }

            // Redirect to index after successful save
            return redirect()->route('administrator.organization.departments.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let Livewire handle validation errors
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error saving department: ' . $e->getMessage());
            session()->flash('error', 'Failed to save department. Please try again.');
        } finally {
            $this->isSaving = false;
        }
    }

    public function cancel()
    {
        return redirect()->route('administrator.organization.departments.index');
    }

    public function resetForm(): void
    {
        if ($this->isEdit) {
            $this->loadDepartmentData();
        } else {
            $this->setDefaults();
        }
        $this->resetValidation();
    }

    public function getFormTitleProperty(): string
    {
        return $this->isEdit ? 'Edit Department' : 'Create New Department';
    }

    public function getBreadcrumbsProperty(): array
    {
        return [
            ['name' => 'Dashboard', 'url' => route('administrator.dashboard')],
            ['name' => 'Organization', 'url' => '#'],
            ['name' => 'Departments', 'url' => route('administrator.organization.departments.index')],
            ['name' => $this->formTitle, 'url' => null],
        ];
    }

    // Check if form has changes
    public function getHasChangesProperty(): bool
    {
        if (!$this->isEdit || !$this->department->exists) {
            return !empty($this->name) || !empty($this->slug) || !empty($this->code) ||
                   !empty($this->description) || !empty($this->division_id);
        }

        return $this->name !== $this->department->name ||
               $this->slug !== $this->department->slug ||
               $this->code !== ($this->department->code ?? '') ||
               $this->description !== ($this->department->description ?? '') ||
               $this->division_id !== $this->department->division_id ||
               $this->sort_order !== $this->department->sort_order ||
               $this->is_active !== $this->department->is_active;
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
            $this->division_id,
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

    // Get available universities
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

    // Get available faculties based on selected university
    public function getFacultiesProperty()
    {
        try {
            if (!$this->university_id) {
                return collect();
            }

            return Faculty::where('university_id', $this->university_id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error loading faculties', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    // Get available divisions based on selected faculty
    public function getDivisionsProperty()
    {
        try {
            if (!$this->faculty_id) {
                return collect();
            }

            return Division::where('faculty_id', $this->faculty_id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error loading divisions', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    // Get hierarchy path
    public function getHierarchyPathProperty(): string
    {
        if (!$this->division_id) {
            return 'No division selected';
        }

        try {
            $division = Division::with('faculty.university')->find($this->division_id);
            if (!$division) {
                return 'Division not found';
            }

            return $division->faculty->university->name . ' > ' .
                   $division->faculty->name . ' > ' .
                   $division->name;
        } catch (\Exception $e) {
            return 'Error loading hierarchy';
        }
    }

    // Get division info for display
    public function getDivisionInfoProperty(): ?Division
    {
        if (!$this->division_id) {
            return null;
        }

        try {
            return Division::with('faculty.university')->find($this->division_id);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function refreshForm()
    {
        if ($this->isEdit && $this->department->exists) {
            $this->loadDepartmentData();
        }
    }

    // Validation messages
    protected function getMessages(): array
    {
        return [
            'name.required' => 'Department name is required.',
            'name.max' => 'Department name cannot exceed 255 characters.',
            'slug.unique' => 'This URL slug is already taken for this division.',
            'slug.regex' => 'Slug must contain only lowercase letters, numbers, and hyphens.',
            'code.unique' => 'This department code is already taken for this division.',
            'code.max' => 'Department code cannot exceed 20 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'division_id.required' => 'Please select a division.',
            'division_id.exists' => 'Selected division does not exist.',
            'sort_order.required' => 'Sort order is required.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order must be at least 0.',
        ];
    }

    public function render()
    {
        return view('livewire.backend.organization.departments-form', [
            'breadcrumbs' => $this->breadcrumbs,
            'hasChanges' => $this->hasChanges,
            'formStats' => $this->formStats,
            'universities' => $this->universities,
            'faculties' => $this->faculties,
            'divisions' => $this->divisions,
            'hierarchyPath' => $this->hierarchyPath,
            'divisionInfo' => $this->divisionInfo,
        ]);
    }
}
