<?php

namespace App\Http\Controllers;

use App\Area;
use App\CriticalRisk;
use App\Informe;
use App\Location;
use App\Report;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Style_Alignment;
use PHPExcel_Worksheet_Drawing;

class GeneralInformController extends Controller
{
    public function general(Request $request)
    {
        // show the reports
        $location_id = $request->input('location_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if ($start_date && $end_date) {
            $start = Carbon::parse($start_date);
            $end = Carbon::parse($end_date);

            $informs = Informe::where('from_date', '>=', $start)
                ->where('to_date', '<=', $end);

            if ($location_id)
                $informs = $informs->where('location_id', $location_id);

            $informs = $informs->pluck('id');
            // dd($informs);

            $reports = Report::whereIn('informe_id', $informs)
                ->with('user')
                ->with('work_front')
                ->with('area')
                ->with('responsible')
                ->with('critical_risks')
                ->whereNull('cloned_into_id')
                ->orderBy('state') // ascendant order => A, C
                ->orderBy('id', 'desc')
                ->get();
            // dd($reports->pluck('description'));

        } else {
            $reports = collect();
        }

        $locations = Location::get(['id', 'name']);

        // deliver work to the proper method (this form has submit buttons with different purposes)
        if ($request->has('excel'))
            return $this->getGeneralReportsExcel($reports, $start_date, $end_date);
        else if ($request->has('charts'))
            return $this->getGeneralReportsCharts($reports, $start_date, $end_date, $location_id, $locations);
        // else
        return view('informe.general')->with(compact(
            'reports', 'locations',
            'start_date', 'end_date', 'location_id'
        ));
    }

    public function getGeneralReportsExcel($reports, $start_date, $end_date) {
        $nameExcel = "Consolidado de reportes";

        Excel::create($nameExcel, function($excel) use ($reports, $start_date, $end_date) {

            $excel->sheet('Consolidado', function($sheet) use ($reports, $start_date, $end_date) {

                $sheet->mergeCells('A1:S1');
                $sheet->getDefaultStyle()
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                    ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $sheet->setHeight(1, 60);

                $sheet->row(1, function($row) {
                    // call cell manipulation methods
                    $row->setBackground('#03DCF0');
                    $row->setFont(array(
                        'family'     => 'Arial',
                        'size'       => '30',
                        'bold'       =>  true,
                        'align'      => 'center'
                    ));
                });

                $sheet->row(1, ["CONSOLIDADO DE REPORTES ($start_date - $end_date)"]);
                $lastIndexRow = 0;

                // Reports del inform

                $sheet->row($lastIndexRow+3, ['Fecha/Hora', 'Reportado por', 'Cargo', 'Frente de Trabajo', 'Área', 'Responsable', 'Cargo', 'Aspecto', 'Riesgo crítico', 'Descripción', 'Imagen', 'Potencial', 'Acción a tomar', 'Fecha planeada', 'Observacion', 'Imagen Accion', 'N° de inspecciones', 'Estado', 'Fecha de cierre']);

                $indexStart = $lastIndexRow + 4;
                $rowStart = $indexStart-1;
                $positions = 'A'.$rowStart.':S'.$rowStart;
                $sheet->cells($positions, function($cells) {
                    $cells->setBackground('#01C3F3');
                    $cells->setAlignment('center');
                });

                foreach ($reports as $report){
                    $objDrawing = new PHPExcel_Worksheet_Drawing;
                    $objDrawing2 = new PHPExcel_Worksheet_Drawing();
                    $row = [];
                    $row[0] = $report->created_at;
                    $row[1] = $report->user->name;
                    $row[2] = $report->user->position->name;
                    $row[3] = $report->work_front->name;
                    $row[4] = $report->area->name;
                    $row[5] = $report->responsible->name;
                    $row[6] = $report->responsible->position->name;
                    $row[7] = $report->aspect;
                    $row[8] = $report->critical_risks->name;
                    $row[9] = $report->description;
                    $row[10] = "";
                    $row[11] = $report->potential;
                    $row[12] = $report->actions;
                    $row[13] = $report->planned_date;
                    $row[14] = $report->observations;
                    $row[15] = "";
                    $row[16] = $report->inspections;
                    $row[17] = "";
                    $row[18] = $report->deadline;
                    $sheet->appendRow($row);
                    $cellState = 'R'.$indexStart;
                    $sheet->cell($cellState, function($cell) use($report){
                        // manipulate the cell
                        $cell->setValue($report->state);
                        if ($report->state == 'Abierto'){
                            $cell->setBackground('#FF0000');
                            $cell->setFontColor('#ffffff');
                        } else {
                            $cell->setBackground('#1A8800');
                            $cell->setFontColor('#ffffff');
                        }
                    });
                    $coordinate = 'K'.$indexStart;
                    if ($report->image == null){
                        $objDrawing->setPath(public_path('images/report/default.png'));
                    } else {
                        $objDrawing->setPath(public_path('images/report/'.$report->image));
                    }
                    $objDrawing->setCoordinates($coordinate);
                    $objDrawing->setWorksheet($sheet);
                    $objDrawing->setWidthAndHeight(110,90);
                    $coordinate2= 'P'.$indexStart;
                    if ($report->image_action == null){
                        $objDrawing2->setPath(public_path('images/action/default.png'));
                    } else {
                        $objDrawing2->setPath(public_path('images/action/'.$report->image_action));
                    }
                    $objDrawing2->setCoordinates($coordinate2);
                    $objDrawing2->setWorksheet($sheet);
                    $objDrawing2->setWidthAndHeight(110,90);
                    $indexStart += 1;
                }
                $totalRows = $indexStart;

                $sheet->cells(function ($cells){
                    $cells->setBackground('#1A8800');
                    $cells->setAlignment('center');
                    $cells->setVAlignment('center');
                    $cells->setFont(array(
                        'family'     => 'Arial',
                        'size'       => '24',
                        'bold'       =>  true
                    ));
                });
                $sheet->setWidth(
                    array(
                        'A'=> '20',
                        'B'=> '30',
                        'C'=> '15',
                        'D'=> '15',
                        'E'=> '15',
                        'F'=> '20',
                        'G'=> '20',
                        'H'=> '20',
                        'I'=> '20',
                        'J'=> '20',
                        'K'=> '15',
                        'L'=> '15',
                        'M'=> '35',
                        'N'=> '15',
                        'O'=> '15',
                        'P'=> '15',
                        'Q'=> '25',
                        'R'=> '15',
                        'S'=> '15',
                        'T'=> '15',
                        'U'=> '15',
                        'V'=> '15',
                        'W'=> '15',
                        'X'=> '15'
                    )
                );

                for( $intRowNumber = $lastIndexRow+4; $intRowNumber <= $totalRows; $intRowNumber++){
                    $sheet->setheight($intRowNumber, 65);
                }

            });

        })->export('xlsx');
    }

