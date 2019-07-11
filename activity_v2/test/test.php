<?php

include dirname(dirname(__FILE__)) . "/include/config.php";

$bwm = new Car("bwm", 100);
$audi = new Car("audi", 111);

echo $audi->getBrand() . "\n";
echo $bwm->getBrand() . "\n";
echo spl_autoload_functions();