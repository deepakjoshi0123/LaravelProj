<?php
namespace App\Services;
use Illuminate\Support\Facades\Mail;
class MailService{
    public static function sendMail($template,$data,$sendTo,$subject){
        Mail::send($template,$data, function ($message) use($sendTo,$subject) {
            $message->to($sendTo,'trello clone')
            ->subject($subject);
        });
    }
}