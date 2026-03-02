<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - SmartPOS Owner</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .bg-gradient-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .border-left-primary {
      border-left: 4px solid #4e73df;
    }

    .border-left-success {
      border-left: 4px solid #1cc88a;
    }

    .border-left-info {
      border-left: 4px solid #36b9cc;
    }

    .border-left-warning {
      border-left: 4px solid #f6c23e;
    }
  </style>
</head>

<body>
  <div class="d-flex">
    <!-- Sidebar Owner -->
    <div class="bg-dark text-white" style="width: 250px; min-height: 100vh;">
      <div class="p-3">
        <h4 class="text-center">Smart<span class="text-primary">POS</span></h4>
        <hr>
        <nav class="nav flex-column">
          <a class="nav-link text-white" href="{{ route('owner.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
          </a>
          <a class="nav-link text-white" href="{{ route('admin.reports.index') }}">
            <i class="fas fa-chart-bar me-2"></i> Laporan
          </a>
          <a class="nav-link text-white" href="{{ route('admin.branches.index') }}">
            <i class="fas fa-store me-2"></i> Cabang
          </a>
          <a class="nav-link text-white" href="{{ route('profile.edit') }}">
            <i class="fas fa-user me-2"></i> Profile
          </a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link text-white bg-transparent border-0">
              <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
          </form>
        </nav>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
      <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container-fluid">
          <span class="navbar-brand">Owner Panel</span>
          <div>
            <span class="me-3">{{ Auth::user()->name }}</span>
          </div>
        </div>
      </nav>

      <div class="p-4">
        @yield('content')
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>

</html>
