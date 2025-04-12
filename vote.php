<?php
require 'db.php';
session_start();

$vote = $_POST['vote'] ?? null;
$poll_id = $_POST['poll_id'] ?? null;

if (!$vote || !$poll_id) {
    die("Data tidak valid.");
}

$stmt = $pdo->prepare("SELECT * FROM polls WHERE id = ?");
$stmt->execute([$poll_id]);
$poll = $stmt->fetch();

if (!$poll) {
    die("Polling tidak ditemukan.");
}

$stmt = $pdo->prepare("INSERT INTO votes (poll_id, vote) VALUES (?, ?)");
$stmt->execute([$poll_id, $vote]);

$stmt = $pdo->query("SELECT * FROM blockchain_votes WHERE poll_id = $poll_id ORDER BY id DESC LIMIT 1");
$lastBlock = $stmt->fetch();
$prevHash = $lastBlock ? $lastBlock['hash'] : '0000000000';

$blockData = $vote . $prevHash;
$hash = hash('sha256', $blockData);

$stmt = $pdo->prepare("INSERT INTO blockchain_votes (data, hash, previous_hash, poll_id) VALUES (?, ?, ?, ?)");
$stmt->execute([$vote, $hash, $prevHash, $poll_id]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Vote Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        let countdown = 3;

        function updateCountdown() {
            if (countdown > 0) {
                document.getElementById('countdown').innerText = countdown + " detik";
                countdown--;
            } else {
                window.location.href = 'index.php'; 
            }
        }
        setInterval(updateCountdown, 1000);
    </script>
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
            </ul>
            <?php if (isset($_SESSION['username'])): ?>
                <span class="navbar-text me-3">Halo, <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><strong><?= $_SESSION['username'] ?></strong></a></span>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-primary">Login</a>
            <?php endif ?>
        </div>
    </nav>

    <div class="container py-4 text-center">
        <h3>Vote Berhasil Disimpan!</h3>
        <p>Terima kasih telah berpartisipasi dalam polling ini.</p>
        <p>Anda akan diarahkan kembali ke halaman utama dalam <span id="countdown">3 detik</span>.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>