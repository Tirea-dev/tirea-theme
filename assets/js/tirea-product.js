/**
 * TIREA — Sélecteur de packs WooCommerce
 * Supporte plusieurs instances + slider d'images + compteur fusionné
 */
(function($) {
    'use strict';

    $(document).ready(function() {

        // Scroll-to-top sur le CTA final (remplace l'ancien onclick inline)
        $('.tirea-final-btn').on('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // ============================================
        // Helpers erreur panier (inline)
        // ============================================
        function showCartError($section, message) {
            const $err = $section.find('.tirea-cart-error');
            if (!$err.length) return;
            $err.text(message).removeAttr('hidden');
        }

        function hideCartError($section) {
            const $err = $section.find('.tirea-cart-error');
            if (!$err.length) return;
            $err.text('').attr('hidden', 'hidden');
        }

        // ============================================
        // Init de chaque section sélecteur
        // ============================================
        $('.tirea-product-section').each(function() {
            initTireaSection($(this));
        });

        function initTireaSection($section) {
            const productId = $section.data('product-id');
            const $packs = $section.find('.tirea-pack');
            const $slides = $section.find('.tirea-slide');
            const $thumbnails = $section.find('.tirea-thumbnail');
            const $totalPrice = $section.find('.tirea-total-price');
            const $ctaBtn = $section.find('.tirea-cta-btn');
            const $ctaText = $ctaBtn.find('.tirea-cta-text');
            const $mainImageContainer = $section.find('.tirea-main-image');
            const $prevArrow = $section.find('.tirea-slider-prev');
            const $nextArrow = $section.find('.tirea-slider-next');

            const totalSlides = $slides.length;
            let currentSlide = 0;

            // ===== Utilitaires =====
            function formatPrice(price) {
                return parseFloat(price).toFixed(2).replace('.', ',') + ' €';
            }

            function updateTotal() {
                const $selected = $section.find('.tirea-pack.selected');
                if (!$selected.length) return;
                const price = parseFloat($selected.data('price'));
                $totalPrice.text(formatPrice(price));
            }

            // ===== Slider =====
            function showSlide(index) {
                if (index < 0) index = totalSlides - 1;
                if (index >= totalSlides) index = 0;
                currentSlide = index;

                $slides.removeClass('active');
                $slides.filter('[data-slide-index="' + index + '"]').addClass('active');

                $thumbnails.removeClass('active');
                $thumbnails.filter('[data-slide-index="' + index + '"]').addClass('active');
            }

            // Flèches
            $prevArrow.on('click', function(e) {
                e.preventDefault();
                showSlide(currentSlide - 1);
            });

            $nextArrow.on('click', function(e) {
                e.preventDefault();
                showSlide(currentSlide + 1);
            });

            // Miniatures
            $thumbnails.on('click', function() {
                const idx = parseInt($(this).data('slide-index'), 10);
                showSlide(idx);
            });

            // ===== Swipe tactile (mobile + drag souris desktop) =====
            let touchStartX = 0;
            let touchEndX = 0;
            let isDragging = false;
            let mouseStartX = 0;

            // Touch (mobile)
            $mainImageContainer.on('touchstart', function(e) {
                touchStartX = e.originalEvent.touches[0].clientX;
            });

            $mainImageContainer.on('touchend', function(e) {
                touchEndX = e.originalEvent.changedTouches[0].clientX;
                handleSwipe();
            });

            function handleSwipe() {
                const threshold = 50; // pixels minimum pour considérer un swipe
                const diff = touchEndX - touchStartX;
                if (Math.abs(diff) < threshold) return;
                if (diff < 0) {
                    showSlide(currentSlide + 1); // swipe gauche = suivant
                } else {
                    showSlide(currentSlide - 1); // swipe droite = précédent
                }
            }

            // Drag souris (desktop)
            $mainImageContainer.on('mousedown', function(e) {
                isDragging = true;
                mouseStartX = e.clientX;
                $(this).css('cursor', 'grabbing');
                e.preventDefault();
            });

            $(document).on('mouseup', function(e) {
                if (!isDragging) return;
                isDragging = false;
                $mainImageContainer.css('cursor', 'grab');
                const diff = e.clientX - mouseStartX;
                if (Math.abs(diff) < 80) return;
                if (diff < 0) {
                    showSlide(currentSlide + 1);
                } else {
                    showSlide(currentSlide - 1);
                }
            });

            // ===== Sélection d'un pack =====
            function selectPack($pack) {
                $packs.removeClass('selected');
                $pack.addClass('selected');

                // Lien pack → image : on affiche l'image associée au pack
                const slideIdx = parseInt($pack.data('slide-index'), 10);
                if (!isNaN(slideIdx)) {
                    showSlide(slideIdx);
                }

                updateTotal();

                // Une sélection valide → on cache un éventuel message d'erreur
                hideCartError($section);
            }

            // Init prix au chargement
            updateTotal();

            // Init slide selon pack par défaut
            const $defaultPack = $section.find('.tirea-pack.selected');
            if ($defaultPack.length) {
                const defaultSlideIdx = parseInt($defaultPack.data('slide-index'), 10);
                if (!isNaN(defaultSlideIdx)) showSlide(defaultSlideIdx);
            }

            $packs.on('click', function() {
                selectPack($(this));
            });

            // ===== Ajouter au panier =====
            $ctaBtn.on('click', function(e) {
                e.preventDefault();

                // On repart toujours d'un état propre à chaque clic
                hideCartError($section);

                const $selected = $section.find('.tirea-pack.selected');
                if (!$selected.length) {
                    showCartError($section, 'Veuillez sélectionner un pack.');
                    return;
                }

                const variationId = $selected.data('variation-id');

                $ctaBtn.addClass('loading').prop('disabled', true);
                $ctaText.text('Ajout en cours...');

                $.ajax({
                    url: tireaData.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'tirea_add_to_cart',
                        product_id: productId,
                        variation_id: variationId,
                        quantity: 1,
                        nonce: tireaData.nonce,
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.data.redirect;
                        } else {
                            $ctaBtn.removeClass('loading').prop('disabled', false);
                            $ctaText.text('Ajouter au panier');
                            showCartError($section, (response.data && response.data.message) || 'Une erreur est survenue.');
                        }
                    },
                    error: function() {
                        $ctaBtn.removeClass('loading').prop('disabled', false);
                        $ctaText.text('Ajouter au panier');
                        showCartError($section, 'Erreur réseau, veuillez réessayer.');
                    }
                });
            });
        }

        // ============================================
        // EXPÉDITION (chrono ou date) + RÉCEPTION ESTIMÉE
        // ============================================
        const $shippingInline = $('.tirea-shipping-inline');
        const $receptionValue = $('.tirea-reception-value');

        if ($shippingInline.length || $receptionValue.length) {

            const joursCourts = ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'];
            const moisCourts = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];

            // Heures de départ par jour (0 = dimanche … 6 = samedi)
            const departuresByDay = { 0: [], 1: [11, 16], 2: [11, 16], 3: [11, 16], 4: [11, 16], 5: [11, 16], 6: [11] };
            const HIDE_BEFORE_MS = 10 * 60 * 1000; // on masque le chrono 10 min avant le départ

            function departuresOn(date) {
                return (departuresByDay[date.getDay()] || []).map(function(h) {
                    const d = new Date(date);
                    d.setHours(h, 0, 0, 0);
                    return d;
                });
            }

            // Prochain départ du jour encore à plus de 10 min, sinon null
            function getCountdownTarget(now) {
                const todays = departuresOn(now);
                for (let i = 0; i < todays.length; i++) {
                    if (todays[i] - now > HIDE_BEFORE_MS) return todays[i];
                }
                return null;
            }

            // Jour de départ réel : aujourd'hui si un départ du jour est encore
            // affichable, sinon le prochain jour qui a un départ
            function getShipDay(now) {
                if (getCountdownTarget(now)) {
                    const today = new Date(now);
                    today.setHours(0, 0, 0, 0);
                    return today;
                }
                const d = new Date(now);
                d.setHours(0, 0, 0, 0);
                do {
                    d.setDate(d.getDate() + 1);
                } while (departuresOn(d).length === 0);
                return d;
            }

            function addBusinessDays(date, days) {
                const result = new Date(date);
                let added = 0;
                while (added < days) {
                    result.setDate(result.getDate() + 1);
                    if (result.getDay() !== 0) added++; // on saute le dimanche
                }
                return result;
            }

            function formatDate(date) {
                return joursCourts[date.getDay()] + ' ' + date.getDate() + ' ' + moisCourts[date.getMonth()];
            }

            // Ligne "expédition" : chrono le jour même, sinon date du prochain départ
            if ($shippingInline.length) {
                function updateShipping() {
                    const now = new Date();
                    const target = getCountdownTarget(now);

                    if (target) {
                        const totalMinutes = Math.floor((target - now) / 60000);
                        const hours = Math.floor(totalMinutes / 60);
                        const minutes = totalMinutes % 60;
                        const timerStr = hours > 0 ? (hours + 'h ' + minutes + 'min') : (minutes + 'min');
                        $shippingInline.html(', expédition dans <strong class="tirea-timer-value">' + timerStr + '</strong>').show();
                    } else {
                        $shippingInline.html(', expédition le <strong class="tirea-timer-value">' + formatDate(getShipDay(now)) + '</strong>').show();
                    }
                }

                updateShipping();
                setInterval(updateShipping, 1000);
            }

            // Ligne "réception estimée" : jour de départ + 2 jours ouvrés
            if ($receptionValue.length) {
                function updateReception() {
                    $receptionValue.text(formatDate(addBusinessDays(getShipDay(new Date()), 2)));
                }

                updateReception();
                setInterval(updateReception, 60000);
            }
        }
    });

