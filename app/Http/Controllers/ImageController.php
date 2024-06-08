<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public static function toBase64(string $url):string{
        return config("app.env") == "local" ? "data:image/png;base64,".base64_encode(Storage::disk('asset')->get($url)) : asset($url);
    }
}