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
        if ($request->get('workfront') == null OR $request->get('workfront') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el frente de trabajo del reporte']);

        if ($request->get('area') == null OR $request->get('area') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el área del reporte']);

        if ($request->get('responsible') == null OR $request->get('responsible') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el responsable del reporte']);

        if ($request->get('aspect') == null OR $request->get('aspect') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el aspecto del reporte']);

        if ($request->get('risk') == null OR $request->get('risk') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el riesgo crítico del reporte']);

        if ($request->get('potencial') == null OR $request->get('potencial') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el potencial del reporte']);

        if ($request->get('state') == null OR $request->get('state') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el estado del reporte']);

        if ($request->get('planned-date') == null OR $request->get('planned-date') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el fecha planeada del reporte']);
        
        if ($request->get('deadline') < $request->get('deadline'))
            return response()->json(['error' => true, 'message' => 'Inconsistencia de fechas']);

        if ($request->get('inspections') == null OR $request->get('inspections') == "" OR $request->get('inspections') < 1)
            return response()->json(['error' => true, 'message' => 'Es necesario escribir una cantidad adecuada']);

        if ($request->get('description') != null AND strlen($request->get('description')) < 5)
            return response()->json(['error' => true, 'message' => 'Es necesario mas de 5 caracteres la descripción del reporte']);

        if ($request->get('actions') != null AND strlen($request->get('actions')) <5)
            return response()->json(['error' => true, 'message' => 'Es necesario escribir mas de 5 caracteres las acciones a tomar en el reporte']);

        if ($request->get('observation') != null AND strlen($request->get('observation')) < 5)
            return response()->json(['error' => true, 'message' => 'Es necesario escribir mas de 5 caracteres las observaciones en el reporte']);

        if ( $request->file('image') == null OR $request->file('image') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario subir una imagen para el reporte']);

        if ( $request->file('image-action') == null OR $request->file('image-action') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario subir una imagen de la acción tomada para el reporte']);
        
        // TODO: Faltan validaciones de los que crean
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
            'observations' => $request->get('observation'),
            'image' => $request->file('image')->getClientOriginalExtension(),
            'image_action' => $request->file('image-action')->getClientOriginalExtension()
        ]);

        $extension_image = $request->file('image')->getClientOriginalExtension();
        $file_name_image = $report->id . '.' . $extension_image;

        $path_image = public_path('images/report/' . $file_name_image);

        Image::make($request->file('image'))
            ->fit(144, 144)
            ->save($path_image);

        $extension_image_action = $request->file('image-action')->getClientOriginalExtension();
        $file_name_image_action = $report->id . '.' . $extension_image_action;

        $path_image_action = public_path('images/action/' . $file_name_image_action);

        Image::make($request->file('image-action'))
            ->fit(144, 144)
            ->save($path_image_action);

        $report->save();
        return response()->json(['error' => false, 'message' => 'Informe registrado correctamente']);
    }

    public function edit( Request $request )
    {
        //dd($request->all());
        if ($request->get('workfront') == null OR $request->get('workfront') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el frente de trabajo del reporte']);

        if ($request->get('area') == null OR $request->get('area') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el área del reporte']);

        if ($request->get('responsible') == null OR $request->get('responsible') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el responsable del reporte']);

        if ($request->get('aspect') == null OR $request->get('aspect') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el aspecto del reporte']);

        if ($request->get('risk') == null OR $request->get('risk') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el riesgo crítico del reporte']);

        if ($request->get('potencial') == null OR $request->get('potencial') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el potencial del reporte']);

        if ($request->get('state') == null OR $request->get('state') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el estado del reporte']);

        if ($request->get('planned-date') == null OR $request->get('planned-date') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el fecha planeada del reporte']);

        if ($request->get('deadline') == null OR $request->get('deadline') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario escoger el fecha de cierre del reporte']);

        if ($request->get('deadline') < $request->get('deadline'))
            return response()->json(['error' => true, 'message' => 'Inconsistencia de fechas']);

        if ($request->get('inspections') == null OR $request->get('inspections') == "" OR $request->get('inspections') < 0)
            return response()->json(['error' => true, 'message' => 'Es necesario escribir una cantidad adecuada']);

        if ($request->get('description') == null OR $request->get('description') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario indicar la descripción del reporte']);

        if ($request->get('actions') == null OR $request->get('actions') == "")
            return response()->json(['error' => true, 'message' => 'Es necesario indicar las acciones a tomar en el reporte']);

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
        return response()->json(['error' => false, 'message' => 'Reporte modificado correctamente']);
    }

    public function delete( Request $request )
    {
        //dd($request->all());
        // TODO: Solo el que puede creas es el super administrador o administrador
        if (Auth::user()->role_id > 2)
            return response()->json(['error' => true, 'message' => 'No tiene permisos para eliminar un informe.']);

        $report = Report::find($request->get('id'));

        if($report == null)
            return response()->json(['error' => true, 'message' => 'No existe el reporte especificado.']);

        // TODO: Validaciones en el futuro

        $report->delete();

        return response()->json(['error' => false, 'message' => 'Reporte eliminado correctamente.']);

    }
    
    
}
