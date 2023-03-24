1. Di sini, saya menggunakan terminal untuk menjalankan perintah Git Clone.
   `git clone git@github.com:ronbackup/Test-Surplus-Indonesia.git`

2. Buat database

3. Setup Environment Variable
   `cp .env.example .env`
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3307
   DB_DATABASE=database_name
   DB_USERNAME=root
   DB_PASSWORD=

4. Jalankan perintah berikut setelah setup environment
   `php artisan key:generate`

5. Melakukan migrate & seed dengan menjalankan perintah berikut ini
   `php artisan migrate --seed`

6. Run Local Dev Server
   `php artisan serve`

7. Lalu buka URL berikut:

-   http://localhost:8000/api/category/
-   http://localhost:8000/api/category-product/
-   http://localhost:8000/api/product/
-   http://localhost:8000/api/product-image/
-   http://localhost:8000/api/image/
