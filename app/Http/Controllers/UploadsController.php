<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Storage;
use App\Company;

class UploadsController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'company']);
  }

  public function uploadCompanyLogo(Request $request)
  {
    $request->validate([
      'company_id'        => 'required'
    ]);

    $imagePath = '';
    if ($request->hasFile('imageData')) {
      $file = $request->file('imageData');
      $name = 'logo.';
      $name = $name . $file->getClientOriginalExtension();;
      $imagePath = 'companies/' . $request->company_id . '/' . $name;
      Storage::disk('local')->put($imagePath, file_get_contents($file), 'public');

      $company = Company::where('id', '=', request()->company_id)->first();
      $company->logo_path = $imagePath;
      $company->update();
    }

    return response()->json([
      'data'  => [
        'image_path'  =>  $imagePath
      ],
      'success' =>  true
    ]);
  }

  public function uploadUserPhoto(Request $request)
  {
    $request->validate([
      'userid'        => 'required'
    ]);

    $imagePath = '';
    if ($request->hasFile('attachment')) {
      $file = $request->file('attachment');
      $name = 'photo.';
      $name = $name . $file->getClientOriginalExtension();;
      $imagePath = 'users/' . $request->userid . '/' . $name;
      Storage::disk('local')->put($imagePath, file_get_contents($file), 'public');

      $user = User::where('id', '=', request()->userid)->first();
      $user->attachment = $imagePath;
      $user->update();
    }

    return response()->json([
      'data'  => [
        'image_path'  =>  $imagePath
      ],
      'success' =>  true
    ]);
  }
}
