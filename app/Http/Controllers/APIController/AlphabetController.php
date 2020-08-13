<?php

namespace App\Http\Controllers\APIController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Alphabet;

class AlphabetController extends Controller
{
    //
    public function store(Request $request)
    {
        $aplphabet = new Alphabet();
        $aplphabet->name = $request->name;
        $aplphabet->save();
        if ($aplphabet) {
            return response()->json([
                'message' => 'Alphabet Successfully Added!'
            ]);
        }
    }
    public function getAlphabets()
    {
        $aplphabets = Alphabet::all();
        if ($aplphabets) {
            return response()->json([
                'data' => $aplphabets
            ]);
        } else {
            return response()->json([
                'message' => 'Alphabets do not exist'
            ]);
        }
    }
}
