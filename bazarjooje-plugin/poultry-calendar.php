<?php
/**
 * BazarJooje — Poultry Farmer Calendar (Vaccination & Rearing Schedule)
 */

if (!defined('ABSPATH')) exit;

class BJ_Poultry_Calendar {

    private static $instance = null;
    private $table;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'bj_calendar_events';

        register_activation_hook(BJ_PLUGIN_FILE, [$this, 'activate']);
        add_action('admin_menu', [$this, 'admin_menu']);

        add_action('wp_ajax_bj_save_calendar_event', [$this, 'ajax_save_event']);
        add_action('wp_ajax_bj_delete_calendar_event', [$this, 'ajax_delete_event']);

        add_shortcode('bazarjooje_calendar', [$this, 'shortcode_calendar']);
    }

    public function activate() {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table} (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            cycle varchar(20) NOT NULL DEFAULT 'broiler',
            day_number int(11) NOT NULL DEFAULT 0,
            title varchar(255) NOT NULL,
            description text,
            type varchar(30) NOT NULL DEFAULT 'management',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY cycle_day (cycle, day_number)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        $this->seed_events();
    }

    public function get_types() {
        return [
            'vaccination' => ['label' => 'واکسیناسیون', 'icon' => '💉', 'color' => '#dc2626'],
            'feeding'     => ['label' => 'تغذیه', 'icon' => '🌾', 'color' => '#f59e0b'],
            'management'  => ['label' => 'مدیریتی', 'icon' => '🛠️', 'color' => '#2563eb'],
            'health'      => ['label' => 'بهداشتی', 'icon' => '🩺', 'color' => '#16a34a'],
        ];
    }

    private function seed_events() {
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$this->table} WHERE cycle = 'broiler'");
        if ($count > 0) return;

        $events = [
            [1, 'ورود جوجه به سالن', 'دمای سالن ۳۲-۳۴ درجه سانتی‌گراد، آب و دان در دسترس همه جوجه‌ها، بازدید از کیفیت جوجه (وزن، فعالیت، ناف).', 'management'],
            [1, 'واکسن مارک (در صورت عدم تزریق در جوجه‌کشی)', 'معمولاً در جوجه‌کشی انجام می‌شود؛ بررسی کارت واکسیناسیون جوجه‌کشی الزامی است.', 'vaccination'],
            [3, 'کنترل وزن و یکنواختی جوجه', 'نمونه‌گیری وزن از چند نقطه سالن و مقایسه با استاندارد سویه.', 'health'],
            [5, 'واکسن گامبورو (نوبت اول)', 'به‌صورت قطره چشمی یا در آب آشامیدنی، بسته به برنامه دامپزشک منطقه.', 'vaccination'],
            [7, 'واکسن نیوکاسل + برونشیت عفونی', 'قطره چشمی یا اسپری، کاهش دمای سالن به ۲۹-۳۱ درجه.', 'vaccination'],
            [10, 'تعویض دان آغازین به دان رشد', 'بررسی مصرف دان و آب، کنترل تهویه سالن.', 'feeding'],
            [14, 'واکسن گامبورو (نوبت دوم - یادآور)', 'در مناطق با فشار بیماری بالا توصیه می‌شود.', 'vaccination'],
            [18, 'کنترل تراکم و تهویه سالن', 'بازچینی جوجه‌ها برای جلوگیری از تراکم بیش از حد.', 'management'],
            [21, 'واکسن یادآور نیوکاسل (در صورت نیاز منطقه‌ای)', 'بر اساس نظر دامپزشک و وضعیت اپیدمیولوژیک منطقه.', 'vaccination'],
            [24, 'تعویض دان رشد به دان پایانی', 'کاهش تدریجی دمای سالن متناسب با سن گله.', 'feeding'],
            [28, 'کنترل ضریب تبدیل غذایی (FCR)', 'محاسبه میزان دان مصرفی نسبت به افزایش وزن گله.', 'health'],
            [35, 'بازدید نهایی دامپزشکی پیش از کشتار', 'بررسی سلامت عمومی گله و عدم وجود علائم بیماری.', 'health'],
            [38, 'قطع آنتی‌بیوتیک و افزودنی‌های دارویی', 'رعایت دوره حذف دارویی (withdrawal period) پیش از کشتار.', 'feeding'],
            [42, 'وزن‌کشی نهایی و آماده‌سازی برای کشتار', 'هماهنگی با کشتارگاه و قطع دان و آب طبق برنامه استاندارد قبل از حمل.', 'management'],
        ];

        foreach ($events as $e) {
            $wpdb->insert($this->table, [
                'cycle' => 'broiler',
                'day_number' => $e[0],
                'title' => $e[1],
                'description' => $e[2],
                'type' => $e[3],
            ]);
        }
    }

    private function get_events($cycle = 'broiler') {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->table} WHERE cycle = %s ORDER BY day_number ASC, id ASC",
            $cycle
        ));
    }

    // =========================================
    // ADMIN MENU
    // =========================================
    public function admin_menu() {
        add_submenu_page('bazarjooje', 'تقویم مرغدار', '📅 تقویم مرغدار', 'manage_options', 'bazarjooje-calendar', [$this, 'page_calendar']);
    }

    public function page_calendar() {
        $types = $this->get_types();
        $events = $this->get_events();
        ?>
        <div class="wrap bj-wrap">
            <div class="bj-header">
                <h1>📅 تقویم مرغدار</h1>
                <p class="bj-subtitle">برنامه واکسیناسیون و مدیریت دوره پرورش جوجه گوشتی (۴۲ روزه)</p>
            </div>

            <div class="bj-add-product">
                <h3>➕ افزودن رویداد جدید</h3>
                <div class="bj-form-row">
                    <input type="number" id="bjEvDay" placeholder="روز (مثلاً ۷)" min="0" style="width:110px">
                    <input type="text" id="bjEvTitle" placeholder="عنوان رویداد" style="flex:1;min-width:200px">
                    <select id="bjEvType">
                        <?php foreach ($types as $k => $t): ?>
                            <option value="<?php echo esc_attr($k); ?>"><?php echo esc_html($t['icon'] . ' ' . $t['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="bj-form-row" style="margin-top:10px">
                    <textarea id="bjEvDesc" placeholder="توضیحات..." style="width:100%;min-height:60px;padding:8px 12px;border:2px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:13px"></textarea>
                </div>
                <div class="bj-form-row" style="margin-top:10px">
                    <button class="button button-primary" onclick="bjAddCalEvent()">➕ افزودن</button>
                </div>
            </div>

            <table class="bj-price-form">
                <thead>
                    <tr><th>روز</th><th>عنوان</th><th>نوع</th><th>توضیحات</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $ev): $t = $types[$ev->type] ?? $types['management']; ?>
                    <tr data-id="<?php echo esc_attr($ev->id); ?>">
                        <td class="bj-prod-name">روز <?php echo esc_html($ev->day_number); ?></td>
                        <td><?php echo esc_html($ev->title); ?></td>
                        <td><span style="color:<?php echo esc_attr($t['color']); ?>"><?php echo esc_html($t['icon'] . ' ' . $t['label']); ?></span></td>
                        <td style="max-width:320px;white-space:normal;font-size:13px;color:#64748b"><?php echo esc_html($ev->description); ?></td>
                        <td><button class="button button-link-delete" onclick="bjDeleteCalEvent(<?php echo esc_attr($ev->id); ?>, this)">🗑️ حذف</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <script>
        function bjAddCalEvent() {
            var day = document.getElementById('bjEvDay').value;
            var title = document.getElementById('bjEvTitle').value;
            var type = document.getElementById('bjEvType').value;
            var desc = document.getElementById('bjEvDesc').value;
            if (!day || !title) { alert('روز و عنوان الزامی است'); return; }
            jQuery.post(bjAdmin.ajaxurl, {
                action: 'bj_save_calendar_event',
                nonce: bjAdmin.nonce,
                day_number: day, title: title, type: type, description: desc
            }, function(res){
                if (res.success) location.reload();
                else alert('خطا در ذخیره');
            });
        }
        function bjDeleteCalEvent(id, btn) {
            if (!confirm('حذف این رویداد؟')) return;
            jQuery.post(bjAdmin.ajaxurl, {
                action: 'bj_delete_calendar_event',
                nonce: bjAdmin.nonce,
                id: id
            }, function(res){
                if (res.success) jQuery(btn).closest('tr').remove();
            });
        }
        </script>
        <?php
    }

    // =========================================
    // AJAX
    // =========================================
    public function ajax_save_event() {
        check_ajax_referer('bj_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error();

        global $wpdb;
        $wpdb->insert($this->table, [
            'cycle' => 'broiler',
            'day_number' => intval($_POST['day_number']),
            'title' => sanitize_text_field($_POST['title']),
            'description' => sanitize_textarea_field($_POST['description']),
            'type' => sanitize_text_field($_POST['type']),
        ]);

        wp_send_json_success();
    }

    public function ajax_delete_event() {
        check_ajax_referer('bj_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error();

        global $wpdb;
        $wpdb->delete($this->table, ['id' => intval($_POST['id'])]);

        wp_send_json_success();
    }

    // =========================================
    // SHORTCODE
    // =========================================
    public function shortcode_calendar($atts) {
        $types = $this->get_types();
        $events = $this->get_events();
        if (!$events) return '';

        $events_json = wp_json_encode(array_map(function($e) {
            return [
                'day' => (int) $e->day_number,
                'title' => $e->title,
                'desc' => $e->description,
                'type' => $e->type,
            ];
        }, $events));

        ob_start();
        ?>
        <div class="bj-calendar">
            <div class="bj-calc-field" style="max-width:320px;margin:0 auto 24px">
                <label>📅 تاریخ ورود جوجه به سالن</label>
                <input type="date" id="bjCalArrival" onchange="bjRenderCalendar()">
            </div>

            <div class="bj-cal-legend">
                <?php foreach ($types as $k => $t): ?>
                    <span class="bj-cal-legend-item"><span class="bj-cal-dot" style="background:<?php echo esc_attr($t['color']); ?>"></span><?php echo esc_html($t['icon'] . ' ' . $t['label']); ?></span>
                <?php endforeach; ?>
            </div>

            <div id="bjCalToday" class="bj-cal-today"></div>
            <div id="bjCalTimeline" class="bj-cal-timeline"></div>
        </div>
        <script>
        (function(){
            var bjCalEvents = <?php echo $events_json; ?>;
            var bjCalTypes = <?php echo wp_json_encode($types); ?>;

            window.bjRenderCalendar = function() {
                var arrivalVal = document.getElementById('bjCalArrival').value;
                var $timeline = jQuery('#bjCalTimeline');
                var $today = jQuery('#bjCalToday');
                var currentDay = null;

                if (arrivalVal) {
                    var arrival = new Date(arrivalVal);
                    var now = new Date();
                    arrival.setHours(0,0,0,0);
                    now.setHours(0,0,0,0);
                    currentDay = Math.floor((now - arrival) / 86400000) + 1;
                    if (currentDay < 1) {
                        $today.html('🐣 جوجه‌ها هنوز وارد سالن نشده‌اند.');
                    } else if (currentDay > 42) {
                        $today.html('✅ دوره پرورش این گله به پایان رسیده است (روز ' + currentDay + ').');
                    } else {
                        $today.html('📍 امروز <strong>روز ' + currentDay + '</strong> دوره پرورش است.');
                    }
                } else {
                    $today.html('');
                }

                var html = '';
                bjCalEvents.forEach(function(ev) {
                    var t = bjCalTypes[ev.type] || bjCalTypes.management;
                    var cls = 'bj-cal-future';
                    if (currentDay !== null) {
                        if (ev.day < currentDay) cls = 'bj-cal-past';
                        else if (ev.day === currentDay) cls = 'bj-cal-now';
                    }
                    html += '<div class="bj-cal-item ' + cls + '">' +
                        '<div class="bj-cal-day" style="--cal-color:' + t.color + '">روز ' + ev.day + '</div>' +
                        '<div class="bj-cal-body">' +
                            '<div class="bj-cal-title">' + t.icon + ' ' + ev.title + '</div>' +
                            (ev.desc ? '<div class="bj-cal-desc">' + ev.desc + '</div>' : '') +
                        '</div>' +
                    '</div>';
                });
                $timeline.html(html);
            };

            jQuery(document).ready(function(){ bjRenderCalendar(); });
        })();
        </script>
        <?php
        return ob_get_clean();
    }
}

BJ_Poultry_Calendar::instance();
