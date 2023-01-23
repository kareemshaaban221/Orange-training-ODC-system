# Orange-training-ODC-system

### Steps of the installation process

- make a clone from odc-admin-panel branch
```git clone -b odc-admin-panel https://github.com/kareemshaaban221/Orange-training-ODC-system.git```
- open a terminal in the main directory of the project and write
```composer install```
- create new file and name it `.env`
- copy the `.env.example` file to `.env` file
- write on the terminal
```php artisan key:generate```
- create a mysql database called
```odc_registration_system```
- write on the terminal
```php artisan migrate```
- run a server and enjoy testing on postman!

### Don't forget to take a look on `routes/api.php` file to know the available routes!
