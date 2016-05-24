# Minecraft Skin Renderer

## Description

Minecraft skin renderer library.

## Features

* Only responsible for rendering - You can manage how you want to
    * Download the raw skin data
    * Store the rendered skin
    * Cache the result
* Easy to install and use
* Easy and flexible skin rotation for 3d skin rendering

## ToDo

* 3D-Rendering --> skin rotations/perspective view
* 1.8 Skin format support
* Add image smoothing
* Add arms, legs, head position manipulation
* Add cape rendering

## Installation

With composer it's just:

    composer require games647/minecraft-skin-renderer

For non-composer projects, you can drop the files from the /src folder into a libraries folder and use it with a
require statement at the top of the PHP-File. You can see a example in the example.php file.

## Usage

```PHP
//this is only used if you don't use composer
require __DIR__ . '/PATH_TO_LIB_FOLDER/MinecraftSkins.php';

use \MinecraftSkins\MinecraftSkins;

[...]

//load the skin from a file
$skinImage = imagecreatefrompng("cd6be915b261643fd13621ee4e99c9e541a551d80272687a3b56183b981fb9a.png");

//render the skin and make it 5 times bigger
$result = MinecraftSkins::skin($skinImage, 5);

//this part is for rendering the skin only
//tell the browser that we will send the raw image without HTML
header('Content-type: image/png');
imagepng($renderedSkin);
```

## Examples

Dinnerbone head
```PHP
MinecraftSkins::head($skinImage, 5);
```
![Dinnerbone head](http://i.imgur.com/di5eMd6.png)

Dinnerbone full skin
```PHP
MinecraftSkins::skin($skinImage, 5);
```
![Dinnerbone skin](http://i.imgur.com/g1QQCXP.png)

## Useful links

* http://minecraft.gamepedia.com/Skin
* https://github.com/minotar/skin-spec
