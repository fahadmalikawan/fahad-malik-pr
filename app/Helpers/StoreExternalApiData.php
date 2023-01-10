<?php

namespace App\Helpers;

use App\Models\TestApiRecord;

class StoreExternalApiData
{
    public function storeExternalApiData()
    {
        /*
        $response = Http::get('https://api.publicapis.org/entries');
        $json = $response->json();
        */
        // Could not use Http Class as it returns below error.
        // cURL error 60: SSL certificate problem: certificate has expired
        // Issue from endpoint, however I tested another API and that worked fine
        // So I'm using simplest file_get_content, and string to json. The old core php way, which fulfills the need for now.

        $response = file_get_contents('https://api.publicapis.org/entries');
        $json_to_array = json_decode($response, true);

        $total_rows = $json_to_array['count'];
        $rows_inserted = 0;
        $duplicates = 0;
        $error_rows = 0;

        foreach ($json_to_array['entries'] as $row) {
            $row_lower_key = array_change_key_case($row, CASE_LOWER);
            $row_exists = TestApiRecord::where('api', $row_lower_key['api'])
                ->where('description', $row_lower_key['description'])
                ->where('auth', $row_lower_key['auth'])
                ->where('https', $row_lower_key['https'])
                ->where('cors', $row_lower_key['cors'])
                ->where('link', $row_lower_key['link'])
                ->where('category', $row_lower_key['category'])
                ->count();
            if ($row_exists > 0) {
                $duplicates++;
                continue;
            }
            // dd($row_lower_key);
            if (TestApiRecord::create($row_lower_key)) {
                $rows_inserted++;
            } else {
                $error_rows++;
            }
        }

        return response()->json([
            'Total Rows' => $total_rows,
            'Rows inserted' => $rows_inserted,
            'Duplicates skipped' => $duplicates,
            'Errors' => $error_rows,
        ]);
    }
}
