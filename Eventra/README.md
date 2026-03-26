# Eventra - Event Booking Platform

## 🚀 Quick Setup (XAMPP)

1. **Start XAMPP** (Apache + MySQL)

2. **Create Database:**
```sql
CREATE DATABASE eventra;
```

3. **Import SQL:**
```bash
# Copy database.sql to MySQL or phpMyAdmin
# Admin login: admin / admin123
```

4. **Project ready at:** `http://localhost/Eventra`

## 🌟 Features

✅ **User System** - Register/Login/Logout  
✅ **Event Browsing** - Responsive cards  
✅ **Booking Flow** - Events → Confirm → Payment → Success  
✅ **Payment Simulation** - Dummy card/UPI with validation  
✅ **Dashboard** - Upcoming & History  
✅ **Admin Panel** - `http://localhost/Eventra/admin/` (admin/admin123)  
✅ **Duplicate Prevention**  
✅ **Modern UI** - Responsive, animated  
✅ **Security** - PDO prepared statements, password_hash  

## 📱 Test Flow

1. Register new user
2. Browse Events → Book Now
3. Confirm → Pay (any card: 4111111111111111)
4. Success → Dashboard

## 📁 Structure

```
Eventra/
├── config/db.php
├── index.php (home)
├── events.php
├── booking.php
├── payment.php
├── success.php
├── dashboard.php
├── admin/...
├── assets/css/style.css
├── assets/js/script.js
└── database.sql
```

**✨ NEW: Professional Footer + Blog System**
- Footer on all pages (logo, quick links, contact)
- Blog page + details (SEO/content ready)
- Admin blog CRUD (/admin/blogs.php)

**Production Ready! 🔥**

