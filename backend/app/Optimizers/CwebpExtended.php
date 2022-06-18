<?php

namespace App\Optimizers;

use Spatie\ImageOptimizer\Image;
use Spatie\ImageOptimizer\Optimizers\Cwebp;

class CwebpExtended extends Cwebp
{
    private $mimes = [
        'image/png',
        'image/jpeg',
        'image/tiff',
        'image/webp'
    ];

    public function canHandle(Image $image): bool
    {
        return in_array($image->mime(), $this->mimes);
    }
}
