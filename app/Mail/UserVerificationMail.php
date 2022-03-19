<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(protected User $user)
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to($this->user->email);
        $this->from('support@pixelfix.net.au');
        $this->subject('Please verify your account');
        return $this->markdown('mail.welcome')->with(['code' => $this->user->verification_code]);
    }
}
