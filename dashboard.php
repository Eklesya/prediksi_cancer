<?php
include '../controller/database.php';

// Mengambil data untuk histogram
$data = $conn->query("SELECT severity, COUNT(*) cnt FROM predictions GROUP BY severity");
$labels = [];
$counts = [];
$summary = ['Low' => 0, 'Medium' => 0, 'High' => 0, 'Unknown' => 0];
$total = 0;

while ($r = $data->fetch_assoc()) {
    $labels[] = $r['severity'];
    $counts[] = $r['cnt'];
    $summary[$r['severity']] = $r['cnt'];
    $total += $r['cnt'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Prediksi Kanker</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/chart.js"></script>
    <style>
        .welcome-message {
            background-color: #e0f7fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #00695c;
            text-align: center;
            font-size: 18px;
        }

        .info-message {
            background-color: #fff3e0;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            color: #e65100;
            text-align: center;
            font-size: 16px;
        }

        .dashboard-cards {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 10px;
            width: 200px;
            text-align: center;
        }

        .card h3 {
            margin-bottom: 10px;
        }

        .low { background-color: #e8f5e9; color: #2e7d32; }
        .medium { background-color: #fff8e1; color: #f9a825; }
        .high { background-color: #ffebee; color: #c62828; }
        .unknown { background-color: #eceff1; color: #546e7a; }
    </style>
</head>
<body>
<header>
    <h1>Dashboard Sistem Prediksi Risiko Kanker</h1>
</header>
<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="prediction.php">Prediksi Kanker</a>
    <a href="history.php">Riwayat Prediksi</a>
</nav>

<div class="container">
    <div class="welcome-message">
        <h2>Selamat Datang di Sistem Prediksi Risiko Kanker</h2>
        <p>Website ini membantu Anda memprediksi tingkat risiko kanker berdasarkan data pasien.</p>
    </div>

    <div class="info-message">
        <p>Data yang ditampilkan bersifat edukatif dan bukan merupakan pengganti konsultasi atau diagnosis medis dari dokter profesional.</p>
    </div>

    <h2>Ringkasan Prediksi</h2>
    <div class="dashboard-cards">
        <div class="card low">
            <h3>Low</h3>
            <p><?= $summary['Low'] ?> Kasus</p>
        </div>
        <div class="card medium">
            <h3>Medium</h3>
            <p><?= $summary['Medium'] ?> Kasus</p>
        </div>
        <div class="card high">
            <h3>High</h3>
            <p><?= $summary['High'] ?> Kasus</p>
        </div>
       
        <div class="card" style="background-color: #f0f0f0; color: #333;">
            <h3>Total Prediksi</h3>
            <p><strong><?= $total ?> Kasus</strong></p>
        </div>
    </div>

 
    <canvas id="histChart"></canvas>

    <script>
        const ctx = document.getElementById('histChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Jumlah Prediksi',
                    data: <?= json_encode($counts) ?>,
                    backgroundColor: [
                        '#28a745', // Low
                        '#ffc107', // Medium
                        '#dc3545', // High
                        
                    ]
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</div>

<footer>
    <p>&copy; 2025 Sistem Prediksi Risiko Kanker. Website ini bersifat edukatif dan bukan pengganti diagnosis dokter.</p>
</footer>
</body>
</html>
