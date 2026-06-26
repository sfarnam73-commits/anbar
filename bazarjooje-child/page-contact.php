<?php
/**
 * Template Name: تماس با ما
 */
get_header(); ?>

<style>
.contact-hero{background:linear-gradient(135deg,var(--primary-deeper),var(--primary));padding:60px 0;text-align:center;color:#fff;position:relative;overflow:hidden}
.contact-hero::before{content:'';position:absolute;inset:0;background:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="30" cy="30" r="2" fill="rgba(255,255,255,0.05)"/><circle cx="70" cy="60" r="3" fill="rgba(255,255,255,0.04)"/><circle cx="50" cy="90" r="2.5" fill="rgba(255,255,255,0.03)"/></svg>');background-size:100px}
.contact-hero h1{font-size:32px;font-weight:900;margin:0 0 8px}
.contact-hero p{font-size:15px;opacity:.85;max-width:600px;margin:0 auto}

.contact-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin:-40px auto 40px;max-width:1000px;position:relative;z-index:2;padding:0 20px}
.contact-card{background:#fff;border-radius:var(--r);padding:24px;text-align:center;box-shadow:var(--shadow-md);border:1px solid var(--border);transition:all .25s}
.contact-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);border-color:var(--primary)}
.contact-card-icon{font-size:32px;margin-bottom:10px}
.contact-card-title{font-size:14px;font-weight:700;color:var(--text);margin-bottom:6px}
.contact-card-val{font-size:13px;color:var(--primary);font-weight:600;direction:ltr}
.contact-card-desc{font-size:11px;color:var(--text-light);margin-top:4px}

.contact-main{max-width:1000px;margin:0 auto;padding:40px 20px}
.contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:32px}

