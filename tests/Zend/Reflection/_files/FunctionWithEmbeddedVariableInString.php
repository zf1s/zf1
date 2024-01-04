<?php

function firstOne() {
    $substitute = "Testing";
    // ${var} interpolation is deprecated in PHP 8.2
    // https://php.watch/versions/8.2/$%7Bvar%7D-string-interpolation-deprecated
    // $varA = "${substitute} 123!";
    $varB = "{$substitute} 123!";
    $varC = "$substitute 123!";
    // $varD = "${substitute}";
}

function secondOne() {}
