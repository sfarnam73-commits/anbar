<?php
/**
 * Template Name: درباره ما
 */
get_header(); ?>

<style>
.about-hero{background:linear-gradient(135deg,var(--primary-deeper),var(--primary));padding:60px 0;text-align:center;color:#fff;position:relative;overflow:hidden}
.about-hero::before{content:'';position:absolute;inset:0;background:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.05)"/><circle cx="80" cy="40" r="3" fill="rgba(255,255,255,0.04)"/><circle cx="50" cy="80" r="2.5" fill="rgba(255,255,255,0.03)"/></svg>');background-size:100px}
.about-hero h1{font-size:32px;font-weight:900;margin:0 0 8px}
.about-hero p{font-size:15px;opacity:.85;max-width:600px;margin:0 auto}

.about-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin:-40px auto 40px;max-width:900px;position:relative;z-index:2;padding:0 20px}
.about-stat{background:#fff;border-radius:var(--r);padding:24px;text-align:center;box-shadow:var(--shadow-md);border:1px solid var(--border)}
.about-stat-val{font-size:28px;font-weight:900;color:var(--primary);display:block}
.about-stat-lbl{font-size:13px;color:var(--text-light);margin-top:4px}

.about-story{max-width:1000px;margin:0 auto;padding:40px 20px}
.about-founder{display:grid;grid-template-columns:300px 1fr;gap:40px;align-items:start;margin-bottom:48px}
.founder-img-wrap{position:relative}
.founder-img{width:100%;border-radius:var(--r-lg);box-shadow:var(--shadow-lg);aspect-ratio:1;object-fit:cover}
.founder-img-wrap::after{content:'';position:absolute;inset:-8px;border:3px solid var(--primary);border-radius:20px;z-index:-1;opacity:.3}
.founder-badge{position:absolute;bottom:-12px;right:20px;background:var(--primary);color:#fff;padding:8px 20px;border-radius:50px;font-size:12px;font-weight:700;box-shadow:var(--shadow)}
.founder-info h2{font-size:24px;font-weight:900;color:var(--text);margin:0 0 4px}
.founder-info .founder-role{font-size:14px;color:var(--primary);font-weight:600;margin-bottom:16px;display:block}
.founder-info p{font-size:14px;line-height:2.2;color:var(--text-light);margin-bottom:14px}
.founder-edu{display:flex;align-items:center;gap:10px;background:var(--primary-pale);padding:14px 18px;border-radius:var(--r);margin-top:16px;border:1px solid #bbf7d0}
.founder-edu-icon{font-size:28px}
.founder-edu-text{font-size:13px;font-weight:600;color:var(--primary-dark)}
.founder-edu-text small{display:block;font-weight:400;color:var(--text-light);font-size:12px}

.about-timeline{max-width:800px;margin:0 auto;padding:40px 20px}
.timeline-title{text-align:center;font-size:20px;font-weight:800;margin-bottom:32px;color:var(--text)}
.timeline{position:relative;padding-right:40px}
.timeline::before{content:'';position:absolute;right:14px;top:0;bottom:0;width:3px;background:linear-gradient(180deg,var(--primary),var(--primary-light));border-radius:2px}
.tl-item{position:relative;margin-bottom:32px;padding-right:30px}
.tl-item::before{content:'';position:absolute;right:-30px;top:6px;width:16px;height:16px;background:var(--primary);border:3px solid #fff;border-radius:50%;box-shadow:0 0 0 3px var(--primary-light);z-index:1}
.tl-year{display:inline-block;background:var(--primary);color:#fff;padding:3px 14px;border-radius:20px;font-size:13px;font-weight:700;margin-bottom:8px}
.tl-item h4{font-size:15px;font-weight:700;color:var(--text);margin:0 0 4px}
.tl-item p{font-size:13px;color:var(--text-light);line-height:1.9;margin:0}

.about-mission{background:linear-gradient(135deg,var(--primary-pale),#e8f5e9);padding:48px 0}
.mission-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:1000px;margin:0 auto;padding:0 20px}
.mission-card{background:#fff;padding:28px;border-radius:var(--r);box-shadow:var(--shadow);text-align:center;border:1px solid var(--border);transition:all .25s}
.mission-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-md)}
.mission-icon{font-size:36px;margin-bottom:12px}
.mission-card h4{font-size:15px;font-weight:700;color:var(--text);margin:0 0 8px}
.mission-card p{font-size:13px;color:var(--text-light);line-height:1.9;margin:0}

.about-services{max-width:1000px;margin:0 auto;padding:48px 20px}
.services-title{text-align:center;font-size:20px;font-weight:800;margin-bottom:28px}
.services-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}
.srv-card{display:flex;align-items:start;gap:16px;background:#fff;padding:22px;border-radius:var(--r);box-shadow:var(--shadow);border:1px solid var(--border);transition:all .2s}
.srv-card:hover{border-color:var(--primary);box-shadow:var(--shadow-md)}
.srv-icon{width:48px;height:48px;background:var(--primary-pale);border-radius:var(--r);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0}
.srv-card h4{font-size:14px;font-weight:700;color:var(--text);margin:0 0 4px}
.srv-card p{font-size:12px;color:var(--text-light);line-height:1.8;margin:0}

.about-cta{background:linear-gradient(135deg,var(--primary),var(--primary-dark));padding:48px 0;text-align:center;color:#fff}
.about-cta h3{font-size:22px;font-weight:800;margin:0 0 8px}
.about-cta p{font-size:14px;opacity:.85;margin:0 0 20px}
.cta-btns{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.cta-btn{padding:12px 28px;border-radius:50px;font-size:14px;font-weight:700;font-family:inherit;cursor:pointer;transition:all .2s;display:inline-flex;align-items:center;gap:8px;border:2px solid transparent}
.cta-btn-w{background:#fff;color:var(--primary)}
.cta-btn-w:hover{transform:translateY(-2px);box-shadow:var(--shadow-lg)}
.cta-btn-o{background:transparent;border-color:rgba(255,255,255,.4);color:#fff}
.cta-btn-o:hover{border-color:#fff;background:rgba(255,255,255,.1)}

@media(max-width:768px){
  .about-stats{grid-template-columns:repeat(2,1fr);margin-top:-30px}
  .about-founder{grid-template-columns:1fr;text-align:center}
  .founder-img-wrap{max-width:250px;margin:0 auto}
  .mission-grid{grid-template-columns:1fr}
  .services-grid{grid-template-columns:1fr}
  .timeline{padding-right:30px}
  .about-hero h1{font-size:24px}
}
</style>

<!-- HERO -->
<section class="about-hero">
  <h1>درباره بازار جوجه ایران</h1>
  <p>از سال ۱۳۹۲ در کنار مرغداران ایران — مرجع قیمت جوجه یکروزه و دانش صنعت طیور</p>
</section>

<!-- STATS -->
<div class="about-stats">
  <div class="about-stat">
    <span class="about-stat-val">+۱۳</span>
    <span class="about-stat-lbl">سال تجربه</span>
  </div>
  <div class="about-stat">
    <span class="about-stat-val">۱۳۹۲</span>
    <span class="about-stat-lbl">سال شروع فعالیت</span>
  </div>
  <div class="about-stat">
    <span class="about-stat-val">+۲۰k</span>
    <span class="about-stat-lbl">دنبال‌کننده تلگرام</span>
  </div>
  <div class="about-stat">
    <span class="about-stat-val">۳ نفر</span>
    <span class="about-stat-lbl">تیم فعلی</span>
  </div>
</div>

<!-- FOUNDER STORY -->
<div class="about-story">
  <div class="about-founder">
    <div class="founder-img-wrap">
      <img src="<?php echo esc_url(BJ_CHILD_URI . '/assets/img/sina-farnam.jpg'); ?>" alt="سینا فرنام" class="founder-img">
      <span class="founder-badge">بنیان‌گذار</span>
    </div>
    <div class="founder-info">
      <h2>سینا فرنام</h2>
      <span class="founder-role">بنیان‌گذار و مدیر بازار جوجه ایران</span>

      <p>
        سلام، من <strong>سینا فرنام</strong> هستم. وقتی ۲۲ ساله بودم وارد صنعت طیور شدم — خیلی جوان بودم ولی عشق و علاقه‌ام به این کار باعث شد که تمام زندگی حرفه‌ایم رو وقف این صنعت کنم.
      </p>
      <p>
        از سال <strong>۱۳۹۲</strong> تا الان بیش از ۱۳ سال تجربه در حوزه طیور دارم. در سال <strong>۱۴۰۰</strong> کانال تلگرامی «بازار جوجه ایران» رو راه‌اندازی کردم که به لطف شما، به یکی از <strong>کانال‌های مرجع در زمینه قیمت‌گذاری جوجه یکروزه</strong> تبدیل شد.
      </p>
      <p>
        الان در آستانه ۳۷ سالگی، تصمیم گرفتم با ساخت این سایت، خدمات بیشتر و بهتری به مرغداران عزیز ارائه بدم. از قیمت‌های دقیق روزانه گرفته تا مقالات علمی بروز دنیا، انجمن تخصصی و ابزارهای کاربردی.
      </p>

      <div class="founder-edu">
        <span class="founder-edu-icon">🎓</span>
        <div class="founder-edu-text">
          کارشناسی ارشد تغذیه طیور
          <small>دارای مقالات متعدد علمی در حوزه تغذیه و پرورش طیور</small>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- TIMELINE -->
<div class="about-timeline">
  <h3 class="timeline-title">مسیری که طی کردیم</h3>
  <div class="timeline">
    <div class="tl-item">
      <span class="tl-year">۱۳۹۲</span>
      <h4>ورود به صنعت طیور</h4>
      <p>در ۲۲ سالگی فعالیت حرفه‌ای در حوزه طیور و مرغداری آغاز شد</p>
    </div>
    <div class="tl-item">
      <span class="tl-year">۱۳۹۲–۱۴۰۰</span>
      <h4>کسب تجربه و تحصیلات تخصصی</h4>
      <p>اخذ مدرک کارشناسی ارشد تغذیه طیور، انتشار مقالات علمی و فعالیت مستمر در صنعت</p>
    </div>
    <div class="tl-item">
      <span class="tl-year">۱۴۰۰</span>
      <h4>تأسیس کانال بازار جوجه ایران</h4>
      <p>راه‌اندازی کانال تلگرامی @joojeiran — تبدیل شدن به یکی از کانال‌های مرجع قیمت جوجه یکروزه</p>
    </div>
    <div class="tl-item">
      <span class="tl-year">۱۴۰۴</span>
      <h4>انتشار اپلیکیشن در کافه‌بازار</h4>
      <p>اپلیکیشن بازار جوجه ایران برای دسترسی آسان‌تر مرغداران به قیمت‌ها منتشر شد</p>
    </div>
    <div class="tl-item">
      <span class="tl-year">۱۴۰۵</span>
      <h4>راه‌اندازی سایت bazarjooje.ir</h4>
      <p>سایت جامع با قیمت روزانه، نمودار، انجمن تخصصی، مقالات علمی و ابزارهای کاربردی</p>
    </div>
  </div>
</div>

<!-- MISSION -->
<section class="about-mission">
  <div style="text-align:center;margin-bottom:28px;padding:0 20px">
    <div class="section-title" style="justify-content:center"><span class="dot"></span> باور ما</div>
  </div>
  <div class="mission-grid">
    <div class="mission-card">
      <div class="mission-icon">🎯</div>
      <h4>دانش = صرفه تولید</h4>
      <p>هرچه دانش مرغدار بالاتر بره، صرفه تولید هم قطعاً بیشتر خواهد شد. ما اینجاییم تا این دانش رو در اختیار شما بذاریم.</p>
    </div>
    <div class="mission-card">
      <div class="mission-icon">🤝</div>
      <h4>در کنار شما</h4>
      <p>تولید در ایران همواره با چالش‌هایی مواجه بوده. ما می‌خوایم این چالش‌ها رو از سر مرغدار کمتر کنیم.</p>
    </div>
    <div class="mission-card">
      <div class="mission-icon">📈</div>
      <h4>رشد مستمر</h4>
      <p>با تیم ۳ نفره شروع کردیم و هر روز در حال رشد هستیم. سعی می‌کنیم انجمن و سایت رو روز‌به‌روز بهتر کنیم.</p>
    </div>
  </div>
</section>

<!-- SERVICES -->
<div class="about-services">
  <h3 class="services-title">خدمات ما</h3>
  <div class="services-grid">
    <div class="srv-card">
      <div class="srv-icon">💰</div>
      <div>
        <h4>قیمت‌های دقیق روزانه</h4>
        <p>قیمت جوجه یکروزه، مرغ زنده، تخم مرغ و نهاده‌های دامی — هر روز بروزرسانی می‌شه</p>
      </div>
    </div>
    <div class="srv-card">
      <div class="srv-icon">📊</div>
      <div>
        <h4>نمودار روند قیمت</h4>
        <p>نمودارهای هفتگی، ماهانه و سه‌ماهه برای تحلیل روند بازار و تصمیم‌گیری بهتر</p>
      </div>
    </div>
    <div class="srv-card">
      <div class="srv-icon">📝</div>
      <div>
        <h4>مقالات علمی بروز</h4>
        <p>ترجمه و تولید مقالات از آخرین تحقیقات دنیا در حوزه تغذیه و پرورش طیور</p>
      </div>
    </div>
    <div class="srv-card">
      <div class="srv-icon">💬</div>
      <div>
        <h4>انجمن تخصصی مرغداران</h4>
        <p>محلی برای سوال، تبادل تجربه و مشاوره بین مرغداران و متخصصان صنعت طیور</p>
      </div>
    </div>
    <div class="srv-card">
      <div class="srv-icon">🔢</div>
      <div>
        <h4>ماشین‌حساب هزینه مرغداری</h4>
        <p>محاسبه دقیق هزینه تمام‌شده هر کیلو مرغ زنده با وارد کردن هزینه‌ها</p>
      </div>
    </div>
    <div class="srv-card">
      <div class="srv-icon">📱</div>
      <div>
        <h4>اپلیکیشن موبایل</h4>
        <p>دسترسی آسان به قیمت‌ها و اخبار از طریق اپلیکیشن بازار جوجه ایران در کافه‌بازار</p>
      </div>
    </div>
  </div>
</div>

<!-- CTA -->
<section class="about-cta">
  <h3>همراه ما باشید</h3>
  <p>ما با تمام وجود اینجاییم تا به شما کمک کنیم</p>
  <div class="cta-btns">
    <a href="https://t.me/joojeiran" class="cta-btn cta-btn-w">📱 کانال تلگرام</a>
    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="cta-btn cta-btn-o">📧 تماس با ما</a>
    <a href="<?php echo esc_url(home_url('/forum')); ?>" class="cta-btn cta-btn-o">💬 ورود به انجمن</a>
  </div>
</section>

<?php get_footer(); ?>
