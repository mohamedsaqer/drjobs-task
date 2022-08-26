##install

composer install

cp .env.example .env

put your database credential

php artisan key:generate

php artisan migrate --seed

## credential
Active Admin
email: admin@admin.com
password: password

Active User
email: user@user.com
password: password

the other admins and users created randomly by seeder
