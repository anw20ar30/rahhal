/* رحّال - التحقق من النماذج بالعربية */

function showError(id, msg) {
    const f = document.getElementById(id), e = document.getElementById(id+'_error');
    if (f) f.classList.add('is-invalid');
    if (e) { e.textContent = msg; e.classList.add('show'); }
}
function clearError(id) {
    const f = document.getElementById(id), e = document.getElementById(id+'_error');
    if (f) f.classList.remove('is-invalid');
    if (e) { e.textContent = ''; e.classList.remove('show'); }
}
function isEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
function isMobile(v) { return /^(05\d{8}|\+9665\d{8}|009665\d{8})$/.test(v.replace(/\s/g,'')); }

// نموذج التسجيل
const regForm = document.getElementById('registerForm');
if (regForm) {
    ['first_name','last_name','email','password','confirm_password','address','mobile']
        .forEach(id => { const el = document.getElementById(id); if (el) el.addEventListener('input', () => clearError(id)); });
    regForm.addEventListener('submit', function(e) {
        e.preventDefault(); let v = true;
        const fn = document.getElementById('first_name')?.value.trim();
        const ln = document.getElementById('last_name')?.value.trim();
        const em = document.getElementById('email')?.value.trim();
        const pw = document.getElementById('password')?.value;
        const cp = document.getElementById('confirm_password')?.value;
        const ad = document.getElementById('address')?.value.trim();
        const mo = document.getElementById('mobile')?.value.trim();
        if (!fn||fn.length<2) { showError('first_name','يرجى إدخال الاسم الأول (حرفان على الأقل)'); v=false; }
        if (!ln||ln.length<2) { showError('last_name','يرجى إدخال اسم العائلة (حرفان على الأقل)'); v=false; }
        if (!em) { showError('email','يرجى إدخال البريد الإلكتروني'); v=false; }
        else if (!isEmail(em)) { showError('email','صيغة البريد الإلكتروني غير صحيحة'); v=false; }
        if (!pw) { showError('password','يرجى إدخال كلمة المرور'); v=false; }
        else if (pw.length<6) { showError('password','يجب أن تكون كلمة المرور 6 أحرف على الأقل'); v=false; }
        if (!cp) { showError('confirm_password','يرجى تأكيد كلمة المرور'); v=false; }
        else if (pw!==cp) { showError('confirm_password','كلمة المرور وتأكيدها غير متطابقتين'); v=false; }
        if (!ad||ad.length<5) { showError('address','يرجى إدخال العنوان (5 أحرف على الأقل)'); v=false; }
        if (!mo) { showError('mobile','يرجى إدخال رقم الجوال'); v=false; }
        else if (!isMobile(mo)) { showError('mobile','رقم جوال سعودي صحيح مثال: 0501234567'); v=false; }
        if (v) this.submit();
    });
}

// نموذج الدخول
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    ['login_email','login_password'].forEach(id => { const el=document.getElementById(id); if(el) el.addEventListener('input',()=>clearError(id)); });
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault(); let v = true;
        const em = document.getElementById('login_email')?.value.trim();
        const pw = document.getElementById('login_password')?.value;
        if (!em) { showError('login_email','يرجى إدخال البريد الإلكتروني'); v=false; }
        else if (!isEmail(em)) { showError('login_email','صيغة البريد الإلكتروني غير صحيحة'); v=false; }
        if (!pw) { showError('login_password','يرجى إدخال كلمة المرور'); v=false; }
        else if (pw.length<6) { showError('login_password','كلمة المرور قصيرة جدًا'); v=false; }
        if (v) this.submit();
    });
}

// نموذج الحجز
const bookForm = document.getElementById('bookingForm');
if (bookForm) {
    bookForm.addEventListener('submit', function(e) {
        e.preventDefault(); let v = true;
        const ci = document.getElementById('check_in')?.value;
        const co = document.getElementById('check_out')?.value;
        const rooms = parseInt(document.getElementById('rooms')?.value);
        const total = parseFloat(document.getElementById('total_price')?.value);
        if (!ci) { showError('check_in','يرجى اختيار تاريخ الوصول'); v=false; }
        if (!co) { showError('check_out','يرجى اختيار تاريخ المغادرة'); v=false; }
        else if (ci && new Date(co) <= new Date(ci)) { showError('check_out','يجب أن يكون تاريخ المغادرة بعد تاريخ الوصول'); v=false; }
        if (!rooms||rooms<1) { showError('rooms','يرجى اختيار عدد الغرف'); v=false; }
        if (!total||total<=0) { alert('يرجى التحقق من التواريخ وعدد الغرف لحساب التكلفة'); v=false; }
        if (v) this.submit();
    });
}

// نموذج الإدارة
const expForm = document.getElementById('experienceForm');
if (expForm) {
    expForm.addEventListener('submit', function(e) {
        e.preventDefault(); let v = true;
        [['title','يرجى إدخال اسم التجربة'],['hotel_name','يرجى إدخال اسم الفندق'],
         ['description','يرجى إدخال وصف التجربة'],['category_id','يرجى اختيار الفئة'],
         ['price_per_night','يرجى إدخال السعر']].forEach(([id,msg]) => {
            const el = document.getElementById(id);
            if (!el||!el.value.trim()) { showError(id, msg); v=false; } else clearError(id);
        });
        if (v) this.submit();
    });
}
