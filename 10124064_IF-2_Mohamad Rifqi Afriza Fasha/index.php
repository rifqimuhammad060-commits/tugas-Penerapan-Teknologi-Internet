<?php
session_start();
require 'koneksi.php';

// Proteksi Halaman: Jika belum login, lempar kembali ke halaman login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Pengaturan Bahasa (Dual Language) via Session
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'id';

// Kamus Bahasa Antarmuka & Notifikasi
$text = [
    'id' => [
        'title' => 'Manajemen Data Mahasiswa',
        'welcome' => 'Selamat datang, Administrator!',
        'logout' => 'Keluar (Logout)',
        'add_heading' => 'Tambah Data Mahasiswa Baru',
        'list_heading' => 'Daftar Data Mahasiswa',
        'th_no' => 'No',
        'th_nim' => 'NIM',
        'th_nama' => 'Nama Lengkap',
        'th_email' => 'Email',
        'th_jurusan' => 'Jurusan',
        'th_angkatan' => 'Angkatan',
        'th_aksi' => 'Aksi',
        'btn_save' => 'Simpan Data',
        'btn_delete' => 'Hapus',
        'confirm_delete' => 'Yakin ingin menghapus data ini?',
        'success_add' => 'Data mahasiswa berhasil ditambahkan!',
        'success_del' => 'Data mahasiswa berhasil dihapus!',
        'empty_data' => 'Belum ada data mahasiswa.'
    ],
    'en' => [
        'title' => 'Student Data Management',
        'welcome' => 'Welcome, Administrator!',
        'logout' => 'Logout',
        'add_heading' => 'Add New Student Data',
        'list_heading' => 'Student Data List',
        'th_no' => 'No',
        'th_nim' => 'NIM',
        'th_nama' => 'Full Name',
        'th_email' => 'Email',
        'th_jurusan' => 'Major',
        'th_angkatan' => 'Batch Year',
        'th_aksi' => 'Action',
        'btn_save' => 'Save Data',
        'btn_delete' => 'Delete',
        'confirm_delete' => 'Are you sure you want to delete this data?',
        'success_add' => 'Student data successfully added!',
        'success_del' => 'Student data successfully deleted!',
        'empty_data' => 'No student data available.'
    ]
];

$t = $text[$lang];
$pesan = "";

// Proses Tambah Data (Create)
if (isset($_POST['tambah'])) {
    $nim     = trim($_POST['nim']);
    $nama    = trim($_POST['nama']);
    $email   = trim($_POST['email']);
    $jurusan = trim($_POST['jurusan']);
    $angkatan= trim($_POST['angkatan']);

    if (!empty($nim) && !empty($nama) && !empty($email) && !empty($jurusan) && !empty($angkatan)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO mahasiswa (nim, nama, email, jurusan, angkatan) VALUES (:nim, :nama, :email, :jurusan, :angkatan)");
            $stmt->execute([
                ':nim' => $nim,
                ':nama' => $nama,
                ':email' => $email,
                ':jurusan' => $jurusan,
                ':angkatan' => $angkatan
            ]);
            $pesan = "<div class='alert success'>{$t['success_add']}</div>";
        } catch (PDOException $e) {
            $pesan = "<div class='alert error'>Gagal: " . $e->getMessage() . "</div>";
        }
    }
}

// Proses Hapus Data (Delete)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    try {
        $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $pesan = "<div class='alert success'>{$t['success_del']}</div>";
    } catch (PDOException $e) {
        $pesan = "<div class='alert error'>Gagal menghapus: " . $e->getMessage() . "</div>";
    }
}

// Ambil Data Mahasiswa dari Database (Read)
$stmt = $pdo->query("SELECT * FROM mahasiswa ORDER BY id DESC");
$mahasiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $t['title'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f6f9; color: #333; }
        .header-bar { display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px 20px; border-radius: 8px; box-shadow: 0px 2px 5px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 2px 5px rgba(0,0,0,0.05); margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
        th { background-color: #007BFF; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 13px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button, .btn { padding: 8px 14px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; font-size: 13px; }
        button:hover, .btn:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #a71d2a; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; font-size: 14px; }
        .alert.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .lang-link { margin-left: 10px; font-weight: bold; color: #007BFF; text-decoration: none; }
    </style>
</head>
<body>

<div class="header-bar">
    <div>
        <strong><?= $t['welcome'] ?></strong>
    </div>
    <div>
        Language: 
        <?php if ($lang == 'id'): ?>
            <a href="index.php?lang=en" class="lang-link">English</a>
        <?php else: ?>
            <a href="index.php?lang=id" class="lang-link">Indonesia</a>
        <?php endif; ?>
        | <a href="logout.php" class="lang-link" style="color: #dc3545;"><?= $t['logout'] ?></a>
    </div>
</div>

<?= $pesan ?>

<div class="card">
    <h3><?= $t['add_heading'] ?></h3>
    <form method="POST" action="">
        <div class="form-group">
            <label><?= $t['th_nim'] ?>:</label>
            <input type="text" name="nim" required placeholder="Contoh: 10124064">
        </div>
        <div class="form-group">
            <label><?= $t['th_nama'] ?>:</label>
            <input type="text" name="nama" required placeholder="Contoh: Mohamad Rifqi Afriza Fasha">
        </div>
        <div class="form-group">
            <label><?= $t['th_email'] ?>:</label>
            <input type="email" name="email" required placeholder="Contoh: rifqi@email.com">
        </div>
        <div class="form-group">
            <label><?= $t['th_jurusan'] ?>:</label>
            <input type="text" name="jurusan" required placeholder="Contoh: Teknik Informatika">
        </div>
        <div class="form-group">
            <label><?= $t['th_angkatan'] ?>:</label>
            <input type="number" name="angkatan" required placeholder="Contoh: 2024">
        </div>
        <button type="submit" name="tambah"><?= $t['btn_save'] ?></button>
    </form>
</div>

<div class="card">
    <h3><?= $t['list_heading'] ?></h3>
    <table>
        <thead>
            <tr>
                <th><?= $t['th_no'] ?></th>
                <th><?= $t['th_nim'] ?></th>
                <th><?= $t['th_nama'] ?></th>
                <th><?= $t['th_email'] ?></th>
                <th><?= $t['th_jurusan'] ?></th>
                <th><?= $t['th_angkatan'] ?></th>
                <th><?= $t['th_aksi'] ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($mahasiswa) > 0): ?>
                <?php $no = 1; foreach ($mahasiswa as $mhs): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($mhs['nim']) ?></td>
                    <td><?= htmlspecialchars($mhs['nama']) ?></td>
                    <td><?= htmlspecialchars($mhs['email']) ?></td>
                    <td><?= htmlspecialchars($mhs['jurusan']) ?></td>
                    <td><?= htmlspecialchars($mhs['angkatan']) ?></td>
                    <td>
                        <a href="index.php?hapus=<?= $mhs['id'] ?>" class="btn btn-danger" onclick="return confirm('<?= $t['confirm_delete'] ?>');"><?= $t['btn_delete'] ?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #777;"><?= $t['empty_data'] ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>