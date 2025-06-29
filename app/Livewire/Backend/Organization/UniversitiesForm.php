<?php

namespace App\Livewire\Backend\Organization;

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
class UniversitiesForm extends Component
{
    use AuthorizesRequests;

    public ?University $university = null;
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

    #[Validate('nullable|string|max:500')]
    public string $address = '';

    #[Validate('nullable|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|email|max:255')]
    public string $email = '';

    #[Validate('nullable|url|max:255')]
    public string $website = '';

    #[Validate('required|boolean')]
    public bool $is_active = true;

    // UI state
    public bool $autoGenerateSlug = true;
    public bool $isSaving = false;

    protected $listeners = [
        'universityUpdated' => 'refreshForm',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if we have a university from route model binding
        if (request()->route('university')) {
            $this->university = request()->route('university');
            $this->isEdit = true;
            $this->loadUniversityData();
        } else {
            $this->university = new University();
            $this->isEdit = false;
            $this->setDefaults();
        }
    }

    protected function loadUniversityData(): void
    {
        $this->name = $this->university->name;
        $this->slug = $this->university->slug;
        $this->code = $this->university->code ?? '';
        $this->description = $this->university->description ?? '';
        $this->address = $this->university->address ?? '';
        $this->phone = $this->university->phone ?? '';
        $this->email = $this->university->email ?? '';
        $this->website = $this->university->website ?? '';
        $this->is_active = $this->university->is_active ?? true;

        // Don't auto-generate slug when editing
        $this->autoGenerateSlug = false;
    }

    protected function setDefaults(): void
    {
        $this->name = '';
        $this->slug = '';
        $this->code = '';
        $this->description = '';
        $this->address = '';
        $this->phone = '';
        $this->email = '';
        $this->website = '';
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

    public function save()
    {
        // Check permissions
        if (!auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'Only Super Admin can manage universities.');
            return;
        }

        $this->isSaving = true;

        try {
            // Dynamic validation rules based on edit/create mode
            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'address' => 'nullable|string|max:500',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|url|max:255',
                'is_active' => 'required|boolean',
            ];

            // Slug validation - different for create/edit
            if ($this->isEdit && $this->university->exists) {
                $rules['slug'] = 'nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:universities,slug,' . $this->university->id;
                $rules['code'] = 'nullable|string|max:20|unique:universities,code,' . $this->university->id;
            } else {
                $rules['slug'] = 'nullable|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:universities,slug';
                $rules['code'] = 'nullable|string|max:20|unique:universities,code';
            }

            $this->validate($rules);

            $data = [
                'name' => $this->name,
                'slug' => $this->slug ?: Str::slug($this->name),
                'code' => $this->code ?: null,
                'description' => $this->description ?: null,
                'address' => $this->address ?: null,
                'phone' => $this->phone ?: null,
                'email' => $this->email ?: null,
                'website' => $this->website ?: null,
                'is_active' => $this->is_active,
            ];

            DB::transaction(function () use ($data) {
                if ($this->isEdit) {
                    $this->university->update($data);
                    Log::info('University updated: ' . $this->university->name);
                } else {
                    $this->university = University::create($data);
                    Log::info('University created: ' . $this->university->name);
                }
            });

            $message = $this->isEdit
                ? "University '{$this->university->name}' updated successfully!"
                : "University '{$this->university->name}' created successfully!";

            session()->flash('success', $message);

            // Dispatch events for real-time updates
            if ($this->isEdit) {
                $this->dispatch('universityUpdated');
            } else {
                $this->dispatch('universityCreated');
            }

            // Redirect to index after successful save
            return redirect()->route('administrator.organization.universities.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let Livewire handle validation errors
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error saving university: ' . $e->getMessage());
            session()->flash('error', 'Failed to save university. Please try again.');
        } finally {
            $this->isSaving = false;
        }
    }

    public function cancel()
    {
        return redirect()->route('administrator.organization.universities.index');
    }

    public function resetForm(): void
    {
        if ($this->isEdit) {
            $this->loadUniversityData();
        } else {
            $this->setDefaults();
        }
        $this->resetValidation();
    }

    public function getFormTitleProperty(): string
    {
        return $this->isEdit ? 'Edit University' : 'Create New University';
    }

    public function getBreadcrumbsProperty(): array
    {
        return [
            ['name' => 'Dashboard', 'url' => route('administrator.dashboard')],
            ['name' => 'Organization', 'url' => '#'],
            ['name' => 'Universities', 'url' => route('administrator.organization.universities.index')],
            ['name' => $this->formTitle, 'url' => null],
        ];
    }

    // Check if form has changes
    public function getHasChangesProperty(): bool
    {
        if (!$this->isEdit || !$this->university->exists) {
            return !empty($this->name) || !empty($this->slug) || !empty($this->code) ||
                   !empty($this->description) || !empty($this->address) || !empty($this->phone) ||
                   !empty($this->email) || !empty($this->website);
        }

        return $this->name !== $this->university->name ||
               $this->slug !== $this->university->slug ||
               $this->code !== ($this->university->code ?? '') ||
               $this->description !== ($this->university->description ?? '') ||
               $this->address !== ($this->university->address ?? '') ||
               $this->phone !== ($this->university->phone ?? '') ||
               $this->email !== ($this->university->email ?? '') ||
               $this->website !== ($this->university->website ?? '') ||
               $this->is_active !== $this->university->is_active;
    }

    // Get form statistics
    public function getFormStatsProperty(): array
    {
        $filledFields = 0;
        $totalFields = 9; // Total number of form fields

        $fields = [
            $this->name,
            $this->slug,
            $this->code,
            $this->description,
            $this->address,
            $this->phone,
            $this->email,
            $this->website
        ];

        foreach ($fields as $field) {
            if (!empty(trim($field))) {
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

    public function refreshForm()
    {
        if ($this->isEdit && $this->university->exists) {
            $this->loadUniversityData();
        }
    }

    // Validation messages
    protected function getMessages(): array
    {
        return [
            'name.required' => 'University name is required.',
            'name.max' => 'University name cannot exceed 255 characters.',
            'slug.unique' => 'This URL slug is already taken.',
            'slug.regex' => 'Slug must contain only lowercase letters, numbers, and hyphens.',
            'code.unique' => 'This university code is already taken.',
            'code.max' => 'University code cannot exceed 20 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'address.max' => 'Address cannot exceed 500 characters.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'email.email' => 'Please enter a valid email address.',
            'website.url' => 'Please enter a valid website URL.',
        ];
    }

    public function render()
    {
        return view('livewire.backend.organization.universities-form', [
            'breadcrumbs' => $this->breadcrumbs,
            'hasChanges' => $this->hasChanges,
            'formStats' => $this->formStats,
        ]);
    }
}