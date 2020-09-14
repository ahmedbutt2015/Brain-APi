<?php

namespace App\Http\Controllers\APIController;

use App\Code;
use App\Http\Controllers\Controller;
use App\Interlocutor;
use App\Tag;
use App\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class InterlocutorController extends Controller
{
    public function getAllcustomers()
    {
        $customers = Interlocutor::orderBy('name', 'ASC')->get();
        return response()->json([
            'data' => $customers
        ]);
    }
    public function getCustomerwithAuthUser($id)
    {
        $customers = Interlocutor::where('user_id', '=', $id)->orderBy('name', 'ASC')->get();
        return response()->json([
            'data' => $customers
        ]);
    }
    public function store(Request $request)
    {

        $customer = new Interlocutor();

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone_number = $request->phone_no;
        $customer->image = $request->file;
        $customer->user_id = $request->user_id;
        $customer->save();

        foreach ($request->tags as $value) {

            if ($alreadyTag=Tag::where('name', '=', $value)->first()) {
                $customer->tags()->attach($alreadyTag);
            }else{
                $tag = new Tag();
                $tag->name=$value;
                $tag->user_id=$request->user_id;
                $tag->save();
                $customer->tags()->attach($tag);
            }
         }

        if ($customer) {
            return response()->json([
                'message' => 'Customer successfully Added!',
                'customer' => $customer,
            ]);
        } else {
            return response()->json([
                'message' => 'Something went wrong!'
            ]);
        }
    }


    public function getCustomer($id)
    {
        $customer = Interlocutor::find($id);
        if ($customer) {
            return response()->json([
                'data' => $customer
            ]);
        } else {
            return response()->json([
                'message' => "Customer Not Found"
            ]);
        }
    }
    public function updateCustomer(Request $request, $id)
    {
        $customer = Interlocutor::find($id);
        if ($customer) {
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone_number = $request->phone_no;
            $customer->image = $request->file;
            $customer->user_id = $request->user_id;
            $customer->save();

            return response()->json([
                'data' => $customer,
                'message' => 'successfully updated!'
            ]);
        } else {
            return response()->json([
                'message' => "Customer Not Found"
            ]);
        }
    }


    public function deleteCustomer($id)
    {
        $customer = Interlocutor::find($id);
        if ($customer) {
            $customer->delete();

            return response()->json([
                'message' => 'successfully Deleted!'
            ]);
        } else {
            return response()->json([
                'message' => "Contact Not Found"
            ]);
        }
    }
    public function getOnlineCustomers($id){
        
        $customer = Interlocutor::where('hashCode','=',$id)->first();
        $customer->is_online=1;
        $customer->is_code=1;
         $customer->save();
         if ($customer) {
             return response()->json([
                 'customer' => $customer
             ]);
         }else{
             return response()->json([
                 'message'=>"Customer not found"
             ]);
         }
    }

    public  function saveCodes(Request $request){
     $code= new Code();
     $code->name=$request->name;
     $code->url=$request->url;
     $code->icons_list=$request->icons;
     $code->colors_list=$request->colors;
     $code->user_id=$request->user_id;
     $code->save();
     if ($code){
         return response()->json([
             'message' => 'Customer successfully Added!',
             'customer' => $code,
         ]);
     }else{
         return response()->json([
             'message' => 'Something went wrong!'
         ]);
     }
    }
    public function getCodes($id){
          $codes=Code::where('user_id','=',$id)->get()->toArray();
          if ($codes){
              return response()->json([
                  'data'=>$codes
              ]);
          }else{
              return response()->json([
                  'message'=>"Code not found"
              ]);
          }
    }
    public function getSingleCode($id){
        $code=Code::find($id);
        if ($code){
            return response()->json([
                'data'=>$code
            ]);
        }else{
            return response()->json([
                'message'=>'Code not found'
            ]);
        }
    }
    public function updateCode(Request $request,$id){
        $code = Code::find($id);
        if ($code) {
            $code->name=$request->name;
            $code->url=$request->url;
            $code->icons_list=$request->icons;
            $code->colors_list=$request->colors;
            $code->user_id=$request->user_id;
            $code->save();

            return response()->json([
                'data' => $code,
                'message' => 'successfully updated!'
            ]);
        } else {
            return response()->json([
                'message' => "Code Not Found"
            ]);
        }
    }
    public function deleteCode($id){
        $code = Code::find($id);
        if ($code) {
            $code->delete();

            return response()->json([
                'message' => 'successfully Deleted!'
            ]);
        } else {
            return response()->json([
                'message' => "Contact Not Found"
            ]);
        }
    }
    public function getInterlocutorsData(Request $request){

        $systemData=System::select('data')->where('user_id','=',$request->user_id)->where('url','=',$request->url)->first();
        if ($systemData) {
            return response()->json(
                $systemData
            );
        }else{
            return response()->json([
                'message'=>'No system data available'
            ]);
        }

    }
    public function getRelatedCustomer($id){
        $tag=Tag::find($id);
        $interlocutors=$tag->interlocutors;
        return response()->json([
            'data'=>$interlocutors
        ]);
  }
  public function getOnlineCustomersHash(Request $request){
        
    $customer = Interlocutor::where('hashCode','=',$request->encryptedData)->first();
        $code=Code::find($request->id);
    if($code->colors_list==="Green Color"){
        $customer->is_online=1;
        $customer->is_code=1;
        $customer->save();        
    }elseif($code->colors_list==="Blue Color"){
        $customer->is_online=2;
        $customer->is_code=2; 
        $customer->save();
    }elseif($code->colors_list==="Red Color"){
        $customer->is_online=3;
        $customer->is_code=3;
        $customer->save(); 
    }

     if ($customer) {
         return response()->json([
             'customer' => $customer
         ]);
     }else{
         return response()->json([
             'message'=>"Customer not found"
         ]);
     }
  }
}
