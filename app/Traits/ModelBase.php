<?php

namespace App\Traits;

use App\Classes\Help;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

trait ModelBase
{
    public static function filter($filters, $sort = false){
        $query = $filters["query"] ?? "";
        $advanced = $filters["advanced_search"] ?? [];
        $modelfilter = $filters["modelfilter"] ?? [];
	    $advanced_values = [];
        $fields = self::getTableFields();
        
        foreach($fields as $key => $field){
            if(!empty($field["filter"])){
                if(!empty($advanced[$key])){
                    if(($field["advanced-type"] ?? null) == "date-range"){
                        $dates = explode(" - ", $advanced[$key]);
                        
                        $filter[] = ($field["custom-filter"] ?? $key)." BETWEEN ? AND ?";
                        $advanced_values[] = Help::convert_date($dates[0]);
                        $advanced_values[] = Help::convert_date($dates[1] ?? $dates[0]);
                    }
                    else if(($field["advanced-type"] ?? null) == "in-array"){
                        $multi_filter = [];
                        
                        foreach($advanced[$key] as $value){
                            $multi_filter[] = "CONVERT(".($field["custom-filter"] ?? $key)." using 'utf8') LIKE '%".$value."%'";
                        }
                        
                        $filter[] = "(".implode(" AND ", $multi_filter).")";
                    }
                    else if(($field["advanced-type"] ?? null) == "like"){
                        $filter[] = "CONVERT(".($field["custom-filter"] ?? $key)." using 'utf8') LIKE '%".$advanced[$key]."%'";
                    }
                    else{
                        $multi_filter = [];
                        
                        foreach($advanced[$key] as $value){
                            $multi_filter[] = "CONVERT(".($field["custom-filter"] ?? $key)." using 'utf8') = ?";
                            $advanced_values[] = $value;
                        }
                        
                        $filter[] = "(".implode(" OR ", $multi_filter).")";
                    }
                }
                else if(Help::empty_dictionary($advanced)){
                    $filter[] = "CONVERT(".($field["custom-filter"] ?? $key)." using 'utf8') LIKE ?";
                    $filter_values[] = "%".$query."%";
                }
            }
            
            if(!empty($field["sort"])){
                $model_sort[] = ($field["custom-filter"] ?? $key)." ".$field["sort"];
            }
        }
        
        $search = !empty($filter) ? self::whereRaw("(".implode(Help::empty_dictionary($advanced) ? " OR " : " AND ", $filter).")", !Help::empty_dictionary($advanced) ? $advanced_values : $filter_values) : self::query();
        
        // Check model filter
        foreach($modelfilter as $key => $value){
            $search = $search->whereRaw("CONVERT(".($fields[$key]["custom-filter"] ?? $fields[$key]["key"] ?? $key)." using 'utf8') = ?", $value);
        }
        
        if($sort){
            $search->orderByRaw(implode(",", $model_sort));
        }
        
        return $search;
    }
}
