<?php
/**
 * BazarJooje — Province Price Comparison
 */

if (!defined('ABSPATH')) exit;

class BJ_Province_Prices {

    private static $instance = null;
    private $table;
    private $table_products;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        global $wpdb;
        $this->table = $wpdb->prefix . 'bj_province_prices';
        $this->table_products = $wpdb->prefix . 'bj_products';

        register_activation_hook(BJ_PLUGIN_FILE, [$this, 'activate']);
        add_action('admin_menu', [$this, 'admin_menu']);

        add_action('wp_ajax_bj_save_province_prices', [$this, 'ajax_save_province_prices']);
        add_action('wp_ajax_bj_get_province_compare', [$this, 'ajax_get_province_compare']);
        add_action('wp_ajax_nopriv_bj_get_province_compare', [$this, 'ajax_get_province_compare']);

        add_shortcode('bazarjooje_province_compare', [$this, 'shortcode_compare']);
    }

    public function activate() {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table} (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            province varchar(50) NOT NULL,
            product_id bigint(20) UNSIGNED NOT NULL,
            price bigint(20) NOT NULL,
            price_date date NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY province_product_date (province, product_id, price_date),
            KEY product_date (product_id, price_date)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public function get_provinces() {
        return [
            'آذربایجان شرقی', 'آذربایجان غربی', 'اردبیل', 'اصفهان', 'البرز', 'ایلام',
            'بوشهر', 'تهران', 'چهارمحال و بختیاری', 'خراسان جنوبی', 'خراسان رضوی',
            'خراسان شمالی', 'خوزستان', 'زنجان', 'سمنان', 'سیستان و بلوچستان', 'فارس',
            'قزوین', 'قم', 'کردستان', 'کرمان', 'کرمانشاه', 'کهگیلویه و بویراحمد',
            'گلستان', 'گیلان', 'لرستان', 'مازندران', 'مرکزی', 'هرمزگان', 'همدان', 'یزد',
        ];
    }

    private function get_products() {
        global $wpdb;
        return $wpdb->get_results("SELECT id, name, category, unit FROM {$this->table_products} WHERE is_active = 1 ORDER BY category, sort_order");
    }

    // =========================================
    // ADMIN MENU
    // =========================================
    public function admin_menu() {
        add_submenu_page('bazarjooje', 'مقایسه قیمت استان‌ها', '🗺️ قیمت استانی', 'manage_options', 'bazarjooje-province', [$this, 'page_province']);
    }

    public function page_province() {
        $products = $this->get_products();
        $provinces = $this->get_provinces();
        $today = current_time('Y-m-d');
        $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : ($products ? $products[0]->id : 0);
        $date = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : $today;

        global $wpdb;
        $existing = $wpdb->get_results($wpdb->prepare(
            "SELECT province, price FROM {$this->table} WHERE product_id = %d AND price_date = %s",
            $product_id, $date
        ), OBJECT_K);
        ?>
        <div class="wrap bj-wrap">
            <div class="bj-header">
                <h1>🗺️ مقایسه قیمت استان‌ها</h1>
                <p class="bj-subtitle">ثبت قیمت هر محصول به تفکیک استان</p>
            </div>

            <div class="bj-date-selector">
                <label>📦 محصول:</label>
                <select id="bjProvProduct" onchange="bjProvLoad()">
                    <?php foreach ($products as $p): ?>
                        <option value="<?php echo esc_attr($p->id); ?>" <?php selected($p->id, $product_id); ?>><?php echo esc_html($p->name); ?></option>
                    <?php endforeach; ?>
                </select>
                <label>📅 تاریخ:</label>
                <input type="date" id="bjProvDate" value="<?php echo esc_attr($date); ?>" max="<?php echo esc_attr($today); ?>">
                <button class="button button-primary" onclick="bjProvLoad()">بارگذاری</button>
            </div>

            <table class="bj-price-form">
                <thead>
                    <tr><th>استان</th><th>قیمت (تومان)</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($provinces as $prov):
                        $val = isset($existing[$prov]) ? $existing[$prov]->price : '';
                    ?>
                    <tr>
                        <td class="bj-prod-name"><?php echo esc_html($prov); ?></td>
                        <td>
                            <input type="number" class="bj-price-input bj-prov-input" data-province="<?php echo esc_attr($prov); ?>" value="<?php echo esc_attr($val); ?>" placeholder="قیمت...">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="bj-actions">
                <button class="button button-primary button-hero" onclick="bjProvSaveAll()">💾 ذخیره قیمت‌های استانی</button>
                <span class="bj-save-status" id="bjProvSaveStatus"></span>
            </div>
        </div>
        <script>
        function bjProvLoad() {
            var p = document.getElementById('bjProvProduct').value;
            var d = document.getElementById('bjProvDate').value;
            window.location.href = window.location.pathname + '?page=bazarjooje-province&product_id=' + p + '&date=' + d;
        }
        function bjProvSaveAll() {
            var product_id = document.getElementById('bjProvProduct').value;
            var date = document.getElementById('bjProvDate').value;
            var prices = [];
            document.querySelectorAll('.bj-prov-input').forEach(function(el){
                var v = parseInt(el.value);
                if (v) prices.push({province: el.dataset.province, price: v});
            });
            var status = document.getElementById('bjProvSaveStatus');
            status.innerHTML = '<span style="color:#64748b">⏳ در حال ذخیره...</span>';
            jQuery.post(bjAdmin.ajaxurl, {
                action: 'bj_save_province_prices',
                nonce: bjAdmin.nonce,
                product_id: product_id,
                date: date,
                prices: JSON.stringify(prices)
            }, function(res){
                if (res.success) {
                    status.innerHTML = '<span style="color:#16a34a">✅ ذخیره شد (' + res.data.count + ' استان)</span>';
                } else {
                    status.innerHTML = '<span style="color:#dc2626">⚠️ خطا در ذخیره</span>';
                }
            });
        }
        </script>
        <?php
    }

    // =========================================
    // AJAX
    // =========================================
    public function ajax_save_province_prices() {
        check_ajax_referer('bj_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error();

        global $wpdb;
        $product_id = intval($_POST['product_id']);
        $date = sanitize_text_field($_POST['date']);
        $prices = json_decode(stripslashes($_POST['prices']), true);

        if (!$product_id || !$date || !is_array($prices)) wp_send_json_error();

        $count = 0;
        foreach ($prices as $p) {
            $province = sanitize_text_field($p['province']);
            $price = intval($p['price']);
            if (!$province || !$price) continue;

            $wpdb->query($wpdb->prepare(
                "INSERT INTO {$this->table} (province, product_id, price, price_date) VALUES (%s, %d, %d, %s)
                 ON DUPLICATE KEY UPDATE price = VALUES(price)",
                $province, $product_id, $price, $date
            ));
            $count++;
        }

        wp_send_json_success(['count' => $count]);
    }

    public function ajax_get_province_compare() {
        check_ajax_referer('bj_front_nonce', 'nonce');

        global $wpdb;
        $product_id = intval($_GET['product_id']);
        if (!$product_id) wp_send_json_error();

        $latest_date = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(price_date) FROM {$this->table} WHERE product_id = %d",
            $product_id
        ));

        if (!$latest_date) wp_send_json_success(['rows' => [], 'date' => null]);

        $rows = $wpdb->get_results($wpdb->prepare(
            "SELECT province, price FROM {$this->table} WHERE product_id = %d AND price_date = %s ORDER BY price DESC",
            $product_id, $latest_date
        ));

        wp_send_json_success(['rows' => $rows, 'date' => $latest_date]);
    }

    // =========================================
    // SHORTCODE
    // =========================================
    public function shortcode_compare($atts) {
        $products = $this->get_products();
        if (!$products) return '';

        ob_start();
        ?>
        <div class="bj-province-compare">
            <div class="bj-chart-controls">
                <select id="bjProvCompareProduct" class="bj-chart-select" onchange="bjLoadProvinceCompare()">
                    <?php foreach ($products as $p): ?>
                        <option value="<?php echo esc_attr($p->id); ?>"><?php echo esc_html($p->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="bj-prov-update" id="bjProvCompareDate"></div>
            <div id="bjProvCompareList" class="bj-prov-list">
                <div class="bj-prov-empty">⏳ در حال بارگذاری...</div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

BJ_Province_Prices::instance();
