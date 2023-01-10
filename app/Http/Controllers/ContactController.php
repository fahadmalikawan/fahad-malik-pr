<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacts.index');
    }

    public function uploadCsv(Request $request)
    {
        // Validate the request and the uploaded file
        $request->validate([
            'contacts_csv_file' => 'required|file|mimetypes:text/csv,application/vnd.ms-excel',
        ]);

        if (($open = fopen($request->file('contacts_csv_file')->getRealPath(), "r")) !== false) {

            // Get first row containing column names and iterate a line
            $columns = fgetcsv($open);

            $rows_inserted = 0;
            $duplicates = [];
            $error_rows = [];

            // Continue reading after 1st line till end of file
            $i = 0;
            while (($row = fgetcsv($open)) !== false) {
                $i++;
                // Combine the column names and row values into an associative array
                $row_assoc = array_combine($columns, $row);

                $validator = Validator::make($row_assoc, [
                    'id' => 'required|integer|unique:contacts,id',
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255',
                    'phone' => 'required|string|max:255',
                    'address' => 'required|string|max:255',
                ]);
                if ($validator->fails()) {
                    array_push($error_rows, $i);
                    continue;
                }

                $row_exists = Contact::where('name', $row_assoc['name'])
                    ->where('email', $row_assoc['email'])
                    ->where('phone', $row_assoc['phone'])
                    ->where('address', $row_assoc['address'])
                    ->count();
                if ($row_exists > 0) {
                    array_push($duplicates, $i);
                    continue;
                }

                if (Contact::create($row_assoc)) {
                    $rows_inserted++;
                }
            }

            fclose($open);
        }

        return redirect()->back()
            ->with('rows_inserted', $rows_inserted)
            ->with('duplicates', $duplicates)
            ->with('error_rows', $error_rows);
    }

    public function uploadCsvTogether(Request $request)
    {
        // Validate the request and the uploaded file
        $request->validate([
            'contacts_csv_file' => 'required|file|mimetypes:text/csv,application/vnd.ms-excel',
        ]);

        if (($open = fopen($request->file('contacts_csv_file')->getRealPath(), "r")) !== false) {

            // Get first row containing column names and iterate a line
            $columns = fgetcsv($open);

            $data_to_store = [];

            // Continue reading after 1st line till end of file
            while (($row = fgetcsv($open)) !== false) {
                // Combine the column names and row values into an associative array
                $data_to_store[] = array_combine($columns, $row);
            }

            fclose($open);
        }

        $validator = Validator::make($data_to_store, [
            '*.id' => 'required|integer|unique:contacts,id',
            '*.name' => 'required|string|max:255',
            '*.email' => 'required|email|unique:contacts,email',
            '*.phone' => 'required|string|max:255',
            '*.address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Contact::insert($data_to_store);

        return redirect()->back()->with('success', 'Contacts imported successfully!');
    }
}
