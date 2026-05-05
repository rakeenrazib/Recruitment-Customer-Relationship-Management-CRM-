<?php

namespace Database\Seeders;

use App\Factories\UserFactory;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Job;
use App\Models\RecruiterVerificationRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Companies
        $aurora = UserFactory::create('company', [
            'company_name' => 'Aurora Fintech Labs',
            'email' => 'hello@aurorafintech.test',
            'password' => 'password',
            'industry' => 'Financial Technology',
            'website' => 'https://aurorafintech.test',
            'location' => 'Banani, Dhaka',
            'description' => 'A leading fintech company in Bangladesh.'
        ]);

        $pixelbridge = UserFactory::create('company', [
            'company_name' => 'PixelBridge Studio',
            'email' => 'careers@pixelbridge.test',
            'password' => 'password',
            'industry' => 'Digital Product Agency',
            'website' => 'https://pixelbridge.test',
            'location' => 'Gulshan, Dhaka',
            'description' => 'A digital product agency focusing on great UX.'
        ]);

        // 2. Recruiters
        $nadia = UserFactory::create('recruiter', [
            'full_name' => 'Nadia Rahman',
            'email' => 'nadia.rahman@aurorafintech.test',
            'password' => 'password',
            'company_id' => $aurora->company->id,
            'title' => 'Senior Technical Recruiter',
            'phone' => '+880 1712-480219',
        ]);
        // Verify Nadia
        $nadia->recruiter->update(['verified_at' => now()]);
        RecruiterVerificationRequest::where('recruiter_id', $nadia->recruiter->id)->update(['status' => 'approved']);

        $marcus = UserFactory::create('recruiter', [
            'full_name' => 'Marcus Chen',
            'email' => 'marcus.chen@pixelbridge.test',
            'password' => 'password',
            'company_id' => $pixelbridge->company->id,
            'title' => 'Talent Partner',
            'phone' => '+880 1819-774530',
        ]);
        // Verify Marcus
        $marcus->recruiter->update(['verified_at' => now()]);
        RecruiterVerificationRequest::where('recruiter_id', $marcus->recruiter->id)->update(['status' => 'approved']);

        // 3. Candidates
        $farhan = UserFactory::create('candidate', [
            'full_name' => 'Farhan Ahmed',
            'email' => 'farhan.ahmed@example.test',
            'password' => 'password',
            'location' => 'Dhanmondi, Dhaka',
            'portfolio' => 'https://farhanahmed.dev',
        ]);
        $farhan->update(['skills' => 'Laravel, REST APIs, MySQL, Vue.js, Tailwind CSS']);

        $sadia = UserFactory::create('candidate', [
            'full_name' => 'Sadia Islam',
            'email' => 'sadia.islam@example.test',
            'password' => 'password',
            'location' => 'Uttara, Dhaka',
            'portfolio' => 'https://sadia.design',
        ]);
        $sadia->update(['skills' => 'Product Design, Figma, UX Research, Design Systems, HTML/CSS']);

        // 4. Jobs
        Job::create([
            'user_id' => $nadia->id,
            'recruiter_id' => $nadia->recruiter->id,
            'company_id' => $aurora->company->id,
            'title' => 'Senior Laravel Engineer',
            'company' => 'Aurora Fintech Labs',
            'location' => 'Hybrid - Banani, Dhaka',
            'salary' => 185000,
            'job_type' => 'full-time',
            'status' => 'open',
            'description' => 'We are looking for a Senior Laravel Engineer to join our team and build scalable APIs.',
            'requirements' => 'Laravel, PHP, MySQL',
        ]);

        Job::create([
            'user_id' => $marcus->id,
            'recruiter_id' => $marcus->recruiter->id,
            'company_id' => $pixelbridge->company->id,
            'title' => 'Product Designer',
            'company' => 'PixelBridge Studio',
            'location' => 'On-site - Gulshan, Dhaka',
            'salary' => 140000,
            'job_type' => 'full-time',
            'status' => 'open',
            'description' => 'We need an excellent Product Designer to lead design systems and UX research.',
            'requirements' => 'Figma, UI/UX, Design Systems',
        ]);
    }
}
