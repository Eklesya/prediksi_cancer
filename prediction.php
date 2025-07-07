<?php
include '../controller/database.php';
$resultMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cancer_type = $_POST['cancer_type'];
    $years = intval($_POST['years']);
    $country_region = $_POST['country_region'];

    // Ambil input faktor risiko
    $smoking = intval($_POST['smoking']);
    $genetic = intval($_POST['genetic']);
    $alcohol = intval($_POST['alcohol']);
    $pollution = intval($_POST['pollution']);
    $obesity = intval($_POST['obesity']);
    $survival_years = intval($_POST['survival_years']);

    // Hitung total skor
    $total = $smoking + $genetic + $alcohol + $pollution + $obesity;

    // Tentukan tingkat keparahan dan keterangan
    if ($total >= 1 && $total <= 4) {
        $severity = 'Low';
        $note = 'Tidak perlu penanganan khusus';
    } elseif ($total > 4 && $total <= 7) {
        $severity = 'Medium';
        $note = 'Disarankan konsultasi dengan dokter';
    } elseif ($total > 7 && $total) {
        $severity = 'High';
        $note = 'Segera lakukan penanganan medis';
    } 

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO predictions (cancer_type, years, country_region, smoking, genetic, alcohol, pollution, obesity, survival_years, severity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisiiiiiss", $cancer_type, $years, $country_region, $smoking, $genetic, $alcohol, $pollution, $obesity, $survival_years, $severity);
    $stmt->execute();
    $stmt->close();

    // Tampilkan hasil
    $resultMsg = "Prediksi: <strong>$severity</strong> untuk tipe kanker <em>$cancer_type</em> di <em>$country_region</em><br><strong>Keterangan:</strong> $note";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prediksi Risiko Kanker</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header>
    <h1>Prediksi Risiko Kanker</h1>
</header>
<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="prediction.php">Prediksi Kanker</a>
    <a href="history.php">Riwayat Prediksi</a>
</nav>
<div class="container">
    <h2>Formulir Prediksi Kanker</h2>
    <form method="POST">

        <label>Tipe Kanker:</label>
        <select name="cancer_type" required>
            <option value="">-- Pilih Tipe Kanker --</option>
            <option value="Lung Cancer">Lung Cancer</option>
            <option value="Breast Cancer">Breast Cancer</option>
            <option value="Colorectal Cancer">Colorectal Cancer</option>
            <option value="Prostate Cancer">Prostate Cancer</option>
            <option value="Skin Cancer">Skin Cancer</option>
            <option value="Leukemia">Leukemia</option>
            <option value="Pancreatic Cancer">Pancreatic Cancer</option>
        </select>

        <label>Tahun:</label>
        <input type="number" name="years" min="2000" max="2100" required>

        <label>Wilayah / Negara:</label>
        <select name="country_region" required>
    <option value="">-- Pilih Negara --</option>
    <option value="Indonesia">Indonesia</option>
    <option value="Malaysia">Malaysia</option>
    <option value="Singapura">Singapura</option>
    <option value="Vietnam">Vietnam</option>
    <option value="Thailand">Thailand</option>
    <option value="Filipina">Filipina</option>
    <option value="Jepang">Jepang</option>
    <option value="Korea Selatan">Korea Selatan</option>
    <option value="Cina">Cina</option>
    <option value="India">India</option>
        </select>


        <label>Tingkat Merokok:</label>
        <input type="number" name="smoking" min="0" max="10" required>

        <label>Faktor Genetik:</label>
        <input type="number" name="genetic" min="0" max="10" required>

        <label>Konsumsi Alkohol:</label>
        <input type="number" name="alcohol" min="0" max="10" required>

        <label>Tingkat Polusi Udara:</label>
        <input type="number" name="pollution" min="0" max="10" required>

        <label>Tingkat Obesitas:</label>
        <input type="number" name="obesity" min="0" max="10" required>

        <label>Tahun Bertahan Hidup:</label>
        <input type="number" name="survival_years" min="0" max="100" required>

        <button type="submit">Prediksi</button>
    </form>

    <div class="result">
    <?php if (!empty($resultMsg)) : ?>
        <h3>Hasil Prediksi</h3>
        <p><?php echo $resultMsg; ?></p>
    <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; 2025 Sistem Prediksi Risiko Kanker. Website ini bersifat edukatif dan bukan pengganti diagnosis dokter.</p>
</footer>
</body>
</html>
