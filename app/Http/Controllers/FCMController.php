<?php

namespace App\Http\Controllers;

use LaravelFCM\Message\Exceptions\InvalidOptionsException;
use LaravelFCM\Message\{OptionsBuilder, PayloadDataBuilder, PayloadNotificationBuilder};

class FCMController extends Controller
{
    /**
     * Send web push notifications to registered token
     */
    public function send(): void
    {
        $optionBuilder = new OptionsBuilder();
        try {
            $optionBuilder->setTimeToLive(60 * 20);
        } catch (InvalidOptionsException $e) {
            dd($e);
        }

        $notificationBuilder = new PayloadNotificationBuilder('my title');
        $notificationBuilder->setBody('Hello world')->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option       = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data         = $dataBuilder->build();
        $token        = "eEz73lRYE5m_n2jb1ZPmkl:APA91bHRUfGRUut6-YBr_Zy6hTCaB5l1KGAPvNcOBB373MEh3taJqX_JO92NefNJe4OeDKyi058f2MkYCHPq0kN9zwlV_KppFE4kkvSf-RRLNx6NF-AeyWO3cDBfS0ZFwQlG5lfz_1Xs";

        $downstreamResponse = \FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        // return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $downstreamResponse->tokensToModify();

        // return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:error) - in production you should remove from your database the tokens
        $downstreamResponse->tokensWithError();

        dump($downstreamResponse);
    }
}
