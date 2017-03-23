<?php

namespace App\Http\Controllers;

use App\Informe;
use App\Observation;
use App\Report;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function getReportsExcel($id_informe){

        $nameExcel = "Informe ".$id_informe;

        Excel::create($nameExcel, function($excel) use ($id_informe) {

            $informe = Informe::with('location')->with('user')->find($id_informe);

            $excel->sheet('Informe', function($sheet) use($informe) {
                $dataexcel = [];
                $sheet->mergeCells('A1:J1');
                $sheet->getDefaultStyle()
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);

                // Cabecera del informe
                array_push($dataexcel, ['REPORTE DE INFORME '.$informe->id]);
                array_push($dataexcel, ['', '', '', '']);
                array_push($dataexcel, ['Localización', $informe->location->name]);
                array_push($dataexcel, ['Onduty', $informe->user->name]);
                array_push($dataexcel, ['Fecha de registro', $informe->updated_at]);
                array_push($dataexcel, ['Fecha de visita desde ', $informe->from_date->format('d/m/Y')]);
                array_push($dataexcel, ['Fecha de visita hasta ', $informe->to_date->format('d/m/Y')]);

                array_push($dataexcel, ['', '', '', '']);
                array_push($dataexcel, ['', '', '', '']);

                // Observaciones del informe
                $observations = Observation::where('informe_id', $informe->id)->get();
                array_push($dataexcel, ['Turno', 'Supervisor en turno', 'HSE en turno', 'N° de hombres', 'N° de mujeres', 'Total de personas', 'Horas en el turno', 'Horas de trabajo', 'Observaciones']);
                foreach ($observations as $observation){
                    array_push($dataexcel, [$observation->turn, $observation->supervisor->name, $observation->hse->name, $observation->man, $observation->woman, $observation->total_people, $observation->turn_hours, $observation->work_hours, $observation->observation]);
                }

                // Reportes del informe

                $reports = Report::where('informe_id', $informe->id)
                    ->with('user')
                    ->with('work_front')
                    ->with('area')
                    ->with('responsible')
                    ->with('critical_risks')
                    ->get();
                array_push($dataexcel, ['', '', '', '']);
                array_push($dataexcel, ['', '', '', '']);
                array_push($dataexcel, ['Fecha/Hora', 'Reportado por', 'Cargo', 'Frente de Trabajo', 'Área', 'Responsable', 'Cargo', 'Aspecto', 'Riesgo crítico', 'Descripción', 'Imagen', 'Potencial', 'Acción a tomar', 'Fecha planeada', 'Observacion', 'Imagen Accion', 'N° de inspecciones', 'Estado', 'Fecha de cierre']);
                foreach ($reports as $report){
                    array_push($dataexcel, [$report->created_at, $report->user->name, $report->user->position->name, $report->work_front->name, $report->area->name, $report->responsible->name, $report->responsible->position->name, $report->aspect, $report->critical_risks->name, $report->description, "IMAGEN", $report->potential, $report->actions, $report->planned_date, $report->observations, "IMAGEN ACCION", $report->inspections, $report->state, $report->deadline]);
                }


                $sheet->cells(function ($cells){
                    $cells->setBackground('#F5F5F5');
                    $cells->setAlignment('center');
                    $cells->setVAlignment('center');
                });
                $sheet->setWidth(
                    array(
                        'A'=> '15',
                        'B'=> '30',
                        'C'=> '15',
                        'D'=> '15',
                        'E'=> '15',
                        'F'=> '15',
                        'G'=> '15',
                        'H'=> '15',
                        'I'=> '15',
                        'J'=> '15',
                        'K'=> '15',
                        'L'=> '15',
                        'M'=> '35',
                        'N'=> '15',
                        'O'=> '15',
                        'P'=> '15',
                        'Q'=> '15',
                        'R'=> '15',
                        'S'=> '15',
                        'T'=> '15',
                        'U'=> '15',
                        'V'=> '15',
                        'W'=> '15',
                        'X'=> '15'
                    )
                );
                $sheet->fromArray($dataexcel, null, 'A1', false, false);

            });

        })->export('xlsx');
    }
}
