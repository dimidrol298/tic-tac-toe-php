# tic-tac-toe-php

## Installation

Clone the repository-
```
git clone https://github.com/dimidrol298/RaDevs-laravel-crud.git
```
Then do a composer install and composer update
```
composer install && composer update
```
## Database setup

Add base dump.
```
tic-tac-toe.sql
```
Configure database access in config/db.php file

## API routes:
//POST create new game
http://localhost/api/start

//GET all-games
http://localhost/api/all-games

//GET get game 
http://localhost/api/game/9

//DELETE delete game
http://localhost/api/game/7/delete

//POST make move
Params [row, column]
http://localhost/api/game/9/move

