-- Eventra Database Schema
-- Create database first: CREATE DATABASE eventra; USE eventra;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Events table (sample data)
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    event_date DATETIME NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO events (title, description, image, event_date, price) VALUES
('Tech Conference 2024', 'Join us for the latest in AI and cloud tech', 'concert.jpg', '2024-12-15 18:00:00', 99.99),
('Music Festival', 'Live performances from top artists', 'festival.jpg', '2024-11-20 19:00:00', 149.99),
('Webinar: Modern PHP', 'Advanced PHP best practices', 'webinar.jpg', '2024-10-30 20:00:00', 49.99);

-- Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
);

-- Admin table
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Sample admin (password: admin123 hashed)
INSERT INTO admin (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password_hash('admin123', PASSWORD_DEFAULT)

-- Blogs table
CREATE TABLE blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample blogs
INSERT INTO blogs (title, content, image) VALUES
('Welcome to Eventra', 'Discover our platform for booking amazing events. Modern UI, secure payments, and more!', 'blog1.jpg'),
('Top Tech Events 2024', 'Must-attend technology conferences and webinars this year.', 'blog2.jpg'),
('Event Planning Tips', 'Pro tips for organizing successful events from scratch.', 'blog3.jpg');


