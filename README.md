# Internity — Automated Internship & Capstone Tracking System (OJT Manager)

## Project Summary

Internity is a centralized web portal for tracking student OJT (On-the-Job Training). Student trainees clock in/out, submit daily task logs, upload weekly PDF reports, and monitor their hour-completion progress. Faculty advisors and industry mentors review logs, approve rendered hours, grade milestone submissions, and filter students by risk status.

Developed for **ICT 07: Web Design and Development (4BSCS)** — Project Option C, with Honors / Extra Credit features (PDF/CSV reporting engine, and rate limiting & audit logging middleware).

---

## File Structure

```
Internity/
│── backend/                 # Laravel 13 application (OOP PHP MVC)
│   ├── app/                 # Controllers / Models / Middleware
│   ├── config/              # Settings, DB config, view path -> ../templates
│   ├── database/            # Migrations, seeders, factories
│   ├── routes/              # Web and API routes
│   ├── resources/           # Vite entry points importing ../static assets
│   ├── storage/             # Logs and private file uploads
│   ├── public/index.php     # Front controller / web root
│   └── artisan              # Laravel CLI
│── static/                  # Static assets (bundled via Vite)
│   ├── css/                 # Custom CSS (overrides Bootstrap)
│   ├── js/                  # Vanilla JS (fetch operations, DOM manipulation)
│   └── vendor/              # Bootstrap, Bootstrap Icons, Chart.js (local copies)
│── templates/               # HTML5 responsive Blade layout templates
│── docs/                    # ERD diagram, SRS document, API spec
│── .env.example             # Environment template (no real secrets)
│── .gitignore               # Ignored files (vendor, .env, node_modules, cache)
│── database_seed.sql        # SQL script to seed sample data
└── README.md
```

---

## Libraries Used

| Layer | Library / Framework | Version |
|-------|---------------------|---------|
| Backend | Laravel (PHP) | 13.x |
| Backend | barryvdh/laravel-dompdf (PDF generation) | 3.1 |
| Database | MySQL | 8.x |
| Frontend | HTML5, CSS3, Vanilla JavaScript (ES6+, Fetch API) | — |
| Frontend | Bootstrap | 5.3.9 |
| Frontend | Bootstrap Icons | 1.13.2 |
| Frontend | Chart.js | 4.6.1 |
| Build Tool | Vite (`laravel-vite-plugin`) | 8.x |

Bootstrap, Bootstrap Icons, and Chart.js are stored locally in `static/vendor/` and bundled by Vite into `backend/public/build/`, so the built application runs completely offline during the defense.

---

## Setup

### Prerequisites

- PHP 8.3+ (extensions: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `zip`, `dom`, `curl`)
- Composer 2.x
- Node.js 20+ & npm (used by Vite to bundle `static/` CSS & JS)
- MySQL 8.x (e.g. XAMPP or Laragon)
- Git

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/77whitesheeps/Internity.git
cd Internity/backend

# 2. Install PHP and frontend build dependencies
composer install
npm install

# 3. Create the environment file (backend/.env) and generate the app key
cp .env.example .env          # Windows PowerShell: Copy-Item .env.example .env
php artisan key:generate

# 4. Create a MySQL database named "internity", then update backend/.env:
#      - DB_USERNAME / DB_PASSWORD for your MySQL server
#      - INTERNITY_ADMIN_NAME / INTERNITY_ADMIN_EMAIL / INTERNITY_ADMIN_PASSWORD
#        (the initial administrator account created by the seeder)

# 5. Run migrations and seed roles, the admin account, and local demo users
php artisan migrate --seed

# 6. Build frontend assets with Vite
npm run build                 # Or run `npm run dev` during active development

# 7. Start the development server
php artisan serve
```

Open **http://127.0.0.1:8000** in your browser.
