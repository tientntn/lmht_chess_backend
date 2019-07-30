<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',
    'sizes' => array(
        "avatars" => array("100x100", "200x200"),
        "equipments" => array("100x100", "600x600"),
        "heroes"    => array("100x100","600x600"),
        "pieces"    => array("100x100","600x600"),
        "piecesList"    => array("100x100","600x600"),
        "combos"    => array("100x100","600x600"),
        "news"    => array("100x100","600x600"),
    ),
    'image_root' => env('IMAGE_ROOT'),
    'image_url' => env('IMAGE_URL'),

);
