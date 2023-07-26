untuk pertama kali dilakukan 
buka terminal pada projek anda kemudian 

cp .env.example .env

kemudian buat database pada mysql di local server, setting nama database anda pada .env

pada terminal lakukan 

composer install

setelah itu 

php artisan key:generate

php artisan migrate

php artisan db:seed

npm run dev

php artisan serve