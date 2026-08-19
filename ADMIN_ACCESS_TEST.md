# Test Admin Access Control

## Cara testing:

1. **Login sebagai user biasa (role: 'user')**:
   - Coba akses `/dashboard`
   - Seharusnya diredirect ke halaman home dengan pesan error

2. **Login sebagai admin (role: 'admin')**:
   - Coba akses `/dashboard`
   - Seharusnya bisa mengakses dashboard normal
   - Coba akses `/admin/users`
   - Coba akses `/admin/analytics`

## Fitur yang sudah diimplementasi:

✅ **AdminMiddleware**: Middleware khusus untuk memblokir akses non-admin
✅ **Route Protection**: Semua route admin dilindungi middleware 'admin'
✅ **Controller Security**: DashboardController hanya untuk admin
✅ **View Security**: Dashboard view memiliki double-check role
✅ **Flash Messages**: Error message muncul di halaman home jika akses ditolak
✅ **User Experience**: Notifikasi yang user-friendly

## Routes yang dilindungi:
- `/dashboard` - Dashboard utama (middleware: auth, verified, admin)
- `/admin/users` - Management users (middleware: auth, admin)
- `/admin/analytics` - Analytics dashboard (middleware: auth, admin)
- `/admin` - Admin dashboard alternatif (middleware: auth, admin)

## Cara membuat user admin:
```sql
UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
```

Atau via seeder/factory jika perlu.
