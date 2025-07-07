<?php
include '../controller/database.php';

// Proses hapus data jika tombol hapus ditekan
if (isset($_GET['delete'])) {
    $created_at = urldecode($_GET['delete']);

    // Hapus data berdasarkan waktu unik
    $stmt = $conn->prepare("DELETE FROM predictions WHERE created_at = ?");
    $stmt->bind_param("s", $created_at);
    $stmt->execute();
    $stmt->close();

    header("Location: history.php");
    exit;
}

// Query ambil data
$result = $conn->query("SELECT * FROM predictions ORDER BY created_at DESC");

// Cek apakah query berhasil
if (!$result) {
    die("Error pada query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Prediksi</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header, nav, footer {
            text-align: center;
        }

        .container {
            max-width: 1300px;
            background: #fff;
            margin: 30px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            font-size: 14px;
        }

        th, td {
            padding: 8px 10px;
            text-align: center;
            border: 1px solid #ddd;
            word-wrap: break-word;
        }

        th {
            background-color: #00695c;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .delete-btn {
            padding: 5px 10px;
            background-color: #e53935;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 12px;
        }

        .delete-btn:hover {
            background-color: #c62828;
        }

        footer {
            margin-top: 50px;
            padding: 20px 0;
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<header>
    <h1>Riwayat Prediksi Risiko Kanker</h1>
</header>
<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="prediction.php">Prediksi Kanker</a>
    <a href="history.php">Riwayat Prediksi</a>
</nav>

<div class="container">
    <h2>Data Riwayat Prediksi</h2>

    <table>
        <thead>
            <tr>
                <th>Tipe Kanker</th>
                <th>Tahun</th>
                <th>Negara</th>
                <th>Merokok</th>
                <th>Genetik</th>
                <th>Alkohol</th>
                <th>Polusi</th>
                <th>Obesitas</th>
                <th>Total Skor</th>
                <th>Tahun Bertahan Hidup</th>
                <th>Tingkat Keparahan</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
                $total = $row['smoking'] + $row['genetic'] + $row['alcohol'] + $row['pollution'] + $row['obesity'];

                // Keterangan sesuai tingkat keparahan yang disimpan dari Python
                if ($row['severity'] == 'Low') {
                    $note = 'Tidak perlu penanganan khusus';
                } elseif ($row['severity'] == 'Medium') {
                    $note = 'Disarankan konsultasi dengan dokter';
                } else {
                    $note = 'Segera lakukan penanganan medis';
                }
            ?>
            <tr>
                <td><?= htmlspecialchars($row['cancer_type']); ?></td>
                <td><?= $row['years'] ?: '-'; ?></td>
                <td><?= htmlspecialchars($row['country_region']) ?: '-'; ?></td>
                <td><?= $row['smoking']; ?></td>
                <td><?= $row['genetic']; ?></td>
                <td><?= $row['alcohol']; ?></td>
                <td><?= $row['pollution']; ?></td>
                <td><?= $row['obesity']; ?></td>
                <td><strong><?= $total; ?></strong></td>
                <td><?= $row['survival_years'] ? $row['survival_years'] . " Tahun" : '-'; ?></td>
                <td style="color: <?= ($row['severity'] == 'High') ? 'red' : (($row['severity'] == 'Medium') ? 'orange' : 'green') ?>;">
                    <strong><?= $row['severity']; ?></strong>
                </td>
                <td><?= $note; ?></td>
                <td>
                    <a class="delete-btn" href="history.php?delete=<?= urlencode($row['created_at']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<footer>
    <p>&copy; 2025 Sistem Prediksi Risiko Kanker. Website ini bersifat edukatif dan bukan pengganti diagnosis dokter.</p>
</footer>
</body>
</html>
