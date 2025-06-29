{{-- Quick Stats Widget สำหรับ Dashboard --}}
@can('blog.posts.view')
    <div class="mb-4">
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
            <h4 class="text-sm font-semibold text-slate-800 mb-3">สถิติด่วน</h4>
            <div class="grid grid-cols-2 gap-3">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ App\Models\BlogPost::where('status', 'published')->count() }}</div>
                    <div class="text-xs text-slate-500">เผยแพร่</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-amber-600">{{ App\Models\BlogPost::where('status', 'draft')->count() }}</div>
                    <div class="text-xs text-slate-500">ร่าง</div>
                </div>
            </div>
        </div>
    </div>
@endcan
