# Page Assessment Task


## Stack used
- PHP 7.2 (Laravel Framework)
- SQLITE || MySQL Database


## Installation & Set-up

- clone the project 
```
git clone https://github.com/gr8skills/page-assessment.git page_assessment
```
- SQLITE || MySQL Database setup in .env file (defaulted to sqlite)

- create sqlite database file (to use sqlite)
```
touch database/database.sqlite
``` 

- migrate table and seed data
```
php artisan migrate --seed
``` 

- serve the project
```
php artisan serve
``` 

- hit the api below to add more wallet accounts to the seeded users using these parameters [wallet_qty:int, user_qty:int] POST Call
```
http://127.0.0.1:8000/api/v1/more/wallets
``` 


## apis

- **[all users](http://127.0.0.1:8000/api/v1/users)** {GET Request}
```
http://127.0.0.1:8000/api/v1/users
``` 

- **[a user’s detail including the wallets he/she own and his/her transaction history](http://127.0.0.1:8000/api/v1/user/id)** {GET Request}
```
http://127.0.0.1:8000/api/v1/user/id
``` 
- **[All Wallets]( http://127.0.0.1:8000/api/v1/wallets)** {GET Request}
```
http://127.0.0.1:8000/api/v1/user/id
``` 

- **[a wallet’s detail including its owner, type and the transaction history of that wallet](http://127.0.0.1:8000/api/v1/wallet/nuban)** {GET Request}
```
 http://127.0.0.1:8000/api/v1/wallet/nuban OR http://127.0.0.1:8000/api/v1/wallet/id
``` 
- **[count of users, count of wallets, total wallet balance, total volume of transactions.](http://127.0.0.1:8000/api/v1/stats)** {GET Request}

- **[Send money from one wallet to another](http://127.0.0.1:8000/api/v1/send-money)** {POST Call [Form-data: sender => nuban || id, receiver: nuban || id, amount: double]}
```
  http://127.0.0.1:8000/api/v1/send-money
``` 

# &nbsp;

## Important mentions
- A sample sqlite database file (database_sample.sqlite) is included in database folder
- To use this sample instead of creating a new one, rename the file to database.sqlite
- To use MySQL instead of SQLITE, modify the .env file with the correct credentials

