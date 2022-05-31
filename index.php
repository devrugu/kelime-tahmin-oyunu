<?php
    require 'functions.php';
    require 'dbh.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

</head>
<body>
    <?php
        if (isset($_POST['tekrar_kelime_olustur_submit'])) {
            $sql = "DELETE FROM tahminler";
            mysqli_query($conn, $sql);
        }

        if (!isset($_POST['basla_submit']) && !isset($_POST['kelime_submit'])) {
            if (isset($_GET['success'])) {
                if (isset($_GET['success']) == "oyunKazanildi") {
                    echo '<div class="success">
                    girilen kelime==>"'.$_GET['random_kelime'].'"==>('.$_GET['harf_ve_yeri_dogru'].'+)  ==>  Tebrikler. Oyunu kazandınız.
                                </div>';
                    
                    echo '<div class="tekrar-oyna">';
                    echo '<form action="index.php" method="post">';
                        echo '<button type="submit">Tekrar Oyna</button>';
                    echo '</form>';
                    echo '</div>';
                } 
            }
            else {
                $sql = "DELETE FROM tahminler";
                mysqli_query($conn, $sql);
                ?>
                <div class="karsilama">
                <h1>Kelime Tahmin Oyununa Hoş geldiniz</h1>
                </div>
                <div class="zorluk">
                    
                <form action="index.php" method="post">
                    
                    <label for="zorluk" class="label">Zorluk seçiniz</label>
                    <select name="zorluk" id="zorluk">
                        <option value="4">KOLAY</option>
                        <option value="6">ORTA</option>
                        <option value="8">ZOR</option>
                        <option value="10">ÇOK ZOR</option>
                    </select>
                    
                    <button type="submit" name="basla_submit">Oyuna başla</button>
                </form>
                
                </div>
                <?php
            }
        }
        else {
            if (isset($_POST['basla_submit'])) {
                do {
                    $random_kelime = random_kelime_uret($_POST['zorluk']);
                } while (karakter_tekrari_var_mi($random_kelime));
            }
            if (isset($_POST['random_kelime'])) {
                $random_kelime = $_POST['random_kelime'];
            }
            
            ?>

            <div class="sifirdan-basla">
            <form action="index.php" method="post">
                <button type="submit" name="tekrar_kelime_olustur_submit">Oyuna sıfırdan başla</button>
            </form>
            </div>

            <div class="zorluk-derecesi">
            <h1>Şu anki Zorluk ==> <span class="badge bg-info"><?php switch (strlen($random_kelime)) {
                case '4':
                    echo 'Kolay';
                    break;
                case '6':
                    echo 'Orta';
                    break;
                case '8':
                    echo 'Zor';
                    break;
                case '10':
                    echo 'Çok Zor';
                    break;
            } ?></span></h1>
            </div>

            

            <div class="oyun">
            <form action="index.php" method="post">
            <label for="kullanici_kelimesi" class="label">Kelime giriniz:<sub class="sub">(<?php echo strlen($random_kelime); ?> harfli ve her harfi farklı olmalı)</sub></label>
            <input type="hidden" name="random_kelime" value="<?php echo $random_kelime; ?>">
            <input type="text" name="kullanici_kelimesi" id="kullanici_kelimesi" pattern="[a-z]*" title="sadece ingilizce karakterler olmalı" autofocus style="border-radius: 2em;"><br><br>
            <div class="dene">
            <button type="submit" name="kelime_submit">Dene</button>
            </div>
            </form>
            </div>

            

            <?php
            //echo $random_kelime; //hile
            
            
            if (isset($_POST['kelime_submit'])) {
        
                $kullanici_kelimesi = $_POST['kullanici_kelimesi'];

                $harf_ve_yeri_dogru = 0;
                $sadece_harf_dogru = 0;

                if (empty($kullanici_kelimesi)) {
                    echo '<div class="hata">
                    Denemek için bir kelime girmeniz lazım!
                    </div>';
                }
                else {
                    if (strlen($random_kelime) != strlen($kullanici_kelimesi)) {
                        echo '<div class="hata">
                        '.strlen($random_kelime).' karakter uzunluğunda bir kelime giriniz!
                              </div>';
                    }
                    else {
                        
                            if ($kullanici_kelimesi == $random_kelime) {
                                $harf_ve_yeri_dogru = strlen($kullanici_kelimesi);
                                echo '<div class="success">
                                '.$harf_ve_yeri_dogru.'+'.$sadece_harf_dogru.'-==>Oyunu kazandınız
                                </div>';
                                $sql = "DELETE FROM tahminler";
                                mysqli_query($conn, $sql);
                                
                                header('Location: index.php?success=oyunKazanildi&harf_ve_yeri_dogru='.$harf_ve_yeri_dogru.'&sadece_harf_dogru='.$sadece_harf_dogru.'&random_kelime='.$random_kelime);
                            }
            
                            for ($i=0; $i < strlen($kullanici_kelimesi); $i++) {
                                for ($j=0; $j < strlen($kullanici_kelimesi); $j++) {
                                    if ($kullanici_kelimesi[$i] == $random_kelime[$j]) {
                                        if ($i == $j) {
                                            $harf_ve_yeri_dogru++;
                                        }
                                        else {
                                            $sadece_harf_dogru++;
                                        }
                                    }
                                }
                            }
                            $sql = "SELECT * FROM tahminler";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                if ($row['tahmin'] == $kullanici_kelimesi) {
                                    $sql2 = 'DELETE FROM tahminler WHERE tahmin="'.$kullanici_kelimesi.'"';
                                    mysqli_query($conn, $sql2);
                                }
                            }
                            $sql = 'INSERT INTO tahminler (tahmin, harf_ve_yeri_dogru, sadece_harf_dogru) VALUES ("'.$kullanici_kelimesi.'", "'.$harf_ve_yeri_dogru.'", "'.$sadece_harf_dogru.'")';
                            mysqli_query($conn, $sql);

                            echo '<div class="uyari">
                            girilen kelime==>"'.$kullanici_kelimesi.'"==>('.$harf_ve_yeri_dogru.'+'.$sadece_harf_dogru.'-)
                            </div><br>';
                            $sql = 'SELECT * FROM tahminler;';
                            $result = mysqli_query($conn, $sql);
                            echo '<p class="uyari">Önceki tahminler</p>';
                            while ($row = mysqli_fetch_assoc($result)) {
                                if ($row['tahmin'] == $kullanici_kelimesi) {
                                    continue;
                                }
                                if (mysqli_num_rows($result) == 1) {
                                    echo '<p class="uyari">tahmin yapılmadı</p>';
                                }
                                
                                else{
                                    echo '<div class="uyari">
                                    önceki kelime==>"'.$row['tahmin'].'"==>('.$row['harf_ve_yeri_dogru'].'+'.$row['sadece_harf_dogru'].'-)
                                    </div><br>';
                                }
                            
                                
                            
                        
                            
                            }

                            
                        
                    }
                } 
            }
        }
    ?>

    
</body>


</html>