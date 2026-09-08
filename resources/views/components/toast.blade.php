@if (session('status') || session('success') || session('error'))
    <div x-data="{ show: true }"
         x-init="setTimeout(() => show = false, 4000)"
         x-show="show"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-5 right-5 z-50 max-w-sm w-full bg-white rounded-xl shadow-xl border border-gray-100 p-4 pointer-events-auto overflow-hidden"
         role="alert">
        
        <div class="flex items-start">
            <!-- Icon -->
            <div class="shrink-0">
                @if (session('error'))
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                @else
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-semibold text-gray-900">
                    {{ session('error') ? 'Terjadi Kesalahan' : 'Pemberitahuan' }}
                </p>
                <p class="mt-0.5 text-xs text-gray-500 leading-relaxed">
                    {{ session('status') ?? session('success') ?? session('error') }}
                </p>
            </div>

            <!-- Close Button -->
            <div class="ml-4 shrink-0 flex">
                <button @click="show = false" type="button" class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <span class="sr-only">Tutup</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-100">
            <div class="h-full {{ session('error') ? 'bg-red-500' : 'bg-emerald-500' }} transition-all duration-[4000ms] ease-linear w-full"
                 x-init="$nextTick(() => { $el.style.width = '0%' })"></div>
        </div>

    </div>
@endif
