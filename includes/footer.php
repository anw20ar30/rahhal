<footer class="site-footer" id="contact">
  <div class="footer-top">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-4 col-md-6">
          <img src="<?= $base_url??'../' ?>assets/images/logo.png" alt="رحّال" style="height:80px;width:auto;margin-bottom:12px;display:block;">
          <p class="footer-tagline">منصة رحّال لحجز تجارب الإقامة الاستثنائية. نقدم لك أرقى الوجهات في المملكة العربية السعودية.</p>
          <div class="socials">
            <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-btn"><i class="fab fa-snapchat-ghost"></i></a>
            <a href="#" class="social-btn"><i class="fab fa-tiktok"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-6">
          <div class="footer-heading">روابط سريعة</div>
          <ul class="footer-list">
            <li><a href="<?= $base_url??'../' ?>index.php"><i class="fas fa-chevron-left ms-2"></i>الرئيسية</a></li>
            <li><a href="<?= $base_url??'../' ?>pages/experiences.php"><i class="fas fa-chevron-left ms-2"></i>التجارب</a></li>
            <li><a href="<?= $base_url??'../' ?>pages/about.php"><i class="fas fa-chevron-left ms-2"></i>من نحن</a></li>
            <li><a href="<?= $base_url??'../' ?>pages/contact.php"><i class="fas fa-chevron-left ms-2"></i>تواصل معنا</a></li>
            <li><a href="#"><i class="fas fa-chevron-left ms-2"></i>الأسئلة الشائعة</a></li>
            <li><a href="#"><i class="fas fa-chevron-left ms-2"></i>سياسة الخصوصية</a></li>
            <li><a href="#"><i class="fas fa-chevron-left ms-2"></i>الشروط والأحكام</a></li>
          </ul>
        </div>
        <div class="col-lg-2 col-md-6">
          <div class="footer-heading">الوجهات</div>
          <ul class="footer-list">
            <li><a href="<?= $base_url??'../' ?>pages/experiences.php?city=نيوم"><i class="fas fa-map-marker-alt ms-2"></i>نيوم</a></li>
            <li><a href="<?= $base_url??'../' ?>pages/experiences.php?city=السويد"><i class="fas fa-map-marker-alt ms-2"></i>السويد</a></li>
            <li><a href="<?= $base_url??'../' ?>pages/experiences.php?city=المالديف"><i class="fas fa-map-marker-alt ms-2"></i>المالديف</a></li>
            <li><a href="<?= $base_url??'../' ?>pages/experiences.php?city=إدنبرة"><i class="fas fa-map-marker-alt ms-2"></i>إدنبرة</a></li>
          </ul>
        </div>
        <div class="col-lg-4 col-md-6" id="about">
          <div class="footer-heading">تواصل معنا</div>
          <div class="contact-row"><i class="fas fa-phone-alt"></i><div><span>920 000 111</span><small>خدمة العملاء 24/7</small></div></div>
          <div class="contact-row"><i class="fas fa-envelope"></i><div><span>info@rahhal.sa</span><small>البريد الإلكتروني</small></div></div>
          <div class="contact-row"><i class="fas fa-map-marker-alt"></i><div><span>جدة، المملكة العربية السعودية</span><small>المقر الرئيسي</small></div></div>
          <div class="newsletter">
            <p>اشترك في نشرتنا البريدية</p>
            <div class="nl-form">
              <input type="email" class="nl-input" placeholder="بريدك الإلكتروني">
              <button class="nl-btn">اشتراك</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="footer-bottom">
      <span><i class="far fa-copyright ms-1"></i> 2026 رحّال — جميع الحقوق محفوظة</span>
      <div class="payment-icons">
        <i class="fab fa-cc-visa"></i>
        <i class="fab fa-cc-mastercard"></i>
        <i class="fab fa-apple-pay"></i>
      </div>
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $base_url??'../' ?>assets/js/main.js"></script>
</body>
</html>
