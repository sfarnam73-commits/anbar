(function($){
    'use strict';

    // =========================================
    // MOBILE NAV TOGGLE
    // =========================================
    $(document).on('click', '.hamburger', function() {
        $('#bjNavList').toggleClass('open');
    });
    $(document).on('click', '.nav-item > a', function() {
        if (window.innerWidth <= 768) {
            $('#bjNavList').removeClass('open');
        }
    });

    // =========================================
    // PRICE TABLE TAB SWITCHING
    // =========================================
    window.bjSwitchPriceTab = function(cat, btn) {
        $(btn).closest('.price-tabs').find('.p-tab').removeClass('active');
        $(btn).addClass('active');

        var $rows = $('#priceBody tr');
        if (cat === 'all') {
            $rows.show();
        } else {
            $rows.hide();
            $rows.filter('[data-cat="' + cat + '"]').show();
        }
    };

    // =========================================
    // LIVE CLOCK
    // =========================================
    function updateClock() {
        var now = new Date();
        var h = now.getHours().toString().padStart(2, '0');
        var m = now.getMinutes().toString().padStart(2, '0');
        var time = toPersian(h) + ':' + toPersian(m);
        var $el = $('#liveTime');
        if ($el.length) $el.text(time);
    }
    setInterval(updateClock, 60000);

    // =========================================
    // PRICE CHART
    // =========================================
    var priceChartInstance = null;

    window.bjSwitchChart = function(chartKey, btn) {
        $(btn).closest('.chart-tabs').find('.ch-tab').removeClass('active');
        $(btn).addClass('active');

        var productMap = {
            'chick_chart': 1,
            'live_chart': 9,
            'egg_chart': 15,
            'feed_chart': 20
        };
        var colorMap = {
            'chick_chart': '#16a34a',
            'live_chart': '#dc2626',
            'egg_chart': '#f59e0b',
            'feed_chart': '#8b5cf6'
        };

        var pid = productMap[chartKey] || 1;
        var color = colorMap[chartKey] || '#16a34a';
        var days = parseInt($('.cp-btn.active').data('days')) || 30;

        loadChart(pid, days, color);
    };

    window.bjChangePeriod = function(days, btn) {
        $(btn).closest('.chart-period').find('.cp-btn').removeClass('active');
        $(btn).addClass('active');

        var activeChart = $('.ch-tab.active').data('chart') || 'chick_chart';
        var productMap = {
            'chick_chart': 1,
            'live_chart': 9,
            'egg_chart': 15,
            'feed_chart': 20
        };
        var colorMap = {
            'chick_chart': '#16a34a',
            'live_chart': '#dc2626',
            'egg_chart': '#f59e0b',
            'feed_chart': '#8b5cf6'
        };

        loadChart(productMap[activeChart] || 1, days, colorMap[activeChart] || '#16a34a');
    };

    function loadChart(productId, days, color) {
        $.get(bjSite.ajaxurl, {
            action: 'bj_get_chart',
            product_id: productId,
            days: days
        }, function(res) {
            if (!res.success) return;
            renderChart(res.data, color);
        });
    }

    function renderChart(data, color) {
        var ctx = document.getElementById('priceChart');
        if (!ctx) return;

        if (priceChartInstance) priceChartInstance.destroy();

        var jalaliLabels = data.labels.map(function(d) {
            var parts = d.split('-');
            return toPersian(parts[1] + '/' + parts[2]);
        });

        var gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, color + '40');
        gradient.addColorStop(1, color + '05');

        priceChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: jalaliLabels,
                datasets: [{
                    label: 'قیمت (تومان)',
                    data: data.data,
                    borderColor: color,
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: color,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { family: 'Vazirmatn', size: 12 },
                        bodyFont: { family: 'Vazirmatn', size: 13, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 8,
                        rtl: true,
                        callbacks: {
                            label: function(ctx) {
                                return toPersian(numberFormat(ctx.parsed.y)) + ' تومان';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Vazirmatn', size: 11 }, color: '#94a3b8' }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { family: 'Vazirmatn', size: 11 }, color: '#94a3b8',
                            callback: function(v) { return toPersian(numberFormat(v)); }
                        }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });

        if (data.stats) {
            $('#chartSummary').html(
                '<div class="cs-item"><div class="cs-label">قیمت فعلی</div><div class="cs-val">' + toPersian(numberFormat(data.stats.current)) + '</div></div>' +
                '<div class="cs-item"><div class="cs-label">بالاترین</div><div class="cs-val up">' + toPersian(numberFormat(data.stats.high)) + '</div></div>' +
                '<div class="cs-item"><div class="cs-label">پایین‌ترین</div><div class="cs-val down">' + toPersian(numberFormat(data.stats.low)) + '</div></div>' +
                '<div class="cs-item"><div class="cs-label">میانگین</div><div class="cs-val">' + toPersian(numberFormat(data.stats.avg)) + '</div></div>'
            );
        }
    }

    // =========================================
    // LOGIN MODAL
    // =========================================
    window.bjOpenModal = function() {
        $('#loginModal').addClass('open');
    };
    window.bjCloseModal = function() {
        $('#loginModal').removeClass('open');
    };
    $(document).on('click', '#loginModal', function(e) {
        if (e.target === this) bjCloseModal();
    });

    // =========================================
    // BACK TO TOP
    // =========================================
    $(window).on('scroll', function() {
        $('#btt').toggleClass('show', $(window).scrollTop() > 400);
    });
    $(document).on('click', '#btt', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // =========================================
    // DUPLICATE TICKER ITEMS FOR SEAMLESS LOOP
    // =========================================
    $(document).ready(function() {
        $('.ticker-track, .ps-track').each(function() {
            var $track = $(this);
            $track.append($track.children().clone());
        });

        updateClock();

        if ($('#priceChart').length) {
            loadChart(1, 30, '#16a34a');
        }
    });

    // =========================================
    // HELPERS
    // =========================================
    function numberFormat(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function toPersian(str) {
        var persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        return str.toString().replace(/[0-9]/g, function(d) { return persian[parseInt(d)]; });
    }

})(jQuery);
