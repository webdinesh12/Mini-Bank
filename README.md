## Steps to run this project in any system

i) Clone -  
git clone https://github.com/webdinesh12/Mini-Bank.git

ii) Open project folder and rename .env.example file to .env

iii) run "composer install"

iv) Create a new database mini_bank

v) run "php artisan migrate:fresh --seed" - it will create all the tables with a admin credentials

vi) run "php artisan serve"

vii) then to go briwser and hit http://127.0.0.1:8000 and you will redirected to login page where you can login as a admin with below credentials or you can create a new user's account using register button.  

admin email: admin@yopmail.com  
password: Admin1!@
