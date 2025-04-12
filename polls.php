<?php
require 'db.php';
session_start();

$polls = $pdo->query("SELECT * FROM polls ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Polling Blockchain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
        <a class="navbar-brand" href="index.php">Blockchain Poll</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="polls.php">Lihat Semua Polling</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item"><a class="nav-link" href="create_poll.php">+ Buat Polling</a></li>
                <?php endif ?>
                <li class="nav-item"><a class="nav-link" href="validate.php">Cek Validasi Blockchain</a></li>
            </ul>
            <?php if (isset($_SESSION['username'])): ?>
                <span class="navbar-text me-3">Halo, <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><strong><?= $_SESSION['username'] ?></strong></a></span>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-primary">Login</a>
            <?php endif ?>
        </div>
    </nav>

    <div class="container py-4">
        <h3>Daftar Polling</h3>
        <?php if ($polls): ?>
            <ul class="list-group">
                <?php foreach ($polls as $p): ?>
                    <li class="list-group-item">
                        <a href="index.php?poll_id=<?= $p['id'] ?>"><?= htmlspecialchars($p['prompt']) ?></a>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-warning">Belum ada polling yang dibuat.</div>
        <?php endif ?>
        <br>
        <a href="create_poll.php" class="btn btn-primary">+ Buat Polling Baru</a>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="logoutModalLabel">Logout</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Anda yakin ingin logout dari akun <strong><?= $_SESSION['username'] ?? '' ?></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>