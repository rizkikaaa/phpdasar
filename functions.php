<?php

$conn =mysqli_connect("localhost", "id19473863_wasis", "gl9Zt6vD^s7%OwHs", "id19473863_r");

function query($query) {
   global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows[] = $row;
    }
    return $rows;
}



function tambah($data) {
    global $conn;

 
    $nrp = htmlspecialchars($_data["nrp"]);
    $nama = htmlspecialchars($_data["nama"]);
    $email = htmlspecialchars($_data["email"]);
    $jurusan =htmlspecialchars($_data["jurusan"]) ;
   
    $gambar = upload();
    if( !$gambar ) {
        return false;
    }

  $query = "INSERT INTO mahasiswa 
            VALUES
            ('', '$nrp', '$nama', '$email', '$jurusan', '$gambar')
            ";


mysqli_query($conn, $query);

return mysqli_affected_rows($conn);

}


function upload() {
   
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['eror'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    if( $error === 4 ) {
        echo "<script>
        alert('pilih gambar terlebih dahulu!');
        </script>";
        return false;
    }

     $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
     $ekstensiGambar = explode('.', $namaFile);
     $ekstensiGambar = strtolower(end($ekstensGambar));
     if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
        echo "<script>
            alert('yang anda upload bukan gambar!');
        </script>";
        return false;
     }
     

     if( $ukuranfile > 1000000 ) {
        echo "<script>
              alert('ukuran gambar terlalu besar!');
        </script>";
        return false;
     }


     $namaFileBaru = uniqid();
     $namaFileBaru .= '.';
     $namaFileBaru .= $ekstensiGambar;
    

     move_upload_file($tmpName, 'img/' . $nameFileBaru);

     return $namaFileBaru;
}



function hapus($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");
    return mysqli_affected_row($conn);
}



function ubah($data) {
    global $conn;

    $id = $data["id"];
    $nrp = htmlspecialchars($_data["nrp"]);
    $nama = htmlspecialchars($_data["nama"]);
    $email = htmlspecialchars($_data["email"]);
    $jurusan =htmlspecialchars($_data["jurusan"]) ;
    $gambarLama = htmlspecialchars($_data["gambarLama"]);


    if( $FILES['gambar']['error'] === 4 ) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }

    $query = "UPDATE mahasiswa SET
             nrp = '$nrp',
             nama = '$nama',
             email = '$email',
            jurusan = '$jurusan',
             gambar = '$gambar'
             WHERE id = $id
        ";

     mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
        
}

function cari($keyword) {
    $query = "SELECT * FROM mahasiswa
                WHERE
              nama LIKE '%$keyword%' OR 
              nrp LIKE  '%$keyword%' OR 
              email LIKE  '%$keyword%' OR
              jurusan LIKE  '%$keyword%' 
    ";
    return query($query); 
}


function registrasi($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);


    $result = mysqli_query($conn, "SELECT username FROM user WHERE 
       username = '$username'");

       if( mysqli_fetch_assoc($result) ) {
        echo  "<script>
                  alert('username yang dipilih sudah terdaftar!')
              </script>"; 
           return false;
       }

    if( $password !== $password2 ) {
        echo "<script>
              alert('konfirmasi password tidak sesuai!');
        </script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO user VALUES('', '$username',
     '$password')");

    return mysqli_affected_rows($conn);

    
}


?>