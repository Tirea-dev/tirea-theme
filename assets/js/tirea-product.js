/**
 * TIREA — Sélecteur de packs WooCommerce
 * Supporte plusieurs instances + slider d'images + compteur fusionné
 */
(function($) {
    'use strict';

    $(document).ready(function() {

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

                const $selected = $section.find('.tirea-pack.selected');
                if (!$selected.length) {
                    alert('Veuillez sélectionner un pack');
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
                            alert(response.data.message || 'Une erreur est survenue');
                        }
                    },
                    error: function() {
                        $ctaBtn.removeClass('loading').prop('disabled', false);
                        $ctaText.text('Ajouter au panier');
                        alert('Erreur réseau, veuillez réessayer');
                    }
                });
            });
        }

        // ============================================
// Animation des deux moitiés au scroll
// ============================================
const ajusteurVisual = document.getElementById('tireaAjusteurVisual');
if (ajusteurVisual && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
            } else {
                entry.target.classList.remove('in-view');
            }
        });
    }, { threshold: 0.5 });
    observer.observe(ajusteurVisual);
}

        // ============================================
        // COMPTEUR D'EXPÉDITION FUSIONNÉ AU STOCK
        // ============================================
        const $shippingInline = $('.tirea-shipping-inline');
        const $timerValue = $('.tirea-timer-value');

        if ($shippingInline.length) {
            function getShippingCutoff() {
                const now = new Date();
                const day = now.getDay();
                const hour = now.getHours();
                const minute = now.getMinutes();

                // Dimanche : pas d'expédition
                if (day === 0) return null;

                // Créneau MATIN (00h00 → 11h50)
                if (hour < 11 || (hour === 11 && minute < 50)) {
                    const cutoff = new Date(now);
                    cutoff.setHours(12, 0, 0, 0);
                    return cutoff;
                }

                // Samedi : pas d'après-midi
                if (day === 6) return null;

                // Créneau APRÈS-MIDI (12h00 → 14h50)
                if (hour >= 12 && (hour < 14 || (hour === 14 && minute < 50))) {
                    const cutoff = new Date(now);
                    cutoff.setHours(15, 0, 0, 0);
                    return cutoff;
                }

                return null;
            }

            function updateCountdown() {
                const cutoff = getShippingCutoff();

                if (!cutoff) {
                    $shippingInline.hide();
                    return;
                }

                const now = new Date();
                const diff = cutoff - now;

                if (diff <= 0) {
                    $shippingInline.hide();
                    return;
                }

                const totalMinutes = Math.floor(diff / 60000);
                const hours = Math.floor(totalMinutes / 60);
                const minutes = totalMinutes % 60;

                // Format : "2h 34min" si ≥ 1h, "34min" si < 1h
                let timerStr;
                if (hours > 0) {
                    timerStr = hours + 'h ' + minutes + 'min';
                } else {
                    timerStr = minutes + 'min';
                }

                $timerValue.text(timerStr);
                $shippingInline.show();
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        }

        // ============================================
        // RÉCEPTION ESTIMÉE (date dynamique)
        // ============================================
        const $receptionValue = $('.tirea-reception-value');

        if ($receptionValue.length) {
            const joursCourts = ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'];
            const moisCourts = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];

            function getShippingDate() {
                const now = new Date();
                const day = now.getDay();
                const hour = now.getHours();
                const minute = now.getMinutes();

                const shippingDate = new Date(now);
                shippingDate.setHours(0, 0, 0, 0);

                // Lun-Ven
                if (day >= 1 && day <= 5) {
                    if (hour < 14 || (hour === 14 && minute < 50)) return shippingDate;
                    shippingDate.setDate(shippingDate.getDate() + 1);
                    if (shippingDate.getDay() === 0) shippingDate.setDate(shippingDate.getDate() + 1);
                    return shippingDate;
                }

                // Samedi
                if (day === 6) {
                    if (hour < 10 || (hour === 10 && minute < 50)) return shippingDate;
                    shippingDate.setDate(shippingDate.getDate() + 2); // skip dimanche
                    return shippingDate;
                }

                // Dimanche
                shippingDate.setDate(shippingDate.getDate() + 1);
                return shippingDate;
            }

            function addBusinessDays(date, days) {
                const result = new Date(date);
                let added = 0;
                while (added < days) {
                    result.setDate(result.getDate() + 1);
                    if (result.getDay() !== 0) added++;
                }
                return result;
            }

            function formatReceptionDate(date) {
                return joursCourts[date.getDay()] + ' ' + date.getDate() + ' ' + moisCourts[date.getMonth()];
            }

            function updateReception() {
                const shippingDate = getShippingDate();
                const receptionDate = addBusinessDays(shippingDate, 3);
                $receptionValue.text(formatReceptionDate(receptionDate));
            }

            updateReception();
            setInterval(updateReception, 60000);
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

        $('.tirea-photo-item').on('click', function(e) {
            const $img = $(this).find('img');
            if ($img.length && $img.attr('src')) {
                $lightboxImg.attr('src', $img.attr('src'));
                $lightboxImg.attr('alt', $img.attr('alt') || '');
                $lightbox.addClass('active');
                $('body').css('overflow', 'hidden');
            }
        });

        function closeLightbox() {
            $lightbox.removeClass('active');
            $('body').css('overflow', '');
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
            }
        });

})(jQuery);