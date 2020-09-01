<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\InterlocutorsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Interlocutor;
class CsvController extends Controller
{
    public function import(Request $request)
    {
        foreach ($request->file as $item) {
            $interlocutor=new Interlocutor();
            $interlocutor->name=isset($item['name'])?$item['name']:NULL;
            $interlocutor->email=isset($item['email'])?$item['email']:NULL;
            $interlocutor->phone_number=isset($item['phone_number'])?$item['phone_number']:NULL;
            $interlocutor->user_id=isset($item['user_id'])?$item['user_id']:NULL;
            $interlocutor->save();
        }

        return response()->json([
            'status'=>'success'
        ]);



    }
}
