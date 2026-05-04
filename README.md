# Recruitment Customer Relationship Management (CRM)

A final CSE327 project built with Laravel. The system supports recruitment workflows for candidates, recruiters, and companies, including job posting, applications, profile management, company following, recruiter verification, application evaluation, status tracking, and notifications.

## Project Overview

Recruitment CRM is a web application for managing the relationship between companies, recruiters, and job candidates. Candidates can browse companies and jobs, apply with supporting information, track their application status, and follow companies. Recruiters can manage job posts, review applications, update candidate progress, and add interview evaluations. Companies can maintain public profiles and approve recruiter verification requests.

## Key Features

### Authentication and Roles

- Laravel Breeze authentication.
- Role-based user accounts:
  - Candidate
  - Recruiter
  - Company
- Separate profile data for candidates, recruiters, and companies.
- Profile editing with contact, bio, portfolio, resume, headline, and photo fields.

### Candidate Features

- Browse available jobs.
- Search and filter job listings.
- View job details.
- Apply to jobs with a cover letter and CV upload.
- Prevent duplicate applications.
- View submitted applications.
- Track application status updates.
- Follow and unfollow companies.
- View candidate public profile pages.

### Recruiter Features

- Create, edit, close, and delete job posts.
- Manage recruiter profile and company association.
- Submit recruiter verification requests.
- View applications for recruiter-owned jobs.
- Search candidate applications.
- Update application status:
  - Applied
  - Shortlisted
  - Interview Scheduled
  - Rejected
  - Hired
- View a job pipeline board.
- Add, update, and delete interview evaluations.

### Company Features

- Company directory and public company profiles.
- Company profile fields such as industry, website, description, location, and cover photo.
- Review and approve recruiter verification requests.
- Notify followers when company-related updates are published.

### Notifications

- In-app notification list.
- Mark single notifications as read.
- Mark all notifications as read.
- Open notifications and navigate to related records.
- Delete notifications.
- Application status notifications through Laravel events and listeners.

## Design Patterns Used

This project intentionally applies multiple software design patterns as part of the final project requirements.

| Pattern | Location | Purpose |
| --- | --- | --- |
| Factory | `app/Factories/UserFactory.php` | Creates candidate, recruiter, and company users with their related profile records. |
| Factory | `app/Factories/InterviewFactory.php`, `app/Factories/InterviewPlanFactory.php` | Creates interview evaluation strategies and interview plans. |
| Strategy | `app/Patterns/Strategy/StatusTransition` | Encapsulates application status transition behavior. |
| Strategy | `app/Patterns/Strategy/InterviewEvaluation` | Handles different interview evaluation methods such as technical, behavioral, and HR feedback. |
| Observer | `app/Patterns/Observer/CompanyFollowers` | Notifies company followers about relevant company updates. |
| Observer | `app/Observers` | Uses Laravel model observers for company and job events. |
| Singleton | `app/Patterns/Singleton/DatabaseConnectionSingleton.php` | Demonstrates single-instance database connection handling. |
| Decorator | `app/Notifications` | Extends notification behavior through email and log decorators. |
| Facade | `app/Facades/ApplicationFacade.php` | Provides a simplified entry point for application processing. |

## Tech Stack

- PHP 8.3+
- Laravel 13
- Laravel Breeze
- Blade templates
- Tailwind CSS
- Alpine.js
- Vite
- SQLite by default, with Laravel-supported database alternatives available through `.env`
- Pest / PHPUnit for testing

## Requirements

Make sure the following are installed:

- PHP 8.3 or newer
- Composer
- Node.js and npm
- SQLite extension for PHP

## Installation

1. Clone the repository:

```bash
git clone https://github.com/rakeenrazib/Recruitment-Customer-Relationship-Management-CRM-.git
cd Recruitment-Customer-Relationship-Management-CRM-
```

2. Install PHP and JavaScript dependencies:

```bash
composer install
npm install
```

3. Create the environment file and application key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Create the SQLite database file:

```bash
touch database/database.sqlite
```

5. Run migrations and seed demo data:

```bash
php artisan migrate --seed
```

6. Start the development servers:

```bash
composer run dev
```

The application will be available at:

```text
http://127.0.0.1:8000
```

If you prefer to run the Laravel and Vite servers separately:

```bash
php artisan serve
npm run dev
```

## Demo Accounts

Seeded users use the password:

```text
password
```

Useful seeded accounts:

| Role | Email |
| --- | --- |
| Candidate | `test@example.com` |
| Company | `hello@grameenlink.test` |
| Company | `contact@overcare.test` |
| Company | `info@tonda.test` |
| Recruiter | `amina.rahman@grameenlink.test` |
| Recruiter | `sajid.khan@grameenlink.test` |
| Recruiter | `nabila.siddique@overcare.test` |
| Recruiter | `fahad.islam@overcare.test` |
| Recruiter | `mousumi.hossain@tonda.test` |
| Recruiter | `jahid.hasan@tonda.test` |

The seeder also creates additional candidate accounts using `@example.com` addresses.

## Testing

Run the project tests with:

```bash
composer test
```

Or run Laravel's test command directly:

```bash
php artisan test
```

Current tests include authentication, profile management, company directory behavior, notification service behavior, and singleton behavior.

## Main Routes

| Area | Route |
| --- | --- |
| Home | `/` |
| Dashboard | `/dashboard` |
| Jobs | `/jobs` |
| Job pipeline | `/jobs/{job}/pipeline` |
| Candidate applications | `/my-applications` |
| Recruiter applications | `/recruiter/applications` |
| Companies | `/companies` |
| Notifications | `/notifications` |
| Profile settings | `/profile` |

## Project Structure

```text
app/
  Events/              Application domain events
  Factories/           User, interview, and interview-plan factories
  Facades/             Application workflow facade
  Http/Controllers/    Web controllers
  Listeners/           Event listeners
  Models/              Eloquent models
  Notifications/       Notification decorator classes
  Observers/           Laravel model observers
  Patterns/            Explicit design-pattern implementations
  Services/            Application services

database/
  migrations/          Database schema
  seeders/             Demo data

resources/views/       Blade UI templates
routes/                Web and authentication routes
tests/                 Feature and unit tests
```

## Final Project Scope

This final version demonstrates a full recruitment CRM workflow with Laravel MVC, role-based access, relational database modeling, application tracking, notification workflows, profile pages, company following, recruiter verification, and multiple object-oriented design patterns.
