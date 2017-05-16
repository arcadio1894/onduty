<?php

namespace App\Http\Controllers\Api;

use App\Informe;
use App\Report;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class InformController extends Controller
{
    public function byUserLocation(Request $request)
    {
        // get the location where the user belongs
        $user = User::find($request->input('user_id'));
        $location_id = $user->location_id;

        // and the informs in that location
        $informs = Informe::where('location_id', $location_id)
            ->orderBy('id', 'desc')->get([
            'id',
            'user_id', 'from_date', 'to_date',
            'created_at'
        ]);

        foreach ($informs as $inform) {
            $inform->user_name = $inform->user->name; // append the user name
            unset($inform->user); // but not include all the information

            $inform->from_date_format = $inform->from_date->format('d/m/Y');
            $inform->to_date_format = $inform->to_date->format('d/m/Y');
            unset($inform->from_date);
            unset($inform->to_date);

            $inform->isEditable = false;
        }

        // make the last registered inform editable
        $informs->first()->isEditable = true;

        return $informs;
    }

    public function store( Request $request )
    {
        // IT IS AN EXACT COPY OF THE LOGIC USED IN INFORMECONTROLLER

        // Solo puede crear el super administrador, administrador o responsable

        $rules = [
            'user_id' => 'required|exists:users,id',
            'from_date' => 'required',
            'to_date' => 'required',
        ];
        $messages = [
            'from_date.required' => 'Es necesario ingresar la la fecha de inicio de la visita',
            'to_date.required' => 'Es necesario ingresar la fecha de fin de la visita',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // additional validations
        $validator->after(function ($validator) use ($request) {
            $user_id = $request->input('user_id');
            if (User::find($user_id)->role_id > 3) {
                $validator->errors()->add('role', 'No tiene permisos para crear un informe');
            }

            if ($request->get('from_date') > $request->get('to_date')) {
                $validator->errors()->add('inconsistency', 'Inconsistencia en las fechas ingresadas');
            }
        });

        // check if it fails
        if ($validator->fails()) {
            $data['success'] = false;
            $data['errors'] = $validator->messages();

            return $data;
        }

        // go and register the new inform
        $user_id = $request->input('user_id');
        $user = User::find($user_id);

        // Obtener el ultimo informe en la misma location de este que se esta creando
        $last_inform = Informe::where('location_id', $user->location_id)->orderBy('created_at', 'desc')->first();
        if ($last_inform) {
            $inherited_reports = Report::where('informe_id', $last_inform->id)->where('state', 'Abierto')->get();

            // Deshabilitamos al ultimo informe tenga o no reportes abiertos
            $last_inform->active = false;
            $last_inform->save();

            $inform = Informe::create([
                'location_id' => $user->location_id,
                'user_id' => $user->id,
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'active' => true
            ]);

            // inherit the still OPEN reports (from the last inform)
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

                    $report->save();
                }
            }

            $inform->save();
        } else {
            Informe::create([
                'location_id' => $user->location_id,
                'user_id' => $user->id,
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'active' => true
            ]);
        }

        $data['success'] = true;
        return $data;
    }
}
