<?php

namespace Tests\Feature;

use App\Http\Controllers\Loan\LoanApplicationController;
use App\Models\Loan\EcoWaterApplicants;
use App\Models\Loan\LoanApplication;
use App\Models\Management\Customer;
use App\Models\Management\EcoWaterCampus;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EcoWaterRegistrationTest extends TestCase
{
    private LoanApplication $loan;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'services.ecowater.auth_token' => 'test-token',
            'services.ecowater.sender' => 'test-sender',
            'services.ecowater.base_url' => 'https://ecowatercloud.com/',
        ]);
        DB::purge('sqlite');
        Schema::create('customers', fn (Blueprint $table) => $table->id());
        (require database_path('migrations/version1/2026_10_18_140855_create_eco_water_applicants_table.php'))->up();
        (require database_path('migrations/version1/2026_10_18_190000_add_registration_code_to_eco_water_applicants_table.php'))->up();
        $applicant = EcoWaterApplicants::create(['loan_application_id' => 1, 'eco_water_campus_id' => 1]);
        $applicant->setRelation('ecoWaterCampus', new EcoWaterCampus(['campus_code' => 'CA-000057']));
        $this->loan = new LoanApplication(['amount' => 5000]);
        $this->loan->setRelation('ecoWaterApplicant', $applicant);
        $this->loan->setRelation('customer', new Customer([
            'first_name' => 'Asha', 'last_name' => 'Juma', 'gender_id' => 2,
            'phone_number' => '0712345678', 'email' => 'asha@example.com',
        ]));
        Http::preventStrayRequests();
    }

    public function test_registration_posts_json_and_saves_code_without_reposting(): void
    {
        Http::fake(['*' => Http::response([
            'status' => 'success', 'message' => 'Registration completed successfully',
            'data' => ['registration_code' => '5410000127', 'mobile' => '255712345678', 'campus_code' => 'CA-000057'],
        ], 201)]);
        $controller = app(LoanApplicationController::class);
        $this->assertTrue($controller->registerEcowaterStudent($this->loan)['success']);
        $this->assertSame('5410000127', EcoWaterApplicants::first()->registration_code);
        Http::assertSent(fn (Request $request) => $request->url() === 'https://ecowatercloud.com/student_register'
            && $request->method() === 'POST'
            && $request->hasHeader('auth-token', 'test-token')
            && $request->hasHeader('sender', 'test-sender')
            && $request->hasHeader('Content-Type', 'application/json')
            && $request->data() === [
                'full_name' => 'Asha Juma', 'gender' => 'F', 'mobile' => '0712345678',
                'email' => 'asha@example.com', 'campus' => 'CA-000057', 'amount' => 5000,
            ]);
        $this->assertTrue($controller->registerEcowaterStudent($this->loan)['success']);
        Http::assertSentCount(1);
    }

    public function test_rejected_malformed_and_connection_failures_do_not_save_code(): void
    {
        foreach ([Http::response(['status' => 'error'], 422), Http::response(['status' => 'success'], 201), Http::failedConnection()] as $response) {
            Http::fake(['*' => $response]);
            $this->assertFalse(app(LoanApplicationController::class)->registerEcowaterStudent($this->loan)['success']);
            $this->assertNull(EcoWaterApplicants::first()->registration_code);
        }
    }

    public function test_missing_campus_or_credentials_does_not_send_request(): void
    {
        Http::fake();
        config(['services.ecowater.auth_token' => null]);
        $this->assertFalse(app(LoanApplicationController::class)->registerEcowaterStudent($this->loan)['success']);
        config(['services.ecowater.auth_token' => 'test-token']);
        $this->loan->ecoWaterApplicant->setRelation('ecoWaterCampus', null);
        $this->assertFalse(app(LoanApplicationController::class)->registerEcowaterStudent($this->loan)['success']);
        Http::assertNothingSent();
    }
}
