# Backend - Laravel/PHP
1. run docker compose up -d
2. run cp .env.example .env (no extra changes needed here) 
3. run docker compose exec laravel.test composer install
4. run docker compose exec laravel.test php artisan migrate (this will create the DB schema and the stored procedures!)
5. run docker compose exec laravel.test php db:seed (this will give you an admin user: admin@admin.com/admin (email/pass))
5. API should be available at http://localhost/api

# Frontend - Vue/JS - VITE
1. run docker compose exec laravel.test npm i
2. run docker compose exec laravel.test npm run dev
3. SPA should be available at http://localhost