-- =====================================================
-- رحّال — قاعدة البيانات (آخر تحديث)
-- RAHHAL Hotel Experience Booking Platform
-- Database: rahhal_db
-- =====================================================

CREATE DATABASE IF NOT EXISTS rahhal_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rahhal_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS experiences;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS admins;
SET FOREIGN_KEY_CHECKS = 1;

-- ===================== المشرفون =====================
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================== المستخدمون =====================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(500),
    mobile VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================== الفئات =====================
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(200) NOT NULL,
    category_icon VARCHAR(100) DEFAULT 'fas fa-hotel',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================== التجارب =====================
CREATE TABLE experiences (
    experience_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(300) NOT NULL,
    hotel_name VARCHAR(300) NOT NULL,
    description TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    address VARCHAR(500),
    image VARCHAR(500) DEFAULT 'default.jpg',
    price_per_night DECIMAL(10,2) NOT NULL,
    rating DECIMAL(3,1) DEFAULT 4.5,
    available_rooms INT DEFAULT 10,
    is_featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================== الحجوزات =====================
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    experience_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    rooms INT NOT NULL DEFAULT 1,
    total_price DECIMAL(12,2) NOT NULL,
    status ENUM('confirmed','cancelled','pending') DEFAULT 'confirmed',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (experience_id) REFERENCES experiences(experience_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================== بيانات الفئات =====================
INSERT INTO categories (category_id, category_name, category_icon) VALUES
(1,'المنتجعات الفاخرة','fas fa-crown'),
(2,'الفنادق التراثية','fas fa-landmark'),
(3,'المنتجعات الشاطئية','fas fa-umbrella-beach'),
(4,'الإقامات الجبلية','fas fa-mountain'),
(5,'المنتجعات العائلية','fas fa-home'),
(6,'تجارب شهر العسل','fas fa-heart'),
(7,'المخيمات الصحراوية','fas fa-campground'),
(8,'منتجعات الصحة والعافية','fas fa-spa'),
(9,'الفنادق البوتيكية','fas fa-hotel'),
(10,'المنتجعات البيئية','fas fa-leaf');

-- ===================== المشرف الافتراضي =====================
-- اسم المستخدم: admin | كلمة المرور: password
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ===================== مستخدم تجريبي =====================
-- البريد: user@rahhal.sa | كلمة المرور: password
INSERT INTO users (first_name, last_name, email, password, address, mobile) VALUES
('أحمد', 'السعودي', 'user@rahhal.sa', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'الرياض، حي النخيل', '0501234567');

-- ===================== التجارب الفريدة (5) =====================
INSERT INTO experiences
(experience_id, category_id, title, hotel_name, description, city, address, image, price_per_night, rating, available_rooms, is_featured)
VALUES
(11, 1, 'الاندماج الكامل مع الجبل', 'Desert Rock Resort',
 'فيلات محفورة داخل الجبال الصخرية.\nواحدة من أكثر المشاريع المعمارية جرأة في الشرق الأوسط.\nتجربة تشبه منتجعات الخيال العلمي وسط الطبيعة الصحراوية.',
 'نيوم', 'محمية البحر الأحمر، نيوم، المملكة العربية السعودية', 'desert-rock.jpg', 4500.00, 5.0, 6, 1),

(12, 9, 'النوم داخل فندق من الجليد', 'ICEHOTEL',
 'يُعاد بناؤه من الثلج والجليد كل عام.\nكل جناح يُصمم كعمل فني مختلف.\nمن أكثر تجارب الإقامة غرابة على مستوى العالم.',
 'السويد', 'يوكاسيارفي، السويد', 'icehotel.jpg', 3200.00, 4.8, 8, 1),

(13, 3, 'جناح تحت سطح البحر', 'Conrad Maldives Rangali Island',
 'جناح "Muraka" يضع غرفة النوم تحت الماء.\nيمكنك مشاهدة الأسماك والشعاب المرجانية من سريرك.\nمن أكثر تجارب الفنادق ندرة وفخامة.',
 'المالديف', 'جزيرة رانغالي، جنوب أتول آري، المالديف', 'conrad-maldives.jpg', 9800.00, 5.0, 3, 1),

(14, 10, 'منازل معلقة بين الأشجار', 'Treehotel',
 'غرف على شكل مكعب مرآة أو عش طائر.\nتجربة معمارية وسط الغابات الشمالية.\nمناسب لمحبي الطبيعة والتصميم.',
 'السويد', 'هارادس، شمال السويد', 'treehotel.jpg', 2700.00, 4.9, 7, 1),

(15, 9, 'فندق عائم داخل سفينة تاريخية', 'Fingal Hotel',
 'سفينة تاريخية تحولت إلى فندق فاخر.\nتجربة بحرية مختلفة عن الفنادق التقليدية.\nصُنّف ضمن أبرز الفنادق الفريدة عالمياً.',
 'إدنبرة', 'ميناء ليث، إدنبرة، اسكتلندا', 'fingal-hotel.jpg', 3600.00, 4.7, 5, 1);
