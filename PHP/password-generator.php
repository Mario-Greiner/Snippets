<?php
    function generatePassword($lenght = 16){
        $lower = "abcdefghijklmnopqrstuvwxyz";
        $upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numbers = "0123456789";
        $symbols = "!§$%&/()=?+-*[]|{}@,;:";

        $all = $lower . $upper . $numbers . $symbols;

        $pass = array();
        $alphaLength = strlen($all) - 1;
        for ($i = 0; $i < $lenght; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $all[$n];
        }
        return implode($pass);
    }

    echo generatePassword(25);
?>