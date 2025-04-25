# 💼 Job Application Website

A full-featured Job Application Website built using **PHP** and the **Laravel Framework**, designed to connect employers and job seekers. This platform enables job postings, applications, filtering, and more — with a modern and responsive UI using **Tailwind CSS** and **Bootstrap**.

---

## 🚀 Features

- 🔐 **Authentication with Laravel Sanctum**  
  Secure user registration and login system using Sanctum for API token authentication.

- 📝 **Job Posting**  
  Employers can create and manage job postings with detailed job information.

- 📄 **Job Application**  
  Job seekers can browse jobs and apply directly using well-handled and validated application forms.

- 🎯 **Smart Job Filtering**  
  Users can filter job listings by category, location, and type to find suitable jobs faster.

- 🤖 **Relevant Job Suggestions**  
  The platform displays relevant jobs to users based on their activity and profile preferences.

---

## 🛠️ Tech Stack

- **Backend:** PHP (Laravel Framework)
- **Frontend:** Tailwind CSS, Bootstrap
- **Database:** MySQL
- **Authentication:** Laravel Sanctum

---

## 📂 Project Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/job-application-website.git
   cd job-application-website
Install dependencies:

composer install
npm install && npm run dev

Set up the environment:

cp .env.example .env
php artisan key:generate

Configure your .env file for database and Sanctum.

Run migrations:

php artisan migrate

Start the server:

php artisan serve
