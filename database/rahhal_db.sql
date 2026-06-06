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
(1, 'تجربة الإقامة الملكية في قصر فندقي', 'فندق روزوود جدة', 'استمتع بإقامة استثنائية في أحد أرقى فنادق جدة. تضم الغرف المصممة بأناقة فائقة إطلالات خلابة على البحر الأحمر، مع خدمات الكونسيرج على مدار الساعة وتجارب طهي فريدة من نوعها.', 'جدة', 'شارع الأمير محمد بن عبدالعزيز، جدة', 'hotel1.jpg', 1850.00, 4.9, 8, 1),
(3, 'منتجع الأمواج الشاطئي الحصري', 'منتجع الشاطئ الذهبي', 'تجربة لا مثيل لها على شاطئ البحر الأحمر. استمتع بالشاليهات البحرية الخاصة والأنشطة المائية المتنوعة وعروض الغروب المذهلة على امتداد الشريط الساحلي.', 'جدة', 'طريق الكورنيش الشمالي، جدة', 'hotel2.jpg', 1200.00, 4.7, 12, 1),
(4, 'ملاذ الهدوء في قلب جبال الهدا', 'منتجع هدا الجبلي', 'تجربة الهروب إلى الطبيعة الجبلية الخلابة في منتجع هدا المتميز. استرخِ في الأجواء المنعشة واستمتع بالمسارات الجبلية ومشاهدة النجوم.', 'الطائف', 'طريق هدا الجبلي، الطائف', 'hotel3.jpg', 980.00, 4.8, 6, 1),
(2, 'رحلة عبر الزمن في قلب الدرعية', 'فندق درعية هيريتيج', 'اكتشف عراقة التاريخ السعودي في قلب الدرعية التراثية. تركيبة فريدة من التراث الأصيل والرفاهية العصرية في أحضان التاريخ الإسلامي.', 'الرياض', 'حي الدرعية التراثي، الرياض', 'hotel4.jpg', 1450.00, 4.6, 10, 1),
(7, 'ليالي الصحراء تحت النجوم', 'مخيم العلا الفاخر', 'غمر نفسك في سحر الصحراء العربية الأصيلة. مخيمات فاخرة مع جلسات السمر التراثية والركوب وتجربة الطعام البدوي الأصيل.', 'العلا', 'محمية العلا الطبيعية', 'hotel5.jpg', 750.00, 4.8, 15, 1),
(6, 'تجربة شهر العسل الاستثنائية', 'منتجع الغروب الرومانسي', 'أجواء رومانسية استثنائية للأزواج. غرف خاصة على البحر مع حوض سباحة خاص وتجارب عشاء خاصة وخدمة باتلر مخصصة.', 'جدة', 'طريق الواجهة البحرية، جدة', 'hotel6.jpg', 2200.00, 5.0, 4, 1),
(5, 'مغامرة عائلية لا تُنسى', 'منتجع أوركيد العائلي', 'الوجهة المثالية للعائلات. ألعاب مائية، أنشطة ترفيهية للأطفال ومطاعم متنوعة.', 'الدمام', 'طريق الخليج، الدمام', 'hotel7.jpg', 890.00, 4.5, 20, 0),
(8, 'منتجع الصحة والتجديد الكامل', 'سبا ووليلنس الراحة', 'رحلة تجديد شاملة للجسد والروح. علاجات سبا فاخرة وجلسات يوغا وحمامات معدنية.', 'أبها', 'منطقة أبها السياحية، عسير', 'hotel8.jpg', 1100.00, 4.7, 8, 0),
(1, 'قمة الترف في فورسيزونز الرياض', 'فورسيزونز الرياض', 'معايير الضيافة العالمية في قلب العاصمة السعودية. غرف أنيقة بإطلالات بانورامية وخدمات VIP حصرية.', 'الرياض', 'مركز المملكة، شارع الملك فهد', 'hotel9.jpg', 2500.00, 4.9, 5, 0),
(9, 'تجربة بوتيك فريدة في العاصمة', 'فندق الأمير البوتيكي', 'فندق بوتيك راقٍ بتصميم داخلي فريد وخدمة شخصية مخصصة في قلب المدينة.', 'الرياض', 'حي العليا، الرياض', 'hotel10.jpg', 1350.00, 4.6, 7, 0);

-- مستخدم تجريبي (كلمة المرور: password)
INSERT INTO users (first_name, last_name, email, password, address, mobile) VALUES
('أحمد', 'السعودي', 'user@rahhal.sa', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'الرياض، حي النخيل', '0501234567');
