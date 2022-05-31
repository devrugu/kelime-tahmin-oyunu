
<?php
    //local veritabanı bağlantısı
    $dbServername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "kelime_tahmin_oyunu";

    $conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

    if (!$conn) {
        die("Veritabani ile baglanti kurulamadi\nError code: ".mysqli_connect_error());
    }
?>