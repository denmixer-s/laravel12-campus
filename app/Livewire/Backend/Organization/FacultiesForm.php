<?php

namespace App\Livewire\Backend\Organization;

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
class FacultiesForm extends Component
{
    use AuthorizesRequests;

    public ?Faculty $faculty = null;
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

    #[Validate('required|exists:universities,id')]
    public $university_id = '';

    #[Validate('required|in:faculty,office')]
    public string $type = 'faculty';

    #[Validate('required|integer|min:0')]
    public int $sort_order = 0;

    #[Validate('required|boolean')]
    public bool $is_active = true;

    // UI state
    public bool $autoGenerateSlug = true;
    public bool $isSaving = false;

    protected $listeners = [
        'facultyUpdated' => 'refreshForm',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if we have a faculty from route model binding
        if (request()->route('faculty')) {
            $this->faculty = request()->route('faculty');
            $this->isEdit = true;
            $this->loadFacultyData();
        } else {
            $this->faculty = new Faculty();
            $this->isEdit = false;
            $this->setDefaults();
        }
    }

    protected function loadFacultyData(): void
    {
        $this->name = $this->faculty->name;
        $this->slug = $this->faculty->slug;
        $this->code = $this->faculty->code ?? '';
        $this->description = $this->faculty->description ?? '';
        $this->university_id = $this->faculty->university_id;
        $this->type = $this->faculty->type ?? 'faculty';
        $this->sort_order = $this->faculty->sort_order ?? 0;
        $this->is_active = $this->faculty->is_active ?? true;

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
        $this->type = 'faculty';
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
        // Update sort order when university changes
        if (!$this->isEdit) {
            $this->sort_order = $this->getNextSortOrder();
        }
    }

    public function updatedType(): void
    {
        // Update sort order when type changes
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
        if (!$this->university_id) {
            return 0;
        }

        $maxOrder = Faculty::where('university_id', $this->university_id)
            ->where('type', $this->type)
            ->max('sort_order');

        return ($maxOrder ?? 0) + 1;
    }

    public function save()
    {
        // Check permissions
        if (!auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'Only Super Admin can manage faculties.');
            return;
        }

        $this->isSaving = true;

        try {
            // Dynamic validation rules based on edit/create mode
            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'code' => 'nullable|string|max:20',
                'university_id' => 'required|exists:universities,id',
                'type' => 'required|in:faculty,office',
                'sort_order' => 'required|integer|min:0',
                'is_active' => 'required|boolean',
            ];

            // Slug validation - different for create/edit and unique per university
            if ($this->isEdit && $this->faculty->exists) {
                $rules['slug'] = 'nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:faculties,slug,' . $this->faculty->id . ',id,university_id,' . $this->university_id;
                $rules['code'] = 'nullable|string|max:20|unique:faculties,code,' . $this->faculty->id . ',id,university_id,' . $this->university_id;
            } else {
                $rules['slug'] = 'nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:faculties,slug,NULL,id,university_id,' . $this->university_id;
                $rules['code'] = 'nullable|string|max:20|unique:faculties,code,NULL,id,university_id,' . $this->university_id;
            }

            $this->validate($rules);

            $data = [
                'name' => $this->name,
                'slug' => $this->slug ?: Str::slug($this->name),
                'code' => $this->code ?: null,
                'description' => $this->description ?: null,
                'university_id' => $this->university_id,
                'type' => $this->type,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
            ];

            DB::transaction(function () use ($data) {
                if ($this->isEdit) {
                    $this->faculty->update($data);
                    Log::info('Faculty updated: ' . $this->faculty->name);
                } else {
                    $this->faculty = Faculty::create($data);
                    Log::info('Faculty created: ' . $this->faculty->name);
                }
            });

            $message = $this->isEdit
                ? "Faculty '{$this->faculty->name}' updated successfully!"
                : "Faculty '{$this->faculty->name}' created successfully!";

            session()->flash('success', $message);

