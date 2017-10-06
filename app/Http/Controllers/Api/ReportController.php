<?php

namespace App\Http\Controllers\Api;

use App\Report;
use App\User;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ReportController extends Controller
{
    public function byId($id)
    {
        $report = Report::where('id', $id)
            ->first([
                'id', // required to edit from the app
                'informe_id',
                'user_id', // to check if the authenticated user can edit the report
                'work_front_id', 'area_id', 'responsible_id', // will be changed for the names
                'aspect',
                'critical_risks_id', // will be changed for its name
                'potential', 'state',
                'image', 'image_action',
                'planned_date', 'deadline', 'inspections',
                'description', 'actions', 'observations',
                'created_at'
            ]);

        if ($report->image_action)
            $report->image_action = asset('images/action/' . $report->image_action);
        else
            $report->image_action = asset('images/action/default.png');

        if ($report->image)
            $report->image = asset('images/report/' . $report->image);
        else
            $report->image = asset('images/report/default.png');

        $report->work_front_name = $report->work_front->name;
        unset($report->work_front);

        $report->area_name = $report->area->name;
        unset($report->area);

        $report->responsible_name = $report->responsible->name;
        unset($report->responsible);

        $report->critical_risks_name = $report->critical_risks->name;
        unset($report->critical_risks);

        return $report;
    }

    public function byInform(Request $request)
    {
        $reports = Report::where('informe_id', $request->input('inform_id'))
            ->orderBy('state', 'asc')
            ->orderBy('id', 'desc')
            ->get([
                'id', // required to edit from the app
                'informe_id',
                'user_id', // to check if the authenticated user can edit the report
                'work_front_id', 'area_id', 'responsible_id', // will be changed for the names
                'aspect',
                'critical_risks_id', // will be changed for its name
                'potential', 'state',
                'image', 'image_action',
                'planned_date', 'deadline', 'inspections',
                'description', 'actions', 'observations',
                'created_at'
            ]);

        foreach ($reports as $report) {
            if ($report->image_action)
                $report->image_action = asset('images/action/' . $report->image_action);
            else
                $report->image_action = asset('images/action/default.png');

            if ($report->image)
                $report->image = asset('images/report/' . $report->image);
            else
                $report->image = asset('images/report/default.png');

            $report->work_front_name = $report->work_front->name;
            unset($report->work_front);

            $report->area_name = $report->area->name;
            unset($report->area);

            $report->responsible_name = $report->responsible->name;
            unset($report->responsible);

            $report->critical_risks_name = $report->critical_risks->name;
            unset($report->critical_risks);
        }
        return $reports;
    }

    public function store(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'inform_id' => 'required|exists:informes,id',
            'work_front' => 'required',
            'area' => 'required',
            'responsible' => 'required',
            'aspect' => 'required',
            'critical_risk' => 'required',
            'potential' => 'required',
            'state' => 'required',
            'planned_date' => 'required',
            'inspections' => 'required|numeric|min:1',
            'image' => 'isBase64',
            'image_action' => 'isBase64'
        ];

        $messages = [
            'work_front.required' => 'Es necesario escoger el frente de trabajo del reporte',
            'area.required' => 'Es necesario escoger el área del reporte',
            'responsible.required' => 'Es necesario escoger el responsable del reporte',
            'aspect.required' => 'Es necesario escoger el aspecto del reporte',
            'critical_risk.required' => 'Es necesario escoger el riesgo crítico del reporte',
            'potential.required' => 'Es necesario escoger el potencial del reporte',
            'state.required' => 'Es necesario escoger el estado del reporte',
            'planned_date.required' => 'Es necesario escoger la fecha planeada del reporte',
            'inspections.required' => 'Es necesario escribir una cantidad de inspecciones',
            'inspections.numeric' => 'Es necesario escribir una cantidad de inspecciones numérica',
            'inspections.min' => 'Es necesario escribir una cantidad de inspecciones adecuada',
            'image.isBase64' => 'Imagen del reporte no válida',
            'image_action.isBase64' => 'Imagen de acción no válida'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request) {
            $user_id = $request->input('user_id');
            $user = User::find($user_id);

            if ($user AND $user->role_id > 3) {
                $validator->errors()->add('role', 'No tiene permisos para crear un reporte');
            }

            if ($request->get('deadline') AND $request->get('deadline') < $request->get('planned_date')) {
                $validator->errors()->add('inconsistency', 'Inconsistencia de fechas');
            }

            if ($request->get('description') AND strlen($request->get('description'))<5) {
                $validator->errors()->add('description', 'Debe escribir como mínimo 5 caracteres en la descripción');
            }
        });

        if ($validator->fails()) {
            $data['success'] = false;
        } else {
            $data['success'] = true;

            $user_id = $request->input('user_id');

            $report = Report::create([
                'informe_id' => $request->get('inform_id'),
                'user_id' => $user_id,
                'work_front_id' => $request->get('work_front'),
                'area_id' => $request->get('area'),
                'responsible_id' => $request->get('responsible'),
                'aspect' => $request->get('aspect'),
                'critical_risks_id' => $request->get('critical_risk'),
                'potential' => $request->get('potential'),
                'state' => $request->get('state'),
                'planned_date' => $request->get('planned_date'),
                'deadline' => $request->get('deadline'),
                'inspections' => $request->get('inspections'),
                'description' => $request->get('description'),
                'actions' => $request->get('actions'),
                'observations' => $request->get('observations') ?: ''
            ]);

            if ($request->input('image')) {
                $imageBase64 = base64_decode($request->input('image'));
                $extension_image = 'jpg';
                $file_name_image = uniqid() . '.' . $extension_image;
                $path_image = public_path('images/report/' . $file_name_image);

                Image::make($imageBase64)
                    ->fit(285, 285)
                    ->save($path_image);

                $report->image = $file_name_image;
            }

            if ($request->input('image_action')) {
                $imageActionBase64 = base64_decode($request->input('image_action'));
                $extension_image_action = 'jpg';
                $file_name_image_action = uniqid() . '.' . $extension_image_action;
                $path_image_action = public_path('images/action/' . $file_name_image_action);

                Image::make($imageActionBase64)
                    ->fit(285, 285)
                    ->save($path_image_action);

                $report->image_action = $file_name_image_action;
            }

            $report->save();
        }

        $errorFields = $validator->messages()->toArray();
        $errors = [];
        foreach ($errorFields as $field => $errorField) {
            foreach ($errorField as $errorMessage) {
                $errors[] = $errorMessage;
            }
        }

        $data['errors'] = $errors;
        return $data;
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'work_front' => 'required',
            'area' => 'required',
            'responsible' => 'required',
            'aspect' => 'required',
            'critical_risk' => 'required',
            'potential' => 'required',
            'state' => 'required',
            'planned_date' => 'required',
            'inspections' => 'required|numeric|min:1',
            'image' => 'isBase64',
            'image_action' => 'isBase64'
        ];

        $messages = [
            'work_front.required' => 'Es necesario escoger el frente de trabajo del reporte',
            'area.required' => 'Es necesario escoger el área del reporte',
            'responsible.required' => 'Es necesario escoger el responsable del reporte',
            'aspect.required' => 'Es necesario escoger el aspecto del reporte',
            'critical_risk.required' => 'Es necesario escoger el riesgo crítico del reporte',
            'potential.required' => 'Es necesario escoger el potencial del reporte',
            'state.required' => 'Es necesario escoger el estado del reporte',
            'planned_date.required' => 'Es necesario escoger la fecha planeada del reporte',
            'inspections.required' => 'Es necesario escribir una cantidad de inspecciones',
            'inspections.numeric' => 'Es necesario escribir una cantidad de inspecciones numérica',
            'inspections.min' => 'Es necesario escribir una cantidad de inspecciones adecuada',
            'image.isBase64' => 'Imagen del reporte no válida',
            'image_action.isBase64' => 'Imagen de acción no válida'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request) {
            if ($request->get('deadline') ) {
                if ($request->get('deadline') < $request->get('planned_date')) {
                    $validator->errors()->add('inconsistency', 'Inconsistencia de fechas');
                }
            }

            if ($request->get('description') AND strlen($request->get('description'))<5) {
                $validator->errors()->add('description', 'Debe escribir como mínimo 5 caracteres en la descripción');
            }
        });

        if ($validator->fails()) {
            $data['success'] = false;
        } else {
            $data['success'] = true;

            $report = Report::find($id);
            $report->work_front_id = $request->get('work_front');
            $report->area_id = $request->get('area');
            $report->responsible_id = $request->get('responsible');
            $report->aspect = $request->get('aspect');
            $report->critical_risks_id = $request->get('critical_risk');
            $report->potential = $request->get('potential');
            $report->state = $request->get('state');
            $report->planned_date = $request->get('planned_date');
            $report->deadline = $request->get('deadline');
            $report->inspections = $request->get('inspections');
            $report->description = $request->get('description');
            $report->actions = $request->get('actions');
            $report->observations = $request->get('observations') ?: '';
            $report->save();

            if ($request->input('image')) {
                $path = public_path().'/images/report';
                File::delete($path.'/'.$report->image);

                $imageBase64 = base64_decode($request->input('image'));
                $extension_image = 'jpg';
                $file_name_image = uniqid() . '.' . $extension_image;
                $path_image = public_path('images/report/' . $file_name_image);

                Image::make($imageBase64)
                    ->fit(285, 285)
                    ->save($path_image);

                $report->image = $file_name_image;
            }

            if ($request->input('image_action')) {
                $path = public_path().'/images/action';
                File::delete($path.'/'.$report->image_action);

                $imageActionBase64 = base64_decode($request->input('image_action'));
                $extension_image_action = 'jpg';
                $file_name_image_action = uniqid() . '.' . $extension_image_action;
                $path_image_action = public_path('images/action/' . $file_name_image_action);

                Image::make($imageActionBase64)
                    ->fit(285, 285)
                    ->save($path_image_action);

                $report->image_action = $file_name_image_action;
            }

            $report->save();
        }

        $errorFields = $validator->messages()->toArray();
        $errors = [];

        foreach ($errorFields as $field => $errorField) {
            foreach ($errorField as $errorMessage) {
                $errors[] = $errorMessage;
            }
        }

        $data['errors'] = $errors;
        return $data;
    }

}
