<?php
    function random_kelime_uret($kelime_uzunlugu) {
        $karakterler_sessiz = 'bcdfghjklmnpqrstvwxyz';
        $karakterler_sesli = 'aeiou';
        $rastgele_kelime = '';
        $sira = 1;

        for ($i = 0; $i < $kelime_uzunlugu; $i++) {
            if ($sira == 1) {
                $indis = rand(0, strlen($karakterler_sessiz) - 1);
                $rastgele_kelime .= $karakterler_sessiz[$indis];
                $sira = 2;
            }
            elseif ($sira == 2) {
                $indis = rand(0, strlen($karakterler_sesli) - 1);
                $rastgele_kelime .= $karakterler_sesli[$indis];
                $sira = 1;
            }  
        }
        return $rastgele_kelime;
    }

    function karakter_tekrari_var_mi($kelime) {
        $tekrar_var_mi = 0;
        for ($i=0; $i < strlen($kelime); $i++) { 
            for ($j=($i+1); $j < strlen($kelime); $j++) { 
                if ($kelime[$i] == $kelime[$j]) {
                    $tekrar_var_mi = 1;
                }
            }
            if ($tekrar_var_mi == 1) {
                break;
            }
        }
        return $tekrar_var_mi;
    }


?>