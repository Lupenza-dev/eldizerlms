@forelse ($items as $item)
    @php $c = $item['color'] ?: 'slate'; @endphp
    <a href="{{ $item['url'] ?? '#' }}" class="group block">
        <div class="flex items-center gap-4 p-5 rounded-xl border border-slate-200 hover:border-{{ $c }}-300 hover:shadow-md hover:bg-{{ $c }}-50/40 transition-all duration-200">
            <div class="w-12 h-12 bg-{{ $c }}-100 rounded-xl flex items-center justify-center group-hover:bg-{{ $c }}-200 transition-colors shrink-0">
                <i class="bx {{ $item['icon'] ?: 'bx-circle' }} text-{{ $c }}-600 text-2xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h5 class="font-semibold text-slate-800 mb-0.5 text-base leading-tight">{{ $item['title'] }}</h5>
                @if ($item['description'])
                    <p class="text-xs text-slate-500 mb-0">{{ $item['description'] }}</p>
                @endif
            </div>
            <i class="bx bx-chevron-right text-slate-300 text-2xl group-hover:text-{{ $c }}-500 group-hover:translate-x-1 transition-all shrink-0"></i>
        </div>
    </a>
@empty
    <div class="sm:col-span-2 xl:col-span-3 text-center text-sm text-slate-400 py-10">
        <i class="bx bx-lock-alt text-3xl block mb-2"></i>
        No items are available for your role.
    </div>
@endforelse
