<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use App\Interlocutor;
use Illuminate\Http\Request;

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
        $customer->user_id = $request->user_id;
        $customer->save();
        if ($customer) {
            return response()->json([
                'message' => 'Customer successfully Added!',
                'customer' => $customer
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
}
