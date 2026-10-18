<?php

namespace Tests\Feature;

use App\Http\Controllers\Management\EcoCampusController;
use App\Models\Management\EcoWaterCampus;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CampusSyncTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'services.ecowater.auth_token' => 'test-token',
            'services.ecowater.sender' => 'test-sender',
        ]);
        DB::purge('sqlite');
        (require database_path('migrations/version1/2026_10_18_142650_create_eco_water_campuses_table.php'))->up();
        Http::preventStrayRequests();
    }

    public function test_sync_inserts_updates_and_restores_without_duplicates(): void
    {
        Http::fake(['ecowatercloud.com/campus_list' => Http::response([
            'status' => 'success',
            'data' => [['campus_name' => 'St. John University Of Tanzania', 'campus_code' => 'CA-000057']],
        ])]);

        $controller = app(EcoCampusController::class);
        $this->assertSame(200, $controller->sync()->getStatusCode());
        $record = EcoWaterCampus::firstOrFail();
        $record->update(['campus_name' => 'Old name']);
        $record->delete();
        $this->assertSame(200, $controller->sync()->getStatusCode());
        $this->assertSame(1, EcoWaterCampus::withTrashed()->count());
        $this->assertSame('St. John University Of Tanzania', $record->fresh()->campus_name);
        $this->assertFalse($record->fresh()->trashed());
        Http::assertSent(fn (Request $request) => $request->method() === 'GET'
            && $request->url() === 'https://ecowatercloud.com/campus_list'
            && $request->hasHeader('auth-token', 'test-token')
            && $request->hasHeader('sender', 'test-sender'));
    }

    public function test_invalid_response_does_not_partially_insert(): void
    {
        Http::fake(['*' => Http::response(['status' => 'success', 'data' => [
            ['campus_name' => 'Valid campus', 'campus_code' => 'CA-001'],
            ['campus_name' => 'Missing code'],
        ]])]);
        $this->assertSame(502, app(EcoCampusController::class)->sync()->getStatusCode());
        $this->assertSame(0, EcoWaterCampus::count());
    }

    public function test_missing_credentials_do_not_call_api(): void
    {
        config(['services.ecowater.auth_token' => null]);
        Http::fake();
        $this->assertSame(503, app(EcoCampusController::class)->sync()->getStatusCode());
        Http::assertNothingSent();
    }

    public function test_api_failure_and_connection_failure_leave_data_unchanged(): void
    {
        EcoWaterCampus::create(['campus_name' => 'Existing campus', 'campus_code' => 'CA-001']);
        Http::fake(['*' => Http::response(['status' => 'error'], 500)]);
        $this->assertSame(502, app(EcoCampusController::class)->sync()->getStatusCode());
        Http::fake(['*' => Http::failedConnection()]);
        $this->assertSame(502, app(EcoCampusController::class)->sync()->getStatusCode());
        $this->assertSame(1, EcoWaterCampus::count());
    }
}
