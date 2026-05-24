/* TIREA — Pages légales : smooth scroll TOC + section active via IntersectionObserver */
(function(){
  'use strict';

  var links = document.querySelectorAll('.tirea-legal-toc a[href^="#"], .tirea-legal-toc-mobile-list a[href^="#"]');
  if (!links.length) return;

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Smooth scroll au clic sur un lien du TOC
  links.forEach(function(a){
    a.addEventListener('click', function(e){
      var id = a.getAttribute('href');
      if (!id || id.charAt(0) !== '#') return;
      var el = document.querySelector(id);
      if (!el) return;
      e.preventDefault();
      el.scrollIntoView({
        behavior: reduceMotion ? 'auto' : 'smooth',
        block: 'start'
      });
      history.replaceState(null, '', id);

      // Ferme l'accordéon mobile après clic
      var mobile = document.querySelector('.tirea-legal-toc-mobile');
      if (mobile && mobile.open && a.closest('.tirea-legal-toc-mobile')) {
        mobile.open = false;
      }
    });
  });

  // Highlight de la section visible (desktop uniquement)
  var tocLinks = document.querySelectorAll('.tirea-legal-toc a[href^="#"]');
  if (!tocLinks.length || !('IntersectionObserver' in window)) return;

  var map = new Map();
  tocLinks.forEach(function(a){
    map.set(a.getAttribute('href').slice(1), a);
  });

  var io = new IntersectionObserver(function(entries){
    entries.forEach(function(en){
      var id = en.target.id;
      var link = map.get(id);
      if (!link) return;
      if (en.isIntersecting){
        tocLinks.forEach(function(l){ l.classList.remove('is-active'); });
        link.classList.add('is-active');
      }
    });
  }, { rootMargin: '-30% 0px -60% 0px', threshold: 0 });

  document.querySelectorAll('.tirea-legal-section[id]').forEach(function(s){
    io.observe(s);
  });
})();

/* ============================================================
   TIREA — Formulaires AJAX (retours + contact) + modal + email anti-obfuscation
   ============================================================ */
