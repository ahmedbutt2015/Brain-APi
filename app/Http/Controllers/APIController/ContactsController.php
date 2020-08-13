<?php

namespace App\Http\Controllers\APIController;

use App\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    // TODO: Rehan
    public function getAllContacts() {
       $contacts=Contact::all();
       return response()->json([
           'data'=>$contacts
       ]);
    }

    public function createContact(Request $request) {
       $contact=new Contact();
       $contact->firstname=$request->firstname;
       $contact->lastname=$request->lastname;
       $contact->email=$request->email;
       $contact->phone_no=$request->phone_no;
       $contact->user_id=$request->user_id;
       $contact->save();
       return response()->json([
           'data'=>$contact,
             'message'=>"Successfully Added!"
       ]);
    }

    public function getContact($id) {
        $contact=Contact::find($id);
        if ($contact) {
            return response()->json([
                'data' => $contact
            ]);
        }else{
            return response()->json([
                'message' => "Contact Not Found"
            ]);
        }
    }

    public function updateContact(Request $request, $id) {
        $contact=Contact::find($id);
        if ($contact) {
            $contact->firstname=$request->firstname;
            $contact->lastname=$request->lastname;
            $contact->email=$request->email;
            $contact->phone_no=$request->phone_no;
            $contact->user_id=$request->user_id;
            $contact->save();

            return response()->json([
                'data' => $contact,
                'message'=>'successfully updated!'
            ]);
        }else{
            return response()->json([
                'message' => "Contact Not Found"
            ]);
        }
    }

    public function deleteContact ($id) {
        $contact=Contact::find($id);
        if ($contact) {
            $contact->delete();

            return response()->json([
                'message'=>'successfully Deleted!'
            ]);
        }else{
            return response()->json([
                'message' => "Contact Not Found"
            ]);
        }
    }
}
