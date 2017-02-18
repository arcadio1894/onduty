<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class DataController extends Controller
{
    public function postProfileImage(Request $request) {

        $this->validate($request, [
           'photo' => 'required|image'
        ]);

        $user = Auth::user();
        $extension = $request->file('photo')->getClientOriginalExtension();
        $file_name = $user->id . '.' . $extension;

        $path = public_path('images/users/' . $file_name);

        Image::make($request->file('photo'))
            ->fit(144, 144)
            ->save($path);

        $user->image = $extension;
        $user->save();

        $data['success'] = true;
        $data['path'] = $file_name;

        return $data;
    }
}
