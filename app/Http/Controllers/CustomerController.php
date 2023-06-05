<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\ExportService\CustomerExportService;
use App\Services\ImportService\CustomerImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private $customerExportService;
    private $customerImportService;

    public function __construct()
    {
        $this->customerExportService = new CustomerExportService();
        $this->customerImportService = new CustomerImportService();
    }

    public function index(Request $request)
    {
        $customers = Customer::query()
            ->filter($request)
            ->paginate(15);

        return view('customer.index', compact('customers'));
    }

    public function import(Request $request): RedirectResponse
    {
        $file = $request->file('customers');

        return $this->customerImportService->import($file);
    }

    public function export()
    {
        $customers = Customer::query()->paginate(10 );

        return $this->customerExportService->export($customers);
    }
}
