<?php

class Encryption  {

    private $CRYPT_CKEY = "98qW5L4DnS11qYj98P5kL1P6";
    private $CRYPT_CIV = "HBq6Jl4q";
    private $CRYPT_CBIT_CHECK = 32;

    public function __construct() {
        $this->CRYPT_CKEY = sfConfig::get("app_cryptkey");
    }

    public function getCRYPT_CKEY() {
        return $this->CRYPT_CKEY;
    }

    public function setCRYPT_CKEY($CRYPT_CKEY) {
        $this->CRYPT_CKEY = $CRYPT_CKEY;
    }

    public function getCRYPT_CIV() {
        return $this->CRYPT_CIV;
    }

    public function setCRYPT_CIV($CRYPT_CIV) {
        $this->CRYPT_CIV = $CRYPT_CIV;
    }

    public function getCRYPT_CBIT_CHECK() {
        return $this->CRYPT_CBIT_CHECK;
    }

    public function setCRYPT_CBIT_CHECK($CRYPT_CBIT_CHECK) {
        $this->CRYPT_CBIT_CHECK = $CRYPT_CBIT_CHECK;
    }

    public function encrypt($text) {
		echo '1) '.$text.'<br>';
        $text_num = str_split($text, $this->CRYPT_CBIT_CHECK);
        echo '2) '.$text_num.'<br>';
        $text_num = $this->CRYPT_CBIT_CHECK - strlen($text_num[count($text_num) - 1]);
        echo '3) '.$text_num.'<br>';

        for ($i = 0; $i < $text_num; $i++)
            $text = $text . chr($text_num);
        echo '4) '.chr($text_num).'<br>';
        echo '5) '.$text.'<br>';

        $cipher = mcrypt_module_open(MCRYPT_TRIPLEDES, '', 'cbc', '');
        mcrypt_generic_init($cipher, $this->CRYPT_CKEY, $this->CRYPT_CIV);

        $decrypted = mcrypt_generic($cipher, $text);
        mcrypt_generic_deinit($cipher);

        return base64_encode($decrypted);
    }

    public function decrypt($encrypted_text) {
        $cipher = mcrypt_module_open(MCRYPT_TRIPLEDES, '', 'cbc', '');
        mcrypt_generic_init($cipher, $this->CRYPT_CKEY, $this->CRYPT_CIV);

        $decrypted = mdecrypt_generic($cipher, base64_decode($encrypted_text));
        mcrypt_generic_deinit($cipher);

        $last_char = substr($decrypted, -1);

        for ($i = 0; $i < ($this->CRYPT_CBIT_CHECK - 1); $i++) {
            if (chr($i) == $last_char) {
                $decrypted = substr($decrypted, 0, strlen($decrypted) - $i);
                break;
            }
        }

        return $decrypted;
    }

}

?>
