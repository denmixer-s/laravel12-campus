<div class="min-h-screen bg-gray-50" x-data="{
    autoRefresh: @entangle('autoRefresh'),
    refreshInterval: @entangle('refreshInterval'),
    intervalId: null,

    init() {
        this.setupAutoRefresh();
    },

    setupAutoRefresh() {
        if (this.autoRefresh) {
            this.intervalId = setInterval(() => {
                $wire.refreshStats();
            }, this.refreshInterval * 1000);
        } else {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        }
    },

    toggleAutoRefresh() {
        this.autoRefresh = !this.autoRefresh;
        this.setupAutoRefresh();
    }
}" x-init="init()">

    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-l-8 border-blue-500 mb-4">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        สถิติและรายงาน
                    </h1>
                    <p class="text-gray-600 text-sm mt-1">ภาพรวมการใช้งานระบบจัดการเอกสาร</p>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center space-x-3">
                    <!-- Auto Refresh Toggle -->
                    <button @click="toggleAutoRefresh()"
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200"
                        :class="autoRefresh ? 'bg-green-100 text-green-700 hover:bg-green-200' :
                            'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                        <svg class="w-4 h-4 mr-2" :class="{ 'animate-spin': autoRefresh }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        <span x-text="autoRefresh ? 'รีเฟรชอัตโนมัติ' : 'รีเฟรชด้วยตนเอง'"></span>
                    </button>

                    <!-- Manual Refresh -->
                    <button wire:click="refreshStats"
                        class="flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        รีเฟรช
                    </button>

                    <!-- Export Report -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            ส่งออกรายงาน
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                            <div class="py-1">
                                <button wire:click="exportReport('csv')" @click="open = false"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    📄 ส่งออกเป็น CSV
                                </button>
                                <button wire:click="exportReport('excel')" @click="open = false"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    📊 ส่งออกเป็น Excel
                                </button>
                                <button wire:click="exportReport('pdf')" @click="open = false"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    📋 ส่งออกเป็น PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Period Filter -->
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">ช่วงเวลา:</label>
                <select wire:model.live="selectedPeriod"
                    class="px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach ($periodOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Department Filter -->
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">แผนก:</label>
                <select wire:model.live="selectedDepartment"
                    class="px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">ทุกแผนก</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Category Filter -->
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">หมวดหมู่:</label>
                <select wire:model.live="selectedCategory"
                    class="px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">ทุกหมวดหมู่</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">ประเภท:</label>
                <select wire:model.live="selectedType"
                    class="px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">ทุกประเภท</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Reset Filters -->
            @if ($selectedDepartment || $selectedCategory || $selectedType || $selectedPeriod !== '30')
                <button wire:click="resetFilters"
                    class="px-3 py-1.5 text-sm text-red-600 hover:text-red-800 font-medium">
                    รีเซ็ตตัวกรอง
                </button>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Documents -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">เอกสารทั้งหมด</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ number_format($overviewStats['total_documents']) }}</p>
                        <p class="text-sm text-gray-500">{{ $overviewStats['published_documents'] }} เอกสารเผยแพร่</p>
                    </div>
                </div>
            </div>

            <!-- Total Downloads -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">ดาวน์โหลดทั้งหมด</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ number_format($overviewStats['total_downloads']) }}</p>
                        <div class="flex items-center">
                            @if ($overviewStats['download_growth'] > 0)
                                <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-green-600">+{{ $overviewStats['download_growth'] }}%</span>
                            @elseif($overviewStats['download_growth'] < 0)
                                <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-red-600">{{ $overviewStats['download_growth'] }}%</span>
                            @else
                                <span class="text-sm text-gray-500">ไม่เปลี่ยนแปลง</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Views -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">การเข้าชมทั้งหมด</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($overviewStats['total_views']) }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $overviewStats['avg_downloads_per_document'] }}
                            ดาวน์โหลดต่อเอกสาร</p>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">ผู้ใช้ทั้งหมด</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($overviewStats['total_users']) }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $systemHealth['active_users_today'] }} คนใช้งานวันนี้</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

            <!-- Download Trends Chart -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">แนวโน้มการดาวน์โหลด</h3>
                    <div class="text-sm text-gray-500">{{ $periodOptions[$selectedPeriod] }}</div>
                </div>

                <div class="h-64" wire:ignore id="downloadTrendsChart">
                    <!-- Chart will be rendered here with Chart.js -->
                    <canvas id="downloadTrendsCanvas"></canvas>
                </div>
            </div>

            <!-- User Type Distribution -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">การใช้งานตามประเภทผู้ใช้</h3>
                </div>

                <div class="space-y-4">
                    @foreach ($userTypeStats as $type => $stats)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3"
                                    style="background-color: {{ $stats['color'] }}"></div>
                                <span class="text-sm font-medium text-gray-700">{{ $stats['label'] }}</span>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-gray-900">{{ number_format($stats['downloads']) }}
                                </div>
                                <div class="text-xs text-gray-500">ดาวน์โหลด</div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $totalDownloads = array_sum(array_column($userTypeStats, 'downloads'));
                                $percentage = $totalDownloads > 0 ? ($stats['downloads'] / $totalDownloads) * 100 : 0;
                            @endphp
                            <div class="h-2 rounded-full"
                                style="background-color: {{ $stats['color'] }}; width: {{ $percentage }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

            <!-- Popular Documents -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">เอกสารยอดนิยม</h3>

                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    เอกสาร</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    หมวดหมู่</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ดาวน์โหลด</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($popularDocuments as $index => $document)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span
                                                    class="text-xs font-bold text-blue-600">{{ $index + 1 }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($document['title'], 40) }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $document['type'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $document['category'] }}</td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ number_format($document['downloads']) }}</div>
                                        <div class="text-xs text-gray-500">{{ number_format($document['views']) }}
                                            views</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">ไม่มีข้อมูล</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Department Performance -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">ประสิทธิภาพแผนก</h3>

                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    แผนก</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    เอกสาร</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ดาวน์โหลด</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($departmentPerformance as $department)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $department['name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $department['users_count'] }} ผู้ใช้
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ number_format($department['documents_count']) }}</td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ number_format($department['total_downloads']) }}</div>
                                        <div class="text-xs text-gray-500">{{ $department['avg_downloads'] }} เฉลี่ย
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">ไม่มีข้อมูล</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Activities & System Health -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Recent Activities -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">กิจกรรมล่าสุด</h3>

                <div class="space-y-4">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                    @if ($activity['type'] === 'download')
                                        <svg class="w-4 h-4 {{ $activity['color'] }}" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    @elseif($activity['type'] === 'document')
                                        <svg class="w-4 h-4 {{ $activity['color'] }}" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 {{ $activity['color'] }}" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity['formatted_time'] }}</p>
                                </div>
                                <p class="text-sm text-gray-600">{{ Str::limit($activity['description'], 60) }}</p>
                                <p class="text-xs text-gray-500">โดย {{ $activity['user'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="mt-2">ไม่มีกิจกรรมล่าสุด</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- System Health -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">สุขภาพระบบ</h3>

                <div class="space-y-6">
                    <!-- Response Time -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Response Time</span>
                            <span class="text-sm text-gray-900">{{ $systemHealth['avg_response_time'] }} ms</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $responsePercentage = min(($systemHealth['avg_response_time'] / 1000) * 100, 100);
                                $responseColor =
                                    $systemHealth['avg_response_time'] < 200
                                        ? 'bg-green-500'
                                        : ($systemHealth['avg_response_time'] < 500
                                            ? 'bg-yellow-500'
                                            : 'bg-red-500');
                            @endphp
                            <div class="{{ $responseColor }} h-2 rounded-full"
                                style="width: {{ $responsePercentage }}%"></div>
                        </div>
                    </div>

                    <!-- Storage Usage -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Storage Usage</span>
                            <span class="text-sm text-gray-900">{{ $systemHealth['storage_usage_gb'] }} GB</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $storagePercentage = min(($systemHealth['storage_usage_gb'] / 10) * 100, 100); // Assuming 10GB limit
                                $storageColor =
                                    $storagePercentage < 70
                                        ? 'bg-green-500'
                                        : ($storagePercentage < 90
                                            ? 'bg-yellow-500'
                                            : 'bg-red-500');
                            @endphp
                            <div class="{{ $storageColor }} h-2 rounded-full"
                                style="width: {{ $storagePercentage }}%"></div>
                        </div>
                    </div>

                    <!-- Error Rate -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Error Rate</span>
                            <span class="text-sm text-gray-900">{{ $systemHealth['error_rate'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $errorColor =
                                    $systemHealth['error_rate'] < 1
                                        ? 'bg-green-500'
                                        : ($systemHealth['error_rate'] < 5
                                            ? 'bg-yellow-500'
                                            : 'bg-red-500');
                            @endphp
                            <div class="{{ $errorColor }} h-2 rounded-full"
                                style="width: {{ $systemHealth['error_rate'] }}%"></div>
                        </div>
                    </div>

                    <!-- Uptime -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Uptime</span>
                            <span class="text-sm text-gray-900">{{ $systemHealth['uptime_percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full"
                                style="width: {{ $systemHealth['uptime_percentage'] }}%"></div>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="pt-4 border-t border-gray-200">
                        @php
                            $isHealthy =
                                $systemHealth['avg_response_time'] < 500 &&
                                $systemHealth['error_rate'] < 5 &&
                                $systemHealth['uptime_percentage'] > 99;
                        @endphp
                        <div class="flex items-center">
                            <div
                                class="w-3 h-3 rounded-full {{ $isHealthy ? 'bg-green-500' : 'bg-yellow-500' }} mr-2">
                            </div>
                            <span class="text-sm font-medium {{ $isHealthy ? 'text-green-700' : 'text-yellow-700' }}">
                                {{ $isHealthy ? 'ระบบทำงานปกติ' : 'ระบบต้องตรวจสอบ' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Distribution Chart -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">การกระจายตามหมวดหมู่</h3>

            @if ($categoryDistribution->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Chart -->
                    <div class="h-64" wire:ignore>
                        <canvas id="categoryDistributionCanvas"></canvas>
                    </div>

                    <!-- Legend -->
                    <div class="space-y-3">
                        @php
                            $total = $categoryDistribution->sum('count');
                        @endphp
                        @foreach ($categoryDistribution as $index => $category)
                            @php
                                $percentage = $total > 0 ? round(($category['count'] / $total) * 100, 1) : 0;
                                $colors = [
                                    '#3B82F6',
                                    '#10B981',
                                    '#F59E0B',
                                    '#EF4444',
                                    '#8B5CF6',
                                    '#06B6D4',
                                    '#84CC16',
                                    '#F97316',
                                ];
                                $color = $colors[$index % count($colors)];
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full mr-3"
                                        style="background-color: {{ $color }}"></div>
                                    <span class="text-sm font-medium text-gray-700">{{ $category['name'] }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ number_format($category['count']) }}</div>
                                    <div class="text-xs text-gray-500">{{ $percentage }}%</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <p class="mt-2">ไม่มีข้อมูลหมวดหมู่</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center gap-3">
            <svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-gray-700">กำลังโหลดข้อมูล...</span>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
        });

        document.addEventListener('livewire:navigated', function() {
            initCharts();
        });

        function initCharts() {
            // Download Trends Chart
            const downloadTrendsCtx = document.getElementById('downloadTrendsCanvas');
            if (downloadTrendsCtx) {
                const downloadTrendsData = @json($downloadTrends);

                new Chart(downloadTrendsCtx, {
                    type: 'line',
                    data: {
                        labels: downloadTrendsData.map(item => item.formatted_date),
                        datasets: [{
                            label: 'ดาวน์โหลด',
                            data: downloadTrendsData.map(item => item.downloads),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // Category Distribution Chart
            const categoryCtx = document.getElementById('categoryDistributionCanvas');
            if (categoryCtx) {
                const categoryData = @json($categoryDistribution);
                const colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#84CC16', '#F97316'];

                if (categoryData.length > 0) {
                    new Chart(categoryCtx, {
                        type: 'doughnut',
                        data: {
                            labels: categoryData.map(item => item.name),
                            datasets: [{
                                data: categoryData.map(item => item.count),
                                backgroundColor: colors.slice(0, categoryData.length),
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
            }
        }

        // Auto-refresh functionality
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (event) => {
                // You can implement toast notifications here
                console.log(event.type + ': ' + event.message);
            });
        });
    </script>

    <!-- Custom Styles -->
    <style>
        /* Custom animations */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Chart container styles */
        .chart-container {
            position: relative;
            height: 300px;
        }

        /* Custom scrollbar for tables */
        .table-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .table-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .table-scroll::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .table-scroll::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Responsive table */
        @media (max-width: 768px) {
            .responsive-table {
                font-size: 0.875rem;
            }

            .responsive-table th,
            .responsive-table td {
                padding: 0.5rem 0.25rem;
            }
        }

        /* Status indicators */
        .status-indicator {
            position: relative;
            display: inline-block;
        }

        .status-indicator::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: currentColor;
        }

        .status-healthy::after {
            background-color: #10B981;
            animation: pulse 2s infinite;
        }

        .status-warning::after {
            background-color: #F59E0B;
        }

        .status-error::after {
            background-color: #EF4444;
        }
    </style>
</div>
