<?php
include '../controller/database.php';
if(!isset($_GET['id'])) header('Location: history.php');
$id=intval($_GET['id']);
$r=$conn->query("SELECT * FROM predictions WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html><html><head>
    <title>Detail</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<header><h1>Detail Prediksi</h1></header>
<nav><a href="dashboard.php">Dashboard</a><a href="prediction.php">Prediksi</a><a href="history.php">Riwayat</a></nav>
<div class="container">
<p><strong>Nama:</strong> <?=$r['name']?></p>
<p><strong>Gender:</strong> <?=$r['gender']?></p>
<p><strong>Tipe:</strong> <?=$r['cancer_type']?></p>
<p><strong>Merokok:</strong> <?=$r['smoking']?></p>
<p><strong>Genetik:</strong> <?=$r['genetic']?></p>
<p><strong>Alkohol:</strong> <?=$r['alcohol']?></p>
<p><strong>Polusi:</strong> <?=$r['pollution']?></p>
<p><strong>Obesitas:</strong> <?=$r['obesity']?></p>
<p><strong>Keparahan:</strong> <?=$r['severity']?></p>
<a href="history.php">← Kembali</a>
</div><footer>©2025 Prediksi Kanker</footer>
</body></html>
