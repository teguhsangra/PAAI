<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public static function setRomawi($number)
    {
        $romawi = "";
        switch ($number) {
            case 1:
                $romawi = "I";
                break;
            case 2:
                $romawi = "II";
                break;
            case 3:
                $romawi = "III";
                break;
            case 4:
                $romawi = "IV";
                break;
            case 5:
                $romawi = "V";
                break;
            case 6:
                $romawi = "VI";
                break;
            case 7:
                $romawi = "VII";
                break;
            case 8:
                $romawi = "VIII";
                break;
            case 9:
                $romawi = "IX";
                break;
            case 10:
                $romawi = "X";
                break;
            case 11:
                $romawi = "XI";
                break;
            case 12:
                $romawi = "XII";
                break;

            default:
                $romawi = "";
                break;
        }
        return $romawi;
    }

    public static function setZero($number)
    {
        $number_format = "";

        if ($number < 10) {
            $number_format = "000" . $number;
        } else if ($number >= 10 && $number < 100) {
            $number_format = "00" . $number;
        } else if ($number >= 100 && $number < 1000) {
            $number_format = "0" . $number;
        } else if ($number >= 1000 && $number < 10000) {
            $number_format = $number;
        }

        return $number_format;
    }

    public static function getMasterCode($table_name, $prefix_name)
    {
        $sequence = 0;

        $total_data = DB::table($table_name)->count();

        $sequence = $total_data + 1;
        $check_unique_code = false;

        while (!$check_unique_code) {
            $code = $prefix_name . '-' . self::setZero($sequence);

            $get_detail_data = DB::table($table_name)->where('code', $code)->first();

            if ($get_detail_data == null) {
                $check_unique_code = true;
            } else {
                $sequence++;
                $check_unique_code = false;
            }
        }

        return $code;
    }
    public static function getTransactionCode($table_name, $prefix_name,$other_code = null, $set_sequence = null)
    {
        $sequence = 0;
        $total_data = 0;




        $total_data = DB::table($table_name)
        ->count();

        $sequence = $total_data + 1;
        if ($set_sequence != null) {
            $sequence = $set_sequence;
        }
        $check_unique_code = false;

        while (!$check_unique_code) {
            $code = $prefix_name . date('dmy') . self::setZero($sequence);


            $get_detail_data = DB::table($table_name)->where('code', $code)->first();

            if ($get_detail_data == null) {
                $check_unique_code = true;
            } else {
                $sequence++;
                $check_unique_code = false;
            }
        }

        return $code;
    }

    public static function createCustomCode($table_name, $prefix_name)
    {

        $code = self::getTransactionCode($table_name, $prefix_name);

        return $code;
    }
}
