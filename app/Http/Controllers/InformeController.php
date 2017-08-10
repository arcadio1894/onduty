<?php

namespace App\Http\Controllers;

use App\Area;
use App\CriticalRisk;
use App\Informe;
use App\Location;
use App\Report;
use App\User;
use App\WorkFront;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Style_Alignment;
use PHPExcel_Worksheet_Drawing;

class InformeController extends Controller
{
    public function index()
    {
        $informes = Informe::with('location')->with('user')->orderBy('id', 'desc')->get();
        return view('informe.index')->with(compact('informes'));
    }

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

        // deliver work to the proper method (this form has 2 submit buttons)
        if ($request->has('excel'))
            return $this->getGeneralReportsExcel($reports, $start_date, $end_date);
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

    public function store(Request $request)
    {
        // Solo puede crear el super admin, admin o responsible

        $rules = array(
            'fromdate' => 'required',
            'todate' => 'required',
        );

        $messages = array(
            'fromdate.required' => 'Es necesario ingresar la la fecha de inicio de la visita',
            'todate.required' => 'Es necesario ingresar la fecha de fin de la visita',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request){
            if (Auth::user()->role_id > 3) {
                $validator->errors()->add('role', 'No tiene permisos para crear un informe');
            }

            if ($request->get('fromdate') > $request->get('todate')) {
                $validator->errors()->add('inconsistency', 'Inconsistencia en las fechas ingresadas');
            }
        });

        if (!$validator->fails()) {
            // Obtener el ultimo inform en la misma location, de este que se está creando
            $last_inform = Informe::where('location_id', Auth::user()->location_id)->orderBy('created_at', 'desc')->first();
            if ($last_inform) {
                $inherited_reports = Report::where('informe_id', $last_inform->id)->where('state', 'Abierto')->get();

                // Deshabilitamos al ultimo inform (tenga o no reportes abiertos)
                $last_inform->active = false;
                $last_inform->save();

                $inform = Informe::create([
                    'location_id' => Auth::user()->location_id,
                    'user_id' => Auth::user()->id,
                    'from_date' => $request->get('fromdate'),
                    'to_date' => $request->get('todate'),
                    'active' => true
                ]);

                // Heredamos los reports abiertos del ultimo inform si existen
                if ($inherited_reports) {
                    foreach ($inherited_reports as $inherited_report) {
                        $report = Report::create([
                            'informe_id' => $inform->id,
                            'user_id' => $inherited_report->user_id,
                            'work_front_id' => $inherited_report->work_front_id,
                            'area_id' => $inherited_report->area_id,
                            'responsible_id' => $inherited_report->responsible_id,
                            'aspect' => $inherited_report->aspect,
                            'critical_risks_id' => $inherited_report->critical_risks_id,
                            'potential' => $inherited_report->potential,
                            'state' => $inherited_report->state,
                            'planned_date' => $inherited_report->planned_date,
                            'deadline' => $inherited_report->deadline,
                            'inspections' => $inherited_report->inspections,
                            'description' => $inherited_report->description,
                            'actions' => $inherited_report->actions,
                            'observations' => $inherited_report->observations,
                            'image' => $inherited_report->image,
                            'image_action' => $inherited_report->image_action
                        ]);

                        // Vemos si existe la imagen
                        if (file_exists(public_path() . '/images/report/' . $inherited_report->id . '.' . $inherited_report->image)) {
                            // Copiar la imagen
                            $oldPath = public_path() . '/images/report/' . $inherited_report->id . '.' . $inherited_report->image;
                            $newPathWithName = public_path() . '/images/report/' . $report->id . '.' . $inherited_report->image;
                            File::copy($oldPath, $newPathWithName);
                        }

                        // Vemos si existe la imagen de action
                        if (file_exists(public_path() . '/images/action/' . $inherited_report->id . '.' . $inherited_report->image_action)) {
                            // Copiar la imagen
                            $oldPath = public_path() . '/images/action/' . $inherited_report->id . '.' . $inherited_report->image_action;
                            $newPathWithName = public_path() . '/images/action/' . $report->id . '.' . $inherited_report->image_action;
                            File::copy($oldPath, $newPathWithName);
                        }

                        $successfullyClonedReport = $report->save();
                        if ($successfullyClonedReport) {
                            $inherited_report->cloned_into_id = $report->id;
                            $inherited_report->save();
                        }
                    }
                }

                $inform->save();
            } else {
                // just create the new inform
                Informe::create([
                    'location_id' => Auth::user()->location_id,
                    'user_id' => Auth::user()->id,
                    'from_date' => $request->get('fromdate'),
                    'to_date' => $request->get('todate'),
                    'active' => true
                ]);
            }
        }

