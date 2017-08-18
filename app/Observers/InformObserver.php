<?php

namespace App\Observers;

use App\Informe as Inform;
use App\User;
use Illuminate\Support\Facades\Log;

class InformObserver
{

    public function saved(Inform $inform)
    {   // created or updated
        $this->sendNotification($inform, 'saved');
    }

    public function sendNotification(Inform $inform, $action)
    {
        $registrationIds = User::where('location_id', $inform->location_id)->whereNotNull('fcm_token')
            ->pluck('fcm_token');

        $headers = [
            'Authorization: key=' . env('FCM_ACCESS_KEY'),
            'Content-Type: application/json'
        ];

        $data = [
            'updated_entity' => 'inform',
            'updated_id' => $inform->id,
            'action' => $action
        ];

        foreach ($registrationIds as $registrationId) {
            $fields = [
                'to'	=> $registrationId,
                'data'	=> $data
            ];

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch,CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
            /*$result = */curl_exec($ch);
            curl_close($ch);

            // Log::debug($result);
        }

    }

}