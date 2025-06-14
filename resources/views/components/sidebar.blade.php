<aside class="w-64 bg-white shadow-lg flex flex-col">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-gray-800">Chana Frozen</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-red-600 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                </svg>
            </button>
        </form>
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
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
            Penjualan & Kasir
        </a>

        <a href="{{ route('report.index') }}"
        class="flex items-center px-4 py-3 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition
        {{ request()->routeIs('report.index') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            Laporan Penjualan
        </a>
        
        <a href="{{ route('stock.index') }}"
        class="flex items-center px-4 py-3 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition
        {{ request()->routeIs('stock.index') ? 'bg-blue-100 text-blue-700 font-semibold' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
            </svg>
            Mutasi Stok
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