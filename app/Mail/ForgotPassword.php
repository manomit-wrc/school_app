<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $new_password;
    public $user_name;

    public function __construct($new_password,$user_name)
    {
        $this->new_password = $new_password;
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
        return $this->from($from, 'One Click - Admin')->subject('Forgot Password')->view('frontend.student_forget_password.forget_password');
    }
}
