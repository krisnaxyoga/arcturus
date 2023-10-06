<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Vendor;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class AgentImportClass implements ToCollection,WithHeadingRow
{
    
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        // dd($rows);
        foreach ($rows as $row) {
            $companyName = $row['company_name'];

            $existingVendor = Vendor::where('vendor_legal_name', $companyName)->first();

            $user = new User();
            $user->first_name = $row['name'] ?? null;
            $user->email = $row['email'] ?? null;
            $user->mobile_phone = $row['phone'] ?? null;
            $user->password = Hash::make('password123');
            $user->is_active = 1;
            $user->role_id = 3;
            $user->save();

            if ($existingVendor) {
                // Jika entri dengan company name yang sama sudah ada, lewati proses ini
                $userup = User::find($user->id);
                $userup->vendor_id = $existingVendor->id;
                $userup->save();
                // return null;
            }else{
                  // Jika entri dengan company name yang sama belum ada, tambahkan data baru pada tabel vendor
                $data = new Vendor();
                $data->user_id = $user->id;
                $data->vendor_name = $row['name'] ?? null;
                $data->vendor_legal_name = $companyName ?? null;
                $data->type_vendor = 'agent';
                $data->address_line1 = $row['address_line_1'] ?? null;
                $data->address_line2 = $row['address_line_2'] ?? null;
                $data->zip_code = $row['zipcode'] ?? null;
                $data->phone = $row['phone'] ?? null;
                $data->email = $row['email'] ?? null;
                $data->country = $row['country'] ?? null;
                $data->state = $row['state'] ?? null;
                $data->city = $row['city'] ?? null;
                $data->web_uri = $row['web_url'] ?? null;
                $data->is_active = 1;
                $data->save();

                $userup2 = User::find($user->id);
                $userup2->vendor_id = $data->id;
                $userup2->save();
            }

        }
    }
}
