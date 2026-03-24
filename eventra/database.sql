-- Eventra Database Setup
-- Run in phpMyAdmin: Create DB 'eventra' (utf8mb4_unicode_ci), import this.

CREATE DATABASE IF NOT EXISTS eventra CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eventra;

-- Users table (add is_admin for admin check)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Events table
CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    event_date DATETIME NOT NULL,
    location VARCHAR(200),
    price DECIMAL(10,2) DEFAULT 0.00,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings table
CREATE TABLE bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE KEY unique_booking (user_id, event_id)
);

-- Blogs table (NEW)
CREATE TABLE blogs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Data (Register admin@example.com / admin123, or manually set is_admin=1)
INSERT INTO users (name, email, password, is_admin) VALUES 
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1); -- password: password

INSERT INTO events (title, description, event_date, location, price, image) VALUES 
('Tech Conference 2024', 'Join us for the latest in tech trends', '2024-12-01 09:00:00', 'City Convention Center', 99.99, 'tech_conf.jpg'),
('Music Festival', 'Live music all weekend', '2024-11-15 18:00:00', 'Central Park', 49.99, 'music_fest.jpg');

INSERT INTO blogs (title, content, image) VALUES 
('Welcome to Eventra', 'Welcome to Eventra, your premier platform for discovering amazing events, booking tickets, and staying updated with our insightful blog posts. Whether you\'re planning your next adventure or just browsing for inspiration, Eventra has you covered. Explore featured events on the homepage, book with our secure payment system, and dive into our regularly updated blog for tips, stories, and industry news.', 'welcome.jpg'),
('Getting Started with Event Booking', 'Booking events has never been easier! Step 1: Browse featured events on the homepage. Step 2: Click "Book Now" and select your payment method. Step 3: Complete payment and receive instant confirmation. Step 4: View all your bookings in your personal dashboard. Pro tip: You can book multiple events and manage everything from one place. Our system prevents duplicate bookings automatically!', 'booking-guide.jpg'),
('Top 5 Tech Conferences in 2024', '2024 is shaping up to be an incredible year for tech conferences. 1. TechConf 2024 - AI & Machine Learning focus. 2. WebSummit Lisbon - Europe\'s biggest tech event. 3. DeveloperWeek - Hands-on workshops. 4. AWS re:Invent - Cloud innovation. 5. Google I/O - Latest Android/Google tech. Book early to secure your spot and network with industry leaders. Each event offers unique learning opportunities and networking potential.', 'tech-blogs.jpg'),
('Music Festivals You Can\'t Miss This Year', 'Summer festival season is approaching! Here are must-attend music festivals: 1. Coachella - Iconic desert vibes. 2. Glastonbury - Legendary British festival. 3. Tomorrowland - EDM paradise. 4. Lollapalooza - Multi-genre lineup. 5. Burning Man - Art, music, and community. Check event dates, book tickets, and get ready for unforgettable experiences. Pro tip: Book accommodations early as they sell out fast!', 'music-festivals.jpg'),
('Event Planning Tips for Beginners', 'Planning your first event? Here are 5 essential tips: 1. Define clear objectives. 2. Know your audience. 3. Create a realistic budget. 4. Book venue early. 5. Use Eventra for seamless ticketing and management. From small meetups to large conferences, our platform scales with your needs. Check our admin dashboard for easy event creation and management.', 'planning-tips.jpg');

-- Indexes for performance
CREATE INDEX idx_event_date ON events(event_date);
CREATE INDEX idx_blog_created ON blogs(created_at);
