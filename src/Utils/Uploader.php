<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploader
{
    public function uploadImage(UploadedFile $file, string $directory, string $name = "default"): string
    {

        $newFileName = $name . '-' . uniqid() . '.' . $file->guessExtension();
        $file->move($directory, $newFileName);
        return $newFileName;

    }
}