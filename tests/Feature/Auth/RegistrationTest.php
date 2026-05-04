<?php

use App\Models\Company;
use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new candidates can register', function () {
    $response = $this->post('/register', [
        'role' => 'candidate',
        'full_name' => 'Test Candidate',
        'email' => 'candidate@example.com',
        'location' => 'Dhaka',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertDatabaseHas('users', [
        'email' => 'candidate@example.com',
        'role' => 'candidate',
    ]);
    $this->assertDatabaseHas('candidates', [
        'full_name' => 'Test Candidate',
    ]);
});

test('recruiters must register against an existing company account', function () {
    $companyUser = User::factory()->create([
        'role' => 'company',
        'email' => 'company-owner@example.com',
    ]);

    $company = Company::create([
        'user_id' => $companyUser->id,
        'company_name' => 'Acme Corp',
        'website' => 'https://acme.example',
        'location' => 'Dhaka',
    ]);

    $response = $this->post('/register', [
        'role' => 'recruiter',
        'full_name' => 'Recruiter One',
        'email' => 'recruiter@example.com',
        'company_id' => $company->id,
        'department' => 'Talent Acquisition',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertDatabaseHas('recruiters', [
        'full_name' => 'Recruiter One',
        'company_id' => $company->id,
    ]);
    $this->assertDatabaseHas('recruiter_verification_requests', [
        'company_id' => $company->id,
        'status' => 'pending',
    ]);
});
