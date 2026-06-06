# رحّال (RAHHAL) – منصة حجز التجارب الفندقية

> **اكتشف تجارب إقامة استثنائية في أجمل الوجهات**

---

## 📁 هيكل المشروع الكامل

```
rahhal/
├── index.php                          ← الصفحة الرئيسية
├── README.md
│
├── includes/
│   ├── connection.php                 ← اتصال قاعدة البيانات
│   ├── header.php                     ← الهيدر العام
│   ├── footer.php                     ← الفوتر العام
│   └── experience_card.php            ← بطاقة التجربة
│
├── pages/
│   ├── login.php                      ← تسجيل الدخول
│   ├── register.php                   ← إنشاء حساب
│   ├── logout.php                     ← تسجيل الخروج
│   ├── experiences.php                ← قائمة التجارب
│   ├── experience-details.php         ← تفاصيل التجربة
│   ├── book-experience.php            ← نموذج الحجز
│   └── my-bookings.php                ← حجوزاتي
│
├── admin/
│   ├── login.php                      ← دخول الإدارة
│   ├── logout.php
│   ├── dashboard.php                  ← لوحة التحكم
│   ├── experiences.php                ← إدارة التجارب
│   ├── add-experience.php             ← إضافة تجربة
│   ├── edit-experience.php            ← تعديل تجربة
│   ├── delete-experience.php          ← حذف تجربة
│   ├── bookings.php                   ← إدارة الحجوزات
│   ├── users.php                      ← إدارة المستخدمين
│   ├── categories.php                 ← إدارة الفئات
│   └── includes/
│       ├── admin_auth.php
│       ├── admin_header.php
│       └── admin_footer.php
│
├── assets/
│   ├── css/style.css                  ← الأنماط الرئيسية
│   ├── js/main.js                     ← JavaScript الرئيسي
│   ├── js/validation.js               ← التحقق من النماذج
│   ├── images/                        ← صور الفنادق والشعار
│   └── fonts/                         ← خطوط مخصصة
│
├── database/
│   └── rahhal_db.sql                  ← ملف قاعدة البيانات
│
└── docs/
    ├── installation-guide.html        ← دليل التثبيت
    ├── report.html                    ← التقرير الأكاديمي
    └── presentation.html              ← العرض التقديمي
```

---

## ⚙️ متطلبات التثبيت

- XAMPP (PHP 7.4+ مع MySQL 5.7+)
- متصفح حديث (Chrome / Firefox / Edge)

---

## 🚀 خطوات التثبيت السريع

### 1. نسخ الملفات
```
C:\xampp\htdocs\rahhal\
```

### 2. استيراد قاعدة البيانات
```
http://localhost/phpmyadmin
→ أنشئ قاعدة بيانات: rahhal_db (utf8mb4_unicode_ci)
→ Import: database/rahhal_db.sql
```

### 3. تشغيل المشروع
```
http://localhost/rahhal/
```

---

## 👤 بيانات الدخول

| النوع | البريد / المستخدم | كلمة المرور |
|-------|------------------|-------------|
| مستخدم | user@rahhal.sa | password |
| مشرف | admin | password |

رابط الإدارة: `http://localhost/rahhal/admin/login.php`

---

## 🎨 لوحة الألوان

| اللون | الكود |
|-------|-------|
| Beige | #D8C3A5 |
| Dark Brown | #6B4F3A |
| Olive Green | #78866B |
| Warm Cream | #F7F3ED |
| Gold Accent | #B08D57 |

---

## 🗄️ قاعدة البيانات

| الجدول | الوصف |
|--------|-------|
| users | بيانات المستخدمين |
| admins | بيانات المشرفين |
| categories | فئات التجارب (10 فئات) |
| experiences | التجارب الفندقية (10 تجارب) |
| bookings | الحجوزات |

---

## 🔐 الأمان المطبق
- ✅ Prepared Statements ضد SQL Injection
- ✅ `password_hash()` لتشفير كلمات المرور
- ✅ Session Management
- ✅ `htmlspecialchars()` للحماية من XSS
- ✅ التحقق المزدوج: JavaScript + PHP

---

## 📱 التقنيات المستخدمة
- PHP 7.4+ (Procedural)
- MySQL / MariaDB
- HTML5 + CSS3
- Bootstrap 5 RTL
- JavaScript (ES6+)
- Font Awesome 6
- Google Fonts (Tajawal)

---

© 2026 رحّال – جميع الحقوق محفوظة
