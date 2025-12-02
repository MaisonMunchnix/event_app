<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Registration System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4" style="padding: 10px 20px;">
    <a class="navbar-brand" href="/">Event Management System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a href="/" class="nav-link">Event List</a></li>
            
            <?php if(session()->get('isLoggedIn')): ?>
                <li class="nav-item"><a href="/admin/dashboard" class="nav-link">Admin Dashboard</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <?= esc(session()->get('name')) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="/logout">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li class="nav-item"><a href="/login" class="nav-link">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
</body>
</html>