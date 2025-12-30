<?php

namespace App\Jobs;

use App\Exports\UsersExport;
use App\Models\Report;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ExportUsersJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Report $report) {}

    public function handle(): void
    {
        try {
            $this->report->update(['status' => 'processing']);

            $uuid = Str::uuid();
            $fileName = "reports/{$uuid}.xlsx";


            Excel::store(
                new UsersExport($this->report->filter_params),
                $fileName,
                'public'
            );

            $this->report->update([
                'status' => 'completed',
                'report_link' => $fileName
            ]);
        } catch (\Exception $e) {
            $this->report->update(['status' => 'failed']);
            \Log::error($e->getMessage());
        }
    }
}
