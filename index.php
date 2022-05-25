<?php
    require 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

</head>
<body>
    <?php
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
            <input type="text" name="kullanici_kelimesi" id="kullanici_kelimesi" pattern="[a-z]*" title="sadece ingilizce karakterler olmalı" autofocus><br><br>
            <div class="dene">
            <button type="submit" name="kelime_submit">Dene</button>
            </div>
            </form>
            </div>

            

            <?php
            echo $random_kelime; //hile
            
            
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
                        if (karakter_tekrari_var_mi($kullanici_kelimesi)) {
                            echo '<div class="hata">
                            Girilen kelimenin her harfi farklı olmalıdır!
                            </div>';
                        }
                        else {
                            if ($kullanici_kelimesi == $random_kelime) {
                                $harf_ve_yeri_dogru = strlen($kullanici_kelimesi);
                                echo '<div class="success">
                                '.$harf_ve_yeri_dogru.'+'.$sadece_harf_dogru.'-==>Oyunu kazandınız
                                </div>';
                                
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
                            echo '<div class="uyari">
                            girilen kelime==>"'.$kullanici_kelimesi.'"==>('.$harf_ve_yeri_dogru.'+'.$sadece_harf_dogru.'-)
                            </div>';
                            
                        }
                    }
                } 
            }
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>


</html>