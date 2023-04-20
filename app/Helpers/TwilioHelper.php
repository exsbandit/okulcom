<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;

class TwilioHelper
{
    const COUNTRY_CODE = '+90';

    /**
     * @param $to
     * @param $message
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public static function sendMessage($to, $message)
    {
        $e164Format = self::COUNTRY_CODE . substr($to, -10);
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_NUMBER');

        $client = new Client($sid, $token);
        $message = $client->messages->create(
            $e164Format,
            array(
                'from' => $from,
                'body' => $message
            )
        );

        DB::table('sms_messages')->insert([
            'recipient' => $e164Format,
            'message' => $message->body,
            'status' => $message->status,
            'sid' => $message->sid,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
