 <?php
 // koneksi ke database -- URUTANNYA JANGAN SAMPAI SALAH ("host","username","password","namaDatabase")
    // karena koneksi mysqli akan dipakai terus maka dibuat sbg variabel untuk lebih cepat
    $conn = mysqli_connect("localhost","root","","phpdasar");

    
    // analoginya :
    // $result itu sebagai lemari
    // $row sebagai bajunya
    // $rows sebagai kotak kosong
    function query($query) {
        global $conn;
        $result = mysqli_query($conn, $query);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
            // $rows sebagai array baru yang isinya row + row + row
        }
        return $rows;
    }

// TAMBAH

    function tambah($data) {
        global $conn;
    // ambil data dari tiap elemen dalam form 
    // supaya tidak mudah disusupi script dari orang luar alias dihack maka ditambahkan code : htmlspecialchars()
        $nama = htmlspecialchars($data ["nama"]);
        $nrp = htmlspecialchars($data ["nrp"]);
        $email = htmlspecialchars($data ["email"]);
        $jurusan = htmlspecialchars($data ["jurusan"]);
       
// UPLOAD gambar
            $gambar = upload();
            if ( !$gambar) {
                return false;
            }

        $query = "INSERT INTO mahasiswa 
                VALUES
        -- urutannya (id -meskipun nilai kosong krn autoincrement- JANGAN SAMPAI SALAH URUTAN )
                ('', '$nama', '$nrp','$email', '$jurusan', '$gambar')
            ";
// query insert data (koneksi, query) ; untuk menampilkan pakai SELECT * FROM
        mysqli_query($conn, $query);

        return mysqli_affected_rows($conn);
    }

// FUNCTION upload :
    function upload() {
        
        $namaFile = $_FILES['gambar']['name'];
        $ukuranFile = $_FILES['gambar']['size'];
        $error = $_FILES ['gambar']['error'];
        $tmpName = $_FILES ['gambar']['tmp_name'];
    
    // Apakah tidak ada gambar yang diupload
        if ( $error === 4) {
            echo"<script>
                        alert('pilih gambar terlebih dahulu');
                    </script>";
                return false;
        }
    // cek apakah yang diupload adalah gambar
        $ekstensiGambarValid = ['jpg','jpeg','png'];
            // explode :sebuah fungsi untuk memecah string menjadi array
            // contoh : img1.png menjadi = ['img1', 'png'] 
        $ekstensiGambar = explode('.',$namaFile);
            // untuk memastikan yang diambil adalah ekstensi yang terakhir maka gunakan end
            // untuk memastikan semua memakai huruf kecil maka gunakan strtolower
        $ekstensiGambar = strtolower(end($ekstensiGambar));

        if( !in_array($ekstensiGambar, $ekstensiGambarValid)) {
            echo"<script>
                        alert('yang anda upload bukan gambar');
                    </script>";
                return false;
        }
    // cek jika ukurannya terlalu besar
        if( $ukuranFile > 1000000) {
            echo"<script>
                        alert('ukuran gambar terlalu besar');
                    </script>";
                return false;
        }

        // lolos pengecekan, gambar siap diupload
        // generate nama gambar baru
        $namaFileBaru = uniqid();
        $namaFileBaru .= '.';
        $namaFileBaru .= $ekstensiGambar;
        move_uploaded_file($tmpName, 'image/' . $namaFileBaru);
        // kenapa kok namaFileBaru direturn? supaya $gambar diidentifikasi sbg gambar
        return $namaFileBaru;
    
    }

// HAPUS

     function hapus ($id) {
        global $conn;
        mysqli_query ($conn,"DELETE FROM mahasiswa WHERE id = $id");
        
         return mysqli_affected_rows($conn);
    }

// UBAH
     
    function ubah ($data) {
        global $conn;
    // ambil data dari tiap elemen dalam form 
    // supaya tidak mudah disusupi script dari orang luar alias dihack maka ditambahkan code : htmlspecialchars()
        $id = $data["id"];
        $nama = htmlspecialchars($data ["nama"]);
        $nrp = htmlspecialchars($data ["nrp"]);
        $email = htmlspecialchars($data ["email"]);
        $jurusan = htmlspecialchars($data ["jurusan"]);
        $gambarLama = htmlspecialchars($data ["gambarLama"]);

    // cek apakah user pilih gambar baru atau tidak
        if ( $_FILES['gambar']['error'] === 4) {
            $gambar = $gambarLama;
        } else {
            $gambar = upload();
        }

        $query = "UPDATE mahasiswa SET
                nama = '$nama',
                nrp = '$nrp',
                email = '$email',
                jurusan = '$jurusan',
                gambar = '$gambar'
            WHERE id = $id
            ";
// query insert data (koneksi, query) ; untuk menampilkan pakai SELECT * FROM
        mysqli_query($conn, $query);

        return mysqli_affected_rows($conn);
    }

// function cari
    function cari($keyword) {
        $query = "SELECT * FROM mahasiswa
                    WHERE
                    nama LIKE '%$keyword%' OR
                    nrp LIKE '%$keyword%' OR
                    email LIKE '%$keyword%' OR
                    jurusan LIKE '%$keyword%' 
                ";
        return query($query);
    }

// REGISTRASI 
    function registrasi ($data) {
        global $conn;

        // strttolower untuk memaksa semua menjadi huruf kecil 
        // stripslashes untuk melarang pemakaian tanda slash /
        $username = strtolower(stripslashes($data["username"]));
        // memungkinkan user untuk memasukkan tanda kutip sebagai bagian dari passwordnya
        //  cara penulisan butuh 2 parameter :
        // https://www.w3schools.com/php/func_mysqli_real_escape_string.asp
        //  mysqli_real_escape_string(connection, escapestring)
        $password = mysqli_real_escape_string($conn, $data["password"]);
        $password2 = mysqli_real_escape_string($conn, $data["password2"]);

        // cek apakah username sudah ada atau belum 
        // querynya ditampung dulu di variabel dalam hal ini $result
        $result = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");

        // kalau sudah ada maka program akan dihentikan
        if(mysqli_fetch_assoc($result)) {
            echo "<script>
                    alert('username sudah terdaftar !')
                </script>";
            return false;
        }
        // kalau belum ada usernamenya maka program bawah dijalankan

        // cek konfirmasi password
        if ($password !== $password2) {
            echo "<script>
                    alert('konfirmasi password tidak sesuai!');
                </script>";
            return false;
        } 
        // enkripsi password
        $password = password_hash($password, PASSWORD_DEFAULT);
        // untuk cek
        // var_dump($password);die;

        // tambahkan user baru ke database
        mysqli_query($conn, "INSERT INTO users VALUES('', '$username', '$password')");

        // untuk menghasilkan nilai 1 apabila ada data yang ditambahkan ke tabel user
        return mysqli_affected_rows($conn);
    }
?>