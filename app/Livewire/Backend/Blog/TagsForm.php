<?php

namespace App\Livewire\Backend\Blog;

use App\Models\BlogTag;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

#[Layout('components.layouts.dashboard')]
class TagsForm extends Component
{
    public ?BlogTag $tag = null;
    public bool $isEditing = false;

    // Form Properties
    #[Validate('required|string|max:255')]
    public string $name = '';

    public string $slug = '';

    #[Validate('nullable|string|max:1000')]
    public string $description = '';

    #[Validate('required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/')]
    public string $color = '#10B981';

    #[Validate('boolean')]
    public bool $is_active = true;

    // UI States
    public bool $auto_generate_slug = true;
    public bool $slug_manually_edited = false;
    public array $color_presets = [
        '#10B981',
        '#3B82F6',
        '#F59E0B',
        '#EF4444',
        '#8B5CF6',
        '#EC4899',
        '#06B6D4',
        '#84CC16',
        '#F97316',
        '#6366F1',
        '#14B8A6',
        '#F43F5E'
    ];

    // Validation Messages
    protected array $messages = [
        'name.required' => 'กรุณาใส่ชื่อแท็ก',
        'name.string' => 'ชื่อแท็กต้องเป็นข้อความ',
        'name.max' => 'ชื่อแท็กต้องไม่เกิน 255 ตัวอักษร',
        'slug.required' => 'กรุณาใส่ Slug',
        'slug.string' => 'Slug ต้องเป็นข้อความ',
        'slug.max' => 'Slug ต้องไม่เกิน 255 ตัวอักษร',
        'slug.regex' => 'Slug ต้องประกอบด้วยตัวอักษร ตัวเลข และเครื่องหมาย - เท่านั้น',
        'slug.unique' => 'Slug นี้ถูกใช้งานแล้ว',
        'description.string' => 'คำอธิบายต้องเป็นข้อความ',
        'description.max' => 'คำอธิบายต้องไม่เกิน 1000 ตัวอักษร',
        'color.required' => 'กรุณาเลือกสี',
        'color.string' => 'สีต้องเป็นข้อความ',
        'color.size' => 'รูปแบบสีไม่ถูกต้อง',
        'color.regex' => 'รูปแบบสีต้องเป็น hex color เช่น #10B981',
        'is_active.boolean' => 'สถานะต้องเป็น true หรือ false',
    ];

    public function mount($tag = null): void
    {
        if ($tag && $tag instanceof BlogTag && $tag->exists) {
            $this->tag = $tag;
            $this->isEditing = true;
            $this->fill([
                'name' => $tag->name,
                'slug' => $tag->slug,
                'description' => $tag->description ?? '',
                'color' => $tag->color,
                'is_active' => $tag->is_active,
            ]);
            $this->auto_generate_slug = false;
        }
    }



    public function getTitle(): string
    {
        return $this->isEditing
            ? "แก้ไขแท็ก: {$this->tag->name}"
            : 'เพิ่มแท็กใหม่';
    }

    public function updatedName(): void
    {
        if ($this->auto_generate_slug && !$this->slug_manually_edited) {
            $this->generateSlug();
        }
    }

    public function updatedSlug(): void
    {
        if (!empty($this->slug)) {
            $this->slug_manually_edited = true;
            $this->auto_generate_slug = false;
        }
    }

    public function generateSlug(): void
    {
        $this->slug = Str::slug($this->name);
        $this->validateSlugUniqueness();
    }

    public function toggleAutoSlug(): void
    {
        $this->auto_generate_slug = !$this->auto_generate_slug;

        if ($this->auto_generate_slug) {
            $this->slug_manually_edited = false;
            $this->generateSlug();
        }
    }

