<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Allotment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    public array $errors = [];

    public function collection(Collection $rows)
    {
        $rows = $rows->slice(1); // remove header

        foreach ($rows as $index => $row) {

            if ($row->filter()->isEmpty()) {
                continue;
            }

            $validator = Validator::make([
                'name'       => $row[0] ?? null,
                'email'      => $row[1] ?? null,
                'password'   => $row[2] ?? null,
                'mobile_no'  => $row[3] ?? null,
            ], [
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|unique:users,email',
                'password'  => 'required|min:6',
                'mobile_no' => 'required',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->errors[] = $error;
                }
                continue;
            }

            $user = User::create([
                'name'      => $row[0],
                'email'     => $row[1],
                'password'  => Hash::make($row[2]),
                'mobile_no' => $row[3],
                'user_type' => 2,
            ]);

            // Allotment::create([
            //     'user_id' => $user->id,
            //     'flat_id' => $row[4],
            // ]);
        }
    }
}
