<?php
require_once __DIR__ . "/connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: bacanilaimhsw.php");
    exit;
}

$nim = trim($_POST["nim"] ?? "");

if ($nim === "") {
    die("NIM tidak ditemukan.");
}

// Ambil nama file avatar
$stmt = mysqli_prepare(
    $conn,
    "SELECT avatar FROM nilaimahasiswa WHERE NIM = ?"
);
mysqli_stmt_bind_param($stmt, "s", $nim);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Data mahasiswa tidak ditemukan.");
}

$avatar = $data["avatar"] ?? "";

// Hapus data mahasiswa
$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM nilaimahasiswa WHERE NIM = ?"
);
mysqli_stmt_bind_param($stmt, "s", $nim);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    // Hapus file foto jika ada
    if ($avatar !== "") {
        $namaFile = basename($avatar);
        $lokasi = __DIR__ . "/uploads/" . $namaFile;

        if (is_file($lokasi)) {
            unlink($lokasi);
        }
    }

    header("Location: bacanilaimhsw.php");
    exit;
}

$error = mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);

die("Gagal menghapus data: " .
    htmlspecialchars($error, ENT_QUOTES, "UTF-8"));
?>