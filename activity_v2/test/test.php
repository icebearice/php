<?php

include dirname(dirname(__FILE__)) . "/include/config.php";
spl_autoload_extensions('.php,.inc');

$bwm = new Car("bwm", 100);
$audi = new Car("audi", 111);

echo $audi->getBrand() . "\n";
echo $bwm->getBrand() . "\n";

// 获取当前已经注册的autoload function
echo json_encode(spl_autoload_functions()) . "\n";
// 无参数则获取 默认拓展名列表
echo json_encode(spl_autoload_extensions()) . "\n";
// 参数可修改默认拓展名列表
echo json_encode(spl_autoload_extensions()) . "\n";