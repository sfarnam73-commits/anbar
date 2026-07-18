(function () {
  'use strict';

  document.addEventListener('click', function (event) {
    var target = event.target.closest('[data-consultation-cta="true"], .cheraghi-sticky-consultation a');

    if (!target) {
      return;
    }

    if (window.gtag) {
      window.gtag('event', 'consultation_cta_click', {
        event_category: 'engagement',
        event_label: target.textContent.trim(),
        link_url: target.href
      });
    }
  });
}());
