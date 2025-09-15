# Apex-Task-5
Final Project – Full Stack Web Application
📌 Project Overview

This is my final project and certification task, where I developed a fully functional web application using PHP and MySQL.
The project integrates CRUD operations, search, pagination, authentication, and security enhancements into a single cohesive application.

✨ Features

🔐 User Authentication – Secure registration & login with password hashing

📝 CRUD Operations – Add, update, delete, and view tasks

🔍 Search Functionality – Filter tasks by keywords

📑 Pagination – Navigate large datasets efficiently

🛡 Security Enhancements – SQL Injection protection using prepared statements

⚡ Debugged & Tested – Functional, usability, and security testing

🛠 Tech Stack

Frontend: HTML, CSS, JavaScript

Backend: PHP

Database: MySQL

Environment: XAMPP / WAMP

⚙ Installation & Setup
1️⃣ Clone the Repository
git clone https://github.com/your-username/final-project.git
cd final-project

2️⃣ Setup Database

Start XAMPP or WAMP.

Open phpMyAdmin
.

Create a new database:

CREATE DATABASE final_project;


Import tables by running this script:

USE final_project;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    description TEXT
);

3️⃣ Run the Project

Place the project folder inside:

C:\xampp\htdocs\final_project\


Start Apache & MySQL from XAMPP Control Panel.

Open in browser:

http://localhost/final_project/index.php

📷 Screenshots

🔐 Login & Registration Page

📝 CRUD Task Management

🔍 Search & Pagination

🚀 Deliverables

A polished, fully functional web application with authentication, CRUD, search, and pagination.

Output:<img width="1917" height="963" alt="Screenshot 2025-09-12 102322" src="https://github.com/user-attachments/assets/c12f17d9-2d94-4156-b198-c7c2f0f7939e" />
<img width="1917" height="911" alt="Screenshot 2025-09-12 102421" src="https://github.com/user-attachments/assets/d33fcbd7-18f5-43a3-ac3b-3cab108d0b22" />