            // Dispatch events for real-time updates
            if ($this->isEdit) {
                $this->dispatch('facultyUpdated');
            } else {
                $this->dispatch('facultyCreated');
            }

            // Redirect to index after successful save
            return redirect()->route('administrator.organization.faculties.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let Livewire handle validation errors
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error saving faculty: ' . $e->getMessage());
            session()->flash('error', 'Failed to save faculty. Please try again.');
        } finally {
            $this->isSaving = false;
        }
    }

    public function cancel()
    {
        return redirect()->route('administrator.organization.faculties.index');
    }

    public function resetForm(): void
    {
        if ($this->isEdit) {
            $this->loadFacultyData();
        } else {
            $this->setDefaults();
        }
        $this->resetValidation();
    }

    public function getFormTitleProperty(): string
    {
        return $this->isEdit ? 'Edit Faculty' : 'Create New Faculty';
    }

    public function getBreadcrumbsProperty(): array
    {
        return [
            ['name' => 'Dashboard', 'url' => route('administrator.dashboard')],
            ['name' => 'Organization', 'url' => '#'],
            ['name' => 'Faculties', 'url' => route('administrator.organization.faculties.index')],
            ['name' => $this->formTitle, 'url' => null],
        ];
    }

    // Check if form has changes
    public function getHasChangesProperty(): bool
    {
        if (!$this->isEdit || !$this->faculty->exists) {
            return !empty($this->name) || !empty($this->slug) || !empty($this->code) ||
                   !empty($this->description) || !empty($this->university_id);
        }

        return $this->name !== $this->faculty->name ||
               $this->slug !== $this->faculty->slug ||
               $this->code !== ($this->faculty->code ?? '') ||
               $this->description !== ($this->faculty->description ?? '') ||
               $this->university_id !== $this->faculty->university_id ||
               $this->type !== $this->faculty->type ||
               $this->sort_order !== $this->faculty->sort_order ||
               $this->is_active !== $this->faculty->is_active;
    }

    // Get form statistics
    public function getFormStatsProperty(): array
    {
        $filledFields = 0;
        $totalFields = 8; // Total number of form fields

        $fields = [
            $this->name,
            $this->slug,
            $this->code,
            $this->description,
            $this->university_id,
            $this->type,
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

    // Get type options
    public function getTypeOptionsProperty(): array
    {
        return [
            'faculty' => 'Faculty',
            'office' => 'Office',
        ];
    }

    // Get university hierarchy path
    public function getUniversityHierarchyProperty(): string
    {
        if (!$this->university_id) {
            return 'No university selected';
        }

        try {
            $university = University::find($this->university_id);
            return $university ? $university->name : 'Unknown university';
        } catch (\Exception $e) {
            return 'Error loading university';
        }
    }

    public function refreshForm()
    {
        if ($this->isEdit && $this->faculty->exists) {
            $this->loadFacultyData();
        }
    }

    // Validation messages
    protected function getMessages(): array
    {
        return [
            'name.required' => 'Faculty name is required.',
            'name.max' => 'Faculty name cannot exceed 255 characters.',
            'slug.unique' => 'This URL slug is already taken for this university.',
            'slug.regex' => 'Slug must contain only lowercase letters, numbers, and hyphens.',
            'code.unique' => 'This faculty code is already taken for this university.',
            'code.max' => 'Faculty code cannot exceed 20 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'university_id.required' => 'Please select a university.',
            'university_id.exists' => 'Selected university does not exist.',
            'type.required' => 'Please select a type.',
            'type.in' => 'Type must be either Faculty or Office.',
            'sort_order.required' => 'Sort order is required.',
            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order must be at least 0.',
        ];
    }

    public function render()
    {
        return view('livewire.backend.organization.faculties-form', [
            'breadcrumbs' => $this->breadcrumbs,
            'hasChanges' => $this->hasChanges,
            'formStats' => $this->formStats,
            'universities' => $this->universities,
            'typeOptions' => $this->typeOptions,
            'universityHierarchy' => $this->universityHierarchy,
        ]);
    }
}
