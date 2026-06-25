(function($){
    'use strict';

    var adminChart = null;

    // =========================================
    // CATEGORY TAB SWITCHING
    // =========================================
    window.bjSwitchCat = function(cat, btn) {
        $('.bj-tab').removeClass('active');
        $(btn).addClass('active');

        if (cat === 'all') {
            $('.bj-cat-section').show();
        } else {
            $('.bj-cat-section').hide();
            $('#bj-cat-' + cat).show();
        }
    };

    // =========================================
    // LOAD PRICES FOR DATE
    // =========================================
    window.bjLoadPrices = function() {
        var date = $('#bjDate').val();
        if (!date) return;
        window.location.href = window.location.pathname + '?page=bazarjooje&date=' + date;
    };

    // =========================================
    // LIVE CHANGE CALCULATION
    // =========================================
    $(document).on('input', '.bj-price-input', function() {
        var $input = $(this);
        var val = parseInt($input.val()) || 0;
        var yesterday = parseInt($input.data('yesterday')) || 0;
        var $cell = $input.closest('tr').find('.bj-change');

        if (val && yesterday) {
            var diff = val - yesterday;
            if (diff > 0) {
                $cell.html('<span class="bj-up">▲ ' + numberFormat(diff) + '</span>');
            } else if (diff < 0) {
                $cell.html('<span class="bj-down">▼ ' + numberFormat(Math.abs(diff)) + '</span>');
            } else {
                $cell.html('<span class="bj-eq">— ثابت</span>');
            }
        } else {
            $cell.html('');
        }
    });

    // =========================================
    // BULK SAVE ALL PRICES
    // =========================================
    window.bjSaveAll = function() {
        var date = $('#bjDate').val();
        var prices = [];

        $('.bj-price-input').each(function() {
            var val = parseInt($(this).val());
            if (val) {
                prices.push({
                    product_id: parseInt($(this).data('product')),
                    price: val
                });
            }
        });

        if (prices.length === 0) {
            $('#bjSaveStatus').html('<span style="color:#dc2626">⚠️ هیچ قیمتی وارد نشده!</span>');
            return;
        }

        $('#bjSaveStatus').html('<span style="color:#64748b">⏳ در حال ذخیره...</span>');

        $.post(bjAdmin.ajaxurl, {
            action: 'bj_bulk_save',
            nonce: bjAdmin.nonce,
            date: date,
            prices: JSON.stringify(prices)
        }, function(res) {
            if (res.success) {
                $('#bjSaveStatus').html('<span style="color:#16a34a">✅ ' + res.data + '</span>');
                setTimeout(function() { $('#bjSaveStatus').html(''); }, 4000);
            } else {
                $('#bjSaveStatus').html('<span style="color:#dc2626">❌ ' + (res.data || 'خطا') + '</span>');
            }
        }).fail(function() {
            $('#bjSaveStatus').html('<span style="color:#dc2626">❌ خطا در ارتباط</span>');
        });
    };

    // =========================================
    // QUICK CHART
    // =========================================
    window.bjLoadQuickChart = function() {
        var productId = $('#bjChartProduct').val();
        if (!productId) return;

        $.get(bjAdmin.ajaxurl, {
            action: 'bj_get_chart',
            product_id: productId,
            days: 30
        }, function(res) {
            if (!res.success) return;
            renderAdminChart(res.data);
        });
    };

    function renderAdminChart(data) {
        var ctx = document.getElementById('bjAdminChart');
        if (!ctx) return;

        if (adminChart) adminChart.destroy();

        var jalaliLabels = data.labels.map(function(d) {
            var parts = d.split('-');
            return parts[1] + '/' + parts[2];
        });

        adminChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: jalaliLabels,
                datasets: [{
                    label: 'قیمت (تومان)',
                    data: data.data,
                    borderColor: '#15803d',
                    backgroundColor: 'rgba(21,128,61,0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#15803d'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        rtl: true,
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
                            font: { family: 'Vazirmatn, Tahoma' }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        ticks: { font: { family: 'Vazirmatn, Tahoma' } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // =========================================
    // PRODUCT MANAGEMENT
    // =========================================
    window.bjAddProduct = function() {
        var name = $('#bjNewName').val().trim();
        var category = $('#bjNewCat').val();
        var unit = $('#bjNewUnit').val().trim();

        if (!name) {
            alert('لطفاً نام محصول را وارد کنید');
            return;
        }

        $.post(bjAdmin.ajaxurl, {
            action: 'bj_save_product',
            nonce: bjAdmin.nonce,
            name: name,
            category: category,
            unit: unit || 'قطعه'
        }, function(res) {
            if (res.success) {
                location.reload();
            } else {
                alert(res.data || 'خطا');
            }
        });
    };

    window.bjDeleteProduct = function(id) {
        if (!confirm('آیا از غیرفعال کردن این محصول مطمئنید؟')) return;

        $.post(bjAdmin.ajaxurl, {
            action: 'bj_delete_product',
            nonce: bjAdmin.nonce,
            id: id
        }, function(res) {
            if (res.success) {
                $('tr[data-id="' + id + '"]').fadeOut(400, function() { $(this).remove(); });
            }
        });
    };

    // =========================================
    // ARCHIVE
    // =========================================
    window.bjLoadArchive = function() {
        var cat = $('#bjArchiveCat').val();
        var from = $('#bjArchiveFrom').val();
        var to = $('#bjArchiveTo').val();
        var $container = $('#bjArchiveTable');

        $container.html('<p style="text-align:center;padding:40px;color:#64748b">⏳ در حال بارگذاری...</p>');

        $.get(bjAdmin.ajaxurl, {
            action: 'bj_get_chart',
            product_id: 1,
            days: 365
        }, function(res) {
            if (!res.success || !res.data.labels.length) {
                $container.html('<p style="text-align:center;padding:40px;color:#94a3b8">داده‌ای یافت نشد</p>');
                return;
            }
            var html = '<table class="wp-list-table widefat fixed striped"><thead><tr><th>تاریخ</th><th>قیمت</th></tr></thead><tbody>';
            for (var i = res.data.labels.length - 1; i >= 0; i--) {
                html += '<tr><td>' + res.data.labels[i] + '</td><td>' + numberFormat(res.data.data[i]) + ' تومان</td></tr>';
            }
            html += '</tbody></table>';
            $container.html(html);
        });
    };

    // =========================================
    // CSV EXPORT
    // =========================================
    window.bjExportCSV = function() {
        var rows = [];
        $('#bjArchiveTable table thead tr').each(function() {
            var row = [];
            $(this).find('th').each(function() { row.push($(this).text()); });
            rows.push(row.join(','));
        });
        $('#bjArchiveTable table tbody tr').each(function() {
            var row = [];
            $(this).find('td').each(function() { row.push('"' + $(this).text().replace(/"/g, '""') + '"'); });
            rows.push(row.join(','));
        });

        if (rows.length < 2) {
            alert('ابتدا آرشیو را بارگذاری کنید');
            return;
        }

        var BOM = '﻿';
        var blob = new Blob([BOM + rows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'bazarjooje-archive.csv';
        link.click();
    };

    // =========================================
    // NUMBER FORMAT HELPER
    // =========================================
    function numberFormat(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // =========================================
    // INIT
    // =========================================
    $(document).ready(function() {
        if ($('#bjAdminChart').length) {
            bjLoadQuickChart();
        }
    });

})(jQuery);
