# Library Management System

A robust, Object-Oriented Backend for a Library Management System built with **PHP 8** and **MySQL**. This project demonstrates advanced software architecture concepts including **MVC**, **Service Layer**, **Data Mapper Pattern**, and **Polymorphism**.

## 🚀 Project Overview

The system manages a library inventory of over 50,000 books across 5 campus branches. It handles complex business logic such as differentiated borrowing rules for Students vs. Faculty, automatic fine calculation, inventory tracking, and race-condition-safe transactions.

### Key Features
* **User Management:** Polymorphic handling of `Student` (14-day loan) vs `Faculty` (30-day loan) members.
* **Inventory Control:** Real-time stock tracking across multiple branches (Safi, Nador, Youssoufia).
* **Borrowing System:**
    * Eligibility checks (Unpaid fines, loan limits, membership expiry).
    * Transactional "Check-out" ensuring data integrity.
* **Returns & Fines:** Automatic calculation of overdue fines based on daily rates ($0.50 vs $0.25).
* **Renewals:** Logic to allow one-time renewals unless reserved by another user.

---

## 🏗️ Architecture

The project follows a strict **Domain-Driven MVC** structure:

```text
/library-system
|-- /config
|   |-- Database.php          # Singleton Database Connection
|-- /database
|   |-- schema.sql            # Database creation script
|   |-- seeds.sql             # Dummy data for testing
|-- /src
|   |-- /Core                 # Framework essentials (Logger, Router)
|   |-- /Entities             # Type-safe Objects (BookEntity, MemberEntity)
|   |-- /Models               # Data Access Layer / Repositories
|   |-- /Services             # Business Logic (Fine calc, Date logic)
|-- index.php                 # Simulation / Test Script
