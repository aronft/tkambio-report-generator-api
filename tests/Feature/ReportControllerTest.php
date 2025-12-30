<?php

use App\Jobs\ExportUsersJob;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

describe('ReportControllerTest', function () {
    it('denies access to unauthenticated users', function () {
        $this->postJson('/api/reports')->assertStatus(401);
    });

    describe('POST /api/reports (store)', function () {
        it('validates that params are included', function () {
            $user = User::factory()->create();

            $this->actingAs($user)
                ->postJson('/api/reports', [])
                ->assertStatus(422)
                ->assertJsonValidationErrors(['title', 'date_init', 'date_end']);
        });

        it('creates a report and dispatch the job', function () {
            $user = User::factory()->create();

            $this->actingAs($user)
                ->postJson('/api/reports', [
                    'title' => 'Tkambio users report',
                    'date_init' => '2010-01-01',
                    'date_end' => '2011-12-31'
                ])
                ->assertStatus(201);
        });

        it('dispatches the ExportUsersJob after creation', function () {
            Queue::fake();

            $user = User::factory()->create();

            $this->actingAs($user)->postJson('/api/reports', [
                'title' => 'Tkambio users report',
                'date_init' => '1990-01-01',
                'date_end' => '2000-01-01'
            ]);

            $this->assertDatabaseHas('reports', [
                'title' => 'Tkambio users report',
                'status' => 'pending'
            ]);

            Queue::assertPushed(ExportUsersJob::class);
        });
    });

    describe('GET /api/reports (index)', function () {
        it('returns a list of reports belonging only to the authenticated user', function () {
            $user = User::factory()->create();
            $otherUser = User::factory()->create();

            $userReport = Report::factory()->create([
                'user_id' => $user->id,
                'title' => 'Principal report'
            ]);

            $otherReport = Report::factory()->create([
                'user_id' => $otherUser->id,
                'title' => 'other user report'
            ]);

            $response = $this->actingAs($user)
                ->getJson('/api/reports');

            $response->assertStatus(200)
                ->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.id', $userReport->id)
                ->assertJsonMissing(['id' => $otherReport->id])
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'title', 'status', 'download_url', 'parameters', 'created_at']
                    ]
                ]);
        });

        it('paginates the reports list', function () {
            $user = User::factory()->create();

            Report::factory()->count(15)->create([
                'user_id' => $user->id
            ]);

            $response = $this->actingAs($user)->getJson('/api/reports?page=2');

            $response->assertStatus(200)
                ->assertJsonPath('meta.current_page', 2)
                ->assertJsonCount(5, 'data');
        });
    });

    describe('GET /api/reports/{id} (show)', function () {
        it('returns the status and details of a specific report', function () {
            $user = User::factory()->create();
            $report = Report::factory()->create([
                'user_id' => $user->id
            ]);

            $response = $this->actingAs($user)->getJson('/api/reports/' . $report->id);

            $response->assertStatus(200)
                ->assertJsonPath('data.id', $report->id)
                ->assertJsonPath('data.status', $report->status)
                ->assertJsonPath('data.parameters', $report->filter_params);
        });

        it('returns a 404 if the report does not exist', function () {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->getJson('/api/reports/' . 1000);

            $response->assertStatus(404);
        });

        it('prevents users accessing other users reports', function () {
            $user = User::factory()->create();
            $secondUser = User::factory()->create();

            $report = Report::factory()->create([
                'user_id' => $user->id
            ]);

            $response = $this->actingAs($secondUser)->getJson('/api/reports/' . $report->id);

            $response->assertStatus(404);
        });
    });
});
