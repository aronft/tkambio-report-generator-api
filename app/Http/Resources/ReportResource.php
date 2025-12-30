<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'status'     => $this->status,
            'parameters' => $this->filter_params,
            'download_url' => $this->report_link
                ? asset('storage/' . $this->report_link)
                : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
