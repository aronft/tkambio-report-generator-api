<?php

use App\Jobs\ExportUsersJob;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('ExportUsersJobTest', function () {

    it('generates an excel file correctly with 1000 users', function () {
        Storage::fake('public');

        User::factory(1000)->withValidBirthDate()->create();

        $user = User::factory()->create();
        $report = Report::factory()->create([
            'user_id' => $user->id,
            'filter_params' => [
                'date_init' => '1980-01-01',
                'date_end' => '2010-12-31'
            ],
            'status' => 'pending'
        ]);

        (new ExportUsersJob($report))->handle();

        $report->refresh();


        expect($report->status)->toBe('completed');
        expect($report->report_link)->not->toBeNull();

        Storage::disk('public')->assertExists($report->report_link);
    });
});
