# vrn-ehk.ru — документация проекта

## О проекте

Сайт на **1С-Битрикс** — интернет-магазин «Элементы художественной ковки» (ЭХК), Воронеж.  
Каталог кованых элементов, готовых изделий, прайс-лист, фотогалерея.

| Параметр | Значение |
|----------|----------|
| CMS | 1С-Битрикс |
| Шаблон | `bitrix/templates/main/` |
| Кастомный код | `bitrix/php_interface/`, `ajax/`, `catalog/`, `avito_photo/` |
| Медиа товаров | `upload/` (iblock, medialibrary, resize_cache) |
| Локальный URL | **http://localhost:8089/** |
| Локальная БД | `vrn_ehk_local` @ `127.0.0.1` |
| Prod БД | `kovka_bd` |
| Git | [github.com/bziksv/vrn-ehk](https://github.com/bziksv/vrn-ehk) |
| Prod | [https://vrn-ehk.ru/](https://vrn-ehk.ru/) |
| Dev (клиентский стенд) | [https://dev.vrn-ehk.ru/](https://dev.vrn-ehk.ru/) |

### Бизнес-особенности

- Каталог элементов ковки и готовых изделий (опт и розница)
- Обмен с **1С** (модуль `askaron.pro1c`)
- Экспорт на **Avito** (`avito_photo/`)
- Доставка по всей России
- Личный кабинет, корзина, оформление заказа

---

## Сервер и окружения

### Главное правило деплоя

> **Выкат только через Git.**  
> Локально → commit/push в GitHub → `git pull` на нужном стенде.  
> **Запрещено** заливать отдельные файлы через scp/sftp/rsync.

| Окружение | Домен | Путь на сервере | БД | IP |
|-----------|-------|-----------------|----|-----|
| **Prod** | vrn-ehk.ru | `/var/www/vrn-ehk.ru/data/www/vrn-ehk.ru` | `kovka_bd` | 62.109.11.114 |
| **Dev** | dev.vrn-ehk.ru | `/var/www/vrn-ehk.ru/data/www/dev.vrn-ehk.ru` | та же `kovka_bd` | 62.109.11.114 |
| **Local** | localhost:8089 | `vrn-ehk.ru/` (папка сайта в репозитории) | `vrn_ehk_local` | Mac |

**Dev** — стенд для показа клиенту. Код отдельный (свой document root), **база общая с prod**. Пользователи, заказы, опции модулей и UF на dev — те же данные, что на боевом сайте.

**Workflow:** локально → push в Git → `git pull` на **dev** (для клиента) или на **prod** (только по явной просьбе) → очистка кеша Битрикс.

Gitignored-файлы на dev (конфиги БД, лицензия, `upload/`) берутся с prod: `dbconn.php`, `.settings.php`, `license_key.php`; каталог `upload/` — симлинк на prod, потому что медиа общее.

Пока в FastPanel не выпущен SSL на поддомен, **https://dev.vrn-ehk.ru** попадает на сертификат prod. Стенд открывать по **http://dev.vrn-ehk.ru/** (редирект на HTTPS для этого хоста отключён в `.htaccess`). После выпуска сертификата в FastPanel можно вернуть HTTPS.

### Деплой на dev

```bash
# На Mac: в папке сайта (корень git-репозитория)
cd /Users/stanislav/Documents/projects/vrn-ehk/vrn-ehk.ru
git add .
git commit -m "описание изменений"
git push origin main

# На сервере — только dev, не prod
ssh vrn-ehk
cd /var/www/vrn-ehk.ru/data/www/dev.vrn-ehk.ru
git pull origin main
rm -rf bitrix/cache/* bitrix/managed_cache/*
```

### Первичная установка git на dev

FastPanel создаёт пустой каталог со stub `index.php`. Один раз:

```bash
PROD=/var/www/vrn-ehk.ru/data/www/vrn-ehk.ru
DEV=/var/www/vrn-ehk.ru/data/www/dev.vrn-ehk.ru
cd "$DEV"
mv -f index.php /tmp/dev-vrn-ehk-fastpanel-index.php
git clone -b main https://github.com/bziksv/vrn-ehk.git .
cp "$PROD/bitrix/.settings.php" bitrix/
cp "$PROD/bitrix/license_key.php" bitrix/
cp "$PROD/bitrix/php_interface/dbconn.php" bitrix/php_interface/
ln -sfn "$PROD/upload" upload
mkdir -p bitrix/cache bitrix/managed_cache bitrix/stack_cache
chown -R vrn-ehk.ru:vrn-ehk.ru "$DEV"
```

Дальше только `git pull origin main` в этом каталоге.

### Деплой на prod

Только если явно попросили выкатить на боевой.

```bash
ssh vrn-ehk
cd /var/www/vrn-ehk.ru/data/www/vrn-ehk.ru
git pull origin main
rm -rf bitrix/cache/* bitrix/managed_cache/*
```

### SSH-доступ (без пароля)

Ключ на Mac уже есть: `~/.ssh/id_ed25519`. На сервере его пока нет — нужен **один раз** ввести пароль root:

```bash
ssh-copy-id -i ~/.ssh/id_ed25519.pub root@62.109.11.114
```

Проверка:

```bash
ssh vrn-ehk          # alias из ~/.ssh/config
ssh root@62.109.11.114
```

Запись в `~/.ssh/config` (уже добавлена):

```
Host vrn-ehk
    HostName 62.109.11.114
    User root
    IdentityFile ~/.ssh/id_ed25519
    IdentitiesOnly yes
```

Публичный ключ (если копируешь вручную в `/root/.ssh/authorized_keys`):

```
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIGYLkfOkq1DFP6vfJft/JT/4+U3ZM5lsrMLuHqtYSvKV stanislav-almamed-github
```

---

## Структура репозитория

**Git-репозиторий = только папка сайта** `vrn-ehk.ru/`, не родительская директория.

```
/Users/stanislav/Documents/projects/vrn-ehk/     ← рабочая папка (НЕ в git)
├── kovka_bd.sql.gz                              ← дамп БД (НЕ в git)
├── vrn-ehk.ru.tar.gz                            ← архив сайта (НЕ в git)
└── vrn-ehk.ru/                                  ← КОРЕНЬ GIT-РЕПОЗИТОРИЯ
    ├── bitrix/templates/main/                   — шаблон сайта
    ├── bitrix/php_interface/                    — init.php, dbconn, хуки
    ├── ajax/                                    — AJAX-обработчики
    ├── catalog/                                 — каталог
    ├── avito_photo/                             — экспорт Avito
    ├── scripts/                                 — локальный dev
    ├── docs/                                    — документация
    └── .local/                                  — nginx/php-fpm (gitignored)
```

---

## Архитектура

```
Пользователь
    ↓
bitrix/templates/main/      (header, footer, CSS, JS)
    ↓
catalog/index.php           (bitrix:catalog)
ajax/*.php                  (корзина, формы, отзывы)
    ↓
bitrix/php_interface/init.php
    └── класс My, хуки, вспомогательные функции
```

Ключевые директории:

| Путь | Назначение |
|------|------------|
| `bitrix/templates/main/` | Шаблон сайта, компоненты |
| `bitrix/php_interface/init.php` | Инициализация, класс `My`, хуки |
| `ajax/` | feedback, basket, reviews, forms |
| `catalog/` | Точка входа каталога |
| `avito_photo/` | Генерация фото для Avito |

---

## Локальная разработка

Без Docker. Homebrew **nginx** + **PHP 8.3 FPM**.  
Настройки снижены по памяти: 4 php-fpm worker'а, opcache 64 MB.

```bash
cd /Users/stanislav/Documents/projects/vrn-ehk/vrn-ehk.ru

./scripts/setup-local-db.sh           # импорт kovka_bd.sql.gz (один раз)
./scripts/setup-local-db.sh --force     # пересоздать БД из дампа
./scripts/setup-local-db.sh --background  # импорт в фоне (~5–15 мин)

./scripts/start-dev.sh                  # nginx :8089 + php-fpm :9089
./scripts/stop-dev.sh                   # остановка
```

| Параметр | Значение |
|----------|----------|
| URL | http://localhost:8089/ |
| Nginx | `.local/nginx/nginx.conf` → `.local/run/nginx.conf` |
| PHP-FPM | `.local/php/fpm.conf`, `pools.conf` (порт 9089) |
| Учётные данные БД | `.local/db.env` (gitignored) |
| Локальный dbconn | `bitrix/php_interface/dbconn.local.php` (gitignored) |
| Дамп БД | `../kovka_bd.sql.gz` (вне репозитория) |

При первом запуске `start-dev.sh` вызывает `apply-local-db-config.sh`, если `dbconn.local.php` отсутствует.

### Порты соседних проектов на Mac

| Проект | Порт |
|--------|------|
| almamed | 8080 |
| vilmed | 8082 |
| kosmamed | 8083 |
| polimer | 8084 |
| metplus-vrn.ru | 8086 |
| metprof | 8087 |
| oftalmag | 8088 |
| **vrn-ehk.ru** | **8089** |

---

## Git

**Репозиторий:** [github.com/bziksv/vrn-ehk](https://github.com/bziksv/vrn-ehk)  
Корень репозитория — папка `vrn-ehk.ru/`, не `/Users/stanislav/Documents/projects/vrn-ehk/`.

### Что НЕ коммитить

Секреты и тяжёлые данные исключены в `.gitignore`:

- `bitrix/.settings.php` — пароль БД
- `bitrix/php_interface/dbconn.php`, `dbconn.local.php` — пароль БД
- `bitrix/license_key.php`
- `.local/` — локальный dev и бэкапы конфигов
- `upload/`, кеш Битрикс
- дампы и архивы (`*.sql`, `*.sql.gz`, `*.tar.gz`)
- лог 1С-обмена `log_askaron_pro1c__*.txt`

Примеры без секретов: `bitrix/.settings.example.php`, `bitrix/php_interface/dbconn.example.php`.

### Первичная настройка git (если remote пустой)

```bash
cd /Users/stanislav/Documents/projects/vrn-ehk/vrn-ehk.ru
git remote add origin https://github.com/bziksv/vrn-ehk.git
git branch -M main
git push -u origin main
```

---

## Контакты на сайте

| | |
|---|---|
| Телефон | 8 800 755-07-76 |
| Email | info@vrn-ehk.ru |
| Часы работы | пн–пт 8:00–17:00, сб 8:30–15:30 |

---

## Журнал изменений

| Дата | Что сделано |
|------|-------------|
| 2026-08-18 | Стенд **dev.vrn-ehk.ru**: путь `/var/www/vrn-ehk.ru/data/www/dev.vrn-ehk.ru`, общая БД с prod |
| 2026-07-15 | Инициализация git-workflow, `.gitignore`, docs, локальный dev :8089, импорт `kovka_bd` |

### Шаблон записи

```markdown
### YYYY-MM-DD — краткое описание
- **Проблема:** ...
- **Решение:** ...
- **Файлы:** path/to/file.php
```
