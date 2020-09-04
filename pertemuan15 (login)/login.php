<?php 
    require 'functions.php';

    if (isset($_POST["login"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // querynya ditampung dulu di variabel dalam hal ini $result
        // jangan lupa nama databasenya users bukan user
        $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

        // cek username
        // mysqli_num_rows untuk hitung ada berapa baris yang dikembalikan oleh fungsi SELECT dalam variabel $result. Kalau ketemu pasti nilainya 1 = true DAN kalau tidak ada maka nilainya 0 = false
        if(mysqli_num_rows($result) === 1) {
            // cek password
            // Data di database diambil / ditampung dulu di variabel $row
            $row = mysqli_fetch_assoc($result);
            // password_verify untuk mengecek password yg ditulis sama dengan yang di database
            // parameter pertama adalah yang ditulis oleh user
            // karena DATA di database sudah ditampung di var $row maka parameter kedua diambil dari panampungan DATA di $row
            if (password_verify($password, $row['password'])) {
                header("Location: index.php");
                exit;
            }
        }
        // error ini untuk beri pesan kesalahan jadi var nya dibawah
        $error = true;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>

    <style>
        label,button {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Halaman Login</h1>

    <?php if(isset($error)) : ?>
        <p style="color: red; font-style: italic;"> Username / Password salah</p>
    <?php endif ?>

    <form action="" method="post">
        <ul>
            <li>
                <label for="username">Username</label>
                <input type="text" name="username" id="username">
            </li>
            <li>
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </li>
            <li>
                <button type="submit" name="login">Login</button>
            </li>
        </ul>
    </form>
</body>
</html>