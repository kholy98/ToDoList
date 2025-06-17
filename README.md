## TODO List
TODO List is a simple and efficient task management application built with Laravel. It allows users to create, update, and delete tasks, helping them stay organized and productive.


## Features

-Create/update Tasks: Add/edit tasks with a title and description.

-Delete Tasks: Remove tasks using soft delete and restore them again.

-Filter and sort tasks in task list.

-Authentication: Sanctum & Fortify.



## Installation

## 1. Clone the Repository

```bash
git clone https://github.com/kholy98/ToDoList.git
cd ToDoList
```

## 2. Install PHP Dependencies

Install Composer dependencies:
```bash
composer install
```


## 3. Set Up the Environment File

Copy the .env.example file to .env:

```bash
cp .env.example .env
```

Update the .env file with your database credentials:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_list
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```


## 4. Generate Application Key

```bash
php artisan key:generate
```


## 5. Run Migrations

```bash
php artisan migrate
```


## 6. Install Frontend Dependencies

```bash
npm install
```


## 7. Compile Assets

```bash
npm run dev
```


## 8. Start the Development Server

```bash
php artisan serve
```










