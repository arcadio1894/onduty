<?php

namespace App\Observers;

use App\Report;
use App\User;
use Illuminate\Support\Facades\Log;

class ReportObserver
{

    public function saved(Report $report)
    {   // created or updated
        $inform = $report->inform;
        $inform->reports_updated_at = $report->updated_at;
        $inform->save();

        $this->sendNotification($inform->location_id, $report->id);
    }

    public function deleted(Report $report)
    {
        $inform = $report->inform;
        $inform->reports_updated_at = $report->deleted_at;
        $inform->save();

        $this->sendNotification($inform->location_id, $report->id);
    }

    public function sendNotification($location_id, $report_id)
    {
        define('FCM_ACCESS_KEY', env('FCM_ACCESS_KEY'));
        $registrationIds = User::where('location_id', $location_id)->whereNotNull('fcm_token')
            ->pluck('fcm_token');

        $data= [
            'updated_entity' => 'report',
            'updated_id' => $report_id
        ];
        $fields = [
            'to'	=> $registrationIds,
            'data'	=> $data
        ];

        $headers = [
            'Authorization: key=' . FCM_ACCESS_KEY,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        Log::debug($result);
    }

}