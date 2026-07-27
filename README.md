# Fomo API - Online Store (Task 1)

This repository contains the solution for TASK 1: Online Store API. It handles a flash sale scenario where customers buy heavily discounted products, focusing on preventing negative inventory during a race condition.

## Features
- RESTful API with JSON format responses.
- Pessimistic Locking (`lockForUpdate`) implemented to safely handle database race conditions.
- Automated feature test to simulate a burst of concurrent requests.

## Tech Stack
- PHP 8.x
- Laravel 11 (or your version)
- MySQL / SQLite

## Requirements Met
- An Order consists of at minimum one Order Item.
- Prevents negative Inventory quantity value.
- Handles race condition during a burst of Orders.
- Automated functional test included.

## How to Setup & Run
1. Clone this repository.
2. Run `composer install`.
3. Copy `.env.example` to `.env` and set up your database configuration.
4. Run `php artisan key:generate`.
5. Run migrations: `php artisan migrate`.
6. Start the development server: `php artisan serve`.

## How to Run the Race Condition Test
To test the API's ability to handle race conditions (simulating 20 concurrent requests buying a product with only 5 stock):

```bash
php artisan test --filter RaceConditionTest
