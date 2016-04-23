<?php

namespace MinecraftSkins;

class MinecraftSkins {

    const HEAD_SIZE = 8;
    const SKIN_SIZE = 32;

    /**
     *
     * @param resource $rawSkin the raw skin data
     * @param int $scale size scale (default: 8 width * 8 height)
     * @param int $vertRot not implemented
     * @param int $horRot not implemented
     * @param bool $helmet not implemented
     *
     * @return resource the rendered head
     */
    public static function head($rawSkin, $scale = 1, $vertRot = 0, $horRot = 0, $helmet = true) {
        imagesavealpha($rawSkin, true);
        //we don't need create new **transparent** image because we use the full image size
        $canvas = imagecreatetruecolor(self::HEAD_SIZE, self::HEAD_SIZE);

        imagecopyresampled($canvas, $rawSkin, 0, 0, 8, 8, self::HEAD_SIZE, self::HEAD_SIZE, 8, 8);
        if ($helmet) {
            imagecopyresampled($canvas, $rawSkin, 0, 0, 40, 8, self::HEAD_SIZE, self::HEAD_SIZE, 8, 8);
        }

        if ($scale > 1) {
            $canvas = self::resize($canvas, $scale);
        }

        return $canvas;
    }

    /**
     *
     * @param resource $rawSkin the raw skin data
     * @param int $scale size scale (default: 16 width * 32 height)
     * @param int $vertRot not implemented
     * @param int $horRot not implemented
     * @param bool $helmet not implemented
     *
     * @return resource the rendered complete skin
     */
    public static function skin($rawSkin, $scale = 1, $vertRot = 0, $horRot = 0, $helmet = true) {
        $canvas = self::createTransparent(self::SKIN_SIZE / 2, self::SKIN_SIZE);
        imagesavealpha($canvas, true);

        // head
        $head = self::head($rawSkin);
        imagecopyresampled($canvas, $head, 4, 0, 0, 0, self::HEAD_SIZE, self::HEAD_SIZE
                , self::HEAD_SIZE, self::HEAD_SIZE);

        //based on this: https://stackoverflow.com/questions/9483191/full-body-preview-using-the-minecraft-class

        // body
        imagecopyresampled($canvas, $rawSkin, 4, 8, 20, 20, 8, 12, 8, 12);
        // arm left
        imagecopyresampled($canvas, $rawSkin, 0, 8, 44, 20, 4, 12, 4, 12);
        // arm right - must FLIP
        imagecopyresampled($canvas, $rawSkin, 12, 8, 47, 20, 4, 12, -4, 12);
        // leg left
        imagecopyresampled($canvas, $rawSkin, 4, 20, 4, 20, 4, 12, 4, 12);
        // leg right - must FLIP
        imagecopyresampled($canvas, $rawSkin, 8, 20, 7, 20, 4, 12, -4, 12);

        if ($scale > 1) {
            $canvas = self::resize($canvas, $scale);
        }

        return $canvas;
    }

    protected static function resize($image, $scale) {
        $width = imagesx($image);
        $height = imagesy($image);

        $resized = self::createTransparent($width * $scale, $height * $scale);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $width * $scale, $height * $scale, $width, $height);
        return $resized;
    }

    protected static function createTransparent($width, $height) {
        $canvas = imagecreatetruecolor($width, $height);

        imagealphablending($canvas, true);
        imagesavealpha($canvas, true);

        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefill($canvas, 0, 0, $transparent);

        return $canvas;
    }
}