// ============================================
        // BLOC AVIS
        // ============================================

        // ===== Photos : pause auto + reprise après 3s + swipe =====
        $('.tirea-photos-carousel').each(function() {
            const $carousel = $(this);
            let resumeTimer = null;

            function pauseCarousel() {
                $carousel.attr('data-paused', 'true');
                if (resumeTimer) clearTimeout(resumeTimer);
            }

            function scheduleResume() {
                if (resumeTimer) clearTimeout(resumeTimer);
                resumeTimer = setTimeout(function() {
                    $carousel.attr('data-paused', 'false');
                }, 3000);
            }

            // Desktop : pause au survol, reprise au mouseleave
            $carousel.on('mouseenter', pauseCarousel);
            $carousel.on('mouseleave', function() {
                $carousel.attr('data-paused', 'false');
                if (resumeTimer) clearTimeout(resumeTimer);
            });

            // Mobile/tablette : tap = pause, reprise auto 3s après
            let touchStartX = 0;
            let touchStartY = 0;

            $carousel.on('touchstart', function(e) {
                pauseCarousel();
                touchStartX = e.originalEvent.touches[0].clientX;
                touchStartY = e.originalEvent.touches[0].clientY;
            });

            $carousel.on('touchend', function() {
                scheduleResume();
            });

            // Empêcher le tap sur les photos d'ouvrir la lightbox si c'était un swipe
            $carousel.find('.tirea-photo-item').on('touchend', function(e) {
                // Si le doigt a bougé de plus de 10px, c'est un swipe, pas un tap
                const lastTouch = e.originalEvent.changedTouches[0];
                const dx = Math.abs(lastTouch.clientX - touchStartX);
                const dy = Math.abs(lastTouch.clientY - touchStartY);
                if (dx > 10 || dy > 10) {
                    e.preventDefault();
                    return false;
                }
            });
        });

        // ===== Avis : pause survol + flèches manuelles =====
        $('.tirea-reviews-roulette').each(function() {
            const $roulette = $(this);
            const $track = $roulette.find('.tirea-reviews-track');
            const $arrowUp = $roulette.find('.tirea-reviews-arrow-up');
            const $arrowDown = $roulette.find('.tirea-reviews-arrow-down');

            // Pause au survol
            $roulette.on('mouseenter', function() {
                $roulette.attr('data-paused', 'true');
            });
            $roulette.on('mouseleave', function() {
                $roulette.attr('data-paused', 'false');
            });

            // Flèches manuelles : déplacent le track de la hauteur d'une carte (~90px)
            let manualOffset = 0;
            const cardStep = 100; // décalage par clic

            function getCurrentTranslateY($el) {
                const matrix = window.getComputedStyle($el[0]).transform;
                if (matrix === 'none') return 0;
                const values = matrix.match(/matrix.*\((.+)\)/);
                if (!values) return 0;
                return parseFloat(values[1].split(', ')[5]) || 0;
            }

            function manualScroll(direction) {
                $roulette.attr('data-paused', 'true');
                const currentY = getCurrentTranslateY($track);
                const trackHeight = $track[0].scrollHeight;
                const halfHeight = trackHeight / 2;

                let newY = currentY + (direction === 'down' ? -cardStep : cardStep);

                // Boucle infinie : si on dépasse, on reset
                if (newY <= -halfHeight) newY += halfHeight;
                if (newY >= 0) newY -= halfHeight;

                // Désactive temporairement l'animation CSS pour appliquer manuellement
                $track.css({
                    'animation': 'none',
                    'transition': 'transform 0.4s ease',
                    'transform': 'translateY(' + newY + 'px)'
                });

                // Après l'animation manuelle, on reprend l'auto-scroll depuis la nouvelle position
                clearTimeout($roulette.data('resumeTimer'));
                $roulette.data('resumeTimer', setTimeout(function() {
                    // Réactivation auto-scroll : on doit recalculer l'animation depuis newY
                    const animDuration = 60; // secondes
                    const remainingPercent = Math.abs(newY) / halfHeight;
                    const remainingTime = animDuration * (1 - remainingPercent);

                    $track.css('transition', 'none');
                    $track.css('animation', 'tirea-reviews-scroll ' + animDuration + 's linear infinite');
                    $track.css('animation-delay', '-' + (animDuration * remainingPercent) + 's');
                    $roulette.attr('data-paused', 'false');
                }, 3000));
            }

            $arrowUp.on('click', function(e) {
                e.preventDefault();
                manualScroll('up');
            });

            $arrowDown.on('click', function(e) {
                e.preventDefault();
                manualScroll('down');
            });
        });

        // ===== Lightbox photos =====
        const $lightbox = $('#tireaLightbox');
        const $lightboxImg = $lightbox.find('.tirea-lightbox-img');
        const $lightboxClose = $lightbox.find('.tirea-lightbox-close');

        // Mémorise le focus précédent pour le restituer à la fermeture
        let lightboxPreviousFocus = null;

        $('.tirea-photo-item').on('click', function(e) {
            const $img = $(this).find('img');
            if ($img.length && $img.attr('src')) {
                lightboxPreviousFocus = document.activeElement;
                $lightboxImg.attr('src', $img.attr('src'));
                $lightboxImg.attr('alt', $img.attr('alt') || '');
                $lightbox.addClass('active');
                $('body').css('overflow', 'hidden');
                // Donne le focus au bouton fermer (seul élément focusable du dialog)
                if ($lightboxClose.length) {
                    $lightboxClose[0].focus();
                }
            }
        });

        function closeLightbox() {
            $lightbox.removeClass('active');
            $('body').css('overflow', '');
            // Restitue le focus à l'élément qui a ouvert la lightbox
            if (lightboxPreviousFocus && typeof lightboxPreviousFocus.focus === 'function' && document.contains(lightboxPreviousFocus)) {
                lightboxPreviousFocus.focus();
            }
            lightboxPreviousFocus = null;
        }

        $lightbox.on('click', closeLightbox);
        $lightboxClose.on('click', function(e) {
            e.stopPropagation();
            closeLightbox();
        });

        $lightboxImg.on('click', function(e) {
            e.stopPropagation();
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $lightbox.hasClass('active')) {
                closeLightbox();
                return;
            }
            // Focus-trap : un seul focusable (le bouton fermer) → on garde le focus dessus
            if (e.key === 'Tab' && $lightbox.hasClass('active')) {
                e.preventDefault();
                if ($lightboxClose.length) {
                    $lightboxClose[0].focus();
                }
            }
        });

})(jQuery);