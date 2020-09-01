<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;


use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    //
  public function getTag($id){
      $tag = Tag::find($id);
      if ($tag) {
          return response()->json([
              'data' => $tag
          ]);
      } else {
          return response()->json([
              'message' => "Customer Not Found"
          ]);
      }

  }
    public function getTagwithAuthUser($id)
    {

        $tags = Tag::where('user_id', '=', $id)->orderBy('id', 'ASC')->get();
        return response()->json([
            'data' => $tags
        ]);
    }
    public function deleteTag($id){
        $customer = Tag::find($id);
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
