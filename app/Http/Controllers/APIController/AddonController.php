<?php

namespace App\Http\Controllers\APIController;

use App\Addon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    public function createAddon(Request $request){
       $addon=new Addon();
       $addon->name=$request->name;
       $addon->family_id=$request->family_id;
       $addon->save();
       if ($addon){
           return response()->json([
               'message'=>'Addon Successfully Added!'
           ]);
       }else{
           return response()->json([
               'message'=>'Something went wrong!'
           ]);
       }

    }
}
