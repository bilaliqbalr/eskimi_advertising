## Eskimi Advertising

Application for managing advertising campaigns. 

## Installation steps
This application is using laradock for managing docker containers, to install it execute following command
```bash
# To run server
cd ea_laradock
docker-compose up -d nginx mysql redis

# moving back to codebase
cd ../

# Execute following commands to install app
composer install
npm install
npm run dev

php artisan migrate
```
Once all done, access application via `http://localhost`

## Performing test
```bash
php artisan test 
```
