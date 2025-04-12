<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->execute([$user, $pass]);
    $check = $stmt->fetch();

    if ($check) {
        $_SESSION['user_id'] = $check['id'];
        $_SESSION['username'] = $check['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
            </ul>
            <?php if (isset($_SESSION['username'])): ?>
                <span class="navbar-text me-3">Halo, <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><strong><?= $_SESSION['username'] ?></strong></a></span>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-primary">Login</a>
            <?php endif ?>
        </div>
    </nav>

    <div class="container py-4 text-center">
        <h3>Login</h3>
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="username" required placeholder="Username" class="form-control" autofocus><br>
            </div>
            <div class="mb-3">
                <input type="password" name="password" required placeholder="Password" class="form-control"><br>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger mt-3"><?= $error ?></div>
            <?php endif; ?>
        </form>
        <br>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>