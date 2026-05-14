# GymReservas 🏋️

> Plataforma web completa de gestión y reserva de clases para gimnasios, desarrollada como Trabajo de Fin de Grado del ciclo **DAW** (Desarrollo de Aplicaciones Web) en IES La Marisma — curso 2025/2026.


**Demo en producción:** [ieslamarisma.net/proyectos/2026/joseangelaquino/gym-reservas](https://ieslamarisma.net/proyectos/2026/joseangelaquino/gym-reservas)
**Presentación:** [https://jaqutay680.github.io/Proyecto-2DAW-GymReservas](https://jaqutay680.github.io/Proyecto-2DAW-GymReservas)

---

## Índice

1. [Descripción del proyecto](#descripción-del-proyecto)
2. [Características principales](#características-principales)
3. [Stack tecnológico](#stack-tecnológico)
4. [Requisitos del sistema](#requisitos-del-sistema)
5. [Instalación local](#instalación-local)
6. [Configuración del entorno](#configuración-del-entorno)
7. [Base de datos](#base-de-datos)
8. [Google OAuth 2.0](#google-oauth-20)
9. [Estructura del proyecto](#estructura-del-proyecto)
10. [Roles y permisos](#roles-y-permisos)
11. [Planes de suscripción](#planes-de-suscripción)
12. [API interna (rutas AJAX)](#api-interna-rutas-ajax)
13. [Despliegue en producción](#despliegue-en-producción)
14. [Problemas conocidos y soluciones](#problemas-conocidos-y-soluciones)
15. [Autor](#autor)

---

## Descripción del proyecto

**GymReservas** es una aplicación web full-stack que digitaliza la gestión de un gimnasio. Permite a los socios consultar el horario semanal de clases, hacer y cancelar reservas desde cualquier dispositivo, y gestionar su suscripción. Los administradores disponen de un panel completo para gestionar usuarios, actividades, horarios, pagos y suscripciones.

El proyecto está desplegado en hosting compartido real sobre **Nginx + PHP-FPM**, con HTTPS, dominio público y usuarios activos.

---

## Características principales

### 👤 Área de cliente
- Registro e inicio de sesión con **email/contraseña** o **Google OAuth 2.0**
- Dashboard con horario del día actual y clases disponibles
- Reserva de clases con control de aforo en tiempo real
- Filtrado de clases por **día** y **actividad** sin recargar la página (AJAX/JSON)
- Historial de reservas (activas, pasadas y canceladas)
- Gestión de suscripción: ver plan activo, cambiar plan, cancelar
- Historial de pagos
- Actualización de email y contraseña
- Restricción de edad mínima por actividad (calculada desde `birth_date`)
- Validación de **DNI español** con algoritmo mod-23

### 🛡 Panel de administración
- Dashboard con KPIs: ingresos del mes, socios activos, reservas de la semana, clases disponibles
- CRUD completo de **usuarios** (crear, editar, ver pagos, ver auditoría)
- CRUD completo de **actividades** (con edad mínima configurable)
- CRUD completo de **horarios** (asignar actividad, sala, día y franja horaria)
- Registro y generación de **pagos**
- Gestión de **suscripciones**: renovaciones y cancelaciones
- Reportes de actividad e ingresos por período

### 🔐 Autenticación
- Sistema clásico email + contraseña con Bcrypt (Laravel Breeze)
- Google OAuth 2.0 con flujo completo (nuevo usuario → completar perfil; usuario existente → login directo)
- Middleware de protección por rol: `admin`, `profile.complete`

---

## Stack tecnológico

| Capa | Tecnología |
|------|-----------|
| **Backend** | PHP 8.2, Laravel 11 |
| **Frontend** | Blade Templates, CSS puro, Vanilla JS |
| **Base de datos** | MySQL (MariaDB compatible) |
| **Autenticación** | Laravel Breeze, Laravel Socialite 5.x |
| **Iconos** | Bootstrap Icons 1.11 (CDN) |
| **HTTP Client** | GuzzleHTTP (incluido con Laravel) |
| **Servidor** | Nginx + PHP-FPM |
| **Gestor de dependencias** | Composer, NPM |

---

## Requisitos del sistema

| Requisito | Versión mínima |
|-----------|----------------|
| PHP | 8.2+ |
| Composer | 2.x |
| MySQL / MariaDB | 8.0+ / 10.4+ |
| Node.js + NPM | 18+ (solo para compilar assets) |
| Extensiones PHP | `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`, `tokenizer` |

---

## Instalación local

### 1. Clonar el repositorio

```bash
git clone https://github.com/TU_USUARIO/gym-reservas.git
cd gym-reservas/laravel
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Instalar dependencias JS y compilar assets

```bash
npm install
npm run build
```

### 4. Copiar el archivo de entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configurar la base de datos

Edita `.env` con tus credenciales MySQL y ejecuta:

```bash
php artisan migrate
```

### 6. Crear el enlace simbólico de storage

```bash
php artisan storage:link
```

### 7. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`.

---

## Configuración del entorno

Copia `.env.example` a `.env` y configura los siguientes valores:

```dotenv
# ── Aplicación ────────────────────────────────────────────
APP_NAME=GymReservas
APP_ENV=local          # Cambiar a 'production' en el servidor
APP_KEY=               # Generado con: php artisan key:generate
APP_DEBUG=true         # false en producción
APP_URL=http://localhost:8000

# ── Base de datos ─────────────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gymreservas
DB_USERNAME=root
DB_PASSWORD=

# ── Sesión ────────────────────────────────────────────────
SESSION_DRIVER=file        # 'cookie' NO recomendado en producción (límite 4096 bytes)
SESSION_LIFETIME=10080     # 7 días en minutos

# ── Google OAuth ──────────────────────────────────────────
GOOGLE_CLIENT_ID=TU_CLIENT_ID.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=TU_CLIENT_SECRET
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# ── Mail (log para desarrollo) ────────────────────────────
MAIL_MAILER=log
```

### Variables de producción adicionales

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

SESSION_DRIVER=file
SESSION_ENCRYPT=true
SESSION_DOMAIN=.tu-dominio.com

LOG_LEVEL=error

GOOGLE_REDIRECT_URI=https://tu-dominio.com/auth/google/callback
```

---

## Base de datos

### Esquema de tablas

El proyecto usa el prefijo `gym_` en todas las tablas propias para evitar conflictos en hosting compartido.

#### `gym_users`
| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id` | BIGINT PK | Identificador |
| `name` | VARCHAR(255) | Nombre completo |
| `email` | VARCHAR(255) UNIQUE | Correo electrónico |
| `password` | VARCHAR(255) | Hash Bcrypt |
| `google_id` | VARCHAR(50) NULL | ID de cuenta de Google (OAuth) |
| `role` | ENUM('admin','trainer','cliente') | Rol del usuario |
| `plan_type` | ENUM('free','basico','premium') | Plan de suscripción |
| `membership_status` | ENUM('active','inactive') | Estado del socio |
| `dni` | VARCHAR(9) NULL UNIQUE | DNI español (validado mod-23) |
| `birth_date` | DATE NULL | Fecha de nacimiento |
| `wallet_balance` | DECIMAL(10,2) | Saldo de monedero |
| `free_trial_used` | TINYINT(1) | Si ya usó el día de prueba gratuito |
| `profile_completed` | TINYINT(1) DEFAULT 1 | 0 = pendiente de completar (usuarios Google nuevos) |
| `created_at` / `updated_at` | TIMESTAMP | Auditoría |

#### `gym_activities`
| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id` | BIGINT PK | Identificador |
| `name` | VARCHAR(255) | Nombre de la actividad |
| `description` | TEXT NULL | Descripción |
| `min_age` | TINYINT DEFAULT 0 | Edad mínima para acceder |

#### `gym_schedules`
| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id` | BIGINT PK | Identificador |
| `activity_id` | BIGINT FK | Actividad asociada |
| `day_of_week` | VARCHAR(20) | Día en inglés (`monday`, `tuesday`…) |
| `start_time` | TIME | Hora de inicio |
| `end_time` | TIME | Hora de fin |
| `room` | VARCHAR(100) | Nombre de la sala |
| `capacity` | INT | Aforo máximo |

#### `gym_reservations`
| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id` | BIGINT PK | Identificador |
| `user_id` | BIGINT FK | Usuario que reserva |
| `schedule_id` | BIGINT FK | Horario reservado |
| `status` | ENUM('confirmed','cancelled') | Estado |
| `created_at` | TIMESTAMP | Fecha de reserva |

#### `gym_payments`
| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id` | BIGINT PK | Identificador |
| `user_id` | BIGINT FK | Usuario |
| `amount` | DECIMAL(8,2) | Importe en euros |
| `currency` | VARCHAR(3) DEFAULT 'EUR' | Moneda |
| `payment_date` | TIMESTAMP | Fecha del pago |
| `status` | ENUM('paid','pending','failed') | Estado |
| `plan_type` | VARCHAR(20) | Plan al que corresponde |

#### `gym_subscriptions`
| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id` | BIGINT PK | Identificador |
| `user_id` | BIGINT FK | Usuario |
| `plan_type` | VARCHAR(20) | Plan activo |
| `status` | ENUM('active','cancelled') | Estado |
| `started_at` | TIMESTAMP | Fecha de inicio |
| `next_billing_date` | DATE | Próxima fecha de cobro |

### Añadir columnas de OAuth (si migras desde versión anterior)

```sql
ALTER TABLE gym_users
  ADD COLUMN IF NOT EXISTS google_id VARCHAR(50) NULL DEFAULT NULL AFTER email,
  ADD COLUMN IF NOT EXISTS profile_completed TINYINT(1) NOT NULL DEFAULT 1 AFTER free_trial_used;
```

---

## Google OAuth 2.0

### Configuración en Google Cloud Console

1. Accede a [console.cloud.google.com](https://console.cloud.google.com)
2. Crea un proyecto o selecciona uno existente
3. Ve a **APIs & Services → Credentials**
4. Crea una credencial: **OAuth 2.0 Client ID** → tipo **Web application**
5. En **Authorized redirect URIs** añade:
   - Desarrollo: `http://localhost:8000/auth/google/callback`
   - Producción: `https://tu-dominio.com/auth/google/callback`
6. Copia el **Client ID** y el **Client Secret** a tu `.env`

### Flujo de autenticación

```
Usuario pulsa "Continuar con Google"
        ↓
GET /auth/google  →  Redirige a Google (stateless)
        ↓
Google autentica y redirige a /auth/google/callback?code=...
        ↓
[¿El email ya existe en gym_users?]
   ├─ SÍ → Vincula google_id si faltaba → Login → Dashboard
   └─ NO → Crea usuario (profile_completed = 0) → /completar-perfil
                ↓
        Usuario rellena DNI + fecha de nacimiento + plan
                ↓
        profile_completed = 1 → Dashboard
```

### Provider personalizado

El proyecto incluye `App\Socialite\GoogleProvider` que extiende el proveedor oficial para usar el endpoint actualizado de Google:

```php
// app/Socialite/GoogleProvider.php
protected function getTokenUrl(): string
{
    return 'https://oauth2.googleapis.com/token'; // en vez del deprecado /v4/token
}
```

Esto es necesario cuando el vendor instalado en el servidor tiene una versión antigua de Socialite que todavía apuntaba al endpoint `googleapis.com/oauth2/v4/token`, el cual Google rechaza con `invalid_grant`.

---

## Estructura del proyecto

```
laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php  # Login/logout estándar
│   │   │   │   ├── RegisteredUserController.php        # Registro estándar
│   │   │   │   └── GoogleController.php                # OAuth Google completo
│   │   │   ├── AdminController.php                     # Panel de administración
│   │   │   └── ReservationController.php               # Crear/cancelar reservas
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php                     # Solo rol 'admin'
│   │       └── RequireProfileComplete.php              # Perfil OAuth incompleto
│   ├── Models/
│   │   └── User.php                                    # Modelo sobre gym_users
│   ├── Providers/
│   │   └── AppServiceProvider.php                      # Registra GoogleProvider
│   └── Socialite/
│       └── GoogleProvider.php                          # Provider OAuth personalizado
│
├── bootstrap/
│   └── app.php                                         # Aliases de middleware
│
├── config/
│   └── services.php                                    # Credenciales de Google OAuth
│
├── database/
│   └── migrations/                                     # Migraciones de tablas gym_*
│
├── resources/
│   └── views/
│       ├── auth/
│       │   ├── login.blade.php                         # Login con botón Google
│       │   ├── register.blade.php                      # Registro estándar
│       │   └── complete-profile.blade.php              # Completar perfil OAuth
│       ├── dashboard.blade.php                         # Dashboard del cliente
│       ├── my-reservations.blade.php                   # Mis reservas
│       ├── my-payments.blade.php                       # Mis pagos
│       ├── my-subscriptions.blade.php                  # Mi suscripción
│       ├── admin/                                      # Vistas del panel admin
│       ├── legal/                                      # Páginas legales
│       └── partials/                                   # Componentes reutilizables
│
├── routes/
│   ├── web.php                                         # Rutas principales
│   └── auth.php                                        # Rutas de autenticación (Breeze)
│
└── .env.example                                        # Plantilla de configuración
```

---

## Roles y permisos

| Rol | Acceso | Middleware |
|-----|--------|-----------|
| `admin` | Panel de administración completo + área de cliente | `auth` + `admin` |
| `trainer` | Vista de sus propias clases y reservados | `auth` |
| `cliente` | Dashboard, reservas, suscripción, pagos | `auth` + `profile.complete` |

### Middleware personalizados

**`AdminMiddleware`** (`app/Http/Middleware/AdminMiddleware.php`)
```php
// Permite el acceso solo a usuarios con role = 'admin'
// Devuelve 403 si el usuario autenticado no es administrador
```

**`RequireProfileComplete`** (`app/Http/Middleware/RequireProfileComplete.php`)
```php
// Si profile_completed = 0, redirige a /completar-perfil
// Evita bucles de redirección excluyendo las propias rutas del perfil
```

Los alias se registran en `bootstrap/app.php`:
```php
$middleware->alias([
    'admin'            => \App\Http\Middleware\AdminMiddleware::class,
    'profile.complete' => \App\Http\Middleware\RequireProfileComplete::class,
]);
```

---

## Planes de suscripción

| Plan | Precio | Reservas por semana | Descripción |
|------|--------|---------------------|-------------|
| **Free** | 0 €/mes | 1 (día de prueba) | Acceso limitado para probar el servicio |
| **Básico** | 9,99 €/mes | 5 | Acceso habitual para socios regulares |
| **Premium** | 19,99 €/mes | Ilimitadas | Sin restricciones de reserva |

El límite se comprueba en `web.php` (ruta `/dashboard`) comparando las reservas confirmadas de la semana actual con el límite del plan:

```php
$weeklyLimit = match ($user->plan_type ?? 'free') {
    'premium' => 999,
    'basico'  => 5,
    default   => 1,
};
```

---

## API interna (rutas AJAX)

### `GET /dashboard/filters`

Devuelve el HTML de la lista de clases filtrada por día y actividad.

**Parámetros GET:**
| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `day` | string | Día de la semana en inglés (`monday`, `tuesday`…) |
| `activity` | string \| `"all"` | ID de actividad o `"all"` |

**Respuesta:**
```json
{
  "html": "<div class='class-card'>...</div>"
}
```

**Middleware:** `auth` + `profile.complete`

---

## Despliegue en producción

### Estructura en hosting compartido

El proyecto está pensado para desplegarse en un hosting compartido donde el directorio público no es la raíz de Laravel. La estructura en el servidor es:

```
/httpdocs/proyectos/2026/joseangelaquino/
├── laravel/          ← Código fuente de Laravel (NO público)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   └── .env
└── gym-reservas/     ← Directorio público accesible desde la web
    ├── index.php     ← Entry point personalizado
    └── public/       ← Assets (imágenes, JS, CSS compilado)
```

### Entry point personalizado (`gym-reservas/index.php`)

```php
<?php
if (getenv('APP_DEBUG') === 'true') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
while (ob_get_level()) ob_end_clean();

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

try {
    if (file_exists($maintenance = __DIR__ . '/../laravel/storage/framework/maintenance.php')) {
        require $maintenance;
    }
    require __DIR__ . '/../laravel/vendor/autoload.php';
    $app = require_once __DIR__ . '/../laravel/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($request = Request::capture());
    $response->send();
    $kernel->terminate($request, $response);
} catch (Throwable $e) {
    if (!headers_sent()) http_response_code(500);
    // Mostrar error solo en modo debug
}
```

### Pasos de despliegue

1. **Subir archivos** por FTP (todo excepto `vendor/` si ya está instalado en el servidor)
2. **Configurar `.env`** en el servidor con valores de producción
3. **Ejecutar migraciones** (si hay acceso SSH): `php artisan migrate --force`
4. **Limpiar caché de vistas**: borrar archivos de `storage/framework/views/`
5. **Verificar permisos** de escritura en `storage/` y `bootstrap/cache/`

### Consideraciones para hosting compartido

- **`SESSION_DRIVER=file`** en lugar de `cookie` para evitar el límite de 4096 bytes de las cookies (especialmente importante con OAuth donde la URL del callback es muy larga)
- **No ejecutar** `php artisan config:cache` o `php artisan route:cache` a menos que tengas SSH, ya que los archivos cacheados en local pueden tener rutas incorrectas para el servidor
- **Google OAuth**: usar `->stateless()` en Socialite evita problemas de `SameSite` con cookies de sesión en redirecciones cross-origin

---

## Problemas conocidos y soluciones

### 502 Bad Gateway en el callback de Google OAuth

**Causa:** Con `SESSION_DRIVER=cookie`, la URL del callback de Google (`?state=...&code=...&scope=...`) tiene ~600 caracteres. Al guardarse como `_previous.url` en la sesión encriptada, supera el límite de 4096 bytes de las cookies. Nginx devuelve 502 al recibir cabeceras demasiado grandes de PHP-FPM.

**Solución:**
```dotenv
SESSION_DRIVER=file
```

---

### `invalid_grant: Bad Request` en el intercambio de token de Google

**Causa:** La versión de Socialite instalada en el servidor usaba el endpoint deprecado `https://www.googleapis.com/oauth2/v4/token`, que Google rechaza en los nuevos proyectos OAuth.

**Solución:** Provider personalizado en `app/Socialite/GoogleProvider.php` que sobrescribe `getTokenUrl()`:
```php
protected function getTokenUrl(): string
{
    return 'https://oauth2.googleapis.com/token';
}
```

---

### `Target class [admin] does not exist`

**Causa:** El archivo `bootstrap/app.php` en el servidor era antiguo y no tenía registrados los aliases de middleware personalizados.

**Solución:** Asegurarse de que `bootstrap/app.php` contiene:
```php
$middleware->alias([
    'admin'            => \App\Http\Middleware\AdminMiddleware::class,
    'profile.complete' => \App\Http\Middleware\RequireProfileComplete::class,
]);
```

---

### `syntax error, unexpected end of file` en vistas Blade

**Causa:** El servidor tenía una versión antigua de una vista Blade con una directiva `@if` / `@foreach` sin cerrar. Laravel compila las vistas y cachea el resultado en `storage/framework/views/`.

**Solución:** Subir la versión correcta del archivo Blade y **borrar todos los archivos** de `storage/framework/views/` para forzar la recompilación.

---

### `http_response_code(): Cannot set response code - headers already sent`

**Causa:** El entry point personalizado `index.php` intentaba llamar a `http_response_code(500)` dentro del bloque `catch`, pero Laravel ya había empezado a enviar la respuesta HTTP (cabeceras enviadas).

**Solución:**
```php
// Verificar antes de intentar cambiar el código de respuesta
if (!headers_sent()) http_response_code(500);
```

---

## Autor

**José Ángel Aquino**
Ciclo Formativo de Grado Superior — Desarrollo de Aplicaciones Web (DAW)
IES La Marisma · Huelva · Curso 2025/2026

---

## Licencia

Este proyecto ha sido desarrollado con fines académicos como Trabajo de Fin de Grado.
