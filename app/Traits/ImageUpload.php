<?php

namespace App\Traits;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\File;

use Illuminate\Http\UploadedFile;
trait ImageUpload
{
    public function uploadImage($file, $directory)
    {
        $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->move($directory, $fileName);
        return $fileName;
    }
        

}
