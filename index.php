<?php
require 'db.php';
session_start();
$poll_id = $_GET['poll_id'] ?? 1;

$stmt = $pdo->prepare("SELECT * FROM polls WHERE id = ?");
$stmt->execute([$poll_id]);
$poll = $stmt->fetch();
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
        <?php if (!$poll): ?>
            <div class="alert alert-warning">Polling tidak ditemukan.</div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"><?= htmlspecialchars($poll['prompt']) ?></h4>
                    <form action="vote.php" method="POST">
                        <input type="hidden" name="poll_id" value="<?= $poll['id'] ?>">

                        <div class="row">
                            <div class="col-md-6 text-center">
                                <label>
                                    <input type="radio" name="vote" value="<?= $poll['candidate1'] ?>" required>
                                    <h5 class="mt-2"><?= $poll['candidate1'] ?></h5>
                                    <?php if ($poll['candidate1_img']): ?>
                                        <img src="<?= $poll['candidate1_img'] ?>" class="img-fluid" style="max-height: 200px;">
                                    <?php endif ?>
                                </label>
                                <br>
                                <?php
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE poll_id = ? AND vote = ?");
                                $stmt->execute([$poll['id'], $poll['candidate1']]);
                                $count1 = $stmt->fetchColumn();
                                ?>
                                <span><?= $count1 ?> Vote</span>
                            </div>
                            <div class="col-md-6 text-center">
                                <label>
                                    <input type="radio" name="vote" value="<?= $poll['candidate2'] ?>">
                                    <h5 class="mt-2"><?= $poll['candidate2'] ?></h5>
                                    <?php if ($poll['candidate2_img']): ?>
                                        <img src="<?= $poll['candidate2_img'] ?>" class="img-fluid" style="max-height: 200px;">
                                    <?php endif ?>
                                </label>
                                <br>
                                <?php
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE poll_id = ? AND vote = ?");
                                $stmt->execute([$poll['id'], $poll['candidate2']]);
                                $count2 = $stmt->fetchColumn();
                                ?>
                                <span><?= $count2 ?> Vote</span>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success">Vote</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif ?>
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