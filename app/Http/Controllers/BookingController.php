<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\CronJob;
use App\Models\Member;
use App\Models\Booking;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Redirect;
use File;
use Image;
use Mail;

class BookingController extends Controller
{


    public static function create_member($user, $request, $file)
    {
        $return = true;

        $member = new Member;
        $member->user_id = $user->id;
        $member->code  = self::createCustomCode('members', 'ID#');
        $member->aaji = $request['aaji'];
        $member->name = $user->username;
        $member->company_name = $request['company_name'];
        $member->email  = $user->email;
        $member->phone  = $request['phone'];
        $member->birth_date  = date('Y-m-d', strtotime($request['birth_date']));
        $member->is_verified = 'N';
        $member->referral = $request['referral'];
        $member->expired_date = null;

        if (!$member->save()) {
            $return = false;
        }

        self::create_order($member, $request, $file);


        return $return;
    }

    public static function create_order($member, $request, $file)
    {
        $return = true;


        $product = Product::where('id', 1)->first();

        $booking = new Booking;
        $booking->status_id = 1;
        $booking->member_id = $member->id;
        $booking->code = self::createCustomCode('bookings', 'PAAI-MB-');
        $booking->product_id = 1;
        $booking->start_date = date('Y-m-d', strtotime($request['start_date']));
        $booking->end_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($request['start_date'])) . " + 365 day"));
        $booking->total = $product->price;
        $booking->term_notice_period = 1;
        $booking->renewal_status = "on renewal";
        $booking->is_renewal = "N";
        $booking->payment_status = "waiting verified";

        if ($file) {
            $destinationPath = '/uploads/members/booking/';
            $photoName = time() . '.' . $file->getClientOriginalExtension();

            $path = public_path($destinationPath);
            HomeController::check_exist_folder($path);
            $path = $path . $photoName;

            if ($file->getSize() > 2000000) {
                Image::make($file->getRealPath())->resize(2024, 2024, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);
            } else {
                Image::make($file->getRealPath())->save($path);
            }
            $booking->attachment = $destinationPath . '/' . $photoName;
        }

        if (!$booking->save()) {
            $return = false;
        }

        $data['member'] = $member;
        $data['order'] = $booking;
        Mail::send('mail.index', $data, function ($message) use ($data) {
            $message->to($data['member']->email)
                ->cc(['paai.or.id@gmail.com', 'hennydondocambey@gmail.com'])
                ->subject('Member baru');

            $message->attach(public_path() . '/' . $data['order']->attachment);
        });

        Mail::send('mail.admin', $data, function ($message) use ($data) {
            $message->to('paai.or.id@gmail.com')
                ->subject('Member baru');

            $message->attach(public_path() . '/' . $data['order']->attachment);
        });

        return $return;
    }

    public function auto_complete_booking()
    {
        $return = "";

        $cron_job = new CronJob;
        $cron_job->name = "auto_complete_booking";

        $execution = Booking::where('status_id', 2)
            ->where('end_date', '<', date('Y-m-d'))
            ->update(['status_id' => 4]);

        if ($execution) {
            $cron_job->status = true;
            $return = "true";
        } else {
            $cron_job->status = false;
            $return = "false";
        }

        $cron_job->save();

        return $return;
    }
}
