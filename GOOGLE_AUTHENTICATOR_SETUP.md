# Google Authenticator Setup untuk Filament

## 📋 Overview
Implementasi Google Authenticator (2FA) pada Filament Admin Panel untuk meningkatkan keamanan login.

## 🚀 Fitur yang Tersedia

### ✅ Fitur yang Sudah Diimplementasi:
1. **Custom Login Page** dengan 2FA
2. **Halaman Setup 2FA** di profil user
3. **QR Code Generation** untuk Google Authenticator
4. **Verification System** untuk kode 2FA
5. **Enable/Disable 2FA** functionality
6. **Manual Entry Code** sebagai backup

## 📦 Dependencies yang Diinstall

```bash
composer require pragmarx/google2fa-laravel
composer require simplesoftwareio/simple-qrcode
```

## 🗄️ Database Migration

File: `database/migrations/2025_08_14_100827_add_google2fa_to_users_table.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('google2fa_secret')->nullable();
    $table->boolean('google2fa_enabled')->default(false);
    $table->timestamp('google2fa_enabled_at')->nullable();
});
```

## 🔧 Konfigurasi

### 1. Model User (`app/Models/User.php`)
- Menambahkan trait dan method untuk 2FA
- Method untuk generate secret, verify code, dan QR code
- Cast untuk boolean dan datetime fields

### 2. Custom Login Page (`app/Filament/Pages/CustomLogin.php`)
- Extend dari `Filament\Pages\Auth\Login`
- Menambahkan form 2FA verification
- Session management untuk 2FA flow

### 3. 2FA Setup Page (`app/Filament/Pages/TwoFactorSetup.php`)
- Halaman untuk enable/disable 2FA
- QR code generation dan display
- Verification process

### 4. Admin Panel Provider (`app/Providers/Filament/AdminPanelProvider.php`)
```php
->login(\App\Filament\Pages\CustomLogin::class)
->pages([
    Pages\Dashboard::class,
    \App\Filament\Pages\TwoFactorSetup::class,
])
```

## 🎨 View Files

### 1. `resources/views/filament/pages/two-factor-setup.blade.php`
- Form setup 2FA dengan QR code
- Manual entry code display
- Verification form

### 2. `resources/views/filament/pages/two-factor-enabled.blade.php`
- Status ketika 2FA sudah enabled
- Current QR code display
- Security warnings

### 3. `resources/views/filament/pages/two-factor-disabled.blade.php`
- Status ketika 2FA belum enabled
- Information tentang benefits 2FA
- Setup instructions

## 🔄 Flow Login dengan 2FA

1. **User login** dengan email dan password
2. **System check** apakah user memiliki 2FA enabled
3. **Jika 2FA enabled**: 
   - Redirect ke form 2FA verification
   - User input 6-digit code dari Google Authenticator
   - Verify code dan login jika valid
4. **Jika 2FA disabled**:
   - Login langsung tanpa verification tambahan

## 🛠️ Cara Penggunaan

### Untuk Admin/User:

1. **Login ke Filament Admin Panel**
2. **Akses halaman "Two-Factor Authentication"** di menu
3. **Klik "Enable 2FA"** untuk memulai setup
4. **Scan QR Code** dengan Google Authenticator app
5. **Input verification code** untuk mengaktifkan 2FA
6. **Login berikutnya** akan memerlukan kode 2FA

### Untuk Developer:

1. **Jalankan migration** (jika belum):
   ```bash
   php artisan migrate
   ```

2. **Clear cache** setelah perubahan:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

## 🔒 Security Features

- **Secret Key Protection**: Secret key tersimpan encrypted di database
- **Session Management**: Temporary session untuk 2FA verification
- **Validation**: Strict validation untuk 6-digit codes
- **Timeout Protection**: Session timeout untuk security
- **Manual Entry**: Backup dengan manual entry code

## 📱 Google Authenticator App

### Supported Apps:
- Google Authenticator
- Microsoft Authenticator
- Authy
- Any TOTP-compatible app

### Setup Instructions:
1. Download Google Authenticator dari App Store/Play Store
2. Open app dan tap "+" untuk add account
3. Scan QR code yang muncul di halaman setup
4. App akan generate 6-digit codes setiap 30 detik
5. Input code tersebut untuk verification

## 🚨 Troubleshooting

### Common Issues:

1. **QR Code tidak muncul**:
   - Check apakah package QR Code terinstall
   - Clear cache dan restart server

2. **Verification code tidak valid**:
   - Pastikan waktu device sync dengan server
   - Coba code yang baru generate (30 detik refresh)

3. **Login stuck di 2FA form**:
   - Clear browser session
   - Check session configuration

4. **Migration error**:
   - Pastikan database connection aktif
   - Check database permissions

### Debug Commands:
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Check migration status
php artisan migrate:status

# Regenerate autoload
composer dump-autoload
```

## 🔄 Update dan Maintenance

### Untuk update secret key:
```php
$user = auth()->user();
$newSecret = $user->generateGoogle2FASecret();
$user->update(['google2fa_secret' => $newSecret]);
```

### Untuk disable 2FA:
```php
$user = auth()->user();
$user->disableGoogle2FA();
```

## 📞 Support

Jika ada masalah atau pertanyaan:
1. Check error logs di `storage/logs/laravel.log`
2. Verify semua dependencies terinstall dengan benar
3. Pastikan database migration berhasil
4. Test dengan user baru untuk isolate masalah

## 🎯 Next Steps

### Potential Enhancements:
1. **Backup Codes**: Generate backup codes untuk recovery
2. **Email Verification**: Email notification untuk 2FA changes
3. **Admin Override**: Admin bisa disable 2FA untuk user
4. **Audit Log**: Log semua 2FA activities
5. **Rate Limiting**: Prevent brute force attacks
6. **Remember Device**: Option untuk remember device untuk 30 hari

---

**Status**: ✅ Implemented and Ready for Production
**Last Updated**: August 14, 2025
**Version**: 1.0.0 