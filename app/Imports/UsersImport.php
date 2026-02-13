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
    public function collection(Collection $rows)
    {
        // Remove header row
        $rows = $rows->slice(1);

        foreach ($rows as $index => $row) {

            // 🔹 Skip completely empty rows
            if ($row->filter()->isEmpty()) {
                continue;
            }

            // 🔹 Validation rules
            $validator = Validator::make([
                'name'       => $row[0] ?? null,
                'email'      => $row[1] ?? null,
                'password'   => $row[2] ?? null,
                'mobile_no'  => $row[3] ?? null,
                'flat_id'    => $row[4] ?? null,
            ], [
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|unique:users,email',
                'password'  => 'required|min:6',
                'mobile_no' => 'required|digits_between:10,15',
                'flat_id'   => 'required|exists:flats,id',
            ]);

            // 🔹 If validation fails → skip row
            if ($validator->fails()) {
                // optional: log errors
                // Log::error("Row {$index} failed", $validator->errors()->toArray());
                continue;
            }

            // 🔹 Create user
            $user = User::create([
                'name'       => $row[0],
                'email'      => $row[1],
                'password'   => Hash::make($row[2]),
                'mobile_no'  => $row[3],
                'user_type'  => 2,
            ]);

            // 🔹 Create allotment
            Allotment::create([
                'user_id' => $user->id,
                'flat_id' => $row[4],
            ]);
        }
    }
}
