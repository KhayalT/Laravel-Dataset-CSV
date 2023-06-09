<?php


namespace App\Services\ExportService;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class CustomerExportService
{
    const HEADINGS = ['id', 'category', 'firstname', 'lastname', 'email', 'gender', 'birthDate'];

    public function getFileName(): string
    {
        return 'customers'.time().'.csv';
    }

    /**
     * @return StreamedResponse|void
     */
    public function export($data)
    {
        try {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $this->getFileName() . '"',
            ];

             $callback = function () use ($data) {
                $file = fopen('php://output', 'w');

                fputcsv($file, self::HEADINGS);

                foreach ($data as $row) {
                    fputcsv($file, $row->toArray());
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Throwable $th) {
            echo $th->getMessage();
        }
    }
}
