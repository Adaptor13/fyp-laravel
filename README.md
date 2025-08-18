# My Flow

### Commands to sync

> git add . 
~ OR
> git add filename (yours)

> git commit -m 

~ Everyday
> git commit -m "sync: 04032025"

> git commit -m "feat: add login functionality"

> git commit -m "fix: login bug"
-----------------------------------------------------------------------------------------------------------------------------

# SinDa: Integrated Child Welfare System

This project is part of my Final Year Project (FYP). It is a **centralized child welfare platform** built with Laravel, focusing on **secure reporting, case management, and interagency collaboration** in Malaysia.  
The system aims to make child abuse reporting safer, more efficient, and more coordinated, supporting Sustainable Development Goal (SDG) 16: *Peace, Justice, and Strong Institutions*.

---

## Features
- **Anonymous Reporting**: Public users can submit child abuse reports without fear of retaliation.  
- **Role-Based Access**: Different dashboards and access levels for social workers, law enforcement, and administrators.  
- **Case Management**: Track, assign, and update cases in real time.  
- **Audit Trail**: Structured history of cases for accountability.  
- **Secure Authentication**: Role-based middleware to ensure proper authorization.  

---

## Tech Stack (Local Host)
- **Backend**: Laravel 12 (PHP 8.x)  
- **Frontend**: Blade / Bootstrap (with responsive design)  
- **Database**: MySQL with UUID primary keys  
- **Authentication**: Laravel Breeze / custom role middleware  
- **Other**: Mail (SMTP with Gmail), GitHub for version control

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/fyp-laravel.git
   cd fyp-laravel
   
2. Install dependencies
   composer install
   npm install && npm run dev
   
3. Set up the env. file:
   cp .env.example .env
   php artisan key:generate

4. Configure your database in .env, then run:
   php artisan migrate --seed

5. Start the local server:
   php artisan serve
   
## Default Account
    Admin
    Email: admin@sinda.local
    Password: Admin@12345 (change after first login)





   
