<?php
session_start();

    if(!isset($_SESSION["login"])) {
        header("Location: login.php");
        exit;
    }

// BELAJAR PHP untuk pemula 13.Update
// koneksi ke DBMS
require "functions.php";

// ambil data di URL
$id = $_GET["id"];

// querydata mahasiswa berdasarkan id
// angka 0 adalah query array indeks luarnya yaitu ke 0 bisa di lihat di web browser dan view page source
$mhs = query("SELECT * FROM mahasiswa WHERE id = $id")[0];

// untuk cek apakah berhasil ambil data berdasar id bisa pakai var_dump
// var_dump ($mhs["nrp"]);

// cek apakah tombol submit sudah ditekan atau belum
    if (isset ($_POST["submit"])) {

// cek apakah data berhasil ditambahkan atau tidak
        if ( ubah($_POST) > 0){
            // echo "Data berhasil ditambahkan";
            echo "
                <script>
                    alert ('data berhasil diubah');
                    document.location.href = 'index.php';
                </script>
            ";
        } else {
            // echo"Data GAGAL ditambahkan";
            echo "<script>
                    alert ('data gagal diubah');
                    document.location.href = 'index.php';
                </script>
                ";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ubah data mahasiswa</title>
</head>
<body>
    <h1>Ubah Data Mahasiswa</h1>
    <form action="" method="post" enctype="multipart/form-data">

    <!-- untuk mengambil id dan menyembunyikannya spy tidak diubah orang lain -->
    <input type="hidden" name="id" value="<?= $mhs["id"];?>" >
    <input type="hidden" name="gambarLama" value="<?= $mhs["gambar"];?>" >

    <ul>
        <li>
            <label for="nama">Nama :</label>
            <input type="text" name="nama" required value="<?= $mhs["nama"];?>">
            <!-- required untuk form harus diisi / tidk boleh kosong -->
        </li>
        <li>
            <label for="nrp">NRP :</label>
            <input type="text" name="nrp" required value="<?= $mhs["nrp"];?>">
        </li>
        <li>
            <label for="email">Email :</label>
            <input type="text" name="email" required value="<?= $mhs["email"];?>">
        </li>
        <li>
            <label for="jurusan">Jurusan :</label>
            <input type="text" name="jurusan" required value="<?= $mhs["jurusan"];?>">
        </li>
        <li>
            <label for="gambar">Gambar :</label>
            <br>
            <img src="img/<?= $mhs['gambar'];?>" width="100">
            <br>
            <input type="file" name="gambar" id="gambar">
        </li>
        <button type="submit" name="submit">Ubah Data</button>
    </ul>
    </form>
</body>
</html>