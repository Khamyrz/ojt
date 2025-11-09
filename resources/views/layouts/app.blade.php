<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin - Intern Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1e40af;
      --secondary: #64748b;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --dark: #1e293b;
      --light: #f1f5f9;
      --border: #e2e8f0;
      --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    html { margin: 0; padding: 0; }
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 0; /* remove outer padding so sidebar sits flush to the left */
      margin: 0; /* remove default body margin that creates left gap */
    }

    .dashboard-container {
      max-width: none;
      width: 100%;
      margin: 0; /* no outer margin so container touches the viewport edges */
      display: flex;
      gap: 24px;
      background: white;
      border-radius: 0 16px 16px 0; /* flush on the left side */
      overflow: hidden;
      box-shadow: var(--shadow-lg);
      min-height: 100vh; /* fill full height */
    }

    .sidebar {
      width: 280px;
      background: linear-gradient(180deg, var(--dark) 0%, #0f172a 100%);
      padding: 32px 20px;
      display: flex;
      flex-direction: column;
      position: relative;
      border-radius: 0; /* ensure flush-left */
    }

    .sidebar::after { content: ''; position: absolute; top: 0; right: 0; width: 1px; height: 100%; background: rgba(255,255,255,0.1); }

    .brand { margin-bottom: 40px; padding-bottom: 24px; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .brand h2 { color: white; font-size: 24px; font-weight: 700; margin-bottom: 4px; display: flex; align-items: center; gap: 10px; }
    .brand p { color: rgba(255,255,255,0.6); font-size: 13px; margin: 0; }

    .nav-menu { flex: 1; }
    .nav-item { margin-bottom: 8px; }
    .nav-link { display: flex; align-items: center; gap: 12px; padding: 14px 16px; color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 10px; transition: all 0.3s ease; position: relative; font-weight: 500; font-size: 14px; }
    .nav-link:hover { background: rgba(255,255,255,0.1); color: white; transform: translateX(4px); }
    .nav-link.active { background: var(--primary); color: white; }
    .nav-link i { width: 20px; text-align: center; font-size: 16px; }
    .badge { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: var(--danger); color: white; border-radius: 12px; padding: 2px 8px; font-size: 11px; font-weight: 600; }

    .logout-section { padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1); }
    .logout-btn { width: 100%; padding: 14px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .logout-btn:hover { background: var(--danger); color: white; border-color: var(--danger); }

    .main-content { flex: 1; padding: 32px; overflow-y: auto; background: var(--light); }
  </style>
</head>
<body>

  <div class="dashboard-container">
    <div class="sidebar">
      <div class="brand">
        <h2><i class="fas fa-graduation-cap"></i> Admin</h2>
        <p>Intern Management System</p>
      </div>

      <nav class="nav-menu">
        <div class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i><span>Dashboard</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="{{ route('interns') }}" class="nav-link {{ request()->routeIs('interns') ? 'active' : '' }}">
            <i class="fas fa-users"></i><span>Intern List</span>
            @if(isset($pendingCount) && $pendingCount > 0)
              <span class="badge">{{ $pendingCount }}</span>
            @endif
          </a>
        </div>
        <div class="nav-item">
          <a href="{{ route('documents') }}" class="nav-link {{ request()->routeIs('documents') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i><span>Documents</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="{{ route('grades') }}" class="nav-link {{ request()->routeIs('grades') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i><span>Grades</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="{{ route('messages') }}" class="nav-link {{ request()->routeIs('messages') ? 'active' : '' }}">
            <i class="fas fa-envelope"></i><span>Messages</span>
            @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
              <span class="badge">{{ $unreadMessagesCount }}</span>
            @endif
          </a>
        </div>
      </nav>

      <div class="logout-section">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i><span>Logout</span>
          </button>
        </form>
      </div>
    </div>

    <div class="main-content">
      @yield('content')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
