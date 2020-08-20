<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Storage;
use App\PlanTravellingDetail;
use App\SubProduct;

class UploadController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth:api', 'company']);
  }

  public function uploadProfileImage(Request $request)
  {
    $request->validate([
      'user_id'        => 'required',
      'profile_image'  =>  'required'
    ]);

    $image_path = optional($request->file('profile_image'))
      ->store('profileImages', 'public');

    $user = User::where('id', '=', $request->user_id)->first();
    $user->image_path = $image_path;
    $user->update();

    return response()->json([
      'data'  => [
        'image_path'  =>  $image_path
      ],
      'success' =>  true
    ]);
  }

  public function uploadProfile(Request $request)
  {
    $image = $request->image;
    $name = $request->name;

    $realImage = base64_decode($image);
    $path = "profileImages/" . request()->user()->id . '/' . $name;

    Storage::disk('s3')->put($path, $realImage, 'public');

    $user = User::where('id', '=', request()->user()->id)->first();
    $user->image_path = $path;
    $user->update();

    return response()->json([
      'data'  => [
        'image_path'  =>  $path
      ],
      'success' =>  true
    ]);
  }

  public function uploadSignature(Request $request)
  {
    $image = $request->image;
    $name = $request->name;

    $realImage = base64_decode($image);
    $path = "signatureImages/" . request()->user()->id . '/' . $name;

    Storage::disk('s3')->put($path, $realImage, 'public');

    $user = User::where('id', '=', request()->user()->id)->first();
    $user->terms_accepted = '1';
    $user->update();

    return response()->json([
      'data'  => [
        'image_path'  =>  $path
      ],
      'success' =>  true
    ]);
  }

  public function uploadBill(Request $request, $id)
  {
    $image = $request->image;
    $name = $request->name;

    $realImage = base64_decode($image);
    $path = "billImages/" . $id . '/' . $name;

    Storage::disk('s3')->put($path, $realImage, 'public');

    $planTravellingDetail = PlanTravellingDetail::where('id', '=', $id)->first();
    $planTravellingDetail->image_path = $path;
    $planTravellingDetail->update();

    return response()->json([
      'data'  => [
        'image_path'  =>  $path 
      ],
      'success' =>  true
    ]);
  }

  public function uploadAttachments(Request $request)
  {
    $request->validate([
      'id'  =>  'required'
    ]); 

    $image1Path = '';
    $image2Path = '';
    $image3Path = '';
    $image4Path = '';

    // if(count(Storage::disk('s3')->exists('digiloop/subProducts/' . $request->id)) > 0)
    // {
    //     Storage::disk('s3')->deleteDirectory('digiloop/subProducts/' . $request->id);
    // }

    if ($request->hasFile('image1')) {
      $file = $request->file('image1');
      $name = $file->getClientOriginalName();
      // $name = $name . $file->getClientOriginalExtension();;
      $image1Path = 'subProducts/' . $request->id . '/' . $name;
      Storage::disk('s3')->put('digiloop/' .$image1Path, file_get_contents($file), 'public');
    }
    if ($request->hasFile('image2')) {
      $file = $request->file('image2');
      $name = $file->getClientOriginalName();
      // $name = $name . $file->getClientOriginalExtension();;
      $image2Path = 'subProducts/' . $request->id . '/' . $name;
      Storage::disk('s3')->put('digiloop/' .$image2Path, file_get_contents($file), 'public');
    }
    if ($request->hasFile('image3')) {
      $file = $request->file('image3');
      $name = $file->getClientOriginalName();
      // $name = $name . $file->getClientOriginalExtension();;
      $image3Path = 'subProducts/' . $request->id . '/' . $name;
      Storage::disk('s3')->put('digiloop/' .$image3Path, file_get_contents($file), 'public');
    }
    if ($request->hasFile('image4')) {
      $file = $request->file('image4');
      $name = $file->getClientOriginalName();
      // $name = $name . $file->getClientOriginalExtension();;
      $image4Path = 'subProducts/' . $request->id . '/' . $name;
      Storage::disk('s3')->put('digiloop/' .$image4Path, file_get_contents($file), 'public');
    }

    $subProduct = SubProduct::where('id', '=', request()->id)->first();
    if($image1Path != "")
      $subProduct->image1_path = $image1Path;
    if($image2Path != "")
      $subProduct->image2_path = $image2Path;
    if($image3Path != "")
      $subProduct->image3_path = $image3Path;
    if($image4Path != "")
      $subProduct->image4_path = $image4Path;
      $subProduct->update();

    return response()->json([
      'data'  => $subProduct,
      'success' =>  true
    ]);
  }
}
