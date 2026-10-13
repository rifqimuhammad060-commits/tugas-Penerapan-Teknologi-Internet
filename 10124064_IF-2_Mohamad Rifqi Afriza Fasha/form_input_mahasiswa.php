<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Mahasiswa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f6f9; }
        .container { width: 450px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); margin: auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; }
        button:hover { background-color: #0056b3; }
        .result { margin-top: 20px; padding: 15px; background: #d4edda; color: #155724; border-radius: 4px; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Input Data Mahasiswa</h2>
    <p style="font-size: 13px; color: #666; margin-bottom: 20px;">Silakan masukkan data diri dan tugas kamu di bawah ini.</p>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" placeholder="Contoh: Mohamad Rifqi Afriza Fasha" required>
        </div>
        <div class="form-group">
            <label for="nim">NIM:</label>
            <input type="text" id="nim" name="nim" placeholder="Contoh: 10124064" required>
        </div>
        <div class="form-group">
            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" placeholder="Contoh: IF 2" required>
        </div>
        <div class="form-group">
            <label for="mata_kuliah">Mata Kuliah:</label>
            <input type="text" id="mata_kuliah" name="mata_kuliah" placeholder="Contoh: Penerapan Teknologi Internet" required>
        </div>
        <div class="form-group">
            <label for="catatan">Catatan / Pesan Tugas:</label>
            <textarea id="catatan" name="catatan" rows="3" placeholder="Tulis catatan di sini..." required></textarea>
        </div>
        <button type="submit" name="kirim">Kirim Data</button>
    </form>

    <?php
    // Memproses data hanya ketika tombol 'kirim' benar-benar ditekan
    if (isset($_POST['kirim'])) {
        // Mengamankan input dari karakter khusus
        $nama    = isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '';
        $nim     = isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : '';
        $kelas   = isset($_POST['kelas']) ? htmlspecialchars($_POST['kelas']) : '';
        $matkul  = isset($_POST['mata_kuliah']) ? htmlspecialchars($_POST['mata_kuliah']) : '';
        $catatan = isset($_POST['catatan']) ? htmlspecialchars($_POST['catatan']) : '';

        echo "<div class='result'>";
        echo "<strong>Data Berhasil Disimpan!</strong><br><br>";
        echo "<strong>Nama:</strong> " . $nama . "<br>";
        echo "<strong>NIM:</strong> " . $nim . "<br>";
        echo "<strong>Kelas:</strong> " . $kelas . "<br>";
        echo "<strong>Mata Kuliah:</strong> " . $matkul . "<br>";
        echo "<strong>Catatan:</strong> " . $catatan;
        echo "</div>";
    }
    ?>
</div>

</body>
</html>