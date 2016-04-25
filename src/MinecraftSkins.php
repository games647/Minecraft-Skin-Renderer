<?php

namespace MinecraftSkins;

class MinecraftSkins {

    const HEAD_SIZE = 8;
    const SKIN_HEIGHT = 32;
    const SKIN_WIDTH = self::SKIN_HEIGHT / 2;

    const PADDING = 1;

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
        $canvas = imagecreatetruecolor(self::HEAD_SIZE * $scale, self::HEAD_SIZE * $scale);

        imagecopyresampled($canvas, $rawSkin, 0 * $scale, 0 * $scale, 8, 8
                , self::HEAD_SIZE * $scale, self::HEAD_SIZE * $scale, 8, 8);
        if ($helmet) {
            imagecopyresampled($canvas, $rawSkin, 0 * $scale, 0 * $scale, 40, 8
                    , self::HEAD_SIZE  * $scale, self::HEAD_SIZE * $scale, 8, 8);
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
        $canvas = self::createTransparent(self::SKIN_WIDTH * $scale, self::SKIN_HEIGHT * $scale);
        imagesavealpha($canvas, true);

        // head
        $head = self::head($rawSkin, $scale);
        imagecopyresampled($canvas, $head, 4 * $scale, 0 * $scale, 0, 0
                , self::HEAD_SIZE * $scale, self::HEAD_SIZE * $scale
                , self::HEAD_SIZE * $scale, self::HEAD_SIZE * $scale);

        //based on this: https://stackoverflow.com/questions/9483191/full-body-preview-using-the-minecraft-class

        // body
        imagecopyresampled($canvas, $rawSkin, 4 * $scale, 8 * $scale, 20, 20, 8 * $scale, 12 * $scale, 8, 12);
        // arm left
        imagecopyresampled($canvas, $rawSkin, 0 * $scale, 8 * $scale, 44, 20, 4 * $scale, 12 * $scale, 4, 12);
        // arm right - must FLIP
        imagecopyresampled($canvas, $rawSkin, 12 * $scale, 8 * $scale, 47, 20, 4 * $scale, 12 * $scale, -4, 12);
        // leg left
        imagecopyresampled($canvas, $rawSkin, 4 * $scale, 20 * $scale, 4, 20, 4 * $scale, 12 * $scale, 4, 12);
        // leg right - must FLIP
        imagecopyresampled($canvas, $rawSkin, 8 * $scale, 20 * $scale, 7, 20, 4 * $scale, 12 * $scale, -4, 12);

        return $canvas;
    }

    /**
     *
     * @param resource $rawSkin the raw skin data
     * @param int $scale size scale (default: 80 width * 80 height)
     * @param bool $helmet not implemented
     *
     * @return resource the rendered complete skin
     */
    public static function combined($rawSkin, $scale = 1, $helmet = true) {
        $canvas = self::createTransparent(self::HEAD_SIZE * 8, self::HEAD_SIZE * 8);
        imagesavealpha($canvas, true);

        $width = imagesx($canvas);
        $height = imagesy($canvas);

        // head as backround
        $head = self::head($rawSkin, 8);
        imagecopyresampled($canvas, $head, 0, 0, 0, 0, self::HEAD_SIZE * 8, self::HEAD_SIZE * 8
                , self::HEAD_SIZE * 8, self::HEAD_SIZE * 8);

        //white shadow
        $shadow = imagecolorallocate($canvas, 0, 0, 0);
        //head
        $startX = $width - self::PADDING - self::SKIN_WIDTH + 3;
        $startY = $height - self::PADDING - self::SKIN_HEIGHT;
        imagefilledrectangle($canvas, $startX, $startY, $startX + self::HEAD_SIZE + 1, $startY + self::HEAD_SIZE
                , $shadow);
//        //torso
        $startX = $width - self::PADDING - self::SKIN_WIDTH - 1;
        $startY = $height - self::SKIN_HEIGHT + 7;
        imagefilledrectangle($canvas, $startX, $startY, $startX + 17, $startY + 13, $shadow);
//        //legs
        $startX = $width - self::PADDING - self::SKIN_WIDTH + 3;
        $startY = $height - self::SKIN_HEIGHT + 20;
        imagefilledrectangle($canvas, $startX, $startY, $startX + 9, $startY + 11, $shadow);

        //skin foreground
        $skin = self::skin($rawSkin, 1);
        imagecopyresampled($canvas, $skin, $width - self::PADDING - self::SKIN_WIDTH, $height - self::SKIN_HEIGHT, 0, 0
                , self::SKIN_WIDTH, self::SKIN_HEIGHT
                , self::SKIN_WIDTH, self::SKIN_HEIGHT);
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
