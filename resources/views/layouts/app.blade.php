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
        <div class="nav-item">
          <button type="button" onclick="openCreateUserModal()" class="nav-link" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer; font-family: inherit;">
            <i class="fas fa-user-plus"></i><span>Create User</span>
          </button>
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

  <!-- Create User Modal -->
  <div id="createUserModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 500px; margin: 5% auto;">
      <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #e2e8f0;">
        <h2 style="margin: 0; color: #1d3557;">Create Admin User</h2>
        <span class="close" onclick="closeCreateUserModal()" style="cursor: pointer; font-size: 28px; color: #aaa;">&times;</span>
      </div>
      <form id="createUserForm">
        @csrf
        <div style="margin-bottom: 20px;">
          <label style="display: block; margin-bottom: 8px; color: #334155; font-weight: 600;">Name</label>
          <input type="text" name="name" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; box-sizing: border-box;" placeholder="Enter admin name">
        </div>
        <div style="margin-bottom: 20px;">
          <label style="display: block; margin-bottom: 8px; color: #334155; font-weight: 600;">Email</label>
          <input type="email" name="email" required style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; box-sizing: border-box;" placeholder="Enter admin email">
        </div>
        <div style="margin-bottom: 20px;">
          <label style="display: block; margin-bottom: 8px; color: #334155; font-weight: 600;">Password</label>
          <input type="password" name="password" required minlength="8" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; box-sizing: border-box;" placeholder="Enter password (min 8 characters)">
        </div>
        <div style="margin-bottom: 20px;">
          <label style="display: block; margin-bottom: 8px; color: #334155; font-weight: 600;">Confirm Password</label>
          <input type="password" name="password_confirmation" required minlength="8" style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; box-sizing: border-box;" placeholder="Confirm password">
        </div>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
          <button type="button" onclick="closeCreateUserModal()" style="padding: 12px 24px; border: 2px solid #e2e8f0; border-radius: 8px; background: white; color: #64748b; cursor: pointer; font-weight: 600;">Cancel</button>
          <button type="submit" style="padding: 12px 24px; border: none; border-radius: 8px; background: #2563eb; color: white; cursor: pointer; font-weight: 600;">Create User</button>
        </div>
      </form>
    </div>
  </div>

  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(5px);
    }
    .modal-content {
      background: white;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: modalFadeIn 0.3s ease-out;
    }
    @keyframes modalFadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .close:hover {
      color: #000;
    }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function openCreateUserModal() {
      document.getElementById('createUserModal').style.display = 'block';
      document.body.style.overflow = 'hidden';
    }

    function closeCreateUserModal() {
      document.getElementById('createUserModal').style.display = 'none';
      document.body.style.overflow = 'auto';
      document.getElementById('createUserForm').reset();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      const modal = document.getElementById('createUserModal');
      if (event.target === modal) {
        closeCreateUserModal();
      }
    }

    // Handle form submission
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = 'Creating...';

      fetch('{{ route('admin.create-user') }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          closeCreateUserModal();
          Swal.fire({
            title: 'Success!',
            text: 'Another Admin can now login and an OTP verification will be sent to its account',
            icon: 'success',
            confirmButtonText: 'OK'
          });
        } else {
          Swal.fire({
            title: 'Error!',
            text: data.message || 'Failed to create admin user',
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.fire({
          title: 'Error!',
          text: 'An error occurred while creating the user',
          icon: 'error',
          confirmButtonText: 'OK'
        });
      })
      .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
