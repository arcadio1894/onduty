<?php

namespace App\Http\Controllers\Api;

use App\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function byInform(Request $request)
    {
        $reports = Report::where('informe_id', $request->input('inform_id'))->get([
            'id', // required to edit from the app
            // inform_id is not necessary, because we already know it
            'user_id', // to check if the authenticated user can edit the report
            'work_front_id', 'area_id', 'responsible_id', // will be changed for the names
            'aspect',
            'critical_risks_id', // will be changed for its name
            'potential', 'state',
            'image', 'image_action',
            'planned_date', 'deadline', 'inspections',
            'description', 'actions', 'observations',
            'created_at'
        ]);

        foreach ($reports as $report) {
            if ($report->image_action)
                $report->image_action = asset('images/action/' . $report->id . '.' . $report->image_action);
            else
                $report->image_action = asset('images/action/default.png');

            if ($report->image)
                $report->image = asset('images/report/' . $report->id . '.' . $report->image);
            else
                $report->image = asset('images/report/default.png');

            $report->work_front_name = $report->work_front->name;
            unset($report->work_front);

            $report->area_name = $report->area->name;
            unset($report->area);

            $report->responsible_name = $report->responsible->name;
            unset($report->responsible);

            $report->critical_risks_name = $report->critical_risks->name;
            unset($report->critical_risks);
        }
        return $reports;
    }
}
