# La Sangre Berlín — Sitio Web Oficial

Sitio web del dúo de punk rock latino acústico **La Sangre Berlín**, desarrollado como proyecto final de ciclo (TFG DAW).

**Demo:** [lasangreberlin.com](http://lasangreberlin.com)

---

## Stack

| Capa | Tecnología |
|---|---|
| Backend | Laravel 12 (PHP 8.2) |
| Frontend | Vue 3 + Vite 7 (SPA) |
| Estilos | Tailwind CSS v4 + CSS scoped |
| Auth | Laravel Sanctum (sesión SPA) |
| Base de datos | MySQL |
| i18n | vue-i18n v9 (ES / EN / DE) |
| Despliegue | Shared hosting (PiensaSolutions) vía FTP |

---

## Funcionalidades

### Sitio público
- **Home** — Hero animado, sección About, estadísticas, embed Spotify, conciertos próximos y pasados, resumen del rider técnico
- **Discografía** — Grid de álbumes con covers, embed Spotify por álbum
- **Galería** — Grid filtrable (EPK / Fotos) con lightbox
- **Tech Rider** — Rider técnico completo, channel list, stage plot, descarga de PDF
- **Contacto / Booking** — Formulario con envío por email, info de contacto
- **Legal** — Impressum, Datenschutz, Política de Cookies (trilingüe)
- **Multilingüe** — Español, Inglés y Alemán con cambio de idioma persistente

### Panel de administración (`/admin`)
- Gestión de conciertos (manual + sync desde Bandsintown)
- Gestión de discografía con subida de portadas
- Gestión de galería con subida de imágenes
- Rider técnico y PDFs descargables
- Bandeja de mensajes de contacto
- Estadísticas de visitas
- Ajustes del sitio

---

## Instalación local

### Requisitos
- PHP 8.2+
- Composer
- Node.js 20+
- MySQL

### Pasos

```bash
# 1. Clonar
git clone https://github.com/IkerHI/la-sangre-berlin.git
cd la-sangre-berlin

# 2. Dependencias PHP
composer install

# 3. Dependencias JS
npm install

# 4. Variables de entorno
cp .env.example .env
php artisan key:generate
# Edita .env con tus credenciales de base de datos

# 5. Base de datos
php artisan migrate
php artisan db:seed --class=AdminSeeder

# 6. Symlink de storage
php artisan storage:link

# 7. Compilar assets
npm run build

# 8. Servir
php artisan serve
```

El sitio estará en `http://localhost:8000`.  
Panel de administración: `http://localhost:8000/admin/login`

---

## Variables de entorno relevantes

```env
APP_URL=http://localhost

DB_DATABASE=lasangre
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

SPOTIFY_CLIENT_ID=...
SPOTIFY_CLIENT_SECRET=...
SPOTIFY_ARTIST_ID=...

BANDSINTOWN_APP_ID=...
BANDSINTOWN_ARTIST="la sangre"

ADMIN_EMAIL=admin@ejemplo.com
ADMIN_PASSWORD=contraseña_segura
```

---

## Estructura del proyecto

```
├── app/
│   ├── Http/Controllers/Api/        # Controladores REST (público + admin)
│   ├── Models/                      # Eloquent models
│   └── Services/                    # BandsintownService, SpotifyService
├── resources/
│   ├── css/app.css                  # Estilos globales + tokens de diseño
│   ├── js/
│   │   ├── components/              # AppHeader, AppFooter, AnnouncementBar
│   │   ├── i18n/                    # Traducciones ES / EN / DE
│   │   ├── pages/                   # Páginas públicas + admin
│   │   ├── router/                  # Vue Router (SPA)
│   │   └── stores/                  # Pinia (auth store)
│   └── views/                       # Blade entry point
├── routes/
│   ├── api.php                      # API REST
│   └── web.php                      # SPA catch-all
└── public/build/                    # Assets compilados por Vite
```

---

## Despliegue

El proyecto usa un script FTP (`deploy.ps1`, no incluido en el repo) para subir al servidor de shared hosting con la siguiente estructura:

```
/html/          → web root (public/)
/data/laravel/  → app root (resto del proyecto)
```

Post-deploy: visitar `/setup.php` para ejecutar migraciones, crear el symlink de storage y cachear la configuración.

---

## Autor

**Iker Díaz Herrán**  
Proyecto Final de Ciclo — Desarrollo de Aplicaciones Web (DAW)  
[lasangreberlin.com](http://lasangreberlin.com) · [contacto@lasangreberlin.com](mailto:contacto@lasangreberlin.com)
