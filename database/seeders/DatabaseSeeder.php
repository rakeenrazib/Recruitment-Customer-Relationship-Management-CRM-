<?php

namespace Database\Seeders;

use App\Factories\UserFactory;
use App\Models\Candidate;
use App\Models\User;
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
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'role' => 'candidate',
                'password' => bcrypt('password'),
            ]
        );

        Candidate::firstOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => 'Test User',
                'location' => 'Dhaka',
            ]
        );

        $companies = [
            [
                'company_name' => 'Grameen Link',
                'industry' => 'Telecommunications',
                'website' => 'https://grameenlink.test',
                'description' => 'A major telecommunications provider in Bangladesh offering mobile and internet services.',
                'location' => 'Dhaka',
                'email' => 'hello@grameenlink.test',
                'recruiters' => [
                    [
                        'full_name' => 'Amina Rahman',
                        'department' => 'Network Operations',
                        'title' => 'Senior Recruiter',
                        'phone' => '+8801711000001',
                        'email' => 'amina.rahman@grameenlink.test',
                    ],
                    [
                        'full_name' => 'Sajid Khan',
                        'department' => 'Talent Acquisition',
                        'title' => 'Recruitment Lead',
                        'phone' => '+8801711000002',
                        'email' => 'sajid.khan@grameenlink.test',
                    ],
                ],
            ],
            [
                'company_name' => 'Over Care',
                'industry' => 'Healthcare Services',
                'website' => 'https://overcare.test',
                'description' => 'Healthcare services company focused on patient care, digital health, and medical staffing solutions.',
                'location' => 'Chittagong',
                'email' => 'contact@overcare.test',
                'recruiters' => [
                    [
                        'full_name' => 'Nabila Siddique',
                        'department' => 'Clinical Recruiting',
                        'title' => 'Recruitment Specialist',
                        'phone' => '+8801711000003',
                        'email' => 'nabila.siddique@overcare.test',
                    ],
                    [
                        'full_name' => 'Fahad Islam',
                        'department' => 'Human Resources',
                        'title' => 'Recruiter',
                        'phone' => '+8801711000004',
                        'email' => 'fahad.islam@overcare.test',
                    ],
                ],
            ],
            [
                'company_name' => 'Tonda',
                'industry' => 'Automotive',
                'website' => 'https://tonda.test',
                'description' => 'Automotive company specializing in car manufacturing, mobility solutions, and vehicle services.',
                'location' => 'Sylhet',
                'email' => 'info@tonda.test',
                'recruiters' => [
                    [
                        'full_name' => 'Mousumi Hossain',
                        'department' => 'Engineering Recruiting',
                        'title' => 'Lead Recruiter',
                        'phone' => '+8801711000005',
                        'email' => 'mousumi.hossain@tonda.test',
                    ],
                    [
                        'full_name' => 'Jahid Hasan',
                        'department' => 'Operations',
                        'title' => 'Senior Recruiter',
                        'phone' => '+8801711000006',
                        'email' => 'jahid.hasan@tonda.test',
                    ],
                ],
            ],
        ];

        foreach ($companies as $companyData) {
            $companyUser = User::where('email', $companyData['email'])->first();

            if (! $companyUser) {
                $companyUser = UserFactory::create('company', [
                    'company_name' => $companyData['company_name'],
                    'industry' => $companyData['industry'],
                    'website' => $companyData['website'],
                    'description' => $companyData['description'],
                    'location' => $companyData['location'],
                    'email' => $companyData['email'],
                    'password' => 'password',
                ]);
            }

            foreach ($companyData['recruiters'] as $recruiterData) {
                if (User::where('email', $recruiterData['email'])->exists()) {
                    continue;
                }

                UserFactory::create('recruiter', [
                    'company_id' => $companyUser->company->id,
                    'full_name' => $recruiterData['full_name'],
                    'phone' => $recruiterData['phone'],
                    'location' => $companyData['location'],
                    'department' => $recruiterData['department'],
                    'title' => $recruiterData['title'],
                    'bio' => 'Experienced recruiter supporting hiring for ' . $companyData['company_name'] . '.',
                    'email' => $recruiterData['email'],
                    'password' => 'password',
                    'verification_message' => 'Please verify my recruiter account for ' . $companyData['company_name'] . '.',
                ]);
            }
        }

        $candidates = [
            ['full_name' => 'Rahim Ahmed', 'email' => 'rahim.ahmed@example.com', 'phone' => '+8801712000001', 'location' => 'Dhaka', 'bio' => 'Software engineer with a strong background in backend development.'],
            ['full_name' => 'Mina Akter', 'email' => 'mina.akter@example.com', 'phone' => '+8801712000002', 'location' => 'Chittagong', 'bio' => 'Healthcare administration professional focused on patient experience.'],
            ['full_name' => 'Sabbir Hossain', 'email' => 'sabbir.hossain@example.com', 'phone' => '+8801712000003', 'location' => 'Khulna', 'bio' => 'Automotive engineer with experience in vehicle design and quality assurance.'],
            ['full_name' => 'Farhana Parvin', 'email' => 'farhana.parvin@example.com', 'phone' => '+8801712000004', 'location' => 'Sylhet', 'bio' => 'Digital marketing specialist with healthcare and telecom campaign experience.'],
            ['full_name' => 'Imran Noor', 'email' => 'imran.noor@example.com', 'phone' => '+8801712000005', 'location' => 'Rajshahi', 'bio' => 'Operations manager with strong logistics and automotive supply chain knowledge.'],
            ['full_name' => 'Sadia Islam', 'email' => 'sadia.islam@example.com', 'phone' => '+8801712000006', 'location' => 'Barisal', 'bio' => 'Talent acquisition professional with experience in recruiting technical and clinical roles.'],
            ['full_name' => 'Tanzim Karim', 'email' => 'tanzim.karim@example.com', 'phone' => '+8801712000007', 'location' => 'Rangpur', 'bio' => 'UI/UX designer who builds user-centered digital products.'],
            ['full_name' => 'Lamia Sultana', 'email' => 'lamia.sultana@example.com', 'phone' => '+8801712000008', 'location' => 'Mymensingh', 'bio' => 'Customer support leader with telecom and healthcare service experience.'],
            ['full_name' => 'Rashed Chowdhury', 'email' => 'rashed.chowdhury@example.com', 'phone' => '+8801712000009', 'location' => 'Comilla', 'bio' => 'Business analyst specializing in automotive market research.'],
            ['full_name' => 'Nusrat Jahan', 'email' => 'nusrat.jahan@example.com', 'phone' => '+8801712000010', 'location' => 'Gazipur', 'bio' => 'Recruitment coordinator passionate about matching candidates to growth-stage companies.'],
        ];

        foreach ($candidates as $candidateData) {
            if (User::where('email', $candidateData['email'])->exists()) {
                continue;
            }

            UserFactory::create('candidate', [
                'full_name' => $candidateData['full_name'],
                'email' => $candidateData['email'],
                'password' => 'password',
                'phone' => $candidateData['phone'],
                'location' => $candidateData['location'],
                'bio' => $candidateData['bio'],
                'portfolio' => 'https://portfolio.example/' . Str::slug($candidateData['full_name']),
                'resume_link' => 'https://resume.example/' . Str::slug($candidateData['full_name']),
            ]);
        }
    }
}