        return response()->json($validator->messages(), 200);
    }

    public function edit(Request $request)
    {
        $rules = array(
            'fromdate' => 'required',
            'todate' => 'required',
        );

        $messages = array(
            'fromdate.required' => 'Es necesario ingresar la la fecha de inicio de la visita',
            'todate.required' => 'Es necesario ingresar la fecha de fin de la visita',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request){
            if (Auth::user()->role_id > 3) {
                $validator->errors()->add('role', 'No tiene permisos para crear un informe');
            }

            if ($request->get('fromdate') > $request->get('todate')) {
                $validator->errors()->add('inconsistency', 'Inconsistencia en las fechas ingresadas');
            }
        });

        if (!$validator->fails()) {
            $informe = Informe::find( $request->get('id') );
            $informe->location_id = Auth::user()->location_id;
            $informe->user_id = Auth::user()->id;
            $informe->from_date = $request->get('fromdate');
            $informe->to_date = $request->get('todate');
            $informe->save();
        }

        return response()->json($validator->messages(), 200);

    }

    public function delete(Request $request)
    {
        // Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'id' => 'exists:informes'
        );

        $messsages = array(
            'id.exists'=>'No existe el informe especificado',
        );

        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) use($request) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar un informe');
            }

            $report = Report::where('informe_id', $request->get('id'))->first();
            if ($report) {
                $validator->errors()->add('report', 'No puede eliminar porque hay reportes dentro de este informe.');
            }
        });

        if(!$validator->fails()) {
            $informe = Informe::find($request->get('id'));
            $informe->delete();
        }

        // TODO: Validaciones en el futuro

        return response()->json($validator->messages(), 200);

    }
    
    public function graphics($id){
        $informe = Informe::with('location')->with('user')->find($id);

        // Gráfico de reportes segun aspecto
        $porMejorar = Report::where('aspect', 'Por Mejorar')->where('informe_id', $id)->get()->count();
        $positivo = Report::where('aspect', 'Positivo')->where('informe_id', $id)->get()->count();

        $opens = Report::where('state', 'Abierto')->where('informe_id', $id)->get()->count();
        $closed = Report::where('state', 'Cerrado')->where('informe_id', $id)->get()->count();

        //dd(json_encode($data));

        return view('informe.graphics')->with(compact('opens', 'closed','informe', 'porMejorar', 'positivo'));
    }
    
    public function getWorkFrontsGraph($informe_id){
        // Gráfico de reportes segun frentes de trabajo
        $informe = Informe::with('location')->with('user')->find($informe_id);
        $work_fronts = WorkFront::where('location_id', $informe->location_id)->get(['id','name']);
        $array = $work_fronts->toArray();
        foreach ($work_fronts as $k => $front){
            $cant = Report::where('informe_id', $informe_id)->where('work_front_id', $front['id'])->get()->count();
            if ($cant == 0){
                unset($array[$k]);
            } else {
                $array[$k]['y'] = $cant;
            }
        }
        $array = array_values($array);
        return json_encode($array);
    }

    public function getCriticalRisksGraph($informe_id){
        // Gráfico de reportes segun frentes de trabajo
        $informe = Informe::with('location')->with('user')->find($informe_id);

        $risks = CriticalRisk::get(['id','name']);
        $array = $risks->toArray();
        //dd($array);
        foreach ($risks as $k => $risk){
            $cant = Report::where('informe_id', $informe_id)->where('critical_risks_id', $risk['id'])->get()->count();
            if ($cant == 0){
                unset($array[$k]);
            } else {
                $array[$k]['y'] = $cant;
            }
        }
        $array = array_values($array);
        //dd($array);
        return json_encode($array);
    }

    public function getAreasGraph($informe_id){
        // Gráfico de reportes segun frentes de trabajo
        $informe = Informe::with('location')->with('user')->find($informe_id);

        $areas = Area::get(['id','name']);
        $array2 = $areas->toArray();
        //dd($array2);
        foreach ($array2 as $k => $a){
            $cant = Report::where('informe_id', $informe->id)->where('area_id', $a['id'])->get()->count();
            if ($cant == 0){
                //array_splice($array2, $k, 1);
                unset($array2[$k]);
            } else {
                $array2[$k]['y'] = $cant;
            }
        }
        $array2 = array_values($array2);
        //dd($array2);

        return $array2;
    }

    public function getResponsibleGraph($informe_id){
        // Gráfico de reportes segun frentes de trabajo
        $informe = Informe::with('location')->with('user')->find($informe_id);

        $users = User::withTrashed()->where('location_id', $informe->location_id)->get(['id','name']);

        $array = $users->toArray();
        foreach ($users as $k => $user){
            $cant = Report::where('informe_id', $informe_id)->where('responsible_id', $user['id'])->get()->count();
            if ($cant == 0){
                unset($array[$k]);
            } else {
                $array[$k]['y'] = $cant;
            }
        }
        $array = array_values($array);
        return json_encode($array);
    }

    public function getWorkFrontOpensGraph($informe_id){
        // Gráfico de reportes segun frentes de trabajo
        $informe = Informe::with('location')->with('user')->find($informe_id);
        $work_fronts = WorkFront::where('location_id', $informe->location_id)->get(['id','name']);
        $array = $work_fronts->toArray();
        foreach ($work_fronts as $k => $front){
            $cant = Report::where('state', 'Abierto')->where('informe_id', $informe_id)->where('work_front_id', $front['id'])->get()->count();
            if ($cant == 0){
                unset($array[$k]);
            } else {
                $array[$k]['y'] = $cant;
            }
        }
        $array = array_values($array);
        return json_encode($array);
    }

    public function getOpenResponsibleGraph($informe_id) {
        // Gráfico de reportes segun frentes de trabajo
        $informe = Informe::with('location')->with('user')->find($informe_id);

        $users = User::withTrashed()->where('location_id', $informe->location_id)->get(['id','name']);

        $array = $users->toArray();
        foreach ($users as $k => $user){
            $cant = Report::where('state', 'Abierto')->where('informe_id', $informe_id)->where('responsible_id', $user['id'])->get()->count();
            if ($cant == 0){
                unset($array[$k]);
            } else {
                $array[$k]['y'] = $cant;
            }
        }
        $array = array_values($array);
        return json_encode($array);
    }
    
}
