<?php

namespace App\Http\Controllers\APIController;

use App\Family;
use App\Http\Controllers\Controller;
use App\System;
use App\UserAddon;
use Illuminate\Http\Request;
use App\Addon;
class FamilyController extends Controller
{
   public function generalSetting(){
    $families=Family::with('addons')->get();
    return response()->json(
        [
            'data'=>$families,
        ]
    );
   }
    public function getAllFamilies(Request $request){
        $families=Family::with('addons','addons.useraddons')->get();
        $active_addons = UserAddon::where('system_id',$request->system_id)->get()->pluck('addon_id')->toArray();
        $general_active_addons = UserAddon::where('system_id',$request->system_id)
            ->leftJoin('addons','addons.id','=','user_addons.addon_id')->where('addons.family_id','=',1)->get()
            ->pluck('name')->toArray();
        $activeAddons=UserAddon::select('addons.*')->leftJoin('addons','addons.id','=','user_addons.addon_id')->where('system_id',$request->system_id)->get()->pluck('name');

        $activeNames = array();
         foreach ($activeAddons as $value){
               $activeAddonsNames = explode("for",$value);
               $activeName = str_replace(' ', '', $activeAddonsNames[1]);
              $actives = str_replace('?', '', $activeName);
              $active = str_replace('"}', '', $actives);
             $activeNames[]=strtolower($active);
         }

        return response()->json(
            [
                'data'=>$families,
                'system'=>System::find($request->system_id),
                'active_addons'=>$active_addons,
                'general_active_addons'=>$general_active_addons,
                'activeNames'=>$activeNames
            ]
        );
    }
    public function getEditSystem($id){
     $editSystem =  System::find($id);
     return response()->json($editSystem);
    }
        public function getDeleteSystem($id){
        $System =  System::find($id);
        if ($System) {
            $System->delete();

            return response()->json([
                'message'=>'successfully Deleted!'
            ]);
        }else{
            return response()->json([
                'message' => "Contact Not Found"
            ]);
        }
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
