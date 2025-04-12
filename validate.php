<?php
require 'db.php';
session_start();

$blocks = $pdo->query("SELECT * FROM blockchain_votes ORDER BY id")->fetchAll();

$isValid = true;
for ($i = 1; $i < count($blocks); $i++) {
    $prev = $blocks[$i - 1];
    $curr = $blocks[$i];

    $expectedHash = hash('sha256', $curr['data'] . $curr['previous_hash']);
    if ($curr['previous_hash'] !== $prev['hash'] || $curr['hash'] !== $expectedHash) {
        $isValid = false;
        break;
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Status Blockchain</title>
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
        <h2>Status Blockchain:</h2>
        <p>
            <?= $isValid ? "✅ Valid" : "❌ Tidak Valid - Ada data yang dimanipulasi" ?>
        </p>
        <br>
        <a href="index.php" class="btn btn-primary">Kembali ke Polling</a>
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