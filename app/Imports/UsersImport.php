<?php

namespace App\Imports;
use App\Models\User;
use App\Models\Allotment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
   
        $rowsWithoutHeader = $rows->slice(1);

        foreach ($rowsWithoutHeader as $row) {

           $user =  User::create([
                'name'     => $row[0], 
                'email'    => $row[1], 
                'password' => Hash::make($row[2]),
                'mobile_no' => $row[3],
                'user_type' =>2,
            ]);

            Allotment::create([

        
                 'user_id' => $user->id,
                 'flat_id' => $row[4]
                 
              
            ]);


        }
    }
}
