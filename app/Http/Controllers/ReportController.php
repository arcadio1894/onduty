<?php

namespace App\Http\Controllers;

use App\Area;
use App\CriticalRisk;
use App\Informe;
use App\Location;
use App\Report;
use App\User;
use App\WorkFront;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ReportController extends Controller
{
    public function index( $id )
    {
        $informe = Informe::with('location')->with('user')->find($id);
        $users = User::where('id', '<>', 1)->get();
        $workfronts = WorkFront::where('location_id', $informe->location_id)->get();
        $areas = Area::all();
        $risks = CriticalRisk::all();
        $reports = Report::where('informe_id', $id)
            ->with('user')
            ->with('work_front')
            ->with('area')
            ->with('responsible')
            ->with('critical_risks')
            ->get();

        
        //dd($reports);
        return view('report.index')->with(compact('reports', 'informe', 'workfronts', 'areas', 'users', 'risks'));
    }

    public function show( $id )
    {
        $informe = Informe::with('location')->with('user')->find($id);
        $users = User::where('id', '<>', 1)->get();
        $workfronts = WorkFront::where('location_id', $informe->location_id)->get();
        $areas = Area::all();
        $risks = CriticalRisk::all();

        //dd($informes);
        return view('report.show')->with(compact('informe', 'workfronts', 'areas', 'users', 'risks'));
    }

    public function showEdit( $informe_id, $report_id )
    {
        $informe = Informe::with('location')->with('user')->find($informe_id);
        $users = User::where('id', '<>', 1)->get();
        $workfronts = WorkFront::where('location_id', $informe->location_id)->get();
        $areas = Area::all();
        $risks = CriticalRisk::all();
        $report = Report::with('user')
        ->with('work_front')
        ->with('area')
        ->with('responsible')
        ->with('critical_risks')
        ->find($report_id);

        //dd($report);
        return view('report.showEdit')->with(compact('report', 'informe', 'workfronts', 'areas', 'users', 'risks'));
    }

    public function getLocations()
    {
        $locations = Location::all();
        return response()->json($locations);
    }

    public function getUsers()
    {
        $users = User::where('id', '<>', 1)->get();
        return response()->json($users);
    }

    public function store( Request $request )
    {
        //dd($request->all());

        $rules = array(
            'workfront' => 'required',
            'area' => 'required',
            'responsible' => 'required',
            'aspect' => 'required',
            'risk' => 'required',
            'potencial' => 'required',
            'state' => 'required',
            'planned-date' => 'required',
            'inspections' => 'required|min:1',
            'description' => 'min:5',
            'actions' => 'min:5',
            'image' => 'image',
            'image-action' => 'image',
        );
        $messages = array(
            'workfront.required'=>'Es necesario escoger el frente de trabajo del reporte',
            'area.required'=>'Es necesario escoger el área del reporte',
            'responsible.required'=>'Es necesario escoger el responsable del reporte',
            'aspect.required'=>'Es necesario escoger el aspecto del reporte',
            'risk.required'=>'Es necesario escoger el riesgo crítico del reporte',
            'potencial.required'=>'Es necesario escoger el potencial del reporte',
            'state.required'=>'Es necesario escoger el estado del reporte',
            'planned-date.required'=>'Es necesario escoger la fecha planeada del reporte',
            'inspections.required'=>'Es necesario escribir una cantidad de inspecciones',
            'inspections.min'=>'Es necesario escribir una cantidad de inspecciones adecuada',
            'actions.min'=>'Es necesario más de 5 caracteres para las acciones del reporte',
            'description.min'=>'Es necesario más de 5 caracteres para la descripción del reporte',
            'image.image' => 'Solo se admiten archivos tipo imagen',
            'image-action.image' => 'Solo se admiten archivos tipo imagen',
        );
        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para crear un reporte');
            }
            if ( $request->get('deadline') ) {
                if ($request->get('deadline') < $request->get('planned-date')) {
                    $validator->errors()->add('inconsistency', 'Inconsistencia de fechas');
                }
            }

        });

        if(!$validator->fails()) {
            $user = Auth::user();

            $report = Report::create([
                'informe_id' => $request->get('informe'),
                'user_id' => $user->id,
                'work_front_id' => $request->get('workfront'),
                'area_id' => $request->get('area'),
                'responsible_id' => $request->get('responsible'),
                'aspect' => $request->get('aspect'),
                'critical_risks_id' => $request->get('risk'),
                'potential' => $request->get('potencial'),
                'state' => $request->get('state'),
                'planned_date' => $request->get('planned-date'),
                'deadline' => $request->get('deadline'),
                'inspections' => $request->get('inspections'),
                'description' => $request->get('description'),
                'actions' => $request->get('actions'),
                'observations' => $request->get('observation')
            ]);

            if ($request->file('image'))
            {
                $extension_image = $request->file('image')->getClientOriginalExtension();
                $file_name_image = $report->id . '.' . $extension_image;

                $path_image = public_path('images/report/' . $file_name_image);

                Image::make($request->file('image'))
                    ->fit(144, 144)
                    ->save($path_image);

                $report->image = $extension_image;
                $report->save();
            }
            if ($request->file('image-action'))
            {
                $extension_image_action = $request->file('image-action')->getClientOriginalExtension();
                $file_name_image_action = $report->id . '.' . $extension_image_action;

                $path_image_action = public_path('images/action/' . $file_name_image_action);

                Image::make($request->file('image-action'))
                    ->fit(144, 144)
                    ->save($path_image_action);

                $report->image_action = $extension_image_action;
                $report->save();
            }

            $report->save();
        }

        // TODO: Faltan validaciones de los que crean

        return response()->json($validator->messages(), 200);

    }

    public function edit( Request $request )
    {
        //dd($request->all());
        $rules = array(
            'workfront' => 'required',
            'area' => 'required',
            'responsible' => 'required',
            'aspect' => 'required',
            'risk' => 'required',
            'potencial' => 'required',
            'state' => 'required',
            'planned-date' => 'required',
            'inspections' => 'required|min:1',
            'description' => 'min:5',
            'actions' => 'min:5',
            'observation' => 'min:5',
            'image' => 'image',
            'image-action' => 'image',
        );
        $messages = array(
            'workfront.required'=>'Es necesario escoger el frente de trabajo del reporte',
            'area.required'=>'Es necesario escoger el área del reporte',
            'responsible.required'=>'Es necesario escoger el responsable del reporte',
            'aspect.required'=>'Es necesario escoger el aspecto del reporte',
            'risk.required'=>'Es necesario escoger el riesgo crítico del reporte',
            'potencial.required'=>'Es necesario escoger el potencial del reporte',
            'state.required'=>'Es necesario escoger el estado del reporte',
            'planned-date.required'=>'Es necesario escoger la fecha planeada del reporte',
            'inspections.required'=>'Es necesario escribir una cantidad de inspecciones',
            'inspections.min'=>'Es necesario escribir una cantidad de inspecciones adecuada',
            'actions.min'=>'Es necesario más de 5 caracteres para las acciones del reporte',
            'description.min'=>'Es necesario más de 5 caracteres para la descripción del reporte',
            'observation.min'=>'Es necesario más de 5 caracteres para la observación del reporte',
            'image.image' => 'Solo se admiten archivos tipo imagen',
            'image-action.image' => 'Solo se admiten archivos tipo imagen',
        );
        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para editar un reporte');
            }
            if ( $request->get('deadline') ) {
                if ($request->get('deadline') < $request->get('planned-date')) {
                    $validator->errors()->add('inconsistency', 'Inconsistencia de fechas');
                }
            }

        });

        if(!$validator->fails()) {
            $report = Report::find($request->get('reporte'));

            $report->informe_id = $request->get('informe');
            $report->work_front_id = $request->get('workfront');
            $report->area_id = $request->get('area');
            $report->responsible_id = $request->get('responsible');
            $report->aspect = $request->get('aspect');
            $report->critical_risks_id = $request->get('risk');
            $report->potential = $request->get('potencial');
            $report->state = $request->get('state');
            $report->planned_date = $request->get('planned-date');
            $report->deadline = $request->get('deadline');
            $report->inspections = $request->get('inspections');
            $report->description = $request->get('description');
            $report->actions = $request->get('actions');
            $report->observations = $request->get('observation');


            if ( $request->file('image') != null OR $request->file('image') != "")
            {
                $path = public_path().'/images/report';
                File::delete($path.'/'.$request->get('reporte').'.'.$report->image);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileName = $request->get('reporte') . '.' . $extension;
                Image::make($request->file('image'))
                    ->fit(144, 144)
                    ->save($path.'/'.$request->get('reporte').'.'.$extension);
                $report->image = $extension;
            }

            if ( $request->file('image-action') != null OR $request->file('image-action') != "")
            {
                $path = public_path().'/images/action';
                File::delete($path.'/'.$request->get('reporte').'.'.$report->image);
                $extension = $request->file('image-action')->getClientOriginalExtension();
                $fileName = $request->get('reporte') . '.' . $extension;
                Image::make($request->file('image'))
                    ->fit(144, 144)
                    ->save($path.'/'.$request->get('reporte').'.'.$extension);
                $report->image_action = $extension;
            }

            // TODO: Faltan validaciones de los que crean

            $report->save();
        }


        return response()->json($validator->messages(), 200);
    }

    public function delete( Request $request )
    {
        //dd($request->all());
        // TODO: Solo el que puede creas es el super administrador o administrador
        $rules = array(
            'id' => 'exists:reports'
        );

        $messsages = array(
            'id.exists'=>'No existe el reporte especificada',
        );

        $validator = Validator::make($request->all(), $rules, $messsages);

        $validator->after(function ($validator) {
            if (Auth::user()->role_id > 2) {
                $validator->errors()->add('role', 'No tiene permisos para eliminar un reporte');
            }
        });

        if(!$validator->fails()) {
            $report = Report::find($request->get('id'));
            $report->delete();
        }
        // TODO: Validaciones en el futuro

        return response()->json($validator->messages(), 200);

    }
    
    
}
