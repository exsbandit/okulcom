# School APP

How to Run the School App
This is a PHP application that uses the Laravel framework and Mysql database to create a simple messaging system. The app consists of many endpoints for users, orders, products and categories.

To run the app, follow these steps:

Install PHP 8.1 or newer on your machine, if you haven't already.

Install the required dependencies by running composer install in the project root directory. If you don't have Composer installed, you can download it from https://getcomposer.org/.

Create a new SQLite database by running touch chat.db in the project root directory.

Create the necessary database tables by running the following commands:
```php
php artisan migrate
php artisan passport:install
php artisan db:seed DatabaseSeeder

```

Start the PHP built-in web server by running php artisan serve in the project root directory.

Now you can test the API endpoints by sending HTTP requests to default http://localhost:8000/. You can use a tool like Postman or cURL to send requests.

Here are some example requests:

**Create a new user**

POST /api/user/register
``` JSON
{
    "name": "Erdem",
    "email": "erdem@gmail.com",
    "password": "a123456"
}
```
**Login with user**

POST /api/auth/token
``` JSON
{
    "username": "erdem@gmail.com",
    "password": "a123456"
}
```

**Add detail to user**

POST /api/v1/user/detail
``` JSON
{
    "first_name": "TEST",
    "last_name": "User",
    "phone_number": "+905555555507"
}
```

**Create Order**

POST /api/v1/order
``` JSON
{
    "products": [
        {
            "id": 3,
            "quantity": 5
        },
        {
            "id": 4,
            "quantity": 2
        },
        {
            "id": 5,
            "quantity": 3
        }
    ]
}
```
