<?php

use App\Models\Candidate;
use App\Models\Company;
use App\Models\User;

test('candidate can browse company directory', function () {
    $user = User::factory()->create(['role' => 'candidate']);
    Candidate::create([
        'user_id' => $user->id,
        'full_name' => 'Candidate User',
    ]);

    $companyOwner = User::factory()->create(['role' => 'company']);
    Company::create([
        'user_id' => $companyOwner->id,
        'company_name' => 'Northstar Labs',
        'industry' => 'Software',
        'website' => 'https://northstar.example',
        'location' => 'Dhaka',
    ]);

    $response = $this->actingAs($user)->get(route('companies.index'));

    $response->assertOk();
    $response->assertSee('Northstar Labs');
});

test('candidate can search company directory', function () {
    $user = User::factory()->create(['role' => 'candidate']);
    Candidate::create([
        'user_id' => $user->id,
        'full_name' => 'Candidate User',
    ]);

    $firstOwner = User::factory()->create(['role' => 'company']);
    Company::create([
        'user_id' => $firstOwner->id,
        'company_name' => 'Northstar Labs',
        'website' => 'https://northstar.example',
    ]);

    $secondOwner = User::factory()->create(['role' => 'company']);
    Company::create([
        'user_id' => $secondOwner->id,
        'company_name' => 'Blue Orbit',
        'website' => 'https://blueorbit.example',
    ]);

    $response = $this->actingAs($user)->get(route('companies.index', ['search' => 'Northstar']));

    $response->assertOk();
    $response->assertSee('Northstar Labs');
    $response->assertDontSee('Blue Orbit');
});
