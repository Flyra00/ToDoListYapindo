<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Daftar Tugas') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola dan pantau seluruh aktivitas tugas harian Anda.
                </p>
            </div>
            <div>
                <a href="{{ route('todos.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Tambah Todo') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter Status & Filter Bar -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                
                <!-- Status Tabs -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 me-1">Status:</span>
                    <a href="{{ route('todos.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                       class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition {{ $status === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ __('Semua') }}
                    </a>
                    <a href="{{ route('todos.index', array_merge(request()->query(), ['status' => 'active'])) }}"
                       class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition {{ $status === 'active' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ __('Aktif') }}
                    </a>
                    <a href="{{ route('todos.index', array_merge(request()->query(), ['status' => 'completed'])) }}"
                       class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition {{ $status === 'completed' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        {{ __('Selesai') }}
                    </a>
                </div>

                <!-- Secondary Filters: Priority & Category Dropdowns -->
                <form method="GET" action="{{ route('todos.index') }}" class="flex flex-wrap items-center gap-2">
                    <input type="hidden" name="status" value="{{ $status }}">

                    @if ($categories->isNotEmpty())
                        <select name="category" onchange="this.form.submit()" class="text-xs py-1.5 pl-2.5 pr-8 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-gray-600">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ $selectedCategory === $cat ? 'selected' : '' }}>🏷️ {{ $cat }}</option>
                            @endforeach
                        </select>
                    @endif

                    <select name="priority" onchange="this.form.submit()" class="text-xs py-1.5 pl-2.5 pr-8 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-gray-600">
                        <option value="">Semua Prioritas</option>
                        <option value="high" {{ $selectedPriority === 'high' ? 'selected' : '' }}>🔴 Prioritas Tinggi</option>
                        <option value="medium" {{ $selectedPriority === 'medium' ? 'selected' : '' }}>🟡 Prioritas Sedang</option>
                        <option value="low" {{ $selectedPriority === 'low' ? 'selected' : '' }}>🟢 Prioritas Rendah</option>
                    </select>

                    @if ($selectedCategory || $selectedPriority)
                        <a href="{{ route('todos.index', ['status' => $status]) }}" class="text-xs text-red-600 hover:text-red-800 font-medium px-2 py-1">
                            ✕ Reset Filter
                        </a>
                    @endif
                </form>

            </div>

            <!-- Todo Items List -->
            @if ($todos->isEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="mx-auto w-14 h-14 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-1">
                        @if ($status === 'completed')
                            Belum ada tugas yang diselesaikan
                        @elseif ($status === 'active')
                            Tidak ada tugas aktif
                        @else
                            Belum ada tugas
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500 max-w-sm mx-auto mb-6">
                        @if ($status === 'all')
                            Anda belum membuat tugas apapun. Mulai produktivitas Anda hari ini dengan menambahkan tugas baru.
                        @else
                            Silakan periksa kategori filter lain atau tambahkan tugas baru.
                        @endif
                    </p>
                    <a href="{{ route('todos.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        + Buat Todo Baru
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($todos as $todo)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:border-gray-200 transition p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            
                            <!-- Left: Checkbox & Info -->
                            <div class="flex items-start space-x-3.5 flex-1 min-w-0">
                                <!-- Toggle Form -->
                                <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="mt-0.5 shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="{{ $todo->completed ? 'Tandai belum selesai' : 'Tandai selesai' }}" class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500 {{ $todo->completed ? 'bg-emerald-500 border-emerald-500 text-white hover:bg-emerald-600' : 'border-gray-300 hover:border-emerald-500 text-transparent' }}">
                                        <svg class="w-3.5 h-3.5 stroke-current" fill="none" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </form>

                                <!-- Title, Badges & Description -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center flex-wrap gap-2 mb-1">
                                        <a href="{{ route('todos.show', $todo) }}" class="text-base font-semibold transition hover:text-indigo-600 {{ $todo->completed ? 'line-through text-gray-400' : 'text-gray-900' }}">
                                            {{ $todo->title }}
                                        </a>

                                        <!-- Priority Badge -->
                                        @if ($todo->priority === 'high')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800">
                                                High
                                            </span>
                                        @elseif ($todo->priority === 'medium')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">
                                                Medium
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                                Low
                                            </span>
                                        @endif

                                        <!-- Category Pill -->
                                        @if ($todo->category)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                                🏷️ {{ $todo->category }}
                                            </span>
                                        @endif

                                        <!-- Status Badge -->
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $todo->completed ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $todo->completed ? 'Selesai' : 'Aktif' }}
                                        </span>
                                    </div>

                                    @if ($todo->description)
                                        <p class="text-sm text-gray-500 line-clamp-2 mb-2 {{ $todo->completed ? 'line-through text-gray-400' : '' }}">
                                            {{ $todo->description }}
                                        </p>
                                    @endif

                                    <!-- Meta: Due date & Created At -->
                                    <div class="text-xs text-gray-400 flex flex-wrap items-center gap-4">
                                        @if ($todo->due_date)
                                            <span class="flex items-center gap-1 font-medium {{ $todo->isOverdue() ? 'text-red-600' : ($todo->isDueToday() ? 'text-amber-600' : 'text-gray-500') }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Tenggat: {{ $todo->due_date->format('d M Y') }}
                                                @if ($todo->isOverdue())
                                                    <span class="bg-red-100 text-red-700 px-1 rounded text-[10px]">Terlambat</span>
                                                @elseif ($todo->isDueToday())
                                                    <span class="bg-amber-100 text-amber-700 px-1 rounded text-[10px]">Hari Ini</span>
                                                @endif
                                            </span>
                                        @endif

                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Dibuat {{ $todo->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Actions -->
                            <div class="flex items-center space-x-2 shrink-0 self-end sm:self-center border-t sm:border-t-0 pt-3 sm:pt-0 w-full sm:w-auto justify-end">
                                <a href="{{ route('todos.show', $todo) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 transition" title="Lihat Detail">
                                    <svg class="w-4 h-4 sm:me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span class="hidden sm:inline">Detail</span>
                                </a>

                                <a href="{{ route('todos.edit', $todo) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition" title="Edit Todo">
                                    <svg class="w-4 h-4 sm:me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>

                                <form action="{{ route('todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition" title="Hapus Todo">
                                        <svg class="w-4 h-4 sm:me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="hidden sm:inline">Hapus</span>
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="mt-6">
                    {{ $todos->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
