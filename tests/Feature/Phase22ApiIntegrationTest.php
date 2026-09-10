<?php

namespace Tests\Feature;

use App\Models\ApiClient;
use App\Models\ApiScope;
use App\Models\School;
use App\Models\Student;
use App\Services\DeveloperPortal\ApiClientTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase22ApiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed scopes if empty
        if (ApiScope::query()->count() === 0) {
            $this->artisan('db:seed', ['--class' => 'ApiScopeSeeder']);
        }
    }

    public function test_api_client_authentication_successful(): void
    {
        $school = School::query()->create([
            'name' => 'Test Tenant A',
            'code' => 'TTA',
            'is_active' => true,
        ]);

        $client = ApiClient::query()->create([
            'school_id' => $school->id,
            'name' => 'Integration Partner A',
            'client_code' => 'partner-a',
            'status' => 'active',
            'rate_limit_per_minute' => 60,
        ]);

        $scope = ApiScope::query()->where('code', 'students:read')->first();
        $client->scopes()->attach($scope->id, [
            'granted_at' => now(),
        ]);

        $tokenService = app(ApiClientTokenService::class);
        [$plainToken, $tokenModel] = $tokenService->generate($client, 'Test Token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$plainToken,
        ])->getJson('/api/v1/students');

        $response->assertStatus(200);
        $response->assertHeader('X-HafizPlus-Request-Id');
        $response->assertJsonStructure([
            'success',
            'data' => [
                'students',
            ],
        ]);
    }

    public function test_api_client_authentication_fails_with_invalid_token(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
        ])->getJson('/api/v1/students');

        $response->assertStatus(401);
    }

    public function test_api_client_revoked_token_fails(): void
    {
        $school = School::query()->create([
            'name' => 'Test Tenant A',
            'code' => 'TTA',
            'is_active' => true,
        ]);

        $client = ApiClient::query()->create([
            'school_id' => $school->id,
            'name' => 'Integration Partner A',
            'client_code' => 'partner-a',
            'status' => 'active',
            'rate_limit_per_minute' => 60,
        ]);

        $scope = ApiScope::query()->where('code', 'students:read')->first();
        $client->scopes()->attach($scope->id, [
            'granted_at' => now(),
        ]);

        $tokenService = app(ApiClientTokenService::class);
        [$plainToken, $tokenModel] = $tokenService->generate($client, 'Test Token');

        // Revoke the token
        $tokenService->revoke($tokenModel, null, 'Revoking test');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$plainToken,
        ])->getJson('/api/v1/students');

        $response->assertStatus(401);
    }

    public function test_api_client_scope_enforcement(): void
    {
        $school = School::query()->create([
            'name' => 'Test Tenant A',
            'code' => 'TTA',
            'is_active' => true,
        ]);

        $client = ApiClient::query()->create([
            'school_id' => $school->id,
            'name' => 'Integration Partner A',
            'client_code' => 'partner-a',
            'status' => 'active',
            'rate_limit_per_minute' => 60,
        ]);

        // Attach only students:read
        $scope = ApiScope::query()->where('code', 'students:read')->first();
        $client->scopes()->attach($scope->id, [
            'granted_at' => now(),
        ]);

        $tokenService = app(ApiClientTokenService::class);
        [$plainToken, $tokenModel] = $tokenService->generate($client, 'Test Token');

        // Try calling finance bills which requires finance:read
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$plainToken,
        ])->getJson('/api/v1/finance/bills');

        $response->assertStatus(403);
    }

    public function test_tenant_isolation(): void
    {
        $schoolA = School::query()->create([
            'name' => 'Test Tenant A',
            'code' => 'TTA',
            'is_active' => true,
        ]);

        $schoolB = School::query()->create([
            'name' => 'Test Tenant B',
            'code' => 'TTB',
            'is_active' => true,
        ]);

        // Create student in A and B
        $studentA = Student::query()->create([
            'school_id' => $schoolA->id,
            'full_name' => 'Santri Tenant A',
            'student_number' => '111',
            'is_active' => true,
        ]);

        $studentB = Student::query()->create([
            'school_id' => $schoolB->id,
            'full_name' => 'Santri Tenant B',
            'student_number' => '222',
            'is_active' => true,
        ]);

        $clientA = ApiClient::query()->create([
            'school_id' => $schoolA->id,
            'name' => 'Integration Partner A',
            'client_code' => 'partner-a',
            'status' => 'active',
            'rate_limit_per_minute' => 60,
        ]);

        $scope = ApiScope::query()->where('code', 'students:read')->first();
        $clientA->scopes()->attach($scope->id, [
            'granted_at' => now(),
        ]);

        $tokenService = app(ApiClientTokenService::class);
        [$plainToken, $tokenModel] = $tokenService->generate($clientA, 'Test Token A');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$plainToken,
        ])->getJson('/api/v1/students');

        $response->assertStatus(200);

        $students = $response->json('data.students');
        $this->assertCount(1, $students);
        $this->assertEquals('Santri Tenant A', $students[0]['name']);
        $this->assertEquals($schoolA->id, $students[0]['school_id']);
    }

    public function test_rate_limiting(): void
    {
        $school = School::query()->create([
            'name' => 'Test Tenant A',
            'code' => 'TTA',
            'is_active' => true,
        ]);

        $client = ApiClient::query()->create([
            'school_id' => $school->id,
            'name' => 'Integration Partner A',
            'client_code' => 'partner-a',
            'status' => 'active',
            'rate_limit_per_minute' => 2, // limit of 2 requests
        ]);

        $scope = ApiScope::query()->where('code', 'students:read')->first();
        $client->scopes()->attach($scope->id, [
            'granted_at' => now(),
        ]);

        $tokenService = app(ApiClientTokenService::class);
        [$plainToken, $tokenModel] = $tokenService->generate($client, 'Test Token');

        $headers = ['Authorization' => 'Bearer '.$plainToken];

        // 1st request
        $this->withHeaders($headers)->getJson('/api/v1/students')->assertStatus(200);

        // 2nd request
        $this->withHeaders($headers)->getJson('/api/v1/students')->assertStatus(200);

        // 3rd request should fail with 429
        $response = $this->withHeaders($headers)->getJson('/api/v1/students');
        $response->assertStatus(429);
        $response->assertJsonFragment([
            'success' => false,
        ]);
    }
}
