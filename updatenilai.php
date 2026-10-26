<?php
require_once __DIR__ . "/connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: bacanilai.php");
    exit;
}

// Mengambil data dari formulir
$nimLama = trim($_POST["nim_lama"] ?? "");
$nimBaru = trim($_POST["nim_baru"] ?? "");
$nama = trim($_POST["namamahasiswa"] ?? "");
$nilaiInput = $_POST["nilaiakhir"] ?? "";

if (
    $nimLama === "" ||
    $nimBaru === "" ||
    $nama === "" ||
    $nilaiInput === "" ||
    !is_numeric($nilaiInput)
) {
    die("Data tidak lengkap atau tidak valid.");
}

if (strlen($nimBaru) > 20 || strlen($nama) > 100) {
    die("NIM maksimal 20 karakter dan nama maksimal 100 karakter.");
}

// Validasi nilai akhir
$nilai = (float) $nilaiInput;

if (!is_finite($nilai) || $nilai < 0 || $nilai > 100) {
    die("Nilai akhir harus berada antara 0 sampai 100.");
}

// Menghitung nilai huruf dan predikat
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

// Memastikan data mahasiswa lama tersedia
$stmt = mysqli_prepare(
    $conn,
    "SELECT avatar FROM nilaimahasiswa WHERE NIM = ?"
);

mysqli_stmt_bind_param($stmt, "s", $nimLama);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$dataLama = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$dataLama) {
    die("Data mahasiswa tidak ditemukan.");
}

$avatarLama = $dataLama["avatar"] ?? "";

// Memeriksa apakah NIM baru sudah digunakan mahasiswa lain
$stmt = mysqli_prepare(
    $conn,
    "SELECT NIM FROM nilaimahasiswa
     WHERE NIM = ? AND NIM <> ?"
);

mysqli_stmt_bind_param($stmt, "ss", $nimBaru, $nimLama);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_close($stmt);
    die("NIM baru sudah digunakan mahasiswa lain. Silakan gunakan NIM berbeda.");
}

mysqli_stmt_close($stmt);

// Menyiapkan folder avatar
$folder = __DIR__ . "/uploads/";

if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
    die("Folder uploads gagal dibuat.");
}

// Secara default, gunakan foto lama
$avatarBaru = $avatarLama;
$fileAvatarBaruDiunggah = false;

// Memproses foto baru jika dipilih
if (
    isset($_FILES["avatar"]) &&
    $_FILES["avatar"]["error"] !== UPLOAD_ERR_NO_FILE
) {
    if ($_FILES["avatar"]["error"] !== UPLOAD_ERR_OK) {
        die("Upload foto gagal. Periksa ukuran dan pengaturan upload PHP.");
    }

    if ($_FILES["avatar"]["size"] > 2 * 1024 * 1024) {
        die("Ukuran foto maksimal 2 MB.");
    }

    $tmp = $_FILES["avatar"]["tmp_name"];
    $info = getimagesize($tmp);

    if ($info === false) {
        die("File yang diunggah bukan gambar yang valid.");
    }

    $ekstensi = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];

    if (!isset($ekstensi[$info["mime"]])) {
        die("Foto harus berformat JPG, PNG, atau WEBP.");
    }

    $avatarBaru = bin2hex(random_bytes(16))
                . "." . $ekstensi[$info["mime"]];

    if (!move_uploaded_file($tmp, $folder . $avatarBaru)) {
        die("Foto baru gagal disimpan.");
    }

    $fileAvatarBaruDiunggah = true;
}

// Memperbarui data berdasarkan NIM lama
$sql = "UPDATE nilaimahasiswa
        SET NIM = ?,
            NamaMahasiswa = ?,
            NilaiAkhir = ?,
            NilaiHuruf = ?,
            Predikat = ?,
            avatar = ?,
            modified_date = NOW()
        WHERE NIM = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    if ($fileAvatarBaruDiunggah) {
        unlink($folder . $avatarBaru);
    }

    die("Gagal menyiapkan query: " .
        htmlspecialchars(mysqli_error($conn), ENT_QUOTES, "UTF-8"));
}

mysqli_stmt_bind_param(
    $stmt,
    "ssdssss",
    $nimBaru,
    $nama,
    $nilai,
    $huruf,
    $predikat,
    $avatarBaru,
    $nimLama
);

if (mysqli_stmt_execute($stmt)) {
    $berhasil = mysqli_stmt_affected_rows($stmt) >= 0;
    mysqli_stmt_close($stmt);

    if ($berhasil) {
        // Hapus foto lama hanya setelah update berhasil
        if (
            $fileAvatarBaruDiunggah &&
            $avatarLama !== "" &&
            basename($avatarLama) === $avatarLama &&
            is_file($folder . $avatarLama)
        ) {
            unlink($folder . $avatarLama);
        }

        header("Location: bacanilai.php");
        exit;
    }
} else {
    $error = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);

    // Hapus foto baru jika penyimpanan gagal
    if ($fileAvatarBaruDiunggah && is_file($folder . $avatarBaru)) {
        unlink($folder . $avatarBaru);
    }

    die("Gagal memperbarui data: " .
        htmlspecialchars($error, ENT_QUOTES, "UTF-8"));
}
?>