.contact-form-wrap{background:#fff;border-radius:var(--r-lg);padding:32px;box-shadow:var(--shadow-md);border:1px solid var(--border)}
.contact-form-wrap h3{font-size:18px;font-weight:800;color:var(--text);margin:0 0 4px}
.contact-form-wrap .sub{font-size:13px;color:var(--text-light);margin:0 0 24px}

.cf-group{margin-bottom:18px}
.cf-group label{display:block;font-size:13px;font-weight:600;color:var(--text);margin-bottom:6px}
.cf-group input,.cf-group textarea,.cf-group select{width:100%;padding:12px 14px;border:1.5px solid var(--border);border-radius:var(--r);font-family:inherit;font-size:13px;color:var(--text);background:#fafafa;transition:all .2s;box-sizing:border-box}
.cf-group input:focus,.cf-group textarea:focus,.cf-group select:focus{outline:none;border-color:var(--primary);background:#fff;box-shadow:0 0 0 3px rgba(22,163,74,.1)}
.cf-group textarea{resize:vertical;min-height:140px;line-height:2}
.cf-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}

.cf-submit{width:100%;padding:14px;background:var(--primary);color:#fff;border:none;border-radius:var(--r);font-family:inherit;font-size:15px;font-weight:700;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px}
.cf-submit:hover{background:var(--primary-dark);transform:translateY(-2px);box-shadow:var(--shadow-md)}

.cf-msg{padding:14px;border-radius:var(--r);font-size:13px;font-weight:600;margin-top:14px;display:none}
.cf-msg-ok{background:#dcfce7;color:#166534;border:1px solid #86efac}
.cf-msg-err{background:#fef2f2;color:#991b1b;border:1px solid #fca5a5}

.contact-info-wrap h3{font-size:18px;font-weight:800;color:var(--text);margin:0 0 20px}

.ci-item{display:flex;align-items:start;gap:14px;padding:18px;background:#fff;border-radius:var(--r);box-shadow:var(--shadow);border:1px solid var(--border);margin-bottom:12px;transition:all .2s}
.ci-item:hover{border-color:var(--primary);box-shadow:var(--shadow-md)}
.ci-icon{width:48px;height:48px;background:var(--primary-pale);border-radius:var(--r);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0}
.ci-text h4{font-size:14px;font-weight:700;color:var(--text);margin:0 0 4px}
.ci-text p{font-size:13px;color:var(--text-light);margin:0;line-height:1.8}
.ci-text a{color:var(--primary);font-weight:600}

.contact-social{margin-top:20px;padding:24px;background:linear-gradient(135deg,var(--primary-pale),#e8f5e9);border-radius:var(--r-lg);border:1px solid #bbf7d0}
.contact-social h4{font-size:15px;font-weight:700;color:var(--text);margin:0 0 14px}
.social-links{display:flex;gap:10px;flex-wrap:wrap}
.social-link{display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#fff;border-radius:50px;font-size:13px;font-weight:600;color:var(--text);box-shadow:var(--shadow);border:1px solid var(--border);transition:all .2s;text-decoration:none}
.social-link:hover{transform:translateY(-2px);box-shadow:var(--shadow-md);border-color:var(--primary);color:var(--primary)}
.social-link span{font-size:18px}

.contact-faq{max-width:1000px;margin:0 auto;padding:0 20px 48px}
.contact-faq h3{text-align:center;font-size:20px;font-weight:800;margin-bottom:24px;color:var(--text)}
.faq-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.faq-item{background:#fff;padding:22px;border-radius:var(--r);box-shadow:var(--shadow);border:1px solid var(--border);transition:all .2s}
.faq-item:hover{border-color:var(--primary);box-shadow:var(--shadow-md)}
.faq-item h4{font-size:14px;font-weight:700;color:var(--text);margin:0 0 8px;display:flex;align-items:center;gap:8px}
.faq-item h4::before{content:'؟';display:flex;align-items:center;justify-content:center;width:28px;height:28px;background:var(--primary);color:#fff;border-radius:50%;font-size:14px;font-weight:800;flex-shrink:0}
.faq-item p{font-size:13px;color:var(--text-light);line-height:2;margin:0;padding-right:36px}

@media(max-width:768px){
  .contact-cards{grid-template-columns:repeat(2,1fr);margin-top:-30px}
  .contact-grid{grid-template-columns:1fr}
  .cf-row{grid-template-columns:1fr}
  .faq-grid{grid-template-columns:1fr}
  .contact-hero h1{font-size:24px}
  .social-links{justify-content:center}
}
@media(max-width:480px){
  .contact-cards{grid-template-columns:1fr}
}
</style>

<!-- HERO -->
<section class="contact-hero">
  <h1>تماس با ما</h1>
  <p>سوال، پیشنهاد یا انتقادی دارید؟ ما مشتاقانه منتظر شنیدن صدای شما هستیم</p>
</section>

<!-- CONTACT CARDS -->
<div class="contact-cards">
  <div class="contact-card">
    <div class="contact-card-icon">📱</div>
    <div class="contact-card-title">تلگرام</div>
    <div class="contact-card-val">@joojeiran</div>
    <div class="contact-card-desc">سریع‌ترین راه ارتباط</div>
  </div>
  <div class="contact-card">
    <div class="contact-card-icon">📧</div>
    <div class="contact-card-title">ایمیل</div>
    <div class="contact-card-val">Sfarnam73@gmail.com</div>
    <div class="contact-card-desc">پاسخ‌دهی در ۲۴ ساعت</div>
  </div>
  <div class="contact-card">
    <div class="contact-card-icon">📞</div>
    <div class="contact-card-title">تلفن</div>
    <div class="contact-card-val">۰۸۱۳۸۲۶۴۶۴۴</div>
    <div class="contact-card-desc">شنبه تا پنج‌شنبه</div>
  </div>
  <div class="contact-card">
    <div class="contact-card-icon">💬</div>
    <div class="contact-card-title">انجمن</div>
    <div class="contact-card-val">bazarjooje.ir/forum</div>
    <div class="contact-card-desc">پرسش و پاسخ عمومی</div>
  </div>
</div>

<!-- MAIN CONTACT -->
<div class="contact-main">
  <div class="contact-grid">

    <!-- FORM -->
    <div class="contact-form-wrap">
      <h3>فرم تماس</h3>
      <p class="sub">پیام خود را بنویسید — در اسرع وقت پاسخ خواهیم داد</p>

      <form id="bjContactForm" method="post" onsubmit="return bjSubmitContact(event)">
        <?php wp_nonce_field('bj_contact_form', 'bj_contact_nonce'); ?>
        <div class="cf-row">
          <div class="cf-group">
            <label>نام و نام خانوادگی</label>
            <input type="text" name="bj_name" required placeholder="مثال: سینا فرنام">
          </div>
          <div class="cf-group">
            <label>شماره تماس</label>
            <input type="tel" name="bj_phone" placeholder="۰۹۱۲۱۲۳۴۵۶۷" dir="ltr">
          </div>
        </div>
        <div class="cf-group">
          <label>ایمیل</label>
          <input type="email" name="bj_email" required placeholder="email@example.com" dir="ltr">
        </div>
        <div class="cf-group">
          <label>موضوع</label>
          <select name="bj_subject">
            <option value="general">سوال عمومی</option>
            <option value="prices">استعلام قیمت</option>
            <option value="cooperation">پیشنهاد همکاری</option>
            <option value="ads">تبلیغات</option>
            <option value="bug">گزارش مشکل</option>
            <option value="other">سایر</option>
          </select>
        </div>
        <div class="cf-group">
          <label>متن پیام</label>
          <textarea name="bj_message" required placeholder="پیام خود را اینجا بنویسید..."></textarea>
        </div>
        <button type="submit" class="cf-submit">📩 ارسال پیام</button>
        <div class="cf-msg cf-msg-ok" id="cfMsgOk">پیام شما با موفقیت ارسال شد! به زودی پاسخ خواهیم داد.</div>
        <div class="cf-msg cf-msg-err" id="cfMsgErr">خطا در ارسال پیام. لطفاً دوباره تلاش کنید.</div>
      </form>
    </div>

    <!-- INFO -->
    <div class="contact-info-wrap">
      <h3>راه‌های ارتباطی</h3>

      <div class="ci-item">
        <div class="ci-icon">📱</div>
        <div class="ci-text">
          <h4>کانال تلگرام</h4>
          <p>کانال رسمی بازار جوجه ایران<br><a href="https://t.me/joojeiran" target="_blank">t.me/joojeiran</a></p>
        </div>
      </div>

      <div class="ci-item">
        <div class="ci-icon">📧</div>
        <div class="ci-text">
          <h4>ایمیل پشتیبانی</h4>
          <p>Sfarnam73@gmail.com</p>
        </div>
      </div>

      <div class="ci-item">
        <div class="ci-icon">📞</div>
        <div class="ci-text">
          <h4>شماره تماس</h4>
          <p>۰۸۱۳۸۲۶۴۶۴۴<br>۰۹۱۸۸۱۱۱۵۰۴<br>۰۹۱۸۱۷۱۷۰۱۱</p>
        </div>
      </div>

      <div class="ci-item">
        <div class="ci-icon">📍</div>
        <div class="ci-text">
          <h4>آدرس</h4>
          <p><?php echo esc_html(get_option('bj_address', 'ایران — فعالیت به صورت آنلاین')); ?></p>
        </div>
      </div>

      <div class="ci-item">
        <div class="ci-icon">🕐</div>
        <div class="ci-text">
          <h4>ساعات پاسخ‌دهی</h4>
          <p>شنبه تا پنج‌شنبه: ۹ صبح تا ۶ عصر<br>جمعه: تعطیل</p>
        </div>
      </div>

      <!-- SOCIAL -->
      <div class="contact-social">
        <h4>ما را در شبکه‌های اجتماعی دنبال کنید</h4>
        <div class="social-links">
          <a href="https://t.me/joojeiran" class="social-link" target="_blank"><span>📱</span> تلگرام</a>
          <a href="https://bale.ir/bazarjooje" class="social-link" target="_blank"><span>🅱</span> بله</a>
          <a href="https://instagram.com/bazarjooje" class="social-link" target="_blank"><span>📸</span> اینستاگرام</a>
          <a href="https://aparat.com/bazarjooje" class="social-link" target="_blank"><span>🎬</span> آپارات</a>
          <a href="https://wa.me/989188111504" class="social-link" target="_blank"><span>💬</span> واتساپ</a>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- FAQ -->
<div class="contact-faq">
  <h3>سوالات متداول</h3>
  <div class="faq-grid">
    <div class="faq-item">
      <h4>قیمت‌ها هر روز بروزرسانی می‌شه؟</h4>
      <p>بله، قیمت جوجه یکروزه و سایر محصولات هر روز (به جز تعطیلات رسمی) بروزرسانی می‌شوند.</p>
    </div>
    <div class="faq-item">
      <h4>آیا می‌تونم تبلیغ بدم؟</h4>
      <p>بله، برای تبلیغات در سایت و کانال تلگرام از طریق فرم تماس با موضوع «تبلیغات» پیام بدید.</p>
    </div>
    <div class="faq-item">
      <h4>اپلیکیشن بازار جوجه کجاست؟</h4>
      <p>اپلیکیشن بازار جوجه ایران در کافه‌بازار موجوده. کافیه «بازار جوجه ایران» رو سرچ کنید.</p>
    </div>
    <div class="faq-item">
      <h4>چطور عضو انجمن بشم؟</h4>
      <p>کافیه در سایت ثبت‌نام کنید و وارد بخش انجمن بشید. عضویت کاملاً رایگانه.</p>
    </div>
  </div>
</div>

<script>
function bjSubmitContact(e) {
  e.preventDefault();
  var form = document.getElementById('bjContactForm');
  var data = new FormData(form);
  data.append('action', 'bj_contact_submit');

  var btn = form.querySelector('.cf-submit');
  btn.disabled = true;
  btn.textContent = 'در حال ارسال...';

  fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
    method: 'POST',
    body: data,
    credentials: 'same-origin'
  })
  .then(function(r) { return r.json(); })
  .then(function(res) {
    if (res.success) {
      document.getElementById('cfMsgOk').style.display = 'block';
      document.getElementById('cfMsgErr').style.display = 'none';
      form.reset();
    } else {
      document.getElementById('cfMsgErr').style.display = 'block';
      document.getElementById('cfMsgOk').style.display = 'none';
    }
  })
  .catch(function() {
    document.getElementById('cfMsgErr').style.display = 'block';
    document.getElementById('cfMsgOk').style.display = 'none';
  })
  .finally(function() {
    btn.disabled = false;
    btn.innerHTML = '📩 ارسال پیام';
  });

  return false;
}
</script>

<?php get_footer(); ?>
