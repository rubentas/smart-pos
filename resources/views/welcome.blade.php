<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart POS - Point of Sale System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-store text-xl text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800">Smart<span class="text-blue-600">POS</span></span>
                </div>
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">Register</a>
                    @else
                        <a href="{{ url('/dashboard') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="gradient-bg text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">Smart Point of Sale System</h1>
                <p class="text-xl mb-8 text-blue-100">Solusi lengkap untuk manajemen toko, stok, dan transaksi</p>
                @guest
                    <a href="{{ route('register') }}"
                        class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-gray-100 transition inline-flex items-center">
                        <i class="fas fa-rocket mr-2"></i>Mulai Sekarang
                    </a>
                @else
                    <a href="{{ url('/dashboard') }}"
                        class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-gray-100 transition inline-flex items-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>Ke Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Fitur Unggulan</h2>
            <p class="text-gray-600">Semua yang Anda butuhkan untuk mengelola bisnis Anda</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Fitur 1 -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cash-register text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Point of Sale</h3>
                <p class="text-gray-600">Transaksi cepat dengan antarmuka kasir yang intuitif</p>
            </div>

            <!-- Fitur 2 -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-boxes text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Manajemen Stok</h3>
                <p class="text-gray-600">Pantau stok real-time, alert stok menipis</p>
            </div>

            <!-- Fitur 3 -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-2xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Laporan Lengkap</h3>
                <p class="text-gray-600">Analisis penjualan, laba rugi, dan export PDF</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">&copy; {{ date('Y') }} Smart POS. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
