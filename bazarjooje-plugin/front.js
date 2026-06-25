(function($){
    'use strict';

    var frontCharts = {};

    // =========================================
    // PRICE TABLE TABS
    // =========================================
    window.bjFrontTab = function(cat, btn) {
        $(btn).closest('.bj-front-tabs').find('.bj-ftab').removeClass('active');
        $(btn).addClass('active');

        var $rows = $(btn).closest('.bj-front-prices').find('.bj-frow');
        if (cat === 'all') {
            $rows.show();
        } else {
            $rows.hide();
            $rows.filter('[data-cat="' + cat + '"]').show();
        }
    };

    // =========================================
    // CHART — LOAD
    // =========================================
    window.bjFrontChart = function(containerId, productId, days) {
        days = days || 30;
        var $wrap = $('#' + containerId);
        if (!$wrap.length) return;

        $wrap.data('product', productId);
        $wrap.data('days', days);

        $.get(bjFront.ajaxurl, {
            action: 'bj_get_chart',
            product_id: productId,
            days: days
        }, function(res) {
            if (!res.success) return;
            renderFrontChart(containerId, res.data);
        });
    };

    window.bjFrontChartPeriod = function(containerId, days) {
        var $wrap = $('#' + containerId);
        var productId = $wrap.data('product') || 1;

        $wrap.find('.bj-pbtn').removeClass('active');
        $wrap.find('.bj-pbtn').each(function() {
            if ($(this).text().trim()) {
                var btnDays = 30;
                var txt = $(this).text().trim();
                if (txt.indexOf('هفت') !== -1) btnDays = 7;
                else if (txt.indexOf('سه') !== -1) btnDays = 90;
                if (btnDays === days) $(this).addClass('active');
            }
        });

        bjFrontChart(containerId, productId, days);
    };

    function renderFrontChart(containerId, data) {
        var canvasId = containerId + '_canvas';
        var ctx = document.getElementById(canvasId);
        if (!ctx) return;

        if (frontCharts[containerId]) frontCharts[containerId].destroy();

        var jalaliLabels = data.labels.map(function(d) {
            var parts = d.split('-');
            return parts[1] + '/' + parts[2];
        });

        var gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(21,128,61,0.3)');
        gradient.addColorStop(1, 'rgba(21,128,61,0.02)');

        frontCharts[containerId] = new Chart(ctx, {
            type: 'line',
            data: {
                labels: jalaliLabels,
                datasets: [{
                    label: 'قیمت (تومان)',
                    data: data.data,
                    borderColor: '#15803d',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#15803d',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        rtl: true,
                        backgroundColor: 'rgba(30,41,59,0.95)',
                        titleFont: { family: 'Vazirmatn, Tahoma', size: 13 },
                        bodyFont: { family: 'Vazirmatn, Tahoma', size: 14, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(ctx) {
                                return numberFormat(ctx.parsed.y) + ' تومان';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(v) { return numberFormat(v); },
                            font: { family: 'Vazirmatn, Tahoma', size: 12 }
                        },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    x: {
                        ticks: { font: { family: 'Vazirmatn, Tahoma', size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });

        renderChartStats(containerId, data.stats);
    }

    function renderChartStats(containerId, stats) {
        var $el = $('#' + containerId + '_stats');
        if (!$el.length || !stats) return;

        $el.html(
            '<div class="bj-stat-card"><div class="bj-stat-label">قیمت فعلی</div><div class="bj-stat-value">' + numberFormat(stats.current) + '</div></div>' +
            '<div class="bj-stat-card"><div class="bj-stat-label">بالاترین</div><div class="bj-stat-value" style="color:#16a34a">' + numberFormat(stats.high) + '</div></div>' +
            '<div class="bj-stat-card"><div class="bj-stat-label">پایین‌ترین</div><div class="bj-stat-value" style="color:#dc2626">' + numberFormat(stats.low) + '</div></div>' +
            '<div class="bj-stat-card"><div class="bj-stat-label">میانگین</div><div class="bj-stat-value" style="color:#f59e0b">' + numberFormat(stats.avg) + '</div></div>'
        );
    }

    // =========================================
    // CALCULATOR
    // =========================================
    window.bjCalculate = function() {
        var data = {
            action: 'bj_calculate_cost',
            chick_count: parseInt($('#bjCalcCount').val()) || 0,
            chick_price: parseInt($('#bjCalcChickPrice').val()) || 0,
            feed_per_bird: parseFloat($('#bjCalcFeedPerBird').val()) || 4.2,
            feed_price: parseInt($('#bjCalcFeedPrice').val()) || 0,
            mortality: parseFloat($('#bjCalcMortality').val()) || 5,
            avg_weight: parseFloat($('#bjCalcWeight').val()) || 2.5,
            other_costs: parseInt($('#bjCalcOther').val()) || 0
        };

        $.post(bjFront.ajaxurl, data, function(res) {
            if (!res.success) return;
            var r = res.data;

            $('#bjCalcCostPerKg').text(numberFormat(r.cost_per_kg));
            $('#bjCalcDetails').html(
                '<div class="bj-calc-detail-item"><span class="bj-calc-detail-label">جوجه‌های زنده‌مانده</span><span class="bj-calc-detail-val">' + numberFormat(r.surviving_birds) + ' قطعه</span></div>' +
                '<div class="bj-calc-detail-item"><span class="bj-calc-detail-label">هزینه جوجه</span><span class="bj-calc-detail-val">' + numberFormat(r.total_chick_cost) + ' ت</span></div>' +
                '<div class="bj-calc-detail-item"><span class="bj-calc-detail-label">هزینه خوراک</span><span class="bj-calc-detail-val">' + numberFormat(r.total_feed_cost) + ' ت</span></div>' +
                '<div class="bj-calc-detail-item"><span class="bj-calc-detail-label">سایر هزینه‌ها</span><span class="bj-calc-detail-val">' + numberFormat(r.total_other_cost) + ' ت</span></div>' +
                '<div class="bj-calc-detail-item"><span class="bj-calc-detail-label">هزینه کل</span><span class="bj-calc-detail-val">' + numberFormat(r.total_cost) + ' ت</span></div>' +
                '<div class="bj-calc-detail-item"><span class="bj-calc-detail-label">کل گوشت تولیدی</span><span class="bj-calc-detail-val">' + r.total_meat_kg + ' کیلو</span></div>'
            );
            $('#bjCalcResult').slideDown(300);
        });
    };

    // =========================================
    // NUMBER FORMAT HELPER
    // =========================================
    function numberFormat(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // =========================================
    // TICKER — DUPLICATE FOR SEAMLESS LOOP
    // =========================================
    $(document).ready(function() {
        $('.bj-ticker-track').each(function() {
            var $track = $(this);
            var $items = $track.children().clone();
            $track.append($items);
        });
    });

})(jQuery);
