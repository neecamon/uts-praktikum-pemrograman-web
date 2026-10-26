<?php
require_once __DIR__ . "/connect.php";

function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        "UTF-8"
    );
}

// Mengambil NIM lama dari URL
$nim = trim($_GET["nim"] ?? "");

if ($nim === "") {
    header("Location: bacanilaimhsw.php");
    exit;
}

// Mengambil data mahasiswa
$sql = "SELECT * FROM nilaimahasiswa WHERE NIM = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $nim);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Data mahasiswa tidak ditemukan.");
}

// Memeriksa avatar lama
$avatarLama = $data["avatar"] ?? "";
$foto = "";

if (
    $avatarLama !== "" &&
    is_file(__DIR__ . "/uploads/" . basename($avatarLama))
) {
    $foto = "uploads/" . basename($avatarLama);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Nilai Mahasiswa</title>

    <link rel="stylesheet" href="./css/adminlte.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #343a40;
            color: #f1f5f9;
        }

        .card,
        .card-header,
        .card-body,
        .card-footer {
            background: #3d4249;
            color: #f1f5f9;
            border-color: #50565e;
        }

        .form-control {
            background: #343a40;
            color: white;
            border-color: #606770;
        }

        .form-control:focus {
            background: #343a40;
            color: white;
        }

        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 12px;
            background: #d9f0ff;
        }
    </style>
</head>

<body>
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 600px">

        <div class="card-header">
            <h3 class="card-title">Edit Nilai Mahasiswa</h3>
        </div>

        <form action="updatenilai.php"
              method="post"
              enctype="multipart/form-data">

            <div class="card-body">

                <!-- NIM lama digunakan untuk mencari data -->
                <input type="hidden"
                       name="nim_lama"
                       value="<?= e($data['NIM']) ?>">

                <div class="mb-3">
                    <label for="nim_baru" class="form-label">
                        NIM
                    </label>

                    <input type="text"
                           id="nim_baru"
                           name="nim_baru"
                           class="form-control"
                           maxlength="20"
                           value="<?= e($data['NIM']) ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label for="namamahasiswa" class="form-label">
                        Nama Lengkap
                    </label>

                    <input type="text"
                           id="namamahasiswa"
                           name="namamahasiswa"
                           class="form-control"
                           maxlength="100"
                           value="<?= e($data['NamaMahasiswa']) ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label for="nilaiakhir" class="form-label">
                        Nilai Akhir
                    </label>

                    <input type="number"
                           id="nilaiakhir"
                           name="nilaiakhir"
                           class="form-control"
                           min="0"
                           max="100"
                           step="0.01"
                           value="<?= e($data['NilaiAkhir']) ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Avatar</label>

                    <?php if ($foto !== ""): ?>
                        <div>
                            <img src="<?= e($foto) ?>"
                                 class="avatar-preview"
                                 alt="Avatar mahasiswa">
                        </div>
                    <?php else: ?>
                        <p class="text-secondary">
                            Belum ada foto. Kamu bisa mengunggah foto baru.
                        </p>
                    <?php endif; ?>

                    <input type="file"
                           id="avatar"
                           name="avatar"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp">

                    <small class="text-secondary">
                        Format JPG, PNG, atau WEBP. Maksimal 2 MB.
                        Kosongkan jika tidak ingin mengganti foto.
                    </small>

                    <div>
                        <img id="preview"
                             class="avatar-preview"
                             style="display: none"
                             alt="Pratinjau foto baru">
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i>
                    Simpan Perubahan
                </button>

                <a href="bacanilaimhsw.php"
                   class="btn btn-secondary">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<script>
document.getElementById("avatar").addEventListener("change", function() {
    const preview = document.getElementById("preview");
    const file = this.files[0];

    if (file && file.type.startsWith("image/")) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = "block";
    } else {
        preview.removeAttribute("src");
        preview.style.display = "none";
    }
});
</script>

</body>
</html>