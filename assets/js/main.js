/* رحّال - JavaScript الرئيسي */
document.addEventListener('DOMContentLoaded', function () {

    // Sticky Header
    const header = document.getElementById('mainHeader');
    if (header) {
        window.addEventListener('scroll', () => header.classList.toggle('scrolled', window.scrollY > 60));
    }

    // Intersection Observer للبطاقات
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('animate-fade-up');
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.15 });
    document.querySelectorAll('.experience-card,.category-card,.feature-item,.testimonial-card')
        .forEach(el => { el.style.opacity = '0'; obs.observe(el); });

    // حساب التكلفة الإجمالية
    const checkIn   = document.getElementById('check_in');
    const checkOut  = document.getElementById('check_out');
    const roomsInput= document.getElementById('rooms');
    const totalEl   = document.getElementById('total_price_display');
    const totalInput= document.getElementById('total_price');
    const nightsEl  = document.getElementById('nights_display');
    const ppn       = parseFloat(document.getElementById('price_per_night_val')?.value || 0);

    function calcTotal() {
        if (!checkIn || !checkOut) return;
        const d1 = new Date(checkIn.value), d2 = new Date(checkOut.value);
        if (isNaN(d1) || isNaN(d2) || d2 <= d1) {
            if (totalEl) totalEl.textContent = '—';
            if (nightsEl) nightsEl.textContent = '—';
            return;
        }
        const nights = Math.round((d2 - d1) / 86400000);
        const rooms  = parseInt(roomsInput?.value) || 1;
        const total  = nights * rooms * ppn;
        if (nightsEl)  nightsEl.textContent  = nights + ' ليلة';
        if (totalEl)   totalEl.textContent   = total.toLocaleString('ar-SA') + ' ر.س';
        if (totalInput) totalInput.value     = total.toFixed(2);
        // مزامنة ملخص جانبي
        const nd2 = document.getElementById('nights_display_2');
        const td2 = document.getElementById('total_display_2');
        const rd2 = document.getElementById('rooms_display');
        if (nd2) nd2.textContent = nights + ' ليلة';
        if (td2) td2.textContent = total.toLocaleString('ar-SA') + ' ر.س';
        if (rd2) rd2.textContent = rooms + ' غرفة';
    }

    if (checkIn) {
        const today = new Date().toISOString().split('T')[0];
        checkIn.min = today;
        checkIn.addEventListener('change', function () { if (checkOut) checkOut.min = this.value; calcTotal(); });
    }
    if (checkOut)   checkOut.addEventListener('change', calcTotal);
    if (roomsInput) roomsInput.addEventListener('change', calcTotal);

    // إغلاق التنبيهات
    document.querySelectorAll('.alert-auto-close').forEach(a => {
        setTimeout(() => { a.style.transition='opacity .5s'; a.style.opacity='0'; setTimeout(()=>a.remove(),500); }, 4000);
    });

    // تأكيد الإلغاء
    document.querySelectorAll('.btn-cancel-booking').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (confirm('هل أنت متأكد من إلغاء هذا الحجز؟')) window.location.href = this.href;
        });
    });

    // تأكيد الحذف (الإدارة)
    document.querySelectorAll('.btn-delete-exp').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (confirm('هل أنت متأكد من حذف هذه التجربة؟ لا يمكن التراجع.')) window.location.href = this.href;
        });
    });

    // معاينة الصورة
    const imgInput   = document.getElementById('image_upload');
    const imgPreview = document.getElementById('image_preview');
    if (imgInput && imgPreview) {
        imgInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => { imgPreview.src = e.target.result; imgPreview.style.display = 'block'; };
                reader.readAsDataURL(file);
            }
        });
    }

    // Counter Animation
    function animateCounters() {
        document.querySelectorAll('.counter-num').forEach(el => {
            const target = parseInt(el.getAttribute('data-target')) || 0;
            let current = 0;
            const step = target / 60;
            const timer = setInterval(() => {
                current = Math.min(current + step, target);
                el.textContent = Math.floor(current).toLocaleString('ar-SA');
                if (current >= target) clearInterval(timer);
            }, 25);
        });
    }
    const statsSection = document.querySelector('.hero-stats');
    if (statsSection) {
        new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) { animateCounters(); } });
        }, { threshold: .5 }).observe(statsSection);
    }

    // أزرار الكمية
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            if (!input) return;
            let val = parseInt(input.value) || 1;
            if (this.dataset.action === 'plus' && val < parseInt(input.max||99)) input.value = val + 1;
            if (this.dataset.action === 'minus' && val > parseInt(input.min||1)) input.value = val - 1;
            input.dispatchEvent(new Event('change'));
        });
    });

    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function (e) {
            const id = this.getAttribute('href');
            if (id === '#') return;
            const target = document.querySelector(id);
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });

    // إظهار/إخفاء كلمة المرور
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const icon  = this.querySelector('i');
            if (!input) return;
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            if (icon) { icon.classList.toggle('fa-eye', !isPass); icon.classList.toggle('fa-eye-slash', isPass); }
        });
    });

    // مؤشر قوة كلمة المرور
    const passInput = document.getElementById('password');
    const strengthBar = document.getElementById('password_strength');
    if (passInput && strengthBar) {
        passInput.addEventListener('input', function () {
            const val = this.value;
            let s = 0;
            if (val.length>=6) s++;
            if (val.length>=10) s++;
            if (/[A-Z]/.test(val)||/[أ-ي]/.test(val)) s++;
            if (/[0-9]/.test(val)) s++;
            if (/[^A-Za-z0-9]/.test(val)) s++;
            const colors=['','#e74c3c','#e67e22','#f39c12','#27ae60','#1e8449'];
            const widths=['0%','20%','40%','60%','80%','100%'];
            strengthBar.style.width = widths[s]||'0%';
            strengthBar.style.backgroundColor = colors[s]||'#ccc';
        });
    }
});
