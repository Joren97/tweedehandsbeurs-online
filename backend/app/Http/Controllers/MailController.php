<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * Generate a pdf for the given productlist
     *  @param integer $id
     */
    public static function sendMail($template, $templateData, $to, $subject, $attachment = null, $attachmentName = null)
    {
        Mail::send($template, $templateData, function ($message) use ($templateData, $attachment, $subject, $to, $attachmentName) {
            $message->to($to)
                ->subject($subject)
                ->attachData($attachment->output(), $attachmentName);
        });
    }
}