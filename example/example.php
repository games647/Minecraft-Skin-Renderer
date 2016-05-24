<?php

require __DIR__ . '/../src/MinecraftSkins.php';

const SKIN_URL = 'http://s3.amazonaws.com/MinecraftSkins/';

use \MinecraftSkins\MinecraftSkins;

//tell the browser that we will send the raw image without HTML
header('Content-type: image/png');

//Dinnerbone skin
//$skinImage = imagecreatefrompng("cd6be915b261643fd13621ee4e99c9e541a551d80272687a3b56183b981fb9a.png");
//$skinImage = imagecreatefrompng("a116e69a845e227f7ca1fdde8c357c8c821ebd4ba619382ea4a1f87d4ae94.png");
$skinImage = getRawSkin("games647");


$renderedSkin = MinecraftSkins::head($skinImage, 8);
//$renderedSkin = MinecraftSkins::combined($skinImage, 10);
//$renderedSkin = MinecraftSkins::skin($skinImage, 5);
imagepng($renderedSkin);

function getRawSkin($username) {
    //downloads the skin from mojang servers
    $url = SKIN_URL . $username . '.png';
    return imagecreatefrompng($url);
}
