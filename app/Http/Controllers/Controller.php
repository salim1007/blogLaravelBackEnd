<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Facade;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, DispatchesJobs;

    public function saveImage($image , $path = 'public'){
        if(!$image){
            return null;

        }

        $filename = time().'.'.'png';
        //saving the image.....
        \Storage::disk($path)->put($filename, base64_decode($image));

        //return the path.....
        //the url is the base url exp: localhost:80000....
        return URL::to('/').'/storage/'.$path.'/'.$filename;
    }
}
