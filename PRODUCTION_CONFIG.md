# 🚀 PRODUCTION CONFIGURATION GUIDE
# Konfigurasi untuk Deploy ke Hosting dengan Domain raphnesia.my.id

## 📁 File .env untuk Production

Buat file `.env` baru di hosting dengan konfigurasi berikut:

```env
APP_NAME="SMP Al Kautsar API"
APP_ENV=production
APP_KEY=base64:your-app-key-here
APP_DEBUG=false
APP_URL=https://api.raphnesia.my.id

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=raphnesi_smpalk
DB_USERNAME=raphnesi_smpalk
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Livewire Configuration
LIVEWIRE_UPLOAD_DISK=local

# CORS Configuration
CORS_ALLOWED_ORIGINS=https://raphnesia.my.id,http://localhost:3000
```

## 🔑 Generate App Key

Setelah upload ke hosting, jalankan:

```bash
php artisan key:generate
```

## 🌐 CORS Configuration

File `config/cors.php` sudah diupdate untuk allow:
- `http://localhost:3000` (Development)
- `https://raphnesia.my.id` (Production)
- `https://*.vercel.app` (Vercel preview)

## 📱 Next.js Frontend Configuration

### Environment Variables

**Development (.env.local):**
```env
NEXT_PUBLIC_API_BASE_URL=http://localhost:8000/api/v1
```

**Production (.env.production):**
```env
NEXT_PUBLIC_API_BASE_URL=https://api.raphnesia.my.id/api/v1
```

### API Utils

Buat file `lib/api.js` di Next.js:

```javascript
const getApiBaseUrl = () => {
    if (typeof window !== 'undefined') {
        // Client-side
        return process.env.NEXT_PUBLIC_API_BASE_URL || 'http://localhost:8000/api/v1';
    }
    // Server-side
    return process.env.API_BASE_URL || 'http://localhost:8000/api/v1';
};

export const API_BASE_URL = getApiBaseUrl();

export const fetchProfilSettings = async () => {
    try {
        const response = await fetch(`${API_BASE_URL}/profil/settings`);
        const data = await response.json();
        
        if (data.success) {
            return data.data;
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error fetching profil settings:', error);
        return null;
    }
};

export const fetchInfoPPDB = async () => {
    try {
        const response = await fetch(`${API_BASE_URL}/info-ppdb/settings`);
        const data = await response.json();
        
        if (data.success) {
            return data.data;
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error fetching PPDB settings:', error);
        return null;
    }
};
```

## 🚀 Deployment Checklist

### Laravel Backend
- [ ] Upload semua file Laravel ke hosting
- [ ] Set `.env` dengan konfigurasi production
- [ ] Generate `APP_KEY` baru
- [ ] Set database credentials
- [ ] Run `php artisan migrate`
- [ ] Run `php artisan storage:link`
- [ ] Set file permissions (storage, bootstrap/cache)
- [ ] Test API endpoints

### Next.js Frontend
- [ ] Update environment variables
- [ ] Update API utils
- [ ] Deploy ke Vercel
- [ ] Set custom domain `raphnesia.my.id`
- [ ] Test API connection

## 🔍 Testing

### Test API Endpoints
```bash
# Test dari local
curl http://localhost:8000/api/v1/profil/settings

# Test dari production
curl https://api.raphnesia.my.id/api/v1/profil/settings
```

### Test CORS
```javascript
// Test dari browser console
fetch('https://api.raphnesia.my.id/api/v1/profil/settings')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('CORS Error:', error));
```

## 📞 Support

Jika ada error, cek:
1. Laravel logs: `storage/logs/laravel.log`
2. Web server error logs
3. Database connection
4. File permissions
5. CORS configuration

