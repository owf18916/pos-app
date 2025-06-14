<aside class="w-64 bg-white shadow-lg flex flex-col">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-800">Chana Frozen</h1>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2">

        <a href="{{ route('dashboard') }}"
        class="flex items-center px-4 py-3 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition
            {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('products.index') }}"
        class="flex items-center px-4 py-3 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition
            {{ request()->routeIs('products.index') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4"/>
            </svg>
            Manajemen Produk
        </a>

        <a href="{{ route('cashier.index') }}"
        class="flex items-center px-4 py-3 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition
        {{ request()->routeIs('cashier.index') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/>
                <circle cx="12" cy="12" r="10"/>
            </svg>
            Penjualan & Kasir
        </a>

        <a href="{{ route('report.index') }}"
        class="flex items-center px-4 py-3 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition
        {{ request()->routeIs('report.index') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h6"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17h6"/>
            </svg>
            Laporan Penjualan
        </a>

        <a href="#"
        class="flex items-center px-4 py-3 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition cursor-not-allowed opacity-60">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-8 0v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            Manajemen Pengguna
        </a>
    </nav>
</aside>