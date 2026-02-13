@props([
    'total',
    'currentPerPage' => 10,
    'perPageOptions' => [10, 20, 30],
    'routeName',
    'gridId' => 'offersGrid',
    'storageKey' => 'gridLayout',
    'defaultColumns' => 2
])

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <div class="text-sm text-gray-600">
            Found {{ $total }} {{ $total == 1 ? 'offer' : 'offers' }}
        </div>
        
        <form method="GET" action="{{ route($routeName) }}" id="perPageForm{{ $gridId }}" class="flex items-center gap-2">
            @foreach(request()->except(['per_page', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            
            <label class="text-sm font-medium text-gray-700">Show:</label>
            <select name="per_page" onchange="document.getElementById('perPageForm{{ $gridId }}').submit()" class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm cursor-pointer">
                @foreach($perPageOptions as $option)
                    <option value="{{ $option }}" {{ request('per_page', $currentPerPage) == $option ? 'selected' : '' }}>{{ $option }}</option>
                @endforeach
            </select>
            <span class="text-sm text-gray-600">per page</span>
        </form>
    </div>
    
    <div class="flex items-center gap-2 border border-gray-300 rounded-lg p-1">
        <button 
            onclick="setGridLayout{{ $gridId }}(1)" 
            id="grid-1-{{ $gridId }}"
            class="p-2 rounded hover:bg-gray-100 transition cursor-pointer"
            title="1 column"
        >
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/>
            </svg>
        </button>
        <button 
            onclick="setGridLayout{{ $gridId }}(2)" 
            id="grid-2-{{ $gridId }}"
            class="p-2 rounded hover:bg-gray-100 transition cursor-pointer"
            title="2 columns"
        >
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="3" y="3" width="8" height="18" rx="2" stroke-width="2"/>
                <rect x="13" y="3" width="8" height="18" rx="2" stroke-width="2"/>
            </svg>
        </button>
        <button 
            onclick="setGridLayout{{ $gridId }}(3)" 
            id="grid-3-{{ $gridId }}"
            class="p-2 rounded hover:bg-gray-100 transition cursor-pointer"
            title="3 columns"
        >
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <rect x="2" y="3" width="5" height="18" rx="1" stroke-width="2"/>
                <rect x="9.5" y="3" width="5" height="18" rx="1" stroke-width="2"/>
                <rect x="17" y="3" width="5" height="18" rx="1" stroke-width="2"/>
            </svg>
        </button>
    </div>
</div>

@once
    @push('scripts')
    <script>
        function setGridLayout{{ $gridId }}(columns) {
            const grid = document.getElementById('{{ $gridId }}');
            if (!grid) return;

            const buttons = document.querySelectorAll('[id^="grid-"][id$="-{{ $gridId }}"]');
            
            buttons.forEach(btn => {
                btn.classList.remove('border-2', 'border-blue-600', 'bg-blue-50');
                btn.classList.add('hover:bg-gray-100');
                const svg = btn.querySelector('svg');
                if (svg) {
                    svg.classList.remove('text-blue-600');
                    svg.classList.add('text-gray-600');
                }
            });
            
            const activeButton = document.getElementById(`grid-${columns}-{{ $gridId }}`);
            if (activeButton) {
                activeButton.classList.remove('hover:bg-gray-100');
                activeButton.classList.add('border-2', 'border-blue-600', 'bg-blue-50');
                const activeSvg = activeButton.querySelector('svg');
                if (activeSvg) {
                    activeSvg.classList.remove('text-gray-600');
                    activeSvg.classList.add('text-blue-600');
                }
            }
            
            grid.className = 'grid gap-6';
            if (columns === 1) {
                grid.classList.add('grid-cols-1');
            } else if (columns === 2) {
                grid.classList.add('grid-cols-1', 'lg:grid-cols-2');
            } else if (columns === 3) {
                grid.classList.add('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3');
            }
            
            localStorage.setItem('{{ $storageKey }}', columns);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedLayout = localStorage.getItem('{{ $storageKey }}');
            const columns = savedLayout ? parseInt(savedLayout) : {{ $defaultColumns }};
            setGridLayout{{ $gridId }}(columns);
        });
    </script>
    @endpush
@endonce
