# Тема WordPress «Пикник ZonE»

Тема для сайта аренды беседок с поддержкой Custom Post Type «Беседки».

## Установка

1. Скопируйте папку `picnic-zone` в `wp-content/themes/`
2. Активируйте тему в админ-панели WordPress
3. Настройте: Внешний вид → Настроить → Статическая страница — выберите страницу для главной (или оставьте «Последние записи»)

## Структура

- `archive-gazebos.php` — архив беседок
- `single-gazebos.php` — страница одной беседки
- `front-page.php` — главная (лендинг)
- `inc/cpt-gazebos.php` — регистрация CPT и метаполей

## Метаполя беседки

- **Площадь** (м²)
- **Вместимость** (человек)
- **Цена** (руб./сутки)
- **Удобства** (чекбоксы)

## CRM API

В `functions.php` задан URL: `home_url('/api/crm-lead')`. Для работы нужен эндпоинт, принимающий POST с JSON:

```json
{
  "id_besedki": "123",
  "nazvanie_besedki": "Беседка №1",
  "data_nachala": "2026-03-15",
  "data_okonchaniya": "2026-03-16",
  "vremya_nachala": "10:00",
  "vremya_okonchaniya": "22:00",
  "imya_klienta": "Иван",
  "telefon_klienta": "79991234567"
}
```

## Изображения

Добавьте в `assets/img/`:
- `bg.jpg` — фон героя
- `placeholder.jpg` — заглушка для беседок без фото
