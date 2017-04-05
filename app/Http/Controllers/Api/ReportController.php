<?php

namespace App\Http\Controllers\Api;

use App\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function byInform(Request $request)
    {
        $reports = Report::where('informe_id', $request->input('inform_id'))->get();
        return $reports;
    }
}
