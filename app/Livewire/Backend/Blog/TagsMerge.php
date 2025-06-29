<?php

namespace App\Livewire\Backend\Blog;

use App\Models\BlogTag;
use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

#[Layout('components.layouts.dashboard')]
class TagsMerge extends Component
{
    use WithPagination;

    // Merge Process Properties
    public $step = 'select'; // select, preview, confirm, complete
    public array $selectedTags = [];
    public $targetTagId = null;
    public ?BlogTag $targetTag = null;
    
    // Target Tag Creation
    public bool $createNewTarget = false;
    public $newTargetName = '';
    public $newTargetSlug = '';
    public $newTargetDescription = '';
    public $newTargetColor = '#10B981';
    public bool $newTargetActive = true;

    // Search & Filter
    public $search = '';
    public $sortBy = 'posts_count';
    public $sortDirection = 'desc';
    public $perPage = 20;

    // Merge Statistics
    public $totalPostsAffected = 0;
    public $duplicateTagsCount = 0;
    public $mergeSummary = [];

    // UI States
    public $showPreview = false;
    public $mergeInProgress = false;
    public $mergeCompleted = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'step' => ['except' => 'select']
    ];

    public function mount(): void
    {
        $this->loadMergeStatistics();
    }

    public function render()
    {
        $tags = $this->getTagsQuery();
        $availableTargets = $this->getAvailableTargets();

        return view('livewire.backend.blog.tags-merge', [
            'tags' => $tags,
            'availableTargets' => $availableTargets
        ])->title('รวมแท็ก - จัดการแท็กที่ซ้ำกัน');
    }

    private function getTagsQuery()
    {
        $query = BlogTag::withCount('posts');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
            });
        }

        // Exclude already selected tags
        if (!empty($this->selectedTags)) {
            $query->whereNotIn('id', $this->selectedTags);
        }

        // Apply sorting with proper column references
        if ($this->sortBy === 'posts_count') {
            // Use the database column for sorting to avoid ambiguity
            $query->orderBy('blog_tags.posts_count', $this->sortDirection);
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return $query->paginate($this->perPage);
    }

    private function getAvailableTargets()
    {
        $query = BlogTag::query();
        
        // Exclude selected tags from targets
        if (!empty($this->selectedTags)) {
            $query->whereNotIn('id', $this->selectedTags);
        }

        return $query->orderBy('blog_tags.posts_count', 'desc')
                    ->orderBy('name')
                    ->get();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'desc';
        }
        $this->resetPage();
    }

    public function toggleTagSelection($tagId): void
    {
        if (in_array($tagId, $this->selectedTags)) {
            $this->selectedTags = array_diff($this->selectedTags, [$tagId]);
        } else {
            $this->selectedTags[] = $tagId;
        }
        
        $this->calculateMergeStats();
    }

    public function selectAllVisible(): void
    {
        $visibleTagIds = $this->getTagsQuery()->pluck('id')->toArray();
        $this->selectedTags = array_unique(array_merge($this->selectedTags, $visibleTagIds));
        $this->calculateMergeStats();
    }

    public function clearSelection(): void
    {
        $this->selectedTags = [];
        $this->targetTagId = null;
        $this->targetTag = null;
        $this->calculateMergeStats();
    }

    public function setTargetTag($tagId): void
    {
        $this->targetTagId = $tagId;
        $this->targetTag = BlogTag::findOrFail($tagId);
        $this->createNewTarget = false;
        $this->calculateMergeStats();
    }

    public function toggleCreateNewTarget(): void
    {
        $this->createNewTarget = !$this->createNewTarget;
        
        if ($this->createNewTarget) {
            $this->targetTagId = null;
            $this->targetTag = null;
        }
        
        $this->calculateMergeStats();
    }

    public function updatedNewTargetName(): void
    {
        if ($this->createNewTarget && !empty($this->newTargetName)) {
            $this->newTargetSlug = \Str::slug($this->newTargetName);
        }
    }

    private function calculateMergeStats(): void
    {
        if (empty($this->selectedTags)) {
            $this->totalPostsAffected = 0;
            $this->duplicateTagsCount = 0;
            $this->mergeSummary = [];
            return;
        }

        $selectedTagsData = BlogTag::whereIn('id', $this->selectedTags)
            ->withCount('posts')
            ->get();

        $this->totalPostsAffected = $selectedTagsData->sum('posts_count');
        $this->duplicateTagsCount = $selectedTagsData->count();

        // Calculate affected posts (unique posts)
        $affectedPostIds = DB::table('blog_post_tags')
            ->whereIn('blog_tag_id', $this->selectedTags)
            ->distinct()
            ->pluck('blog_post_id');

        $this->totalPostsAffected = $affectedPostIds->count();

        $this->mergeSummary = [
            'tags_to_merge' => $this->duplicateTagsCount,
            'posts_affected' => $this->totalPostsAffected,
            'target_exists' => !$this->createNewTarget && $this->targetTag,
            'create_new' => $this->createNewTarget
        ];
    }

    public function proceedToPreview(): void
    {
        if (empty($this->selectedTags)) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'กรุณาเลือกแท็กที่ต้องการรวม'
            ]);
            return;
        }

        if (!$this->createNewTarget && !$this->targetTagId) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'กรุณาเลือกแท็กปลายทางหรือสร้างแท็กใหม่'
            ]);
            return;
        }

        if ($this->createNewTarget) {
            $this->validate([
                'newTargetName' => 'required|string|max:255',
                'newTargetSlug' => 'required|string|max:255|unique:blog_tags,slug',
                'newTargetDescription' => 'nullable|string|max:1000',
                'newTargetColor' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
            ], [
                'newTargetName.required' => 'กรุณาใส่ชื่อแท็กใหม่',
                'newTargetSlug.unique' => 'Slug นี้ถูกใช้งานแล้ว',
                'newTargetColor.regex' => 'รูปแบบสีไม่ถูกต้อง',
            ]);
        }

        $this->step = 'preview';
        $this->calculateMergeStats();
    }

    public function backToSelect(): void
    {
        $this->step = 'select';
    }

    public function confirmMerge(): void
    {
        $this->step = 'confirm';
    }

    public function executeMerge(): void
    {
        if ($this->mergeInProgress) {
            return;
        }

        $this->mergeInProgress = true;

        try {
            DB::transaction(function () {
                // Create or get target tag
                if ($this->createNewTarget) {
                    $this->targetTag = BlogTag::create([
                        'name' => $this->newTargetName,
                        'slug' => $this->newTargetSlug,
                        'description' => $this->newTargetDescription ?: null,
                        'color' => $this->newTargetColor,
                        'is_active' => $this->newTargetActive,
                        'posts_count' => 0,
                    ]);
                    $this->targetTagId = $this->targetTag->id;
                }

                // Get all post IDs that are tagged with selected tags
                $affectedPostIds = DB::table('blog_post_tags')
                    ->whereIn('blog_tag_id', $this->selectedTags)
                    ->distinct()
                    ->pluck('blog_post_id');

                // Remove old tag associations
                DB::table('blog_post_tags')
                    ->whereIn('blog_tag_id', $this->selectedTags)
                    ->delete();

                // Add new tag associations (avoid duplicates)
                foreach ($affectedPostIds as $postId) {
                    DB::table('blog_post_tags')->updateOrInsert([
                        'blog_post_id' => $postId,
                        'blog_tag_id' => $this->targetTagId,
                    ]);
                }

                // Update target tag posts count
                $newPostsCount = DB::table('blog_post_tags')
                    ->where('blog_tag_id', $this->targetTagId)
                    ->count();

                $this->targetTag->update(['posts_count' => $newPostsCount]);

                // Delete selected tags
                BlogTag::whereIn('id', $this->selectedTags)->delete();
            });

            $this->step = 'complete';
            $this->mergeCompleted = true;

            $this->dispatch('tags-merged', [
                'message' => "รวมแท็ก {$this->duplicateTagsCount} รายการเรียบร้อยแล้ว"
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        } finally {
            $this->mergeInProgress = false;
        }
    }

    public function startOver(): void
    {
        $this->reset([
            'step', 'selectedTags', 'targetTagId', 'targetTag',
            'createNewTarget', 'newTargetName', 'newTargetSlug',
            'newTargetDescription', 'newTargetColor', 'newTargetActive',
            'mergeCompleted', 'mergeInProgress'
        ]);
        
        $this->newTargetColor = '#10B981';
        $this->newTargetActive = true;
        $this->loadMergeStatistics();
    }

    public function loadMergeStatistics(): void
    {
        // Find potential duplicate tags (same name but different IDs)
        $duplicates = DB::table('blog_tags')
            ->select('name', DB::raw('COUNT(*) as count'))
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $this->duplicateTagsCount = $duplicates->sum('count') - $duplicates->count();
    }

    public function findSimilarTags(): array
    {
        // Find tags with similar names (Levenshtein distance)
        $allTags = BlogTag::all(['id', 'name', 'posts_count']);
        $similarGroups = [];

        foreach ($allTags as $tag1) {
            foreach ($allTags as $tag2) {
                if ($tag1->id !== $tag2->id) {
                    $distance = levenshtein(strtolower($tag1->name), strtolower($tag2->name));
                    if ($distance <= 2 && $distance > 0) { // Similar names
                        $key = implode('-', [$tag1->id < $tag2->id ? $tag1->id : $tag2->id, 
                                           $tag1->id > $tag2->id ? $tag1->id : $tag2->id]);
                        
                        if (!isset($similarGroups[$key])) {
                            $similarGroups[$key] = [$tag1, $tag2];
                        }
                    }
                }
            }
        }

        return array_values($similarGroups);
    }

    public function autoSelectSimilar(): void
    {
        $similarGroups = $this->findSimilarTags();
        
        if (!empty($similarGroups)) {
            // Select the first group of similar tags
            $firstGroup = $similarGroups[0];
            $this->selectedTags = collect($firstGroup)->pluck('id')->toArray();
            $this->calculateMergeStats();
            
            $this->dispatch('show-alert', [
                'type' => 'info',
                'message' => 'เลือกแท็กที่คล้ายกันแล้ว: ' . collect($firstGroup)->pluck('name')->join(', ')
            ]);
        } else {
            $this->dispatch('show-alert', [
                'type' => 'info',
                'message' => 'ไม่พบแท็กที่คล้ายกัน'
            ]);
        }
    }

    public function getSelectedTagsDetails()
    {
        if (empty($this->selectedTags)) {
            return collect();
        }

        return BlogTag::whereIn('id', $this->selectedTags)
            ->withCount('posts')
            ->orderBy('blog_tags.posts_count', 'desc')
            ->get();
    }

    #[On('tag-created')]
    #[On('tag-updated')]
    public function refreshData(): void
    {
        $this->loadMergeStatistics();
    }
}