<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

Nama : Muhamad Fatah Rozaq

Email : fatah.rozaq20@gmail.com

Link Skema Database : https://drive.google.com/file/d/1iE1_oG555ZVk9XGr3_NP1rrsXemK9XFX/view?usp=sharing

## Instalasi Proyek

1. composer install
2. npm install && npm run build
3. cp .env.example .env
4. php artisan key:generate

Kemudian konfigurasikan file .env sebagai berikut
- DB_CONNECTION=pgsql
- DB_HOST=127.0.0.1
- DB_PORT=5432
- DB_DATABASE=taskflow_laravel_dashboard
- DB_USERNAME=root
- DB_PASSWORD=

Untuk nama database, username, dan password sesuaikan dengan konfigurasi yang ada di device anda

Setelah itu lanjutkan langkah berikut ini 

5. php artisan migrate:fresh --seed 
6. php artisan make:filament-user 

Melalui command no 6, akan diarahkan untuk mengisi name, email dan password. Data ini akan digunakan untuk login ke dalam dashboard admin

Kemudian lakukan

7. php artisan serve
8. Akses halaman http://127.0.0.1:8000/admin/login






