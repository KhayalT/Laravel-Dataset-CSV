<?php


namespace App\Services\ImportService;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Throwable;

class CustomerImportService
{
    const HEADINGS = ['category', 'firstname', 'lastname', 'email', 'gender', 'birthday'];

    /**
     * @param $file
     * @return RedirectResponse
     */
    public function import($file): RedirectResponse
    {
        try {
            if ($file->isValid()) {
                $handle = fopen($file->getRealPath(), 'r');

                $headerRow = true;
                $headers = [];

                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    if ($headerRow) {
                        $headers = $data;
                        $headerRow = false;
                        continue;
                    }

                    $rowData = array_combine($headers, $data);

                    Customer::query()->updateOrCreate(
                        ['email' => $rowData['email']],
                        $rowData
                    );
                }

                fclose($handle);

                return redirect()->back()->with('success', 'Imported successfully.');
            }

            return redirect()->back()->with('error', 'Some problem occurred.');
        } catch (Throwable $th) {
            echo $th->getMessage();
        }
    }
}
