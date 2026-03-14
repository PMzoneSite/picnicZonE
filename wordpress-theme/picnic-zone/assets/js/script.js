/**
 * Пикник ZonE — Скрипты (WordPress)
 * CRM URL берётся из picnicZone.crmApiUrl (wp_localize_script)
 */
(function() {
    'use strict';

    const CRM_API_URL = (typeof picnicZone !== 'undefined' && picnicZone.crmApiUrl) ? picnicZone.crmApiUrl : 'https://ваш-домен/api/crm-lead';

    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');
    const bookingModal = document.getElementById('bookingModal');
    const modalClose = document.getElementById('modalClose');
    const bookingForm = document.getElementById('bookingModalForm');
    const formStatus = document.getElementById('formStatus');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            icon.classList.toggle('fa-bars', !navMenu.classList.contains('active'));
            icon.classList.toggle('fa-times', navMenu.classList.contains('active'));
        });
    }

    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            if (navMenu) navMenu.classList.remove('active');
            if (menuToggle) {
                const icon = menuToggle.querySelector('i');
                icon.classList.replace('fa-times', 'fa-bars');
            }
        });
    });

    function openBookingModal(id, name) {
        const modalSubtitle = document.getElementById('modalSubtitle');
        const pavilionIdInput = document.getElementById('bookingPavilionId');
        const pavilionNameInput = document.getElementById('bookingPavilionName');
        if (modalSubtitle) modalSubtitle.textContent = name;
        if (pavilionIdInput) pavilionIdInput.value = id;
        if (pavilionNameInput) pavilionNameInput.value = name;
        if (bookingModal) {
            bookingModal.classList.add('active');
            bookingModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            if (bookingForm) bookingForm.reset();
            hideFormStatus();
            setMinDates();
            initPhoneMask(document.getElementById('clientPhone'));
        }
    }

    function closeBookingModal() {
        if (bookingModal) {
            bookingModal.classList.remove('active');
            bookingModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
    }

    if (modalClose) modalClose.addEventListener('click', closeBookingModal);
    bookingModal?.addEventListener('click', (e) => { if (e.target === bookingModal) closeBookingModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && bookingModal?.classList.contains('active')) closeBookingModal(); });

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-book');
        if (btn) {
            const id = btn.dataset.id || '1';
            const name = btn.dataset.name || 'Беседка';
            openBookingModal(id, name);
        }
    });

    function setMinDates() {
        const today = new Date().toISOString().split('T')[0];
        const dateStart = document.getElementById('dateStart');
        const dateEnd = document.getElementById('dateEnd');
        if (dateStart) dateStart.setAttribute('min', today);
        if (dateEnd) dateEnd.setAttribute('min', today);
        dateStart?.addEventListener('change', function() {
            if (dateEnd && this.value) dateEnd.setAttribute('min', this.value);
        });
    }

    function initPhoneMask(input) {
        if (!input) return;
        input.addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '');
            if (v[0] === '8' || v[0] === '7') v = v.substring(1);
            let f = '+7';
            if (v.length > 0) f += ' (' + v.substring(0, 3);
            if (v.length >= 3) f += ') ' + v.substring(3, 6);
            if (v.length >= 6) f += '-' + v.substring(6, 8);
            if (v.length >= 8) f += '-' + v.substring(8, 10);
            e.target.value = v.length ? f : '';
        });
    }

    function isValidPhone(phone) {
        const d = phone.replace(/\D/g, '');
        return d.length >= 10 && (d[0] === '7' || d[0] === '8' || d.length === 10);
    }

    function validateForm() {
        const name = document.getElementById('clientName');
        const phone = document.getElementById('clientPhone');
        const nameError = document.getElementById('nameError');
        const phoneError = document.getElementById('phoneError');
        if (nameError) nameError.textContent = '';
        if (phoneError) phoneError.textContent = '';
        let valid = true;
        if (!name?.value.trim()) { if (nameError) nameError.textContent = 'Введите ваше имя'; valid = false; }
        if (!phone?.value.trim()) { if (phoneError) phoneError.textContent = 'Введите номер телефона'; valid = false; }
        else if (!isValidPhone(phone.value)) { if (phoneError) phoneError.textContent = 'Введите корректный номер'; valid = false; }
        return valid;
    }

    function showFormStatus(msg, type) {
        if (!formStatus) return;
        formStatus.textContent = msg;
        formStatus.className = 'form-status ' + type;
        formStatus.style.display = 'block';
    }
    function hideFormStatus() {
        if (formStatus) { formStatus.textContent = ''; formStatus.className = 'form-status'; formStatus.style.display = 'none'; }
    }

    if (bookingForm) {
        bookingForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            hideFormStatus();
            if (!validateForm()) return;
            const submitBtn = document.getElementById('submitBookingBtn');
            if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Отправка...'; }
            const payload = {
                id_besedki: document.getElementById('bookingPavilionId')?.value || '',
                nazvanie_besedki: document.getElementById('bookingPavilionName')?.value || '',
                data_nachala: document.getElementById('dateStart')?.value || '',
                data_okonchaniya: document.getElementById('dateEnd')?.value || '',
                vremya_nachala: document.getElementById('timeStart')?.value || '',
                vremya_okonchaniya: document.getElementById('timeEnd')?.value || '',
                imya_klienta: document.getElementById('clientName')?.value?.trim() || '',
                telefon_klienta: document.getElementById('clientPhone')?.value?.replace(/\D/g, '') || '',
            };
            try {
                const res = await fetch(CRM_API_URL, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
                if (!res.ok) throw new Error(await res.text() || 'Ошибка');
                await res.json();
                showFormStatus('Заявка успешно отправлена! Мы свяжемся с вами.', 'success');
                bookingForm.reset();
                setMinDates();
                setTimeout(closeBookingModal, 2000);
            } catch (err) {
                console.error('CRM:', err);
                showFormStatus('Не удалось отправить заявку. Позвоните: +7 (XXX) XXX-XX-XX', 'error');
            } finally {
                if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Отправить заявку'; }
            }
        });
    }

    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function(e) {
            const id = this.getAttribute('href');
            if (id === '#') return;
            const el = document.querySelector(id);
            if (el) { e.preventDefault(); el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        });
    });

    window.addEventListener('scroll', () => {
        const h = document.querySelector('.header');
        if (!h) return;
        h.style.backgroundColor = window.scrollY > 100 ? 'rgba(255,255,255,0.98)' : 'rgba(255,255,255,0.95)';
        h.style.boxShadow = window.scrollY > 100 ? '0 5px 15px rgba(0,0,0,0.1)' : '0 2px 10px rgba(0,0,0,0.1)';
    });

    const obs = new IntersectionObserver(entries => entries.forEach(e => e.isIntersecting && e.target.classList.add('animated')), { threshold: 0.1 });
    document.querySelectorAll('.feature, .pavilion-card, .service-card, .gallery-item').forEach(el => obs.observe(el));
})();
