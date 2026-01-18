-- 1. Populate Branches (YouCode Campuses)
INSERT INTO branches (name, location, phone, operatingHours) VALUES 
('YouCode Safi', 'Safi, Morocco', '+212-500-111111', '08:00 - 20:00'),
('YouCode Nador', 'Nador, Morocco', '+212-500-222222', '08:00 - 18:00'),
('YouCode Youssoufia', 'Youssoufia, Morocco', '+212-500-333333', '09:00 - 17:00');

-- 2. Populate Categories
INSERT INTO categories (name) VALUES 
('Computer Science'), 
('Fiction'), 
('Mathematics'), 
('Philosophy'), 
('Business');

-- 3. Populate Authors
-- Note: 'primaryGenre' assumes IDs 1-5 from above.
INSERT INTO authors (name, biography, nationality, birthDate, primaryGenre) VALUES 
('Robert C. Martin', 'Uncle Bob, author of Clean Code.', 'American', '1952-12-05', 1),
('J.K. Rowling', 'British author, best known for Harry Potter.', 'British', '1965-07-31', 2),
('Andrew Hunt', 'Co-author of The Pragmatic Programmer.', 'American', '1960-01-01', 1),
('Albert Camus', 'French philosopher and author.', 'French', '1913-11-07', 4);

-- 4. Populate Books
INSERT INTO books (isbn, title, publicationYear, status) VALUES 
('9780132350884', 'Clean Code', 2008, 'Available'),
('9780201616224', 'The Pragmatic Programmer', 1999, 'Available'),
('9780747532743', 'Harry Potter and the Philosopher''s Stone', 1997, 'Available'),
('9780679720201', 'The Stranger', 1942, 'Maintenance');

-- 5. Link Books to Authors
INSERT INTO book_authors (bookISBN, authorId) VALUES 
('9780132350884', 1), -- Clean Code -> Uncle Bob
('9780201616224', 3), -- Pragmatic Programmer -> Andrew Hunt
('9780747532743', 2), -- Harry Potter -> Rowling
('9780679720201', 4); -- The Stranger -> Camus

-- 6. Link Books to Categories
INSERT INTO book_categories (bookISBN, categoryId) VALUES 
('9780132350884', 1),
('9780201616224', 1),
('9780747532743', 2),
('9780679720201', 2),
('9780679720201', 4);

-- 7. Populate Inventory (Distributing books across YouCode campuses)
INSERT INTO inventory (branchId, bookISBN, totalCopies, availableCopies) VALUES 
-- YouCode Safi
(1, '9780132350884', 10, 8), -- Clean Code
(1, '9780201616224', 5, 5),  -- Pragmatic Programmer
-- YouCode Nador
(2, '9780132350884', 5, 2),  -- Clean Code
(2, '9780747532743', 3, 3),  -- Harry Potter
-- YouCode Youssoufia
(3, '9780201616224', 4, 4),  -- Pragmatic Programmer
(3, '9780679720201', 2, 0);  -- The Stranger (0 available)

-- 8. Populate Members
INSERT INTO members (name, email, phone, type, membership, startDate, expiryDate, isActive) VALUES 
('Ahmed Alami', 'ahmed@student.youcode.ma', '0600000001', 'Student', 'Undergraduate', '2024-09-01', '2025-06-30', TRUE),
('Sara Bennani', 'sara@student.youcode.ma', '0600000002', 'Student', 'graduate', '2024-09-01', '2025-06-30', TRUE),
('Prof. Hassan', 'hassan@faculty.youcode.ma', '0600000003', 'Faculty', 'Professor', '2023-01-01', '2026-01-01', TRUE);

-- 9. Populate Loans (Active and Returned)
INSERT INTO loans (memberId, bookISBN, branchId, borrowDate, dueDate, returnDate, status) VALUES 
-- Ahmed borrowed Clean Code from Safi (Active)
(1, '9780132350884', 1, DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_ADD(NOW(), INTERVAL 9 DAY), NULL, 'Borrowed'),
-- Sara borrowed The Stranger from Youssoufia (Overdue!)
(2, '9780679720201', 3, DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), NULL, 'Overdue'),
-- Prof. Hassan returned Harry Potter
(3, '9780747532743', 2, DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY), 'Returned');

-- 10. Populate Reservations
INSERT INTO reservations (memberId, bookISBN, branchId, reservationDate, status) VALUES 
(1, '9780679720201', 3, NOW(), 'Pending'); -- Ahmed waiting for The Stranger at Youssoufia