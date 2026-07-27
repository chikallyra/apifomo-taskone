# PT FOMO INOVASI TEKNOLOGI – Fullstack Engineer Assessment

This repository contains my solution for the **PT FOMO Inovasi Teknologi Fullstack Engineer Assessment**.

The project consists of two tasks:

1. **Online Store API** – A Laravel REST API that safely handles flash sale purchases and prevents race conditions.
2. **Hidden Item Game** – A PHP command-line application that determines the possible location of a hidden item on a grid.

---

# 🚀 Tech Stack

- PHP 8.3
- Laravel 13
- MySQL

---

# 🛒 Task 1 – Online Store API

## Overview

This REST API simulates an online store flash sale where multiple customers attempt to purchase the same product simultaneously.

The main objective is to ensure that inventory remains consistent even under heavy concurrent requests.

## Features

- RESTful API with JSON responses
- Proper HTTP status codes
- Database transactions
- Pessimistic locking using `lockForUpdate()`
- Prevents negative inventory values
- Orders contain at least one Order Item
- Automated race condition testing

---

## Setup

1. Clone this repository.

2. Install dependencies.

```bash
composer install
```

3. Copy the environment file.

```bash
cp .env.example .env
```

4. Configure your database inside `.env`.

5. Generate the application key.

```bash
php artisan key:generate
```

6. Run database migrations.

```bash
php artisan migrate
```

7. Start the development server.

```bash
php artisan serve
```

---

## Running the Race Condition Test

The test simulates **20 concurrent purchase requests** against a product with **only 5 items in stock**.

```bash
php artisan test --filter RaceConditionTest
```

The expected result is:

- Only the available stock is sold.
- Inventory never becomes negative.
- Excess purchase requests fail safely.

---

# 🗺️ Task 2 – Hidden Item Game

## Overview

This is a command-line PHP program that analyzes a grid to determine the possible locations of a hidden item based on a predefined movement sequence.

The navigation starts from the player position (`X`) and follows the movement pattern:

- Up (North)
- Right (East)
- Down (South)

The program ignores paths blocked by obstacles (`#`) and outputs all valid destination coordinates.

---

## Features

- Grid mapping
- Obstacle (`#`) collision detection
- Navigation and pathfinding logic
- Outputs all possible hidden item coordinates
- Bonus visualization with probable locations marked using `$`

---

## Running the Program

From the project root directory, execute:

```bash
php hidden_item.php
```
