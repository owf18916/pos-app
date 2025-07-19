<div class="min-h-screen flex bg-gray-100">
    <!-- Konten Utama -->
    <main class="flex-1 p-6">
        <h1 class="text-4xl font-extrabold mb-8 text-gray-800">Dashboard</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">

            <!-- Card Total Produk -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex items-center space-x-4 hover:shadow-xl transition-shadow duration-300">
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                         viewBox="0 0 24 24">
                        <path d="M3 3h18v18H3z"/>
                        <path d="M3 9h18"/>
                        <path d="M9 21V9"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Produk</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalProduct }}</p>
                </div>
            </div>

            <!-- Card Kasir Aktif -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex items-center space-x-4 hover:shadow-xl transition-shadow duration-300">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                         viewBox="0 0 24 24">
                        <path d="M16 21v-2a4 4 0 0 0-8 0v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Kasir Aktif</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ $activeCashiers }}</p>
                </div>
            </div>

            <!-- Card Transaksi Hari Ini -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex items-center space-x-4 hover:shadow-xl transition-shadow duration-300">
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                         viewBox="0 0 24 24">
                        <path d="M12 8v4l3 3"/>
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Jumlah Transaksi Hari Ini</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalSales }}</p>
                </div>
            </div>

            <!-- Card Pendapatan Hari Ini -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex items-center space-x-4 hover:shadow-xl transition-shadow duration-300">
                <div class="bg-red-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                         viewBox="0 0 24 24">
                        <path d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8z"/>
                        <path d="M12 3v2"/>
                        <path d="M12 19v2"/>
                        <path d="M4.22 4.22l1.42 1.42"/>
                        <path d="M18.36 18.36l1.42 1.42"/>
                        <path d="M1 12h2"/>
                        <path d="M21 12h2"/>
                        <path d="M4.22 19.78l1.42-1.42"/>
                        <path d="M18.36 5.64l1.42-1.42"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Pendapatan Hari Ini</h2>
                    <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Card Laba Hari Ini -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex items-center space-x-4 hover:shadow-xl transition-shadow duration-300">
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                        viewBox="0 0 24 24">
                        <path d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8z"/>
                        <path d="M12 3v2"/>
                        <path d="M12 19v2"/>
                        <path d="M4.22 4.22l1.42 1.42"/>
                        <path d="M18.36 18.36l1.42 1.42"/>
                        <path d="M1 12h2"/>
                        <path d="M21 12h2"/>
                        <path d="M4.22 19.78l1.42-1.42"/>
                        <path d="M18.36 5.64l1.42-1.42"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Laba Hari Ini</h2>
                    <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($todayProfit, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="w-full">
                <livewire:dashboard.sales-chart />
            </div>

        </div>
    </main>

</div>
