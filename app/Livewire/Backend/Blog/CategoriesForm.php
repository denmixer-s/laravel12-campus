<?php

namespace App\Livewire\Backend\Blog;

use App\Models\BlogCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CategoriesForm extends Component
{
    use WithFileUploads, AuthorizesRequests;

    public ?BlogCategory $category = null;
    public bool $isEdit = false;

    // Form fields
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255')]
    public string $slug = '';

    #[Validate('nullable|string|max:1000')]
    public string $description = '';

    #[Validate('required|string|regex:/^#[A-Fa-f0-9]{6}$/')]
    public string $color = '#3B82F6';

    #[Validate('nullable|string|max:100')]
    public string $icon = '';

    #[Validate('nullable|string|max:255')]
    public string $meta_title = '';

    #[Validate('nullable|string|max:500')]
    public string $meta_description = '';

    #[Validate('nullable|string|max:255')]
    public string $meta_keywords = '';

    #[Validate('nullable|exists:blog_categories,id')]
    public ?int $parent_id = null;

    #[Validate('required|integer|min:0')]
    public int $sort_order = 0;

    #[Validate('required|boolean')]
    public bool $is_active = true;

    // File uploads
    #[Validate('nullable|image|max:5120')] // 5MB max
    public $featured_image = null;

    #[Validate('nullable|image|max:10240')] // 10MB max
    public $banner_image = null;

    // UI state
    public bool $showAdvanced = false;
    public bool $autoGenerateSlug = true;

    protected $listeners = [
        'categoryUpdated' => 'refreshForm',
    ];

    public function mount()
    {
        // Check if we have a category from route model binding
        if (request()->route('category')) {
            $this->category = request()->route('category');
            $this->isEdit = true;
            $this->authorize('blog.categories.edit');
            $this->loadCategoryData();
        } else {
            $this->category = new BlogCategory();
            $this->isEdit = false;
            $this->authorize('blog.categories.create');
            $this->setDefaults();
        }
    }

    protected function loadCategoryData(): void
    {
        $this->name = $this->category->name;
        $this->slug = $this->category->slug;
        $this->description = $this->category->description ?? '';
        $this->color = $this->category->color ?? '#3B82F6';
        $this->icon = $this->category->icon ?? '';
        $this->meta_title = $this->category->meta_title ?? '';
        $this->meta_description = $this->category->meta_description ?? '';
        $this->meta_keywords = $this->category->meta_keywords ?? '';
        $this->parent_id = $this->category->parent_id;
        $this->sort_order = $this->category->sort_order ?? 0;
        $this->is_active = $this->category->is_active ?? true;

        // Don't auto-generate slug when editing
        $this->autoGenerateSlug = false;
    }

    protected function setDefaults(): void
    {
        $this->color = '#3B82F6';
        $this->is_active = true;
        $this->autoGenerateSlug = true;

        // Auto-assign next sort order
        $maxOrder = BlogCategory::where('parent_id', $this->parent_id)->max('sort_order');
        $this->sort_order = ($maxOrder ?? 0) + 1;
    }

    public function updatedName(): void
    {
        if ($this->autoGenerateSlug && !$this->isEdit) {
            $this->slug = Str::slug($this->name);

            // Auto-generate meta_title if empty
            if (empty($this->meta_title)) {
                $this->meta_title = $this->name;
            }
        }
    }

    public function updatedParentId(): void
    {
        // Update sort order when parent changes
        if (!$this->isEdit) {
            $maxOrder = BlogCategory::where('parent_id', $this->parent_id)->max('sort_order');
            $this->sort_order = ($maxOrder ?? 0) + 1;
        }
    }

    public function toggleAutoSlug(): void
    {
        $this->autoGenerateSlug = !$this->autoGenerateSlug;

        if ($this->autoGenerateSlug && !empty($this->name)) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save()
    {
        // Dynamic validation rules based on edit/create mode
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
            'icon' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:blog_categories,id',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'featured_image' => 'nullable|image|max:5120',
            'banner_image' => 'nullable|image|max:10240',
        ];

        // Slug validation - different for create/edit
        if ($this->isEdit && $this->category->exists) {
            $rules['slug'] = 'nullable|string|max:255|unique:blog_categories,slug,' . $this->category->id;
        } else {
            $rules['slug'] = 'nullable|string|max:255|unique:blog_categories,slug';
        }

        $this->validate($rules);

        try {
            $data = [
                'name' => $this->name,
                'slug' => $this->slug ?: Str::slug($this->name),
                'description' => $this->description,
                'color' => $this->color,
                'icon' => $this->icon,
                'meta_title' => $this->meta_title ?: $this->name,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'parent_id' => $this->parent_id,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
            ];

            if ($this->isEdit) {
                $this->category->update($data);
                $message = 'อัปเดตหมวดหมู่สำเร็จ';
            } else {
                $this->category = BlogCategory::create($data);
                $message = 'สร้างหมวดหมู่สำเร็จ';
            }

            // Handle file uploads
            $this->handleFileUploads();

            session()->flash('success', $message);

            $this->dispatch('categoryUpdated');

            return redirect()->route('administrator.blog.categories.index');

        } catch (\Exception $e) {
            session()->flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    protected function handleFileUploads(): void
    {
        if ($this->featured_image) {
            $this->category
                ->addMediaFromDisk($this->featured_image->getRealPath(), 'local')
                ->toMediaCollection('featured_image');
        }

        if ($this->banner_image) {
            $this->category
                ->addMediaFromDisk($this->banner_image->getRealPath(), 'local')
                ->toMediaCollection('banner_image');
        }
    }

    public function removeImage(string $collection): void
    {
        $this->authorize('blog.categories.edit');

        $media = $this->category->getFirstMedia($collection);
        if ($media) {
            $media->delete();
            session()->flash('success', 'ลบรูปภาพสำเร็จ');
        }
    }

    public function cancel()
    {
        return redirect()->route('administrator.blog.categories.index');
    }

    public function getAvailableParentCategoriesProperty()
    {
        $query = BlogCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name');

        // Exclude self and descendants when editing
        if ($this->isEdit && $this->category->exists) {
            $query->where('id', '!=', $this->category->id);

            // Also exclude children to prevent circular reference
            $childrenIds = $this->category->children()->pluck('id')->toArray();
            if (!empty($childrenIds)) {
                $query->whereNotIn('id', $childrenIds);
            }
        }

        return $query->get();
    }

    public function getFormTitleProperty(): string
    {
        return $this->isEdit ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่ใหม่';
    }

    public function getBreadcrumbProperty(): array
    {
        return [
            ['name' => 'Dashboard', 'url' => route('administrator.blog.dashboard')],
            ['name' => 'หมวดหมู่', 'url' => route('administrator.blog.categories.index')],
            ['name' => $this->formTitle, 'url' => null],
        ];
    }

    public function refreshForm()
    {
        if ($this->isEdit && $this->category->exists) {
            $this->loadCategoryData();
        }
    }

    public function render()
    {
        return view('livewire.backend.blog.categories-form', [
            'availableParents' => $this->availableParentCategories,
        ])->layout('components.layouts.dashboard');
    }
}