    public function getGeneralReportsCharts($reports, $start_date, $end_date, $location_id, $locations) {
        // chart 1: by aspect
        $aspectImprove = $reports->where('aspect', 'Por mejorar')->count();
        $aspectPositive = $reports->where('aspect', 'Positivo')->count();

        // chart 2: by locations
        $byLocations = $this->getByLocationsData($reports);

        // chart 3: by critical risks
        $risks = $this->getByRisksData($reports);

        // chart 4: by areas
        $areas = $this->getByAreasData($reports);

        // chart 5: by state
        $open = $reports->where('state', 'Abierto')->count();
        $closed = $reports->where('state', 'Cerrado')->count();

        // chart 6: by responsible users
        $responsibleItems = $this->getByResponsibleData($reports);

        $openedReports = $reports->where('state', 'Abierto'); // all() convert the result into an array (Collection method)

        // chart 7: open reports by locations
        $openLocations = $this->getByLocationsData($openedReports);

        // chart 8: open reports by responsible users
        $byResponsibleOpenReports = $this->getByResponsibleData($openedReports);

        $charts = true;
        return view('informe.general')->with(compact(
            'charts', 'start_date', 'end_date',
            'aspectImprove', 'aspectPositive', // chart 1
            'byLocations', // chart 2
            'risks', // chart 3
            'areas', // chart 4
            'open', 'closed', // chart 5
            'responsibleItems', // chart 6
            'openLocations', // chart 7
            'byResponsibleOpenReports', // chart 8
            'location_id', 'locations'
        ));
    }

    public function getByLocationsData($reports) {
        $locations = Location::get(['id', 'name']);

        foreach ($locations as $key => $location) {
            $workFrontIds = $location->workfront()->pluck('id');
            $quantity = $reports->whereIn('work_front_id', $workFrontIds)->count();
            $location->y = $quantity;
        }

        $locationsWithReports = $locations->filter(function($location) {
            return $location->y > 0;
        })->values(); // re-key (necessary to convert into JSON)

        return $locationsWithReports;
    }

    public function getByRisksData($reports) {
        $risks = CriticalRisk::get(['id', 'name']);

        foreach ($risks as $risk) {
            $quantity = $reports->where('critical_risks_id', $risk->id)->count();
            $risk->y = $quantity;
        }

        $risksWithReports = $risks->filter(function($risk) {
            return $risk->y > 0;
        })->values(); // re-key (necessary to convert into JSON)

        return $risksWithReports;
    }

    public function getByAreasData($reports) {
        $areas = Area::get(['id', 'name']);

        foreach ($areas as $area) {
            $quantity = $reports->where('area_id', $area->id)->count();
            $area->y = $quantity;
        }

        $areasWithReports = $areas->filter(function($area) {
            return $area->y > 0;
        })->values(); // re-key (necessary to convert into JSON)

        return $areasWithReports;
    }

    public function getByResponsibleData($reports) {
        $users = User::withTrashed()->get(['id','name']);

        foreach ($users as $user) {
            $quantity = $reports->where('responsible_id', $user->id)->count();
            $user->y = $quantity;
        }

        $usersWithReports = $users->filter(function($user) {
            return $user->y > 0;
        })->values(); // re-key (necessary to convert into JSON)

        return $usersWithReports;
    }

}
