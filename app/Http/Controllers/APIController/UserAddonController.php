<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserAddon;
class UserAddonController extends Controller
{

    public function store(Request $request){

        foreach ($request->addons as $key => $value) {
            $useraddon=new UserAddon();
            $useraddon->user_id=$request->userId;
            $useraddon->addon_id=$value;
            $useraddon->addon_status=1;
            $useraddon->save();
        }
        return response()->json($useraddon);

    }
}
