-- =====================================================
-- رحّال - قاعدة البيانات الكاملة
-- RAHHAL Hotel Experience Booking Platform
-- =====================================================

CREATE DATABASE IF NOT EXISTS rahhal_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rahhal_db;

-- جدول المشرفين
CREATE TABLE IF NOT EXISTS admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول المستخدمين
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(500),
    mobile VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول الفئات
CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(200) NOT NULL,
    category_icon VARCHAR(100) DEFAULT 'fas fa-hotel',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول التجارب
CREATE TABLE IF NOT EXISTS experiences (
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

-- جدول الحجوزات
CREATE TABLE IF NOT EXISTS bookings (
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

-- Contact form messages
CREATE TABLE IF NOT EXISTS contact_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- البيانات الأولية: الفئات
INSERT INTO categories (category_name, category_icon) VALUES
('المنتجعات الفاخرة', 'fas fa-crown'),
('الفنادق التراثية', 'fas fa-landmark'),
('المنتجعات الشاطئية', 'fas fa-umbrella-beach'),
('الإقامات الجبلية', 'fas fa-mountain'),
('المنتجعات العائلية', 'fas fa-home'),
('تجارب شهر العسل', 'fas fa-heart'),
('المخيمات الصحراوية', 'fas fa-campground'),
('منتجعات الصحة والعافية', 'fas fa-spa'),
('الفنادق البوتيكية', 'fas fa-hotel'),
('المنتجعات البيئية', 'fas fa-leaf');

-- المشرف الافتراضي (كلمة المرور: password)
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- البيانات التجريبية: التجارب
INSERT INTO experiences (category_id, title, hotel_name, description, city, address, image, price_per_night, rating, available_rooms, is_featured) VALUES
(1, 'الاندماج الكامل مع الجبل', 'Desert Rock Resort', 'فيلات محفورة داخل الجبال الصخرية، وواحدة من أكثر المشاريع المعمارية جرأة في الشرق الأوسط. تجربة تشبه منتجعات الخيال العلمي وسط الطبيعة الصحراوية الساحرة، حيث تندمج الإقامة بالكامل مع تكوينات الجبال الصخرية.', 'نيوم', 'محمية البحر الأحمر، نيوم، المملكة العربية السعودية', 'desert-rock.jpg', 4500.00, 5.0, 6, 1),
(9, 'النوم داخل فندق من الجليد', 'ICEHOTEL', 'فندق يُعاد بناؤه من الثلج والجليد كل عام، وكل جناح يُصمم كعمل فني مختلف بأيدي نحاتين عالميين. من أكثر تجارب الإقامة غرابة وتميزاً على مستوى العالم، حيث تنام محاطاً بجدران وأثاث منحوت بالكامل من الجليد.', 'السويد', 'يوكاسيارفي، السويد', 'icehotel.jpg', 3200.00, 4.8, 8, 1),
(3, 'جناح تحت سطح البحر', 'Conrad Maldives Rangali Island', 'جناح "Muraka" الذي يضع غرفة النوم تحت الماء مباشرة، حيث يمكنك مشاهدة الأسماك والشعاب المرجانية الملونة من سريرك. من أكثر تجارب الفنادق ندرة وفخامة في العالم، تجمع بين الرفاهية المطلقة وسحر أعماق المحيط الهندي.', 'المالديف', 'جزيرة رانغالي، جنوب أتول آري، المالديف', 'conrad-maldives.jpg', 9800.00, 5.0, 3, 1),
(10, 'منازل معلقة بين الأشجار', 'Treehotel', 'غرف على شكل مكعب مرآة أو عش طائر معلقة بين الأشجار، تقدم تجربة معمارية فريدة وسط الغابات الشمالية الهادئة. مناسب تماماً لمحبي الطبيعة والتصميم المبتكر، حيث تستيقظ على أصوات الغابة وإطلالات الأشجار الشاهقة.', 'السويد', 'هارادس، شمال السويد', 'treehotel.jpg', 2700.00, 4.9, 7, 1),
(9, 'فندق عائم داخل سفينة تاريخية', 'Fingal Hotel', 'سفينة تاريخية تحولت إلى فندق فاخر عائم، تقدم تجربة بحرية مختلفة تماماً عن الفنادق التقليدية. صُنّف ضمن أبرز الفنادق الفريدة عالمياً، ويجمع بين عراقة التاريخ البحري والرفاهية العصرية على ضفاف ميناء إدنبرة.', 'إدنبرة', 'ميناء ليث، إدنبرة، اسكتلندا', 'fingal-hotel.jpg', 3600.00, 4.7, 5, 1);

-- مستخدم تجريبي (كلمة المرور: password)
INSERT INTO users (first_name, last_name, email, password, address, mobile) VALUES
('أحمد', 'السعودي', 'user@rahhal.sa', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'الرياض، حي النخيل', '0501234567');
