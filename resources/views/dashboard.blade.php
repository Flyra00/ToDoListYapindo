<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Ringkasan aktivitas dan daftar tugas terbaru Anda.
                </p>
            </div>
            <div>
                <a href="{{ route('todos.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-sm">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Tambah Todo Baru') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Summary Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                
                <!-- Total Todos -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Tugas</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalCount }}</p>
                    </div>
                    <div class="w-11 h-11 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>

                <!-- Active Todos -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tugas Aktif</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $activeCount }}</p>
                    </div>
                    <div class="w-11 h-11 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Completed Todos -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tugas Selesai</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $completedCount }}</p>
                    </div>
                    <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

            </div>

            <!-- Recent Tasks Card (Quick Action: Check & Detail Only) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-base">Tugas Terbaru</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Centang tugas yang sudah selesai atau klik detail untuk membaca informasi lengkap.</p>
                    </div>
                    <a href="{{ route('todos.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
                        <span>Kelola Semua di Halaman Todos</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                @if ($todos->isEmpty())
                    <div class="p-10 text-center">
                        <div class="mx-auto w-12 h-12 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Belum ada tugas yang dibuat</p>
                        <p class="text-xs text-gray-400 mt-1 mb-4">Tambahkan tugas baru untuk mulai mengorganisasi aktivitas harian Anda.</p>
                        <a href="{{ route('todos.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-xs font-semibold text-white rounded-lg hover:bg-indigo-700 transition">
                            + Tambah Tugas Sekarang
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach ($todos as $todo)
                            <div class="p-4 sm:px-6 hover:bg-gray-50/50 transition flex items-center justify-between gap-4">
                                
                                <!-- Checkbox & Title Info -->
                                <div class="flex items-center space-x-3.5 flex-1 min-w-0">
                                    <!-- Toggle Form (Centang Langsung di Dashboard) -->
                                    <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="shrink-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="{{ $todo->completed ? 'Tandai belum selesai' : 'Tandai selesai' }}" class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500 {{ $todo->completed ? 'bg-emerald-500 border-emerald-500 text-white hover:bg-emerald-600' : 'border-gray-300 hover:border-emerald-500 text-transparent' }}">
                                            <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- Title, Priority, Category -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center flex-wrap gap-2">
                                            <a href="{{ route('todos.show', $todo) }}" class="text-sm font-semibold transition hover:text-indigo-600 truncate {{ $todo->completed ? 'line-through text-gray-400' : 'text-gray-900' }}">
                                                {{ $todo->title }}
                                            </a>

                                            <!-- Priority Badge -->
                                            @if ($todo->priority === 'high')
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-800">High</span>
                                            @elseif ($todo->priority === 'medium')
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800">Medium</span>
                                            @else
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-800">Low</span>
                                            @endif

                                            <!-- Category -->
                                            @if ($todo->category)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-purple-50 text-purple-700">🏷️ {{ $todo->category }}</span>
                                            @endif
                                        </div>

                                        @if ($todo->due_date)
                                            <div class="text-xs mt-0.5 flex items-center gap-1 {{ $todo->isOverdue() ? 'text-red-600 font-semibold' : ($todo->isDueToday() ? 'text-amber-600 font-semibold' : 'text-gray-400') }}">
                                                <span>Tenggat: {{ $todo->due_date->format('d M Y') }}</span>
                                                @if ($todo->isOverdue())
                                                    <span class="text-[10px] bg-red-100 text-red-700 px-1 rounded">Terlambat</span>
                                                @elseif ($todo->isDueToday())
                                                    <span class="text-[10px] bg-amber-100 text-amber-700 px-1 rounded">Hari Ini</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action: Detail Only -->
                                <div class="shrink-0">
                                    <a href="{{ route('todos.show', $todo) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 transition" title="Lihat Detail Tugas">
                                        <svg class="w-3.5 h-3.5 me-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Detail
                                    </a>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- Card Footer: Link to full Todos page -->
                    <div class="p-4 bg-gray-50 border-t border-gray-100 text-center">
                        <a href="{{ route('todos.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition inline-flex items-center gap-1">
                            <span>Lihat Semua Tugas & Filter Lengkap di Halaman Todos &rarr;</span>
                        </a>
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
