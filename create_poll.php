<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = $_POST['prompt'];
    $c1 = $_POST['candidate1'];
    $c1img = $_POST['candidate1_img'];
    $c2 = $_POST['candidate2'];
    $c2img = $_POST['candidate2_img'];

    $stmt = $pdo->prepare("INSERT INTO polls (prompt, candidate1, candidate1_img, candidate2, candidate2_img, created_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$prompt, $c1, $c1img, $c2, $c2img, $_SESSION['user_id']]);
    echo "Polling berhasil dibuat. <a href='index.php?poll_id={$pdo->lastInsertId()}'>Lihat Poll</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Buat Polling Baru</title>
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
        <h3>Buat Polling Baru</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="prompt" class="form-label">Prompt</label>
                <input type="text" name="prompt" id="prompt" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="candidate1" class="form-label">Kandidat 1</label>
                <input type="text" name="candidate1" id="candidate1" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="candidate1_img" class="form-label">Gambar Kandidat 1 (URL)</label>
                <input type="text" name="candidate1_img" id="candidate1_img" class="form-control">
            </div>

            <div class="mb-3">
                <label for="candidate2" class="form-label">Kandidat 2</label>
                <input type="text" name="candidate2" id="candidate2" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="candidate2_img" class="form-label">Gambar Kandidat 2 (URL)</label>
                <input type="text" name="candidate2_img" id="candidate2_img" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Buat Polling</button>
        </form>
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