# Recruitment CRM System

A Laravel-based recruitment system that allows recruiters to post jobs and candidates to apply, with role-based access control and application tracking.

---

## 🚀 Features

### Authentication
- User registration and login (Laravel Breeze)
- Role-based access:
  - Candidate
  - Recruiter

### Recruiter
- Post new jobs
- View all jobs they created
- View applications for each job

### Candidate
- Browse available jobs
- Apply to jobs with a cover letter
- Prevent duplicate applications
- View all applied jobs (My Applications)

---

## 🛠️ Tech Stack

- **Backend:** Laravel (PHP)
- **Frontend:** Blade + Tailwind CSS
- **Database:** SQLite
- **Auth:** Laravel Breeze

---

## ⚙️ Setup Instructions

### 1. Clone the repository
```bash
git clone https://github.com/rakeenrazib/Recruitment-Customer-Relationship-Management-CRM-.git
cd Recruitment-Customer-Relationship-Management-CRM-

### 2. Install dependencies
```bash
composer install
npm install

### 3. Setup environment
```bash
cp .env.example .env
php artisan key:generate

### 4. Setup database
```bash
touch database/database.sqlite
php artisan migrate

### 5. Run the project
```bash
php artisan serve
npm run dev

### Visit
http://127.0.0.1:8000
