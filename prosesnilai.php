<?php
require_once __DIR__ . "/connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: inputnilai.php");
    exit;
}

$nim = trim($_POST["nim"] ?? "");
$nama = trim($_POST["namamahasiswa"] ?? "");
$nilaiInput = $_POST["nilaiakhir"] ?? "";

if ($nim === "" || $nama === "" ||
    $nilaiInput === "" || !is_numeric($nilaiInput)) {
    die("Data tidak lengkap atau tidak valid.");
}

$nilai = (float)$nilaiInput;

if ($nilai < 0 || $nilai > 100) {
    die("Nilai harus antara 0 sampai 100.");
}

if ($nilai <= 34.50) {
    $huruf = "E";
    $predikat = "Tidak Lulus";
} elseif ($nilai <= 50.50) {
    $huruf = "D";
    $predikat = "Kurang";
} elseif ($nilai <= 65.50) {
    $huruf = "C";
    $predikat = "Cukup";
} elseif ($nilai <= 80.50) {
    $huruf = "B";
    $predikat = "Memuaskan";
} else {
    $huruf = "A";
    $predikat = "Sangat Memuaskan";
}

// Memeriksa apakah NIM sudah digunakan
$cek = mysqli_prepare(
    $conn,
    "SELECT NIM FROM nilaimahasiswa WHERE NIM = ?"
);
mysqli_stmt_bind_param($cek, "s", $nim);
mysqli_stmt_execute($cek);
mysqli_stmt_store_result($cek);

if (mysqli_stmt_num_rows($cek) > 0) {
    mysqli_stmt_close($cek);
    die("NIM sudah terdaftar. Gunakan NIM yang berbeda.");
}
mysqli_stmt_close($cek);

// Menyiapkan folder upload
$folder = __DIR__ . "/uploads/";

if (!is_dir($folder) &&
    !mkdir($folder, 0755, true)) {
    die("Folder uploads gagal dibuat.");
}

$avatar = null;

if (isset($_FILES["avatar"]) &&
    $_FILES["avatar"]["error"] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES["avatar"]["error"] !== UPLOAD_ERR_OK) {
        die("Upload foto gagal.");
    }

    if ($_FILES["avatar"]["size"] > 2 * 1024 * 1024) {
        die("Ukuran foto maksimal 2 MB.");
    }

    $tmp = $_FILES["avatar"]["tmp_name"];

    $info = getimagesize($tmp);

    if ($info === false) {
        die("File yang diunggah bukan gambar yang valid.");
    }

    $mime = $info["mime"];

    $ekstensi = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];

    if (!isset($ekstensi[$mime])) {
        die("Foto harus berformat JPG, PNG, atau WEBP.");
    }

    $avatar = bin2hex(random_bytes(16)) .
              "." . $ekstensi[$mime];

    if (!move_uploaded_file($tmp, $folder . $avatar)) {
        die("Foto gagal disimpan.");
    }
}

// Menyimpan data mahasiswa
$sql = "INSERT INTO nilaimahasiswa
        (NIM, NamaMahasiswa, NilaiAkhir, NilaiHuruf,
         Predikat, created_date, modified_date, avatar)
        VALUES (?, ?, ?, ?, ?, NOW(), NOW(), ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt, "ssdsss",
    $nim, $nama, $nilai, $huruf, $predikat, $avatar
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: bacanilai.php");
    exit;
}

$error = mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);

// Hapus foto jika penyimpanan data gagal
if ($avatar !== null && is_file($folder . $avatar)) {
    unlink($folder . $avatar);
}

die("Gagal menyimpan data: " .
    htmlspecialchars($error, ENT_QUOTES, "UTF-8"));
?>