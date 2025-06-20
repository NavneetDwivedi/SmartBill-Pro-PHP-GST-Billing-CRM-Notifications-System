<?php
require ('config/db.php');
include_once('cron-trigger.php');

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">


<head>

    <meta charset="utf-8" />
    <title>GST Invoice Generator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- plugin css -->
    <link href="assets/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index.php" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="assets/images/dst_02.jpg" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="assets/images/dst_02.jpg" alt="" height="21">
                        </span>
                    </a>

                    <a href="index.php" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="assets/images/dst_02.jpg" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="assets/images/dst_02.jpg" alt="" height="21">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                

            </div>

            <div class="d-flex align-items-center">

                <?php
// Get unread count
$unread_count_query = $conn->query("SELECT COUNT(*) AS total FROM notifications WHERE is_read = 0");
$unread_count = $unread_count_query ? $unread_count_query->fetch_assoc()['total'] : 0;

// Get latest 5 notifications
$notif_query = $conn->query("SELECT n.id, n.message, n.created_at, n.is_read 
                             FROM notifications n 
                             JOIN invoices i ON n.invoice_id = i.id 
                             ORDER BY n.created_at DESC 
                             LIMIT 5");
?>

<div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
  <button type="button" class="btn btn-icon btn-topbar btn-ghost-primary rounded-circle"
          id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside"
          aria-haspopup="true" aria-expanded="false">
    <i class='las la-bell fs-24'></i>
    <?php if ($unread_count > 0): ?>
      <span class="position-absolute topbar-badge fs-9 translate-middle badge rounded-pill bg-danger">
        <?= $unread_count ?>
        <span class="visually-hidden">unread messages</span>
      </span>
    <?php endif; ?>
  </button>

  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
    <div class="dropdown-head rounded-top">
      <div class="p-3 bg-primary bg-pattern">
        <div class="row align-items-center">
          <div class="col">
            <h6 class="m-0 fs-16 fw-semibold text-white">Notifications</h6>
          </div>
          <div class="col-auto dropdown-tabs">
            <span class="badge bg-light-subtle text-light fs-13"><?= $unread_count ?> New</span>
          </div>
        </div>
      </div>
    </div>

    <div class="pt-2">
      <div data-simplebar style="max-height: 300px;" class="pe-2">
        <?php if ($notif_query && $notif_query->num_rows > 0): ?>
          <?php while ($row = $notif_query->fetch_assoc()): ?>
            <div class="text-reset notification-item d-block dropdown-item position-relative">
              <div class="d-flex">
                <div class="avatar-xs me-3">
                  <span class="avatar-title bg-<?= $row['is_read'] ? 'light' : 'info-subtle' ?> text-info rounded-circle fs-16">
                    <i class="bx bx-bell"></i>
                  </span>
                </div>
                <div class="flex-1">
                  <h6 class="mt-0 fs-14 mb-2 lh-base">
                    <?= htmlspecialchars($row['message']) ?>
                    <?php if (!$row['is_read']): ?>
                      <span class="badge bg-danger ms-2">New</span>
                    <?php endif; ?>
                  </h6>
                  <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                    <i class="mdi mdi-clock-outline"></i> <?= date('d M Y, h:i A', strtotime($row['created_at'])) ?>
                  </p>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="p-3 text-muted text-center">No new notifications</p>
        <?php endif; ?>
      </div>

      <div class="my-3 text-center view-all">
        <a href="all-notifications.php" class="btn btn-soft-success btn-sm waves-effect waves-light">
          View All Notifications <i class="ri-arrow-right-line align-middle"></i>
        </a>
      </div>
    </div>
  </div>
</div>



                <div class="dropdown header-item">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-4.jpg" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block fw-medium user-name-text fs-16">Calvin D. <i class="las la-angle-down fs-12 ms-1"></i></span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <a class="dropdown-item" href="#"><i class="bx bx-user fs-15 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                        <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end">11</span><i class="bx bx-wrench fs-15 align-middle me-1"></i> <span key="t-settings">Settings</span></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="#"><i class="bx bx-power-off fs-15 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/dst_02.jpg" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/dst_02.jpg" alt="" height="80">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/dst_02.jpg" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/dst_02.jpg" alt="" height="80">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                   <ul class="navbar-nav" id="navbar-nav">
    <li class="menu-title"><span data-key="t-menu">Menu</span></li>

    <li class="nav-item">
        <a class="nav-link menu-link" href="index.php">
    <i class="las la-tachometer-alt"></i>
    <span data-key="t-dashboard">Dashboard</span>
</a>

    </li>

    <li class="nav-item">
        <a class="nav-link menu-link" href="#sidebarInvoices" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarInvoices">
            <i class="las la-file-invoice"></i>
            <span data-key="t-invoice">Invoices</span>
        </a>
        <div class="collapse menu-dropdown" id="sidebarInvoices">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="invoice.php" class="nav-link" data-key="t-view-invoice">
                        <i class="las la-eye"></i> View All Invoices
                    </a>
                </li>
                <li class="nav-item">
                    <a href="invoice-add.php" class="nav-link" data-key="t-create-invoice">
                        <i class="las la-plus-circle"></i> Create New Invoice
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link menu-link" href="#sidebarClients" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarClients">
            <i class="las la-user-friends"></i>
            <span data-key="t-clients">Clients</span>
        </a>
        <div class="collapse menu-dropdown" id="sidebarClients">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="clients.php" class="nav-link" data-key="t-all-clients">
                        <i class="las la-users"></i> All Clients
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add-clients.php" class="nav-link" data-key="t-add-client">
                        <i class="las la-user-plus"></i> Add Clients
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link menu-link" href="#sidebarProducts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProducts">
            <i class="las la-boxes"></i>
            <span data-key="t-products">Products</span>
        </a>
        <div class="collapse menu-dropdown" id="sidebarProducts">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="products.php" class="nav-link" data-key="t-all-products">
                        <i class="las la-box"></i> All Products
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add-products.php" class="nav-link" data-key="t-add-product">
                        <i class="las la-plus-square"></i> Add Products
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <div class="help-box text-center">
        <img src="assets/images/create-invoice.png" class="img-fluid" alt="">
        <div class="mt-3">
            <a href="invoice-add.php" class="btn btn-primary">
                <i class="las la-file-invoice-dollar me-1"></i> Create Invoice
            </a>
        </div>
    </div>
</ul>


                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>