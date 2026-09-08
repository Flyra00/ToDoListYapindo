<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Rincian Tugas') }}
            </h2>
            <a href="{{ route('todos.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Kembali ke Daftar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Notification -->
            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" x-transition class="flex items-center justify-between p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-500 me-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium text-emerald-800">{{ session('status') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Overdue Warning Banner -->
            @if ($todo->isOverdue())
                <div class="flex items-center p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg text-red-800 text-sm">
                    <svg class="w-5 h-5 text-red-500 me-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span><strong>Perhatian:</strong> Tugas ini telah melewati tenggat waktu yang ditentukan ({{ $todo->due_date->format('d M Y') }}).</span>
                </div>
            @endif

            <!-- Main Detail Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- Status, Priority, & Category Header -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Completion Status Badge -->
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $todo->completed ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                            @if ($todo->completed)
                                <svg class="w-3.5 h-3.5 me-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                                Selesai
                            @else
                                <span class="w-2 h-2 me-1.5 rounded-full bg-blue-500"></span>
                                Sedang Berjalan (Aktif)
                            @endif
                        </span>

                        <!-- Priority Badge -->
                        @if ($todo->priority === 'high')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                🔴 Prioritas Tinggi
                            </span>
                        @elseif ($todo->priority === 'medium')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                🟡 Prioritas Sedang
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                🟢 Prioritas Rendah
                            </span>
                        @endif

                        <!-- Category Badge -->
                        @if ($todo->category)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                🏷️ {{ $todo->category }}
                            </span>
                        @endif
                    </div>

                    <!-- Due Date Indicator -->
                    @if ($todo->due_date)
                        <div class="text-xs font-medium flex items-center gap-1.5 {{ $todo->isOverdue() ? 'text-red-600 font-bold' : ($todo->isDueToday() ? 'text-amber-600 font-bold' : 'text-gray-500') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Tenggat: {{ $todo->due_date->format('d M Y') }}</span>
                            @if ($todo->isOverdue())
                                <span class="text-xs bg-red-100 text-red-700 px-1.5 py-0.5 rounded">Terlambat</span>
                            @elseif ($todo->isDueToday())
                                <span class="text-xs bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded">Hari Ini</span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Content Area -->
                <div class="p-6 sm:p-8 space-y-6">
                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Judul Tugas</h3>
                        <p class="text-2xl font-bold text-gray-900 {{ $todo->completed ? 'line-through text-gray-500' : '' }}">
                            {{ $todo->title }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Deskripsi</h3>
                        @if ($todo->description)
                            <div class="p-4 bg-gray-50 rounded-lg text-sm text-gray-700 whitespace-pre-line leading-relaxed border border-gray-100">
                                {{ $todo->description }}
                            </div>
                        @else
                            <p class="text-sm italic text-gray-400">Tidak ada deskripsi tambahan untuk tugas ini.</p>
                        @endif
                    </div>

                    <div class="text-xs text-gray-400 pt-4 border-t border-gray-100 flex flex-wrap gap-4">
                        <span><strong>Dibuat:</strong> {{ $todo->created_at->format('d M Y, H:i') }}</span>
                        <span><strong>Diperbarui:</strong> {{ $todo->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                <!-- Footer Toolbar -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                    
                    <!-- Toggle Status Button -->
                    <form action="{{ route('todos.toggle', $todo) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm {{ $todo->completed ? 'bg-amber-600 hover:bg-amber-700 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                            @if ($todo->completed)
                                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Tandai Belum Selesai
                            @else
                                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Tandai Telah Selesai
                            @endif
                        </button>
                    </form>

                    <!-- Edit & Delete -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('todos.edit', $todo) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 me-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>

                        <form action="{{ route('todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition">
                                <svg class="w-4 h-4 me-1.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
