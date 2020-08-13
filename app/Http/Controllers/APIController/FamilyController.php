<?php

namespace App\Http\Controllers\APIController;

use App\Family;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FamilyController extends Controller
{

    public function getAllFamilies(){
        $families=Family::with('addons','addons.useraddons')->get();
        return response()->json(
            [
                'data'=>$families
            ]
        );


    }
    public function createFamily(Request $request)
    {
         $family=new Family();
         $family->name=$request->name;
         $family->save();
         if ($family){
             return response()->json([
                 'message'=>'Successfully Added!'
             ]);
         }else{
             return response()->json([
                 'message'=>'Something went wrong!'
             ]);
         }
    }

}
