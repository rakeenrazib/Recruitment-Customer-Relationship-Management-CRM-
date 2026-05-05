<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Job;
use App\Models\Recruiter;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Factories\InterviewPlanFactory;

class DemoDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // Company 1
        $auroraUser = User::create([
            'name' => 'Aurora Fintech Labs',
            'email' => 'hello@aurorafintech.test',
            'password' => $password,
            'role' => 'company',
        ]);
        $auroraUser->markEmailAsVerified();

        $aurora = Company::create([
            'user_id' => $auroraUser->id,
            'company_name' => 'Aurora Fintech Labs',
            'industry' => 'Financial Technology',
            'website' => 'https://aurorafintech.test',
            'location' => 'Banani, Dhaka',
        ]);

        // Company 2
        $pixelBridgeUser = User::create([
            'name' => 'PixelBridge Studio',
            'email' => 'careers@pixelbridge.test',
            'password' => $password,
            'role' => 'company',
        ]);
        $pixelBridgeUser->markEmailAsVerified();

        $pixelBridge = Company::create([
            'user_id' => $pixelBridgeUser->id,
            'company_name' => 'PixelBridge Studio',
            'industry' => 'Digital Product Agency',
            'website' => 'https://pixelbridge.test',
            'location' => 'Gulshan, Dhaka',
        ]);

        // Recruiters
        $nadiaUser = User::create([
            'name' => 'Nadia Rahman',
            'email' => 'nadia.rahman@aurorafintech.test',
            'password' => $password,
            'role' => 'recruiter',
        ]);
        $nadiaUser->markEmailAsVerified();

        $nadiaRecruiter = Recruiter::create([
            'user_id' => $nadiaUser->id,
            'company_id' => $aurora->id,
            'full_name' => 'Nadia Rahman',
            'title' => 'Senior Technical Recruiter',
            'phone' => '+880 1712-480219',
            'verified_at' => now(),
        ]);

        $marcusUser = User::create([
            'name' => 'Marcus Chen',
            'email' => 'marcus.chen@pixelbridge.test',
            'password' => $password,
            'role' => 'recruiter',
        ]);
        $marcusUser->markEmailAsVerified();

        $marcusRecruiter = Recruiter::create([
            'user_id' => $marcusUser->id,
            'company_id' => $pixelBridge->id,
            'full_name' => 'Marcus Chen',
            'title' => 'Talent Partner',
            'phone' => '+880 1819-774530',
            'verified_at' => now(),
        ]);

        // Candidates
        $farhanUser = User::create([
            'name' => 'Farhan Ahmed',
            'email' => 'farhan.ahmed@example.test',
            'password' => $password,
            'role' => 'candidate',
            'skills' => json_encode(['Laravel', 'REST APIs', 'MySQL', 'Vue.js', 'Tailwind CSS']),
        ]);
        $farhanUser->markEmailAsVerified();

        Candidate::create([
            'user_id' => $farhanUser->id,
            'full_name' => 'Farhan Ahmed',
            'location' => 'Dhanmondi, Dhaka',
            'portfolio' => 'https://farhanahmed.dev',
        ]);

        $sadiaUser = User::create([
            'name' => 'Sadia Islam',
            'email' => 'sadia.islam@example.test',
            'password' => $password,
            'role' => 'candidate',
            'skills' => json_encode(['Product Design', 'Figma', 'UX Research', 'Design Systems', 'HTML/CSS']),
        ]);
        $sadiaUser->markEmailAsVerified();

        Candidate::create([
            'user_id' => $sadiaUser->id,
            'full_name' => 'Sadia Islam',
            'location' => 'Uttara, Dhaka',
            'portfolio' => 'https://sadia.design',
        ]);

        // Jobs
        $laravelJob = Job::create([
            'user_id' => $nadiaUser->id,
            'recruiter_id' => $nadiaRecruiter->id,
            'company_id' => $aurora->id,
            'company' => 'Aurora Fintech Labs',
            'title' => 'Senior Laravel Engineer',
            'description' => 'Looking for an experienced Laravel developer to join our fintech product team.',
            'location' => 'Hybrid - Banani, Dhaka',
            'salary' => 185000,
            'job_type' => 'full-time',
            'status' => 'open',
        ]);
        InterviewPlanFactory::createForJob($laravelJob);

        $designerJob = Job::create([
            'user_id' => $marcusUser->id,
            'recruiter_id' => $marcusRecruiter->id,
            'company_id' => $pixelBridge->id,
            'company' => 'PixelBridge Studio',
            'title' => 'Product Designer',
            'description' => 'Seeking a creative product designer with strong Figma skills.',
            'location' => 'On-site - Gulshan, Dhaka',
            'salary' => 140000,
            'job_type' => 'full-time',
            'status' => 'open',
        ]);
        InterviewPlanFactory::createForJob($designerJob);
    }
}
