<?php
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Data Nilai</title>

    <link rel="stylesheet" href="./css/adminlte.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body { background: #343a40; color: #f1f5f9; }
        .card, .card-header, .card-body, .card-footer {
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
        .preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            display: none;
            margin-top: 12px;
        }
    </style>
</head>

<body>
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 600px">
        <div class="card-header">
            <h3 class="card-title">Tambah Data Nilai Mahasiswa</h3>
        </div>

        <form action="prosesnilai.php"
              method="post"
              enctype="multipart/form-data">

            <div class="card-body">
                <div class="mb-3">
                    <label for="nim" class="form-label">NIM</label>
                    <input type="text" id="nim" name="nim"
                           class="form-control" maxlength="20" required>
                </div>

                <div class="mb-3">
                    <label for="namamahasiswa" class="form-label">
                        Nama Lengkap
                    </label>
                    <input type="text" id="namamahasiswa"
                           name="namamahasiswa"
                           class="form-control" maxlength="100" required>
                </div>

                <div class="mb-3">
                    <label for="nilaiakhir" class="form-label">
                        Nilai Akhir
                    </label>
                    <input type="number" id="nilaiakhir"
                           name="nilaiakhir"
                           class="form-control"
                           min="0" max="100" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="avatar" class="form-label">
                        Foto Avatar (opsional)
                    </label>
                    <input type="file" id="avatar" name="avatar"
                           class="form-control"
                           accept="image/jpeg,image/png,image/webp">
                    <small class="text-secondary">
                        Format JPG, PNG, atau WEBP. Maksimal 2 MB.
                    </small>
                    <img id="preview" class="preview" alt="Pratinjau avatar">
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Data
                </button>
                <a href="bacanilai.php" class="btn btn-secondary">
                    Kembali
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