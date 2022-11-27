<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ParameterSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;

class MailController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }



    // public static function ticketing_mail(Request $request, $ticketing_id){
    //     $data['ticketing'] = Ticketing::findOrFail($ticketing_id);

    //     Mail::send('pages.mail.ticketing', $data, function ($message) use ($data) {
    //         $message->from('info@rakomsis.com', 'Rakomsis');

    //         $message->to($data['ticketing']->customer->email)->subject($data['ticketing']->subject);
    //     });

    //     if (Mail::failures()) {
    //         $request->session()->flash('error', 'Email Sent Failed');
    //     } else {
    //         $request->session()->flash('success', 'Email Sent');
    //     }
    // }





}
