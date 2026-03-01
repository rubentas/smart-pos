<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Smart POS - Admin Dashboard')</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    * {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    /* Glassmorphism */
    .glass {
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }

    /* Card hover */
    .stat-card {
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Pulse animation */
    @keyframes pulse-green {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0.5;
      }
    }

    .animate-pulse-green {
      animation: pulse-green 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
  </style>

  @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800 antialiased">
  <div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-72 bg-slate-900 text-white flex flex-col shadow-2xl z-20">
      <!-- Logo -->
      <div class="p-6 border-b border-slate-800">
        <div class="flex items-center space-x-3">
          <div
            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
            <i class="fas fa-store text-xl text-white"></i>
          </div>
          <div>
            <h1 class="text-xl font-bold tracking-tight">Smart<span class="text-blue-400">POS</span></h1>
            <p class="text-xs text-slate-400">Admin Dashboard</p>
          </div>
        </div>
      </div>

      <!-- User Profile -->
      <div class="p-4 mx-4 mt-4">
        <div class="bg-slate-800/50 rounded-2xl p-4 border border-slate-700/50">
          <div class="flex items-center space-x-3">
            <div class="relative">
              <div
                class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                {{ substr(Auth::user()->name, 0, 1) }}
              </div>
              <div
                class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-slate-800 rounded-full animate-pulse-green">
              </div>
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-sm truncate">{{ Auth::user()->name }}</p>
              <p class="text-xs text-slate-400 truncate">{{ Auth::user()->role->name }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1">
        <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Menu Utama</p>

        <a href="{{ route('admin.dashboard') }}"
          class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          <i class="fas fa-home w-5 text-center"></i>
          <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.categories.index') }}"
          class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          <i class="fas fa-tags w-5 text-center"></i>
          <span>Kategori</span>
        </a>

        <a href="{{ route('admin.products.index') }}"
          class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          <i class="fas fa-box w-5 text-center"></i>
          <span>Produk</span>
        </a>

        <a href="{{ route('admin.suppliers.index') }}"
          class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.suppliers.*') ? 'bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          <i class="fas fa-truck w-5 text-center"></i>
          <span>Supplier</span>
        </a>

        <a href="{{ route('admin.purchases.index') }}"
          class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.purchases.*') ? 'bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          <i class="fas fa-shopping-cart w-5 text-center"></i>
          <span>Pembelian</span>
        </a>

        <a href="{{ route('admin.sales.index') }}"
          class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.sales.*') ? 'bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          <i class="fas fa-cash-register w-5 text-center"></i>
          <span>Penjualan</span>
        </a>

        <a href="{{ route('admin.returns.index') }}"
          class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.returns.*') ? 'bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          <i class="fas fa-undo-alt w-5 text-center"></i>
          <span>Retur Barang</span>
        </a>

        <a href="{{ route('admin.branches.index') }}"
          class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.branches.*') ? 'bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          <i class="fas fa-store-alt w-5 text-center"></i>
          <span>Cabang</span>
        </a>

        <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Sistem</p>

        <a href="{{ route('profile.edit') }}"
          class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('profile.edit') ? 'bg-blue-600 text-white font-medium shadow-lg shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
          <i class="fas fa-user-cog w-5 text-center"></i>
          <span>Profile</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
            class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition text-red-400 hover:bg-red-500/10 hover:text-red-300 text-left">
            <i class="fas fa-sign-out-alt w-5 text-center"></i>
            <span>Logout</span>
          </button>
        </form>
      </nav>

      <!-- Bottom Info -->
      <div class="p-4 border-t border-slate-800">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-4 text-center">
          <p class="text-xs text-blue-100 mb-1">Versi Pro</p>
          <p class="text-sm font-semibold">SmartPOS v2.0</p>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-slate-50 relative">
      <!-- Top Bar -->
      <header class="sticky top-0 z-10 glass border-b border-white/20">
        <div class="px-8 py-4 flex justify-between items-center">
          <div>
            <h2 class="text-2xl font-bold text-slate-800">@yield('page_title', 'Dashboard')</h2>
            <p class="text-sm text-slate-500">@yield('page_subtitle', 'Selamat datang kembali, ' . Auth::user()->name)</p>
          </div>

          <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="hidden md:flex items-center bg-white rounded-full px-4 py-2 shadow-sm border border-slate-200">
              <i class="fas fa-search text-slate-400 mr-2"></i>
              <input type="text" placeholder="Cari..." class="bg-transparent outline-none text-sm w-48">
            </div>

            <!-- Notifications -->
            <div class="relative">
              <button
                class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-200 hover:shadow-md transition relative">
                <i class="fas fa-bell text-slate-600"></i>
                <span
                  class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-xs text-white flex items-center justify-center font-bold border-2 border-white">3</span>
              </button>
            </div>
          </div>
        </div>
      </header>

      <!-- Content -->
      <div class="p-8">
        @yield('content')
      </div>
    </main>
  </div>

  @stack('scripts')
</body>

</html>
