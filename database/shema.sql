create DATABASE library;
use library;

CREATE TABLE branches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    operatingHours VARCHAR(100)
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    biography TEXT,
    nationality VARCHAR(50),
    birthDate DATETIME,
    deathDate DATETIME,
    primaryGenre int,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (primaryGenre) REFERENCES categories(id)
);

CREATE TABLE books (
    isbn VARCHAR(13) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    publicationYear YEAR NOT NULL,
    status VARCHAR(100) DEFAULT 'Available',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE book_authors (
    bookISBN VARCHAR(13),
    authorId INT,
    PRIMARY KEY (bookISBN, authorId),
    FOREIGN KEY (bookISBN) REFERENCES books(isbn) ON DELETE CASCADE,
    FOREIGN KEY (authorId) REFERENCES authors(id) ON DELETE CASCADE
);

CREATE TABLE book_categories (
    bookISBN VARCHAR(13),
    categoryId INT,
    PRIMARY KEY (bookISBN, categoryId),
    FOREIGN KEY (bookISBN) REFERENCES books(isbn) ON DELETE CASCADE,
    FOREIGN KEY (categoryId) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    branchId INT,
    bookISBN VARCHAR(13),
    totalCopies INT DEFAULT 0,
    availableCopies INT DEFAULT 0,
    FOREIGN KEY (branchId) REFERENCES branches(id),
    FOREIGN KEY (bookISBN) REFERENCES books(isbn)
);

CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    type ENUM('Student', 'Faculty') NOT NULL,
    membership VARCHAR(50) NOT NULL,
    startDate DATETIME NOT NULL,
    expiryDate DATETIME NOT NULL,
    totalBorrowed INT DEFAULT 0,
    unpaidFines DECIMAL(10, 2) DEFAULT 0.00,
    isActive BOOLEAN DEFAULT TRUE
);

CREATE TABLE loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    memberId INT,
    bookISBN VARCHAR(13),
    branchId INT,
    borrowDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    dueDate DATETIME NOT NULL,
    returnDate DATETIME NULL,
    status ENUM('Borrowed', 'Returned', 'Overdue') DEFAULT 'Borrowed',
    renewalCount INT DEFAULT 0,
    fineApplied DECIMAL(10, 2) DEFAULT 0.00,
    FOREIGN KEY (memberId) REFERENCES members(id),
    FOREIGN KEY (bookISBN) REFERENCES books(isbn),
    FOREIGN KEY (branchId) REFERENCES branches(id)
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    memberId INT,
    bookISBN VARCHAR(13),
    branchId INT,
    reservationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Fulfilled', 'Cancelled') DEFAULT 'Pending',
    FOREIGN KEY (memberId) REFERENCES members(id),
    FOREIGN KEY (bookISBN) REFERENCES books(isbn),
    FOREIGN KEY (branchId) REFERENCES branches(id)
);