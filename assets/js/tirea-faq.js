/**
 * TIREA — FAQ
 * Recherche, "Tout déplier/replier", formulaire de contact AJAX.
 */
(function () {
    'use strict';

    var root = document.getElementById('tirea-faq');
    if (!root) return;

    // ============================================
    // RECHERCHE
    // ============================================
    var searchInput = root.querySelector('#tirea-faq-search-input');
    var emptyMsg = root.querySelector('#tirea-faq-empty');
    var items = root.querySelectorAll('.tirea-faq-item');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var q = searchInput.value.trim().toLowerCase();
            var visible = 0;

            items.forEach(function (it) {
                var haystack = (it.getAttribute('data-q') || '');
                var match = !q || haystack.indexOf(q) !== -1;
                it.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            if (emptyMsg) {
                emptyMsg.hidden = visible !== 0;
            }
        });
    }

    // ============================================
    // TOUT DÉPLIER / REPLIER
    // ============================================
    var expandBtn = root.querySelector('#tirea-faq-expand');
    if (expandBtn) {
        expandBtn.addEventListener('click', function () {
            var anyClosed = Array.prototype.some.call(items, function (it) {
                return !it.open;
            });
            items.forEach(function (it) {
                it.open = anyClosed;
            });
            expandBtn.textContent = anyClosed ? 'Tout replier' : 'Tout déplier';
        });
    }

    // ============================================
    // FORMULAIRE CONTACT (AJAX → wp_mail)
    // ============================================
    var form = root.querySelector('#tirea-faq-contact-form');
    var status = root.querySelector('#tirea-faq-status');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var name = form.name.value.trim();
            var email = form.email.value.trim();
            var message = form.message.value.trim();
            var nonce = form.nonce.value;
            var honeypot = form.website ? form.website.value : '';

            // Reset visuel
            form.querySelectorAll('.error').forEach(function (el) {
                el.classList.remove('error');
            });
            if (status) {
                status.textContent = '';
                status.className = 'tirea-faq-form-status';
            }

            // Validation côté client
            var valid = true;
            if (!name) { form.name.classList.add('error'); valid = false; }
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { form.email.classList.add('error'); valid = false; }
            if (!message) { form.message.classList.add('error'); valid = false; }

            if (!valid) {
                if (status) {
                    status.textContent = 'Merci de remplir tous les champs correctement.';
                    status.className = 'tirea-faq-form-status error';
                }
                return;
            }

            // Préparation requête
            var submitBtn = form.querySelector('.tirea-faq-submit');
            var submitText = form.querySelector('.tirea-faq-submit-text');
            var originalText = submitText ? submitText.textContent : '';
            if (submitBtn) submitBtn.disabled = true;
            if (submitText) submitText.textContent = 'Envoi en cours…';

            var formData = new FormData();
            formData.append('action', 'tirea_faq_contact');
            formData.append('nonce', nonce);
            formData.append('name', name);
            formData.append('email', email);
            formData.append('message', message);
            formData.append('website', honeypot);

            var ajaxUrl = (window.tireaFaqData && window.tireaFaqData.ajax_url) || '/wp-admin/admin-ajax.php';

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res && res.success) {
                        if (status) {
                            status.textContent = res.data && res.data.message
                                ? res.data.message
                                : 'Message envoyé ! Nous vous répondons sous 24h.';
                            status.className = 'tirea-faq-form-status success';
                        }
                        form.reset();
                    } else {
                        if (status) {
                            status.textContent = (res && res.data && res.data.message)
                                ? res.data.message
                                : "Une erreur est survenue. Réessayez ou écrivez-nous directement.";
                            status.className = 'tirea-faq-form-status error';
                        }
                    }
                })
                .catch(function () {
                    if (status) {
                        status.textContent = "Impossible d'envoyer le message. Vérifiez votre connexion.";
                        status.className = 'tirea-faq-form-status error';
                    }
                })
                .finally(function () {
                    if (submitBtn) submitBtn.disabled = false;
                    if (submitText) submitText.textContent = originalText;
                });
        });
    }
})();