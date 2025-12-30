<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $params;
    private $rowNumber = 0;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function query()
    {
        return User::query()
            ->whereBetween('birth_date', [$this->params['date_init'], $this->params['date_end']]);
    }

    public function map($user): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $user->name,
            $user->birth_date->format('d-m-Y')
        ];
    }

    public function headings(): array
    {
        return [
            'N°',
            'Nombre',
            'Fecha de nacimiento'
        ];
    }
}
