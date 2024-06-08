<?php


namespace App\Classes;

use Illuminate\Support\Str;


class Help
{
    public static function convert_date(string $date):string{
		return implode("-", array_reverse(explode("/", $date)));
	}
    
    public static function fragment(string $view, string $fragment, array $data = []):string{
        return view($view, $data)->fragment($fragment);
	}
    
    public static function strToLen(?string $str, int $len, bool $left = false){
        $str = $str ?? "";
        
        if(strlen($str) >= $len){
            return Str::limit($str, $len, '');
        }
        
        return !$left ? Str::padRight($str, $len, " ") : Str::padLeft($str, $len, " ");
    }
    
    public static function euid(int $lenght){
        $time = time();
        $base_36 = base_convert($time , 10, 36);
        $randomize_id = $base_36.Str::random($lenght - Str::length($base_36));
        
        return $randomize_id;
    }
    
    public static function format_number($number, $is_price = false){
        return number_format($number, 2, ',', '.').($is_price ? " €" : "");
    }
    
    public static function empty_dictionary($dictionary){
        foreach($dictionary as $key => $value){
            if(!empty($value)) return false;
        }
        
        return true;
    }
}