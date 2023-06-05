<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::query()->get();
        return view('customer.index', compact('customers'));
    }

    public function import(Request $request): RedirectResponse
    {
        $file = $request->file('customers');

        if ($file->isValid()) {
            $handle = fopen($file->getRealPath(), 'r');

            $headerRow = true;

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if ($headerRow) {
                    $headerRow = false;
                    continue;
                }
                $data['id'] = 1;
            }

            fclose($handle);

            return redirect()->back()->with('success', 'Imported successfully.');
        }

        return redirect()->back()->with('error', 'Some problem occurred.');
    }

    public function export(): StreamedResponse
    {
        $data = Customer::query()->get();

        $fileName = 'customers.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['category', 'firstname', 'lastname', 'email', 'gender', 'birthday']);

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
