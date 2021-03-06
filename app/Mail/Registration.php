<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Registration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $otp;
    public $user_name;

    public function __construct($otp,$user_name)
    {
        $this->otp = $otp;
        $this->user_name = $user_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from = 'manomit@wrctpl.com';
        return $this->from($from, 'One Click - Admin')->subject('Registration OTP')->view('frontend.student_forget_password.registration');
    }
}
