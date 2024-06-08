<?php

namespace App\Http\Controllers;

use App\Classes\Help;
use Illuminate\Http\Request;
use App\Models\PasswordReset;

class SearchTableController extends Controller
{
    // Search table list
    function list(Request $request, $model){
        return Help::fragment("components.search-table", "search-table-body", ["model" => $model, "query" => $request->filter, "advanced" => $request->advanced_search, "page" => $request->page ?? 1, "modelfilter" => json_decode($request->modelfilter ?? "[]")]);
    }
}
