/**
 * Пикник ZonE — Скрипты сайта аренды беседок
 * @description Мобильное меню, модальное окно бронирования, интеграция с CRM
 */

(function() {
    'use strict';

    // ========== Конфигурация ==========
    const CRM_API_URL = 'https://ваш-домен/api/crm-lead';

    // ========== DOM Элементы ==========
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');
    const bookingModal = document.getElementById('bookingModal');
    const modalClose = document.getElementById('modalClose');
    const bookingForm = document.getElementById('bookingModalForm');
    const formStatus = document.getElementById('formStatus');

    // ========== Мобильное меню ==========
    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            if (navMenu.classList.contains('active')) {
                icon.classList.replace('fa-bars', 'fa-times');
            } else {
                icon.classList.replace('fa-times', 'fa-bars');
            }
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

    // ========== Модальное окно бронирования ==========

    /**
     * Открытие модального окна с данными выбранной беседки
     * @param {number} id - ID беседки
     * @param {string} name - Название беседки
     */
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

    /**
     * Закрытие модального окна
     */
    function closeBookingModal() {
        if (bookingModal) {
            bookingModal.classList.remove('active');
            bookingModal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
    }

    if (modalClose) {
        modalClose.addEventListener('click', closeBookingModal);
    }

    bookingModal?.addEventListener('click', (e) => {
        if (e.target === bookingModal) closeBookingModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && bookingModal?.classList.contains('active')) {
            closeBookingModal();
        }
    });

    // Обработчик кнопок «Забронировать»
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-book');
        if (btn) {
            const id = parseInt(btn.dataset.id, 10);
            const name = btn.dataset.name || 'Беседка';
            openBookingModal(id, name);
        }
    });


    /**
     * Установка минимальных дат для полей
     */
    function setMinDates() {
        const today = new Date().toISOString().split('T')[0];
        const dateStart = document.getElementById('dateStart');
        const dateEnd = document.getElementById('dateEnd');

        if (dateStart) dateStart.setAttribute('min', today);
        if (dateEnd) dateEnd.setAttribute('min', today);

        // Синхронизация: дата окончания не может быть раньше даты начала
        dateStart?.addEventListener('change', function() {
            if (dateEnd && this.value) {
                dateEnd.setAttribute('min', this.value);
            }
        });
    }

    /**
     * Маска ввода телефона (российский формат)
     * @param {HTMLInputElement} input - Поле ввода
     */
    function initPhoneMask(input) {
        if (!input) return;

        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            if (value.length > 0) {
                if (value[0] === '8' || value[0] === '7') {
                    value = value.substring(1);
                }
                let formatted = '+7';
                if (value.length > 0) formatted += ' (' + value.substring(0, 3);
                if (value.length >= 3) formatted += ') ' + value.substring(3, 6);
                if (value.length >= 6) formatted += '-' + value.substring(6, 8);
                if (value.length >= 8) formatted += '-' + value.substring(8, 10);
                value = formatted;
            } else {
                value = '';
            }

            e.target.value = value;
        });
    }

    /**
     * Валидация номера телефона (11 цифр без +7)
     * @param {string} phone - Телефон в формате +7 (XXX) XXX-XX-XX
     * @returns {boolean}
     */
    function isValidPhone(phone) {
        const digits = phone.replace(/\D/g, '');
        return digits.length >= 10 && (digits[0] === '7' || digits[0] === '8' || digits.length === 10);
    }

    /**
     * Валидация формы
     * @returns {boolean}
     */
    function validateForm() {
        const name = document.getElementById('clientName');
        const phone = document.getElementById('clientPhone');
        const nameError = document.getElementById('nameError');
        const phoneError = document.getElementById('phoneError');

        let valid = true;

        if (nameError) nameError.textContent = '';
        if (phoneError) phoneError.textContent = '';

        if (!name?.value.trim()) {
            if (nameError) nameError.textContent = 'Введите ваше имя';
            valid = false;
        }

        if (!phone?.value.trim()) {
            if (phoneError) phoneError.textContent = 'Введите номер телефона';
            valid = false;
        } else if (!isValidPhone(phone.value)) {
            if (phoneError) phoneError.textContent = 'Введите корректный номер телефона';
            valid = false;
        }

        return valid;
    }

    /**
     * Показать статус формы
     * @param {string} message - Текст сообщения
     * @param {string} type - 'success' | 'error'
     */
    function showFormStatus(message, type) {
        if (!formStatus) return;
        formStatus.textContent = message;
        formStatus.className = 'form-status ' + type;
        formStatus.style.display = 'block';
    }

    function hideFormStatus() {
        if (formStatus) {
            formStatus.textContent = '';
            formStatus.className = 'form-status';
            formStatus.style.display = 'none';
        }
    }

    /**
     * Отправка данных в CRM
     * @param {Object} data - Данные заявки
     * @returns {Promise<Object>}
     */
    async function sendToCRM(data) {
        const response = await fetch(CRM_API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            const text = await response.text();
            throw new Error(text || 'Ошибка сервера');
        }

        return response.json();
    }

    // Обработка отправки формы
    if (bookingForm) {
        bookingForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            hideFormStatus();

            if (!validateForm()) return;

            const submitBtn = document.getElementById('submitBookingBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Отправка...';
            }

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
                await sendToCRM(payload);
                showFormStatus('Заявка успешно отправлена! Мы свяжемся с вами в ближайшее время.', 'success');
                bookingForm.reset();
                setMinDates();

                setTimeout(() => {
                    closeBookingModal();
                }, 2000);
            } catch (err) {
                console.error('CRM error:', err);
                showFormStatus(
                    'Не удалось отправить заявку. Пожалуйста, позвоните нам: +7 (XXX) XXX-XX-XX',
                    'error'
                );
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Отправить заявку';
                }
            }
        });
    }

    // ========== Плавная прокрутка ==========
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ========== Стиль шапки при прокрутке ==========
    window.addEventListener('scroll', () => {
        const header = document.querySelector('.header');
        if (!header) return;

        if (window.scrollY > 100) {
            header.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
            header.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
            header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        }
    });

    // ========== Анимация при скролле ==========
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.feature, .pavilion-card, .service-card, .gallery-item').forEach(el => {
        observer.observe(el);
    });

})();
