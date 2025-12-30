<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportStoreRequest;
use App\Http\Resources\ReportResource;
use App\Jobs\ExportUsersJob;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(ReportStoreRequest $request)
    {
        $report = $request->user()->reports()->create([
            'title' => $request->title,
            'filter_params' => [
                'date_init' => $request->date_init,
                'date_end' => $request->date_end
            ],
            'status' => 'pending'
        ]);

        ExportUsersJob::dispatch($report);

        return (new ReportResource($report))
            ->additional(['message' => 'El reporte se está generando'])
            ->response()
            ->setStatusCode(201);
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $reports = $request->user()->reports()
            ->latest()
            ->paginate($perPage);

        return (ReportResource::collection($reports))
            ->additional(['message' => 'Datos obtenidos correctamente'])
            ->response()
            ->setStatusCode(200);
    }

    public function show($id)
    {
        $report = auth()->user()->reports()->findOrFail($id);

        return (new ReportResource($report))
            ->additional(['message' => 'Datos obtenidos correctamente'])
            ->response()
            ->setStatusCode(200);
    }
}
