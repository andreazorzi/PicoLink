<?php

namespace App\Http\Controllers;

use App\Classes\Help;
use App\Models\Tag;
use App\Jobs\VisitCountry;
use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Storage;

class TagController extends Controller
{
    public function details(Request $request, ?Tag $tag = null){
		return view('components.backoffice.modals.tag-data', ["tag" => $tag]);
    }
    
    // Create tag
    public function create(Request $request){
        return Tag::createFromRequest($request);
    }
    
    // Update tag
    public function update(Request $request, Tag $tag){
        return $tag->updateFromRequest($request);
    }
    
    // list tags
    public function list(Request $request){
        return Help::fragment("backoffice.tags", "tags");
    }
}