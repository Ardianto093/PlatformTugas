<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "Todos";

$Conn = new mysqli($host, $user, $pass, $db);
if ($Conn->connect_error) {
    die("Koneksi gagal: " . $Conn->connect_error);
}

// Cek login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Tambah tugas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['todo'])) {
    $username = $_SESSION['login'];
    $teks = $_POST['todo'];
    $stmt = $Conn->prepare("INSERT INTO DataUser (Username, teks, Status) VALUES (?, ?, 'Belum')");
    $stmt->bind_param("ss", $username, $teks);
    $stmt->execute();
}

// Tandai selesai
if (isset($_GET['done'])) {
    $id = intval($_GET['done']);
    $Conn->query("UPDATE DataUser SET Status = 'Selesai' WHERE Id = $id");
}

// Hapus tugas
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $Conn->query("DELETE FROM DataUser WHERE Id = $id");
}

// Pagination
$username = $_SESSION['login'];
$perPage = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $perPage;

// Total data
$totalResult = $Conn->query("SELECT COUNT(*) as total FROM DataUser WHERE Username = '$username'");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $perPage);

// Ambil data sesuai halaman
$result = $Conn->query("SELECT * FROM DataUser WHERE Username = '$username' ORDER BY Id DESC LIMIT $perPage OFFSET $offset");
$todos = [];
while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Selamat datang, <b><?= htmlspecialchars($username) ?></b></h2>

        <form method="POST" class="todo-form">
            <input type="text" name="todo" placeholder="Tambahkan tugas..." class="input-todo">
            <button type="submit" class="submit-btn">Tambah</button>
        </form><br>

        <?php foreach ($todos as $item): ?>
            <div class="todo">
                <?= $item['Status'] === 'Selesai' ? '<s>' . htmlspecialchars($item['teks']) . '</s>' : htmlspecialchars($item['teks']) ?>
                <div class="actions">
                    <?php if ($item['Status'] !== 'Selesai'): ?>
                        <a href="?done=<?= $item['Id'] ?>"><button class="done-btn">Selesai</button></a>
                    <?php endif; ?>
                    <a href="?delete=<?= $item['Id'] ?>"><button class="delete-btn">Hapus</button></a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="pagination-link">&laquo; Sebelumnya</a>
            <?php endif; ?>
            <span>Halaman <?= $page ?> dari <?= $totalPages ?></span>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="pagination-link">Berikutnya &raquo;</a>
            <?php endif; ?>
        </div>

        <br><a href="logout.php" class="logout-link">Logout</a>
    </div>
</body>
</html>
