<?php
require_once __DIR__ . "/connect.php";

// Mengambil seluruh data dengan urutan yang konsisten
$sql = "SELECT * FROM nilaimahasiswa ORDER BY NIM ASC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Gagal mengambil data: " . mysqli_error($conn));
}

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        "UTF-8"
    );
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rekap Nilai Mahasiswa</title>

    <link rel="stylesheet" href="./css/adminlte.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #343a40;
            color: #f1f5f9;
        }

        .app-wrapper,
        .app-main,
        .app-content-header,
        .app-content,
        .app-footer {
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

        .table {
            --bs-table-bg: transparent;
            --bs-table-color: #f1f5f9;
            --bs-table-border-color: #50565e;
            margin-bottom: 0;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .form-control,
        .form-select {
            background: #343a40;
            color: white;
            border-color: #606770;
        }

        .form-control:focus,
        .form-select:focus {
            background: #343a40;
            color: white;
            border-color: #0d9bf0;
            box-shadow: 0 0 0 0.15rem
                rgba(13, 155, 240, 0.2);
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        .form-select option {
            background: #343a40;
            color: white;
        }

        /* Breadcrumb kanan atas */
        .breadcrumb-item a {
            color: #0d9bf0 !important;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #ffffff !important;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: #adb5bd !important;
        }

        /* Kotak pencarian */
        .search-box {
            position: relative;
            width: 240px;
        }

        .search-box .form-control {
            padding-left: 34px;
        }

        .search-box .search-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            pointer-events: none;
        }

        /* Avatar */
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
            margin-right: 8px;
            background: #d9f0ff;
            color: #0878b9;
        }

        /* Pagination */
        .pagination .page-link {
            background: #3d4249;
            color: white;
            border-color: #50565e;
        }

        .pagination .page-item.active .page-link {
            background: #0d9bf0;
            border-color: #0d9bf0;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #888;
            background: #343a40;
        }

        .app-footer {
            padding: 16px;
            border-top: 1px solid #50565e;
        }

        @media (max-width: 768px) {
            .search-box {
                width: 100%;
            }

            .toolbar {
                width: 100%;
            }

            .toolbar .form-select,
            .toolbar .btn {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>

<div class="app-wrapper">

    <main class="app-main">

        <!-- Judul halaman dan breadcrumb -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">

                    <div class="col-sm-6">
                        <h1 class="fs-3">
                            Rekap Nilai Mahasiswa
                        </h1>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item">
                                <a href="adminlte.php">Beranda</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Nilai
                            </li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <!-- Tabel rekap nilai -->
        <div class="app-content">
            <div class="container-fluid">

                <div class="card">

                    <div class="card-header">
                        <div class="row g-2 align-items-center">

                            <div class="col-md-3">
                                <h3 class="card-title">
                                    Direktori Nilai
                                </h3>
                            </div>

                            <div class="col-md-9">
                                <div class="toolbar d-flex flex-wrap
                                            justify-content-md-end
                                            align-items-center gap-2">

                                    <!-- Pencarian -->
                                    <div class="search-box">
                                        <i class="bi bi-search search-icon"></i>

                                        <input type="search"
                                               id="cariNama"
                                               class="form-control form-control-sm"
                                               placeholder="Cari berdasarkan nama">
                                    </div>

                                    <!-- Filter predikat -->
                                    <select id="filterPredikat"
                                            class="form-select form-select-sm"
                                            style="width:180px">

                                        <option value="">
                                            Semua Predikat
                                        </option>

                                        <option value="Sangat Memuaskan">
                                            Sangat Memuaskan
                                        </option>

                                        <option value="Memuaskan">
                                            Memuaskan
                                        </option>

                                        <option value="Cukup">
                                            Cukup
                                        </option>

                                        <option value="Kurang">
                                            Kurang
                                        </option>

                                        <option value="Tidak Lulus">
                                            Tidak Lulus
                                        </option>
                                    </select>

                                    <!-- Sorting -->
                                    <select id="sortingNilai"
                                            class="form-select form-select-sm"
                                            style="width:180px">

                                        <option value="default">
                                            Urutan NIM
                                        </option>

                                        <option value="desc">
                                            Nilai Tertinggi
                                        </option>

                                        <option value="asc">
                                            Nilai Terendah
                                        </option>
                                    </select>

                                    <!-- Tambah data -->
                                    <a href="inputnilai.php"
                                       class="btn btn-primary btn-sm">

                                        <i class="bi bi-person-plus-fill"></i>
                                        Tambah Data Nilai
                                    </a>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover
                                          align-middle mb-0"
                                   id="tabelNilai">

                                <thead>
                                    <tr>
                                        <th class="ps-3">NIM</th>
                                        <th>Nama Lengkap</th>
                                        <th>Nilai Akhir</th>
                                        <th>Nilai Huruf</th>
                                        <th>Predikat</th>
                                        <th>Tgl Dibuat</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody id="dataNilai">

                                <?php foreach ($data as $row): ?>

                                    <tr>
                                        <!-- NIM -->
                                        <td class="ps-3">
                                            <?= e($row["NIM"]) ?>
                                        </td>

                                        <!-- Nama dan avatar -->
                                        <td>
                                            <?php
                                            $avatar = $row["avatar"] ?? "";
                                            $namaFile = basename($avatar);

                                            $lokasiFoto = __DIR__
                                                . "/uploads/"
                                                . $namaFile;

                                            $fotoValid = (
                                                $avatar !== "" &&
                                                $namaFile === $avatar &&
                                                is_file($lokasiFoto)
                                            );
                                            ?>

                                            <?php if ($fotoValid): ?>

                                                <img
                                                    src="<?= e("uploads/" . $namaFile) ?>"
                                                    class="avatar"
                                                    alt="Avatar mahasiswa">

                                            <?php else: ?>

                                                <span class="avatar">
                                                    <i class="bi bi-person-fill"></i>
                                                </span>

                                            <?php endif; ?>

                                            <?= e($row["NamaMahasiswa"]) ?>
                                        </td>

                                        <!-- Nilai -->
                                        <td>
                                            <?= e($row["NilaiAkhir"]) ?>
                                        </td>

                                        <!-- Nilai huruf -->
                                        <td>
                                            <?= e($row["NilaiHuruf"]) ?>
                                        </td>

                                        <!-- Predikat -->
                                        <td>
                                            <?= e($row["Predikat"]) ?>
                                        </td>

                                        <!-- Tanggal dibuat -->
                                        <td>
                                            <?= e($row["created_date"] ?? "-") ?>
                                        </td>

                                        <!-- Tombol aksi -->
                                        <td class="text-center">

                                            <a href="formeditnilai.php?nim=<?= urlencode($row["NIM"]) ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit data">

                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="hapusnilai.php"
                                                  method="post"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus data mahasiswa ini?')">

                                                <input type="hidden"
                                                       name="nim"
                                                       value="<?= e($row["NIM"]) ?>">

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Hapus data">

                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                                </tbody>
                            </table>

                        </div>
                    </div>

                    <!-- Informasi data dan pagination -->
                    <div class="card-footer d-flex
                                justify-content-between
                                align-items-center
                                flex-wrap gap-2">

                        <small id="infoData">
                            Menampilkan data...
                        </small>

                        <ul class="pagination pagination-sm mb-0"
                            id="pagination">
                        </ul>

                    </div>

                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="app-footer">

            <strong>Hak cipta &copy; 2026 Prodi TI.</strong>
            Seluruh hak cipta dilindungi.

            <span class="float-end">
                Pemrograman Web - Semester 5
            </span>

        </footer>

    </main>
</div>

<script>
(function () {
    const tbody = document.getElementById("dataNilai");
    const cari = document.getElementById("cariNama");
    const filter = document.getElementById("filterPredikat");
    const sorting = document.getElementById("sortingNilai");
    const pagination = document.getElementById("pagination");
    const info = document.getElementById("infoData");

    /*
     * Simpan referensi seluruh baris asli satu kali.
     * Jangan mengambil ulang baris dari tbody pada setiap render.
     */
    const semuaBaris = Array.from(tbody.querySelectorAll("tr"));

    const perHalaman = 5;
    let halaman = 1;

    function buatTombol(teks, nomor, aktif, nonaktif) {
        const li = document.createElement("li");

        li.className = "page-item";

        if (aktif) {
            li.classList.add("active");
        }

        if (nonaktif) {
            li.classList.add("disabled");
        }

        const link = document.createElement("a");

        link.className = "page-link";
        link.href = "#";
        link.textContent = teks;

        if (!nonaktif) {
            link.addEventListener("click", function (event) {
                event.preventDefault();

                if (nomor !== halaman) {
                    halaman = nomor;
                    tampilkanData();
                }
            });
        }

        li.appendChild(link);
        pagination.appendChild(li);
    }

    function tampilkanData() {
        const kataKunci = cari.value.trim().toLowerCase();
        const predikatDipilih = filter.value.trim().toLowerCase();
        const urutan = sorting.value;

        /*
         * 1. Filter data.
         */
        let hasil = semuaBaris.filter(function (row) {
            const nama = row.cells[1].innerText
                .trim()
                .toLowerCase();

            const predikat = row.cells[4].innerText
                .trim()
                .toLowerCase();

            const cocokNama = nama.includes(kataKunci);

            const cocokPredikat =
                predikatDipilih === "" ||
                predikat === predikatDipilih;

            return cocokNama && cocokPredikat;
        });

        /*
         * 2. Sorting dengan NIM sebagai urutan
         * tambahan jika nilai mahasiswa sama.
         */
        hasil.sort(function (a, b) {
            const nimA = a.cells[0].innerText.trim();
            const nimB = b.cells[0].innerText.trim();

            const nilaiA = Number(
                a.cells[2].innerText.trim()
            );

            const nilaiB = Number(
                b.cells[2].innerText.trim()
            );

            if (urutan === "desc") {
                return (nilaiB - nilaiA) ||
                    nimA.localeCompare(nimB);
            }

            if (urutan === "asc") {
                return (nilaiA - nilaiB) ||
                    nimA.localeCompare(nimB);
            }

            return nimA.localeCompare(nimB);
        });

        /*
         * 3. Hitung jumlah halaman.
         */
        const totalData = hasil.length;

        const totalHalaman = Math.max(
            1,
            Math.ceil(totalData / perHalaman)
        );

        if (halaman > totalHalaman) {
            halaman = totalHalaman;
        }

        /*
         * 4. Ambil hanya data untuk halaman aktif.
         */
        const mulai = (halaman - 1) * perHalaman;

        const dataHalaman = hasil.slice(
            mulai,
            mulai + perHalaman
        );

        /*
         * 5. Kosongkan isi tbody sebelum merender.
         * Referensi semuaBaris tetap tersimpan.
         * Setiap baris hanya dimasukkan sekali per render.
         */
        tbody.replaceChildren();

        if (dataHalaman.length > 0) {
            dataHalaman.forEach(function (row) {
                tbody.appendChild(row);
            });
        } else {
            const rowKosong = document.createElement("tr");
            const cellKosong = document.createElement("td");

            cellKosong.colSpan = 7;
            cellKosong.className = "text-center p-4";
            cellKosong.textContent = "Data tidak ditemukan.";

            rowKosong.appendChild(cellKosong);
            tbody.appendChild(rowKosong);
        }

        /*
         * 6. Perbarui informasi jumlah data.
         */
        const awal = totalData > 0 ? mulai + 1 : 0;

        const akhir = Math.min(
            mulai + perHalaman,
            totalData
        );

        info.textContent =
            "Menampilkan " + awal +
            " sampai " + akhir +
            " dari " + totalData + " data";

        /*
         * 7. Bangun ulang tombol pagination.
         */
        pagination.replaceChildren();

        buatTombol(
            "‹",
            halaman - 1,
            false,
            halaman === 1
        );

        for (let i = 1; i <= totalHalaman; i++) {
            buatTombol(
                String(i),
                i,
                i === halaman,
                false
            );
        }

        buatTombol(
            "›",
            halaman + 1,
            false,
            halaman === totalHalaman
        );
    }

    /*
     * Jika pencarian atau filter berubah,
     * kembalikan ke halaman pertama.
     */
    cari.addEventListener("input", function () {
        halaman = 1;
        tampilkanData();
    });

    filter.addEventListener("change", function () {
        halaman = 1;
        tampilkanData();
    });

    sorting.addEventListener("change", function () {
        halaman = 1;
        tampilkanData();
    });

    // Render awal
    tampilkanData();
})();
</script>

</body>
</html>