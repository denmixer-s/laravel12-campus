<?php

namespace App\Livewire\Backend\Blog;

use App\Models\BlogCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CategoriesReorder extends Component
{
    use AuthorizesRequests;

    public $categories     = [];
    public $categoriesTree = [];
    public $hasChanges     = false;
    public $isSaving       = false;

    public function mount()
    {
        $this->authorize('blog.categories.edit');
        $this->loadCategories();
    }

    public function loadCategories()
    {
        // Load all categories with their relationships
        $this->categories = BlogCategory::with(['parent', 'children'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->toArray();

        // Build hierarchical tree structure
        $this->categoriesTree = $this->buildTree($this->categories);
    }

    private function buildTree($categories, $parentId = null)
    {
        $tree = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $children = $this->buildTree($categories, $category['id']);
                if (! empty($children)) {
                    $category['children'] = $children;
                }
                $tree[] = $category;
            }
        }

        return $tree;
    }

    /**
     * บันทึกการเปลี่ยนแปลงจากการลาก
     */
    public function saveDragChanges($changes)
    {
        $this->authorize('blog.categories.edit');

        if (empty($changes)) {
            return;
        }

        try {
            DB::beginTransaction();

            foreach ($changes as $change) {
                $categoryId = (int) $change['categoryId'];
                $newParentId = isset($change['newParentId']) && $change['newParentId'] !== null 
                    ? (int) $change['newParentId'] 
                    : null;
                $newPosition = (int) $change['newPosition'];

                // ป้องกันการสร้าง infinite loop (ลากพ่อไปเป็นลูก)
                if ($this->wouldCreateInfiniteLoop($categoryId, $newParentId)) {
                    $this->js("
                        console.warn('Cannot move category: would create infinite loop');
                        alert('ไม่สามารถย้ายหมวดหมู่ได้ เนื่องจากจะทำให้เกิดการวนซ้ำ');
                    ");
                    continue;
                }

                // อัพเดต parent_id
                BlogCategory::where('id', $categoryId)
                    ->update(['parent_id' => $newParentId]);

                // คำนวณและอัพเดต sort_order
                $this->updateSortOrder($categoryId, $newParentId, $newPosition);
            }

            DB::commit();

            $this->hasChanges = true;
            
            // ส่ง success response โดยไม่ re-render
            $this->js("
                console.log('Drag changes saved successfully');
                window.dragChangesSuccess = true;
                // แสดง toast notification
                if (typeof showToast === 'function') {
                    showToast('บันทึกการเปลี่ยนแปลงสำเร็จ', 'success');
                }
            ");

        } catch (\Exception $e) {
            DB::rollback();
            
            $this->js("
                console.error('Error saving drag changes: {$e->getMessage()}');
                alert('เกิดข้อผิดพลาดในการบันทึก: {$e->getMessage()}');
            ");
        }
    }

    /**
     * ตรวจสอบว่าการย้ายจะทำให้เกิด infinite loop หรือไม่
     */
    private function wouldCreateInfiniteLoop($categoryId, $newParentId)
    {
        if ($newParentId === null) {
            return false; // ย้ายไปเป็น root category ไม่มีปัญหา
        }

        // ตรวจสอบว่า newParentId เป็น descendant ของ categoryId หรือไม่
        return $this->isDescendant($categoryId, $newParentId);
    }

    /**
     * ตรวจสอบว่า categoryId เป็น ancestor ของ potentialDescendantId หรือไม่
     */
    private function isDescendant($ancestorId, $potentialDescendantId)
    {
        $category = BlogCategory::find($potentialDescendantId);
        
        while ($category && $category->parent_id) {
            if ($category->parent_id == $ancestorId) {
                return true;
            }
            $category = BlogCategory::find($category->parent_id);
        }
        
        return false;
    }

    /**
     * อัพเดต sort_order สำหรับ siblings
     */
    private function updateSortOrder($categoryId, $parentId, $position)
    {
        // หา categories ทั้งหมดที่มี parent เดียวกัน
        $siblings = BlogCategory::where('parent_id', $parentId)
            ->where('id', '!=', $categoryId)
            ->orderBy('sort_order')
            ->get();

        // คำนวณ sort_order ใหม่สำหรับ category ที่ย้าย
        $newSortOrder = ($position + 1) * 10;
        
        // อัพเดต category ที่ย้าย
        BlogCategory::where('id', $categoryId)
            ->update(['sort_order' => $newSortOrder]);

        // อัพเดต siblings ที่เหลือ
        $currentOrder = 10;
        foreach ($siblings as $sibling) {
            if ($currentOrder == $newSortOrder) {
                $currentOrder += 10; // ข้าม order ที่ category ที่ย้ายใช้
            }
            
            if ($sibling->sort_order != $currentOrder) {
                $sibling->update(['sort_order' => $currentOrder]);
            }
            $currentOrder += 10;
        }
    }

    /**
     * รีเฟรชข้อมูล tree
     */
    public function refreshTree()
    {
        $this->loadCategories();
        $this->hasChanges = false;
        
        $this->js("
            console.log('Tree refreshed successfully');
            setTimeout(() => {
                if (typeof initializeSortable === 'function') {
                    initializeSortable();
                }
                if (typeof showToast === 'function') {
                    showToast('รีเฟรชข้อมูลสำเร็จ', 'info');
                }
            }, 200);
        ");
    }

    /**
     * บันทึกการเปลี่ยนแปลงทั้งหมด (เสร็จสิ้น)
     */
    public function saveOrder()
    {
        $this->authorize('blog.categories.edit');

        if (! $this->hasChanges) {
            session()->flash('info', 'ไม่มีการเปลี่ยนแปลงที่ต้องบันทึก');
            return;
        }

        try {
            // การเปลี่ยนแปลงถูกบันทึกแล้วผ่าน saveDragChanges แต่เราจะรีเฟรชข้อมูล
            $this->loadCategories();
            $this->hasChanges = false;
            
            session()->flash('success', 'บันทึกการจัดเรียงหมวดหมู่สำเร็จ');
            $this->dispatch('orderSaved');
            
        } catch (\Exception $e) {
            session()->flash('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    /**
     * รีเซ็ตการเปลี่ยนแปลง
     */
    public function resetOrder()
    {
        $this->loadCategories();
        $this->hasChanges = false;

        session()->flash('info', 'รีเซ็ตการเปลี่ยนแปลงเรียบร้อย');
        $this->dispatch('orderReset');
    }

    /**
     * จัดเรียงอัตโนมัติ
     */
    public function autoSort($type = 'alphabetical')
    {
        $this->authorize('blog.categories.edit');

        try {
            DB::beginTransaction();

            $categories = BlogCategory::all();
            
            switch ($type) {
                case 'alphabetical':
                    $this->sortAlphabetically($categories);
                    break;
                case 'posts_count':
                    $this->sortByPostsCount($categories);
                    break;
                case 'created_date':
                    $this->sortByCreatedDate($categories);
                    break;
            }

            DB::commit();

            $this->loadCategories();
            $this->hasChanges = true;

            session()->flash('success', "จัดเรียงอัตโนมัติแบบ{$this->getSortTypeName($type)}สำเร็จ");
            $this->dispatch('autoSorted', type: $type);
            
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'เกิดข้อผิดพลาดในการจัดเรียง: ' . $e->getMessage());
        }
    }

    private function getSortTypeName($type)
    {
        return match($type) {
            'alphabetical' => 'ตัวอักษร',
            'posts_count' => 'จำนวนโพสต์',
            'created_date' => 'วันที่สร้าง',
            default => 'ตัวอักษร'
        };
    }

    private function sortAlphabetically($categories)
    {
        $grouped = $categories->groupBy('parent_id');

        foreach ($grouped as $parentId => $siblings) {
            $sorted = $siblings->sortBy('name');
            
            $sorted->each(function ($category, $index) {
                $category->update(['sort_order' => ($index + 1) * 10]);
            });
        }
    }

    private function sortByPostsCount($categories)
    {
        $grouped = $categories->groupBy('parent_id');

        foreach ($grouped as $parentId => $siblings) {
            $sorted = $siblings->sortByDesc('posts_count');
            
            $sorted->each(function ($category, $index) {
                $category->update(['sort_order' => ($index + 1) * 10]);
            });
        }
    }

    private function sortByCreatedDate($categories)
    {
        $grouped = $categories->groupBy('parent_id');

        foreach ($grouped as $parentId => $siblings) {
            $sorted = $siblings->sortByDesc('created_at');
            
            $sorted->each(function ($category, $index) {
                $category->update(['sort_order' => ($index + 1) * 10]);
            });
        }
    }

    public function expandAll()
    {
        $this->js("
            document.querySelectorAll('.children').forEach(children => {
                children.style.display = 'block';
            });
            document.querySelectorAll('.toggle-children svg').forEach(icon => {
                icon.style.transform = 'rotate(180deg)';
            });
        ");
    }

    public function collapseAll()
    {
        $this->js("
            document.querySelectorAll('.children').forEach(children => {
                children.style.display = 'none';
            });
            document.querySelectorAll('.toggle-children svg').forEach(icon => {
                icon.style.transform = 'rotate(0deg)';
            });
        ");
    }

    public function cancel()
    {
        return redirect()->route('administrator.blog.categories.index');
    }

    public function getCategoryStats()
    {
        $stats = [
            'total'            => count($this->categories),
            'root_categories'  => count(array_filter($this->categories, fn($cat) => $cat['parent_id'] === null)),
            'child_categories' => count(array_filter($this->categories, fn($cat) => $cat['parent_id'] !== null)),
            'max_depth'        => $this->getMaxDepth($this->categoriesTree),
        ];

        return $stats;
    }

    private function getMaxDepth($tree, $currentDepth = 0)
    {
        $maxDepth = $currentDepth;

        foreach ($tree as $node) {
            if (isset($node['children']) && ! empty($node['children'])) {
                $depth    = $this->getMaxDepth($node['children'], $currentDepth + 1);
                $maxDepth = max($maxDepth, $depth);
            }
        }

        return $maxDepth;
    }

    public function getFormTitleProperty(): string
    {
        return 'จัดเรียงหมวดหมู่';
    }

    public function getBreadcrumbProperty(): array
    {
        return [
            ['name' => 'Dashboard', 'url' => route('administrator.blog.dashboard')],
            ['name' => 'หมวดหมู่', 'url' => route('administrator.blog.categories.index')],
            ['name' => 'จัดเรียงหมวดหมู่', 'url' => null],
        ];
    }

    public function render()
    {
        return view('livewire.backend.blog.categories-reorder', [
            'stats' => $this->getCategoryStats(),
        ])->layout('components.layouts.dashboard');
    }
}