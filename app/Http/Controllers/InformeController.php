<?php

namespace App\Http\Controllers;

use App\Area;
use App\CriticalRisk;
use App\Informe;
use App\Report;
use App\User;
use App\WorkFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class InformeController extends Controller
{
    public function  __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $informes = Informe::with('location')->with('user')->orderBy('id', 'desc')->get();
        return view('informe.index')->with(compact('informes'));
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
    
    public function graphics($id) {
        $informe = Informe::with('location')->with('user')->find($id);

        // Gráfico de reportes segun aspecto
        $porMejorar = Report::where('aspect', 'Por Mejorar')->where('informe_id', $id)->count();
        $positivo = Report::where('aspect', 'Positivo')->where('informe_id', $id)->count();

        $opens = Report::where('state', 'Abierto')->where('informe_id', $id)->count();
        $closed = Report::where('state', 'Cerrado')->where('informe_id', $id)->count();

        return view('informe.graphics')->with(compact('opens', 'closed','informe', 'porMejorar', 'positivo'));
    }
    
    public function getWorkFrontsGraph($informe_id) {
        // Gráfico de reportes segun frentes de trabajo
        $informe = Informe::with('location')->with('user')->find($informe_id);
        $work_fronts = WorkFront::where('location_id', $informe->location_id)->get(['id','name']);
        $array = $work_fronts->toArray();
        foreach ($work_fronts as $k => $front){
            $cant = Report::where('informe_id', $informe_id)->where('work_front_id', $front['id'])->count();
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