    public function validateSlugUniqueness(): void
    {
        if (empty($this->slug)) return;

        $query = BlogTag::where('slug', $this->slug);

        if ($this->isEditing && $this->tag) {
            $query->where('id', '!=', $this->tag->id);
        }

        if ($query->exists()) {
            $baseSlug = $this->slug;
            $counter = 1;

            do {
                $this->slug = $baseSlug . '-' . $counter;
                $counter++;

                $query = BlogTag::where('slug', $this->slug);
                if ($this->isEditing && $this->tag) {
                    $query->where('id', '!=', $this->tag->id);
                }
            } while ($query->exists());
        }
    }

    public function setPresetColor(string $color): void
    {
        $this->color = $color;
    }

    public function getRules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ];

        // Slug validation with uniqueness check
        $slugRule = 'required|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

        if ($this->isEditing && $this->tag) {
            $slugRule .= '|' . Rule::unique('blog_tags', 'slug')->ignore($this->tag->id);
        } else {
            $slugRule .= '|unique:blog_tags,slug';
        }

        $rules['slug'] = $slugRule;

        return $rules;
    }

    public function save(): void
    {
        // Validate form data
        $this->validate($this->getRules(), $this->messages);

        // Ensure slug is generated if empty
        if (empty($this->slug)) {
            $this->generateSlug();
        }

        // Prepare data
        $data = [
            'name' => trim($this->name),
            'slug' => $this->slug,
            'description' => trim($this->description) ?: null,
            'color' => $this->color,
            'is_active' => $this->is_active,
        ];

        try {
            if ($this->isEditing && $this->tag) {
                // Update existing tag
                $this->tag->update($data);

                $message = 'อัปเดตแท็ก "' . $this->name . '" เรียบร้อยแล้ว';
                $this->dispatch('tag-updated', [
                    'message' => $message,
                    'tag' => $this->tag->fresh()
                ]);
            } else {
                // Create new tag
                $tag = BlogTag::create($data);

                $message = 'เพิ่มแท็ก "' . $this->name . '" เรียบร้อยแล้ว';
                $this->dispatch('tag-created', [
                    'message' => $message,
                    'tag' => $tag
                ]);
            }

            // Flash success message
            session()->flash('success', $message);

            // Redirect to tags index
            $this->redirectRoute('administrator.blog.tags.index', navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function cancel(): void
    {
        $this->redirectRoute('administrator.blog.tags.index', navigate: true);
    }

    public function resetForm(): void
    {
        $this->reset([
            'name',
            'slug',
            'description',
            'color',
            'is_active'
        ]);

        $this->color = '#10B981';
        $this->is_active = true;
        $this->auto_generate_slug = true;
        $this->slug_manually_edited = false;

        $this->resetValidation();
    }

    public function duplicate(): void
    {
        if (!$this->isEditing || !$this->tag) {
            return;
        }

        $this->name = $this->tag->name . ' (Copy)';
        $this->description = $this->tag->description;
        $this->color = $this->tag->color;
        $this->is_active = $this->tag->is_active;

        $this->isEditing = false;
        $this->tag = null;
        $this->auto_generate_slug = true;
        $this->slug_manually_edited = false;

        $this->generateSlug();

        $this->dispatch('show-alert', [
            'type' => 'info',
            'message' => 'สร้างสำเนาแท็กเรียบร้อยแล้ว คุณสามารถแก้ไขและบันทึกได้'
        ]);
    }

    public function previewTag(): array
    {
        return [
            'name' => $this->name ?: 'ตัวอย่างแท็ก',
            'color' => $this->color,
            'description' => $this->description ?: 'คำอธิบายแท็ก',
            'is_active' => $this->is_active
        ];
    }

    public function getTagsCount(): int
    {
        return BlogTag::count();
    }

    public function getRecentTags(): \Illuminate\Database\Eloquent\Collection
    {
        return BlogTag::latest()
            ->take(5)
            ->get(['id', 'name', 'color', 'posts_count']);
    }
    public function render()
    {
        return view('livewire.backend.blog.tags-form')
            ->title($this->getTitle());
    }
}
