<?php

namespace App\Imports;

use App\Interlocutor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class InterlocutorsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Interlocutor([
            'name'=>$row['name'],
            'email'=> $row['email'],
            'phone_number'=> $row['phone_number'],
            'user_id'=> $row['user_id'],
        ]);
    }
}
