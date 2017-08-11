<?php

namespace App\Http\Controllers;

use App\Informe;
use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GeneralInformController extends Controller
{
    public function general(Request $request)
    {
        // show the reports
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        if ($start_date && $end_date) {
            $start = Carbon::parse($start_date);
            $end = Carbon::parse($end_date);

            $informs = Informe::where('from_date', '>=', $start)
                ->where('to_date', '<=', $end)->pluck('id');
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

        // deliver work to the proper method (this form has submit buttons with different purposes)
        if ($request->has('excel'))
            return $this->getGeneralReportsExcel($reports, $start_date, $end_date);
        else if ($request->has('charts'))
            return $this->getGeneralReportsCharts($reports, $start_date, $end_date);
        // else
        return view('informe.general')->with(compact('reports', 'start_date', 'end_date'));
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

                $sheet->row(1, ["CONSOLIDADO DE REPORTES ENTRE $start_date y $end_date)"]);
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
                        $objDrawing->setPath(public_path('images/report/'.$report->id.'.'.$report->image));
                    }
                    $objDrawing->setCoordinates($coordinate);
                    $objDrawing->setWorksheet($sheet);
                    $objDrawing->setWidthAndHeight(110,90);
                    $coordinate2= 'P'.$indexStart;
                    if ($report->image_action == null){
                        $objDrawing2->setPath(public_path('images/action/default.png'));
                    } else {
                        $objDrawing2->setPath(public_path('images/action/'.$report->id.'.'.$report->image_action));
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

    public function getGeneralReportsCharts($reports, $start_date, $end_date) {
        // chart 1: by aspect
        $aspectImprove = $reports->where('aspect', 'Por mejorar')->count();
        $aspectPositive = $reports->where('aspect', 'Positivo')->count();

        // chart 5: by state
        $open = $reports->where('state', 'Abierto')->count();
        $closed = $reports->where('state', 'Cerrado')->count();

        $charts = true;
        return view('informe.general')->with(compact(
            'charts', 'start_date', 'end_date',
            'aspectImprove', 'aspectPositive', // chart 1
            'open', 'closed' // chart 5
        ));
    }
}
