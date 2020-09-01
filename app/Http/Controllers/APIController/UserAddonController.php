<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use App\System;
use http\Env\Response;
use Illuminate\Http\Request;
use App\UserAddon;
use App\Addon;
class UserAddonController extends Controller
{

    public function store(Request $request){
        UserAddon::where('user_id',$request->userId)->delete();
        foreach ($request->get('addons',[]) as $key => $value) {
            UserAddon::create([
                "user_id" => $request->userId,
                "system_id" => $request->system_id,
                "addon_id" => $value,
                "addon_status" => 1,
            ]);
        }
        $arr['user_name_list'] = $request->get('user_name_list','');
        $arr['user_name_single'] = $request->get('user_name_single','');
        $arr['contact_name_list'] = $request->get('contact_name_list','');
        $arr['contact_name_single'] = $request->get('contact_name_single','');
        $arr['language'] = $request->get('language','');
        $arr['currency'] = $request->get('currency','');
        $system = System::find($request->system_id);
        $system->data = json_encode($arr);
        $system->save();
        return response()->json([
            "status" => true
        ]);
    }
}
//            $useraddon=new UserAddon();
//            $useraddon->user_id=$request->userId;
//            $useraddon->addon_id=$value;
//            $useraddon->addon_status=1;
//            $useraddon->save();
