<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>POS - Navbar + Sidebar (MDB Design)</title>
  
  <!-- NOTE: replace these CDN links with your MDB version or local files if needed -->
  <!-- MDB (Material Design for Bootstrap) CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" />
  <!-- Font Awesome for icons (optional) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
  <style>
    /* Layout adjustments */
    body {
      min-height: 100vh;
      padding-top: 50px; /* space for fixed navbar */
    }
    .sidebar {
      width: 250px;
      min-height: calc(100vh - 64px);
      position: fixed;
      top: 64px; /* below navbar */
      left: 0;
      background: #ffffff;
      border-right: 1px solid #e6e6e6;
      padding: 1rem 0;
      overflow-y: auto;
      transition: transform .25s ease;
    }
    .sidebar .nav-link {
      color: #333;
      font-weight: 500;
    }
    .sidebar .nav-link.active {
      background: linear-gradient(90deg,#4f46e5, #06b6d4);
      color: #fff !important;
      border-radius: 8px;
    }
    .content {
      margin-left: 250px;
      
    }

    /* Small screens: hide sidebar by default */
    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
        z-index: 1045;
      }
      .sidebar.show {
        transform: translateX(0);
      }
      .content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

<!-- Top Navbar -->
<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container-fluid">
      <button class="btn btn-sm btn-outline-primary d-lg-none" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
      </button>

      <a class="navbar-brand ms-2" href="#">
        <img src="" alt="Logo" style="height:30px; width:auto;" />
        <span class="ms-2 fw-bold">EasyPOS</span>
      </a>

      <form class="d-none d-md-flex input-group w-50 ms-3">
        <span class="input-group-text"><i class="fas fa-search"></i></span>
        <input class="form-control" type="search" placeholder="Search product, barcode, SKU..." aria-label="Search">
      </form>

      <div class="d-flex align-items-center">
        <a class="btn btn-sm btn-outline-secondary me-2 position-relative d-none d-md-inline-flex" href="#">
          <i class="fas fa-shopping-cart"></i>
          <span class="badge rounded-pill bg-danger position-absolute" style="top:-6px; right:-8px;">3</span>
        </a>

        <div class="dropdown me-2">
          <a class="btn btn-sm btn-outline-primary dropdown-toggle" href="#" role="button" id="shiftMenu" data-mdb-toggle="dropdown" aria-expanded="false">
            Shift: <strong class="ms-1">Morning</strong>
          </a>
          <ul class="dropdown-menu" aria-labelledby="shiftMenu">
            <li><a class="dropdown-item" href="#">Morning</a></li>
            <li><a class="dropdown-item" href="#">Evening</a></li>
            <li><a class="dropdown-item" href="#">Night</a></li>
          </ul>
        </div>

        <div class="dropdown">
          <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="userMenu" data-mdb-toggle="dropdown" aria-expanded="false">
            <img src="https://via.placeholder.com/36" alt="Avatar" class="rounded-circle me-2" />
            <span class="d-none d-md-inline">Admin</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>

<!-- Sidebar -->
<aside class="sidebar shadow-lg" id="mainSidebar">
  <div class="px-3 mb-3">
    <small class="text-muted ms-2">Navigation</small>
  </div>

  <ul class="nav nav-pills flex-column mb-0 px-2 gap-1">
    <li class="nav-item">
      <a class="nav-link active d-flex align-items-center" href="#">
        <i class="fas fa-home me-3"></i> Dashboard
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="{{ route('products.index') }}">
        <i class="fas fa-boxes-stacked me-3"></i> Products
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="{{ route('categories.index') }}">
        <i class="fas fa-tags me-3"></i> Categories
      </a>
    </li>
      <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="{{ route('units.index') }}">
        <i class="fas fa-tags me-3"></i> Units
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="{{ route('customers.index') }}">
        <i class="fas fa-users me-3"></i> Customers
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="{{ route('suppliers.index') }}">
        <i class="fas fa-user-tie me-3"></i> Suppliers
      </a>
    </li>
     <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="{{ route('purchases.index') }}">
        <i class="fas fa-user-tie me-3"></i> Purchases Listing
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="">
        <i class="fas fa-user-tie me-3"></i> Sales Listing
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="">
        <i class="fas fa-user-tie me-3"></i>Customer Order
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="">
        <i class="fas fa-user-tie me-3"></i>Supplier Order
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="">
        <i class="fas fa-user me-3"></i> Users
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="#">
        <i class="fas fa-file-invoice-dollar me-3"></i> Reports
      </a>
    </li>
    <li class="nav-item mt-2">
      <small class="text-muted ms-2">Settings</small>
    </li>
    <li class="nav-item">
      <a class="nav-link d-flex align-items-center" href="#">
        <i class="fas fa-cog me-3"></i> Settings
      </a>
    </li>
  </ul>

  <div class="px-3 mt-4">
    <div class="small text-muted">Till: <strong>POS-01</strong></div>
    <div class="small text-muted">Store: <strong>Main Branch</strong></div>
  </div>
</aside>
<main class="content">
@yield('content')

@include('sweetalert::alert')
</main>
<!-- MDB JS (and dependencies) -->
<script>
  // Sidebar toggle for small screens
  const sidebar = document.getElementById('mainSidebar');
  document.getElementById('sidebarToggle').addEventListener('click', () => {
    sidebar.classList.toggle('show');
  });

  // Close sidebar when clicking outside (mobile)
  document.addEventListener('click', (e) => {
    if (window.innerWidth <= 992) {
      if (!sidebar.contains(e.target) && !document.getElementById('sidebarToggle').contains(e.target)) {
        sidebar.classList.remove('show');
      }
    }
  });
  
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap / MDB5 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
<script str="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Bundle alternative if not using MDB -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->

@stack('scripts')
@section('scripts')

</body>
</html>
