<?php

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('UsersExportTest', function () {
    it('contains the correct headers for the excel file', function () {
        $export = new UsersExport(['date_init' => '1980-01-01', 'date_end' => '2010-12-31']);

        expect($export->headings())->toBe([
            'N°',
            'Nombre',
            'Fecha de nacimiento'
        ]);
    });
    it('maps the user model data correctly to the excel columns', function () {
        $user = User::factory()->make([
            'name' => 'Tkambio',
            'birth_date' => now()->parse('1990-01-01')
        ]);

        $export = new UsersExport(['date_init' => '1980-01-01', 'date_end' => '2010-12-31']);

        $mappedData = $export->map($user);

        expect($mappedData)->toBe([
            1,
            'Tkambio',
            '01-01-1990'
        ]);
    });
    it('filters the users by the specified birth_date range', function () {
        User::factory()->create(['birth_date' => '1990-01-01']);
        User::factory()->create(['birth_date' => '1970-01-01']);
        User::factory()->create(['birth_date' => '2015-01-01']);

        $params = ['date_init' => '1980-01-01', 'date_end' => '2010-12-31'];
        $export = new UsersExport($params);

        $results = $export->query()->get();

        expect($results)->toHaveCount(1)
            ->and($results->first()->birth_date->format('Y-m-d'))->toBe('1990-01-01');
    });
});
