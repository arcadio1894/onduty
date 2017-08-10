<?php

namespace App\Http\Controllers;

use App\Informe;
use App\Observation;
use App\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Style_Alignment;
use PHPExcel_Worksheet_Drawing;

class ExcelController extends Controller
{

    public function getReportsExcel($id_informe){

        $nameExcel = "Informe ".$id_informe;

        Excel::create($nameExcel, function($excel) use ($id_informe) {

            $informe = Informe::with('location')->with('user')->find($id_informe);

            $excel->sheet('Informe', function($sheet) use($informe) {
                $dataexcel = [];
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

                // Cabecera del informe
                $sheet->row(1, ['RELACIÓN DE REPORTES DEL INFORME: '.$informe->id]);
                //array_push($dataexcel, ['REPORTE DE INFORME '.$informe->id]);
                $sheet->row(2, ['']);
                //array_push($dataexcel, ['', '', '', '']);
                $sheet->row(3, ['LOCALIZACIÓN: ', $informe->location->name]);
                //array_push($dataexcel, ['Localización', $informe->location->name]);
                $sheet->row(4, ['ONDUTY: ', $informe->user->name]);
                //array_push($dataexcel, ['Onduty', $informe->user->name]);
                $sheet->row(5, ['FECHA DE REGISTRO: ', $informe->updated_at]);
                //array_push($dataexcel, ['Fecha de registro', $informe->updated_at]);
                $sheet->row(6, ['FECHA DE VISITA: ', $informe->from_date->format('d/m/Y').' - '.$informe->to_date->format('d/m/Y')]);
                //array_push($dataexcel, ['Fecha de visita desde ', $informe->from_date->format('d/m/Y')]);
                //array_push($dataexcel, ['Fecha de visita hasta ', $informe->to_date->format('d/m/Y')]);
                $sheet->row(7, ['']);
                $sheet->row(8, ['']);
                //array_push($dataexcel, ['', '', '', '']);
                //array_push($dataexcel, ['', '', '', '']);

                // Observaciones del informe
                $observations = Observation::where('informe_id', $informe->id)->get();
                $sheet->row(9, ['Turno', 'Supervisor en turno', 'HSE en turno', 'N° de hombres', 'N° de mujeres', 'Total de personas', 'Horas en el turno', 'Horas de trabajo', 'Observaciones']);
                //array_push($dataexcel, ['Turno', 'Supervisor en turno', 'HSE en turno', 'N° de hombres', 'N° de mujeres', 'Total de personas', 'Horas en el turno', 'Horas de trabajo', 'Observaciones']);

                // SetBackground headTitle
                $sheet->cells('A9:I9', function($cells) {
                    $cells->setBackground('#01C3F3');
                    $cells->setAlignment('center');
                });
                $sheet->cells('A1:S1', function($cells) {
                    $cells->setBackground('#01C3F3');
                    $cells->setAlignment('center');
                    $cells->setVAlignment('center');
                });
                $sheet->cells('A3:A6', function($cells) {
                    $cells->setBackground('#01C3F3');
                    $cells->setAlignment('center');
                    $cells->setVAlignment('center');
                });


                $i = 0;
                foreach ($observations as $observation){
                    $row = [];
                    $row[0] = $observation->turn;
                    $row[1] = $observation->supervisor->name;
                    $row[2] = $observation->hse->name;
                    $row[3] = $observation->man;
                    $row[4] = $observation->woman;
                    $row[5] = $observation->total_people;
                    $row[6] = $observation->turn_hours;
                    $row[7] = $observation->work_hours;
                    $row[8] = $observation->observation;
                    //array_push($dataexcel, [$observation->turn, $observation->supervisor->name, $observation->hse->name, $observation->man, $observation->woman, $observation->total_people, $observation->turn_hours, $observation->work_hours, $observation->observation]);
                    $sheet->appendRow($row);
                    $i += 1;
                }
                $lastIndexRow = 9+$i;

                // Reportes del informe

                $reports = Report::where('informe_id', $informe->id)
                    ->with('user')
                    ->with('work_front')
                    ->with('area')
                    ->with('responsible')
                    ->with('critical_risks')
                    ->orderBy('state')->orderBy('created_at', 'desc')
                    ->get();

                $sheet->row($lastIndexRow+1, ['']);
                $sheet->row($lastIndexRow+2, ['']);
                $sheet->row($lastIndexRow+3, ['Fecha/Hora', 'Reportado por', 'Cargo', 'Frente de Trabajo', 'Área', 'Responsable', 'Cargo', 'Aspecto', 'Riesgo crítico', 'Descripción', 'Imagen', 'Potencial', 'Acción a tomar', 'Fecha planeada', 'Observacion', 'Imagen Accion', 'N° de inspecciones', 'Estado', 'Fecha de cierre']);
                //array_push($dataexcel, ['', '', '', '']);
                //array_push($dataexcel, ['', '', '', '']);
                //array_push($dataexcel, ['Fecha/Hora', 'Reportado por', 'Cargo', 'Frente de Trabajo', 'Área', 'Responsable', 'Cargo', 'Aspecto', 'Riesgo crítico', 'Descripción', 'Imagen', 'Potencial', 'Acción a tomar', 'Fecha planeada', 'Observacion', 'Imagen Accion', 'N° de inspecciones', 'Estado', 'Fecha de cierre']);

                $indexStart = $lastIndexRow+4;
                //dd($indexStart);
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
                    //array_push($dataexcel, [$report->created_at, $report->user->name, $report->user->position->name, $report->work_front->name, $report->area->name, $report->responsible->name, $report->responsible->position->name, $report->aspect, $report->critical_risks->name, $report->description, "IMAGEN", $report->potential, $report->actions, $report->planned_date, $report->observations, "IMAGEN ACCION", $report->inspections, $report->state, $report->deadline]);
                }
                $totalRows = $indexStart;
                //dd($totalRows);

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

}