(function(){
  'use strict';

  // ===== 1. Email assemblé côté client (anti-Cloudflare obfuscation) =====
  document.querySelectorAll('.tirea-contact-email-link').forEach(function(el){
    var u = el.getAttribute('data-user');
    var h = el.getAttribute('data-host');
    if (!u || !h) return;
    var addr = u + String.fromCharCode(64) + h;
    el.textContent = addr;
    el.setAttribute('href', 'mailto:' + addr);
  });

  // ===== 2. Chips de sujet (page contact) =====
  var chipsWrap = document.querySelector('.tirea-contact-subjects');
  if (chipsWrap){
    var chips = chipsWrap.querySelectorAll('.tirea-contact-chip');
    var hiddenSubject = document.querySelector('input[name="sujet"]');
    chips.forEach(function(c){
      c.addEventListener('click', function(){
        chips.forEach(function(x){
          x.classList.remove('is-active');
          x.setAttribute('aria-checked', 'false');
        });
        c.classList.add('is-active');
        c.setAttribute('aria-checked', 'true');
        if (hiddenSubject) hiddenSubject.value = c.getAttribute('data-subject') || '';
      });
    });
  }

  // ===== 3. Modal =====
  var modal = document.getElementById('tirea-legal-modal');
  var modalTag = document.getElementById('tirea-legal-modal-tag');
  var modalTitle = document.getElementById('tirea-legal-modal-title');
  var modalMsg = document.getElementById('tirea-legal-modal-msg');
  var lastFocusedBeforeModal = null;

  function openModal(type, success){
    if (!modal) return;
    modal.classList.toggle('is-error', !success);

    if (success){
      if (type === 'retour'){
        modalTag.textContent = 'Demande envoyée';
        modalTitle.innerHTML = 'Merci, <em>c\'est bien parti.</em>';
        modalMsg.innerHTML = 'Votre demande de rétractation a bien été reçue. Une copie vous a été envoyée par email. Notre équipe vous recontacte sous 24&nbsp;heures avec l\'adresse de retour.';
      } else {
        modalTag.textContent = 'Message envoyé';
        modalTitle.innerHTML = 'Merci, <em>c\'est bien parti.</em>';
        modalMsg.innerHTML = 'Notre équipe française a bien reçu votre message et vous répond personnellement, par e-mail, sous 24&nbsp;heures.';
      }
    } else {
      modalTag.textContent = 'Une erreur est survenue';
      modalTitle.innerHTML = 'Oups, <em>réessayons.</em>';
      modalMsg.innerHTML = 'Votre demande n\'a pas pu être envoyée. Vérifiez votre connexion ou écrivez-nous directement à <strong>contact@tirea.fr</strong>.';
    }

    lastFocusedBeforeModal = document.activeElement;
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    var closeBtn = modal.querySelector('.tirea-legal-modal-close');
    if (closeBtn) setTimeout(function(){ closeBtn.focus(); }, 50);
  }

  function closeModal(resetForm){
    if (!modal) return;
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (resetForm){
      document.querySelectorAll('.tirea-legal-form').forEach(function(f){ f.reset(); });
      // Reset chips au premier
      if (chipsWrap){
        var chips = chipsWrap.querySelectorAll('.tirea-contact-chip');
        chips.forEach(function(x, i){
          x.classList.toggle('is-active', i === 0);
          x.setAttribute('aria-checked', i === 0 ? 'true' : 'false');
        });
        var hiddenSubject = document.querySelector('input[name="sujet"]');
        if (hiddenSubject && chips[0]) hiddenSubject.value = chips[0].getAttribute('data-subject');
      }
    }
    if (lastFocusedBeforeModal) lastFocusedBeforeModal.focus();
  }

  if (modal){
    modal.addEventListener('click', function(e){
      if (e.target === modal ||
          e.target.hasAttribute('data-modal-close') ||
          e.target.classList.contains('tirea-legal-modal-close')){
        var wasSuccess = !modal.classList.contains('is-error');
        closeModal(wasSuccess);
      }
    });
    document.addEventListener('keydown', function(e){
      if (e.key === 'Escape' && modal.classList.contains('is-open')){
        var wasSuccess = !modal.classList.contains('is-error');
        closeModal(wasSuccess);
      }
    });
  }

  // ===== 4. Submit AJAX (retour + contact) =====
  function attachForm(formId, action, type){
    var form = document.getElementById(formId);
    if (!form || typeof tireaLegalAjax === 'undefined') return;

    form.addEventListener('submit', function(e){
      e.preventDefault();
      var btn = form.querySelector('button[type="submit"]');
      var feedback = form.querySelector('.tirea-legal-form-feedback');
      if (feedback) feedback.textContent = '';

      // Reset aria-invalid
      form.querySelectorAll('[aria-invalid="true"]').forEach(function(el){
        el.setAttribute('aria-invalid', 'false');
      });

      // Validation HTML5 native
      if (!form.checkValidity()){
        var firstInvalid = form.querySelector(':invalid');
        if (firstInvalid){
          firstInvalid.setAttribute('aria-invalid', 'true');
          firstInvalid.focus();
        }
        if (feedback) feedback.textContent = 'Merci de compléter les champs obligatoires.';
        return;
      }

      if (btn){
        btn.classList.add('is-loading');
        btn.setAttribute('aria-busy', 'true');
      }

      var formData = new FormData(form);
      formData.append('action', action);
      formData.append('_wpnonce', tireaLegalAjax.nonce);

      fetch(tireaLegalAjax.url, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
      })
      .then(function(r){ return r.json(); })
      .then(function(json){
        if (json && json.success){
          openModal(type, true);
        } else {
          var msg = (json && json.data && json.data.message) ? json.data.message : '';
          if (feedback) feedback.textContent = msg || 'Une erreur est survenue. Réessayez.';
          openModal(type, false);
        }
      })
      .catch(function(){
        if (feedback) feedback.textContent = 'Erreur de connexion. Réessayez.';
        openModal(type, false);
      })
      .finally(function(){
        if (btn){
          btn.classList.remove('is-loading');
          btn.setAttribute('aria-busy', 'false');
        }
      });
    });
  }

  attachForm('tirea-form-retour', 'tirea_form_retour', 'retour');
  attachForm('tirea-form-contact', 'tirea_form_contact', 'contact');

})();

/* ============================================================
   TIREA — Page Notre Histoire : fade-up des chapitres au scroll
   ============================================================ */
(function(){
  'use strict';

  var chapters = document.querySelectorAll('.tirea-histoire-chapter');
  if (!chapters.length) return;

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Si l'utilisateur préfère réduire les animations, on rend tout visible direct
  if (reduceMotion || !('IntersectionObserver' in window)) {
    chapters.forEach(function(el){ el.classList.add('is-visible'); });
    return;
  }

  var observer = new IntersectionObserver(function(entries){
    entries.forEach(function(entry){
      if (entry.isIntersecting){
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });

  chapters.forEach(function(el){ observer.observe(el); });
})();