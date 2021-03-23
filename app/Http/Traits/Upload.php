<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\File;


trait Upload{

    public function upload($folder_name, $image): string
    {
        $image_name = $folder_name . '_'.time() . '.' .$image->getClientOriginalExtension();
        $image->move(public_path('images/'.$folder_name), $image_name);

        return $image_name;
    }

    public function deleteFile($image_path)
    {
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
    }

}
