<?php

namespace App\Observers;

use App\Report;

class ReportObserver
{

    public function saved(Report $report)
    {   // created or updated
        $inform = $report->inform;
        $inform->reports_updated_at = $report->updated_at;
        $inform->save();
    }

    public function deleted(Report $report)
    {
        $inform = $report->inform;
        $inform->reports_updated_at = $report->deleted_at;
        $inform->save();
    }

}