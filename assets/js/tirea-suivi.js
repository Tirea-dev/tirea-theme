(function () {
    'use strict';

    var form = document.getElementById('tirea-suivi-form');
    if (!form) return;

    var feedback = document.getElementById('tirea-suivi-feedback');
    var result   = document.getElementById('tirea-suivi-result');
    var input    = document.getElementById('tirea-suivi-input');

    function esc(s) {
        var d = document.createElement('div');
        d.textContent = (s == null) ? '' : String(s);
        return d.innerHTML;
    }
    function setFeedback(msg) { if (feedback) feedback.textContent = msg || ''; }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var tracking = form.tracking.value.trim();
        var nonce    = form.nonce.value;
        var honeypot = form.website ? form.website.value : '';

        if (input) input.removeAttribute('aria-invalid');
        setFeedback('');
        if (result) { result.hidden = true; result.innerHTML = ''; }

        if (!tracking) {
            if (input) input.setAttribute('aria-invalid', 'true');
            setFeedback('Merci d\'entrer votre numéro de suivi.');
            return;
        }

        var btn     = form.querySelector('.tirea-suivi-btn');
        var btnText = form.querySelector('.tirea-suivi-btn-text');
        var originalText = btnText ? btnText.textContent : '';
        if (btn) btn.classList.add('is-loading');
        if (btnText) btnText.textContent = 'Recherche…';

        var fd = new FormData();
        fd.append('action', 'tirea_suivi_track');
        fd.append('nonce', nonce);
        fd.append('tracking', tracking);
        fd.append('website', honeypot);

        var ajaxUrl = (window.tireaSuiviData && window.tireaSuiviData.ajax_url) || '/wp-admin/admin-ajax.php';

        fetch(ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res && res.success && res.data) {
                    var d = res.data;
                    var delivered = (d.state === 'delivered');
                    var dateLine = '';
                    if (d.date) {
                        dateLine = '<p class="tirea-suivi-card-date">'
                            + (delivered ? 'Livré le ' : 'Dernière mise à jour : ')
                            + esc(d.date) + '</p>';
                    }
                    var html =
                        '<div class="tirea-suivi-card ' + (delivered ? 'is-delivered' : 'is-transit') + '">'
                          + '<span class="tirea-suivi-card-state">' + (delivered ? 'Livré' : 'En transit') + '</span>'
                          + '<p class="tirea-suivi-card-label">' + esc(d.label) + '</p>'
                          + dateLine
                          + '<p class="tirea-suivi-card-num">N° ' + esc(d.tracking) + '</p>'
                          + '<a class="tirea-suivi-card-link" href="' + esc(d.link) + '" target="_blank" rel="noopener">Voir le détail complet sur La Poste</a>'
                        + '</div>';
                    if (result) { result.innerHTML = html; result.hidden = false; }
                } else {
                    setFeedback((res && res.data && res.data.message)
                        ? res.data.message
                        : 'Service de suivi indisponible. Réessayez plus tard.');
                }
            })
            .catch(function () {
                setFeedback('Impossible de contacter le service. Vérifiez votre connexion.');
            })
            .finally(function () {
                if (btn) btn.classList.remove('is-loading');
                if (btnText) btnText.textContent = originalText;
            });
    });
})();