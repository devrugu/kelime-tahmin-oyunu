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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timecircles/1.5.3/TimeCircles.min.js" integrity="sha512-FofOhk0jW4BYQ6CFM9iJutqL2qLk6hjZ9YrS2/OnkqkD5V4HFnhTNIFSAhzP3x//AD5OzVMO8dayImv06fq0jA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
        if (!isset($_POST['basla_submit']) && !isset($_POST['kelime_submit'])) {
            if (isset($_GET['success'])) {
                if (isset($_GET['success']) == "oyunKazanildi") {
                    echo '<p>girilen kelime==>"'.$_GET['random_kelime'].'"==>('.$_GET['harf_ve_yeri_dogru'].'+)  ==>  Tebrikler. Oyunu kazandınız.</p>';

                    echo '<form action="index.php" method="post">';
                        echo '<button type="submit">Tekrar Oyna</button>';
                    echo '</form>';
                } 
            }
            else {
                ?>
                <form action="index.php" method="post">
                    
                    <label for="zorluk">Zorluk seçiniz</label>
                    <select name="zorluk" id="zorluk">
                        <option value="4">KOLAY</option>
                        <option value="6">ORTA</option>
                        <option value="8">ZOR</option>
                        <option value="10">ÇOK ZOR</option>
                    </select>
                    <button type="submit" name="basla_submit">Oyuna başla</button>
                </form>
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

            <form action="index.php" method="post">
            <label for="kullanici_kelimesi">Kelime giriniz:<sub>(<?php echo strlen($random_kelime); ?> harfli ve her harfi farklı olmalı)</sub></label>
            <input type="hidden" name="random_kelime" value="<?php echo $random_kelime; ?>">
            <input type="text" name="kullanici_kelimesi" id="kullanici_kelimesi" pattern="[a-z]*" title="sadece ingilizce karakterler olmalı" autofocus>
            <button type="submit" name="kelime_submit">Dene</button>
            </form>

            <form action="index.php" method="post">
                <button type="submit" name="tekrar_kelime_olustur_submit">Oyuna sıfırdan başla</button>
            </form>

            <?php
            //echo $random_kelime; //hile
            
            
            if (isset($_POST['kelime_submit'])) {
        
                $kullanici_kelimesi = $_POST['kullanici_kelimesi'];

                $harf_ve_yeri_dogru = 0;
                $sadece_harf_dogru = 0;

                if (empty($kullanici_kelimesi)) {
                    echo '<p>Denemek için bir kelime girmediniz!</p>';
                }
                else {
                    if (strlen($random_kelime) != strlen($kullanici_kelimesi)) {
                        echo '<p>'.strlen($random_kelime).' karakter uzunluğunda bir kelime giriniz!</p>';
                    }
                    else {
                        if (karakter_tekrari_var_mi($kullanici_kelimesi)) {
                            echo '<p>Girilen kelimenin her harfi farklı olmalıdır!</p>';
                        }
                        else {
                            if ($kullanici_kelimesi == $random_kelime) {
                                $harf_ve_yeri_dogru = strlen($kullanici_kelimesi);
                                echo '<p>'.$harf_ve_yeri_dogru.'+'.$sadece_harf_dogru.'-==>Oyunu kazandınız</p>';
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
                            echo '<p>girilen kelime==>"'.$kullanici_kelimesi.'"==>('.$harf_ve_yeri_dogru.'+'.$sadece_harf_dogru.'-)</p>';
                        }
                    }
                } 
            }
        }
    ?>
</body>

<!-- <div data-timer="<?php //echo '900' ; ?>" id="cd_timer" class="div1"></div>

    <script>
        $(function () {
        $("#cd_timer").TimeCircles({animation_interval: "ticks"});
        });
    </script>   -->
</html>