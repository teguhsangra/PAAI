<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Models\User;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\ParameterSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    private $date_format;
    private $add_or_minus_day;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->date_format = 'm/d/Y';
        $this->add_or_minus_day = 1;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request)
    {
        return view('pages.frontend.home');
    }

    public function paymentPage(Request $request)
    {
        $data['bank'] = BankAccount::get();
        return view('pages.frontend.payment', $data);
    }

    public function aboutUsPage(Request $request)
    {
        return view('pages.frontend.about_us');
    }

    public static function check_exist_folder($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    public function is_base64($str)
    {
        if (base64_encode(base64_decode($str, true)) === $str) {
            return true;
        } else {
            return false;
        }
    }

    public static function setMonth($month)
    {
        $month_name = '';
        switch ($month) {
            case 1:
                $month_name = 'JAN';
                break;
            case 2:
                $month_name = 'FEB';
                break;
            case 3:
                $month_name = 'MAR';
                break;
            case 4:
                $month_name = 'APR';
                break;
            case 5:
                $month_name = 'MAY';
                break;
            case 6:
                $month_name = 'JUN';
                break;
            case 7:
                $month_name = 'JUL';
                break;
            case 8:
                $month_name = 'AUG';
                break;
            case 9:
                $month_name = 'SEP';
                break;
            case 10:
                $month_name = 'OCT';
                break;
            case 11:
                $month_name = 'NOV';
                break;
            case 12:
                $month_name = 'DEC';
                break;
            default:
                $month_name = "";
                break;
        }
        return $month_name;
    }

    public static function setIndonesiaMonth($month)
    {
        $month_name = '';
        switch ($month) {
            case 1:
                $month_name = 'Januari';
                break;
            case 2:
                $month_name = 'Februari';
                break;
            case 3:
                $month_name = 'Maret';
                break;
            case 4:
                $month_name = 'April';
                break;
            case 5:
                $month_name = 'Mei';
                break;
            case 6:
                $month_name = 'Juni';
                break;
            case 7:
                $month_name = 'Juli';
                break;
            case 8:
                $month_name = 'Agustus';
                break;
            case 9:
                $month_name = 'September';
                break;
            case 10:
                $month_name = 'Oktober';
                break;
            case 11:
                $month_name = 'November';
                break;
            case 12:
                $month_name = 'Desember';
                break;
            default:
                $month_name = "";
                break;
        }
        return $month_name;
    }

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

    public static function dateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
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


    public function check_availability(Request $request)
    {
        $total_booking = 0;

        $return['available'] = 'true';
        $return['error_message'] = '';

        $array_booth_id = json_decode($request['array_booth_id']);
        $start_date = date('Y-m-d', strtotime($request['start_date']));
        $end_date = date('Y-m-d', strtotime($request['end_date']));
        $booking_id = $request['booking_id'];
        $exhibitor_id = $request['exhibitor_id'];




        if (sizeof($array_booth_id) > 0) {
            // First Room Checking
            if ($total_booking == 0) {
                if ($booking_id != null) {
                    $total_booking = Booking::join('booking_and_booth', 'booking_and_booth.booking_id', 'bookings.id')
                        ->where('start_date', '<=', $end_date)
                        ->where('end_date', '>=', $start_date)
                        ->where('booking_and_booth.booking_id', '!=', $booking_id)
                        ->whereIn('booking_and_booth.booth_id', $array_booth_id)
                        ->count();
                } else {
                    $total_booking = Booking::join('booking_and_booth', 'booking_and_booth.booking_id', 'bookings.id')
                        ->where('start_date', '<=', $end_date)
                        ->where('end_date', '>=', $start_date)
                        ->whereIn('booking_and_booth.booth_id', $array_booth_id)
                        ->count();
                }
            }



            if ($total_booking > 0) {
                $return['available'] = 'false';
                for ($i = 0; $i < sizeof($array_booth_id); $i++) {
                    $room = Booth::findOrFail($array_booth_id[$i]);
                    $return['error_message'] .= '<br>Booth ' . $room->booth_number . ' is not available at ' . $start_date . ' to ' . $end_date;
                }
            } else {
                // Do Nothing
            }

            return $return;
        }
    }

    public function setup_periode(Request $request)
    {
        $driven_by = $request['driven_by'];

        $start_date = date('Y-m-d', strtotime($request['start_date']));
        $length_of_term = $request['length_of_term'];
        $end_date = date('Y-m-d', strtotime($request['end_date']));
        $start_date_counted = $request['start_date_counted'];
        $date_format = $request['date_format'];
        if (!empty($date_format)) {
            $this->date_format = $date_format;
        }

        $message = 'not_complete';

        switch ($driven_by) {
            case "start_date":
                if ($length_of_term != null) {
                    $end_date = date($this->date_format, strtotime("+" . $length_of_term . " months", strtotime($start_date)));
                    $end_date = date($this->date_format, strtotime("-" . $this->add_or_minus_day . " days", strtotime($end_date)));
                    $message = 'complete';
                }
                break;
            case "length_of_term":
                if ($start_date != null || $end_date != null) {
                    if ($start_date != null) {
                        $end_date = date($this->date_format, strtotime("+" . $length_of_term . " months", strtotime($start_date)));
                        $end_date = date($this->date_format, strtotime("-" . $this->add_or_minus_day . " days", strtotime($end_date)));
                    }
                    if ($end_date != null) {
                        $start_date = date($this->date_format, strtotime("-" . $length_of_term . " months", strtotime($end_date)));
                        $start_date = date($this->date_format, strtotime("+" . $this->add_or_minus_day . " days", strtotime($start_date)));
                    }
                    $message = 'complete';
                }
                break;
            case "end_date":
                if ($length_of_term != null) {
                    $start_date = date($this->date_format, strtotime("-" . $length_of_term . " months", strtotime($end_date)));
                    $start_date = date($this->date_format, strtotime("+" . $this->add_or_minus_day . " days", strtotime($start_date)));
                    $message = 'complete';
                }
                break;
        }
        $start_date = date('m/d/Y', strtotime($start_date));
        $end_date = date('m/d/Y', strtotime($end_date));

        $return['message'] = $message;
        $return['start_date'] = $start_date;
        $return['length_of_term'] = $length_of_term;
        $return['end_date'] = $end_date;
        return $return;
    }
}
