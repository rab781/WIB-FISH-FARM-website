<div align="center">
  <img src="public/Images/Logo_WIB_FISH_FARM.png" alt="WIB Fish Farm Logo" width="120" height="120">
  
  # 🐟 WIB FISH FARM
  ### Platform E-Commerce Ikan Hias Premium
  
  [![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
  [![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC34A?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)
  
  <p align="center">
    <strong>Platform e-commerce modern untuk penjualan ikan hias koi dan koki berkualitas tinggi</strong>
  </p>
  
  [🌟 Demo](https://your-demo-url.com) • [📖 Documentation](#dokumentasi) • [🚀 Quick Start](#instalasi) • [🔧 API](#api-reference)
</div>

---

## 🎯 Tentang Proyek

**WIB Fish Farm** adalah platform e-commerce yang dikembangkan khusus untuk mempermudah penjualan dan pembelian ikan hias premium. Berlokasi di Jalan Danau Toba, Kecamatan Sumbersari, Kabupaten Jember, kami menyediakan koleksi ikan koi dan koki berkualitas tinggi yang telah teruji dan bahkan diikutsertakan dalam berbagai kontes.

### 🏆 Keunggulan Platform
- 🎨 **UI/UX Modern** - Interface yang intuitif dan responsif
- 🔒 **Keamanan Tinggi** - Sistem autentikasi dan autorisasi yang robust
- 📊 **Analytics Lengkap** - Dashboard admin dengan laporan real-time
- 🚚 **Integrasi Ongkir** - Terintegrasi dengan RajaOngkir untuk perhitungan ongkos kirim
- 📱 **Mobile Friendly** - Optimized untuk semua perangkat
- ⚡ **Performance** - Optimasi kecepatan loading dan SEO

---

## ✨ Fitur Utama

<table>
<tr>
<td width="50%">

### 🛍️ **Customer Features**
- 🔍 **Katalog Produk** - Browse ikan koi & koki dengan filter canggih
- 🛒 **Shopping Cart** - Keranjang belanja dengan update real-time  
- 💳 **Checkout System** - Proses pemesanan yang mudah dan aman
- 📦 **Order Tracking** - Lacak status pesanan secara real-time
- ⭐ **Review & Rating** - Sistem ulasan dan rating produk
- 🔔 **Notifications** - Notifikasi status pesanan dan promo
- 📱 **Responsive Design** - Akses optimal di semua perangkat
- 🔄 **Return System** - Sistem pengembalian produk

</td>
<td width="50%">

### 👨‍💼 **Admin Features**
- 📊 **Dashboard Analytics** - Statistik penjualan dan performa
- 🐟 **Product Management** - CRUD produk dengan manajemen stok
- 👥 **User Management** - Kelola pelanggan dan admin
- 📋 **Order Management** - Proses dan kelola pesanan
- 💰 **Financial Reports** - Laporan keuangan dan export Excel
- 📈 **Sales Analytics** - Analisis penjualan dan produk terlaris
- 💸 **Expense Tracking** - Manajemen pengeluaran dan biaya
- 🔧 **Diagnostic Tools** - Tools untuk troubleshooting system

</td>
</tr>
</table>

### 💎 **Advanced Features**
- 🤖 **Automated Order Processing** - Otomasi proses pesanan
- 📧 **Email Notifications** - Sistem notifikasi email otomatis
- 🔄 **Stock Management** - Auto-update stok setelah pembelian
- 📊 **Export Reports** - Export laporan ke Excel
- 🛡️ **Payment Verification** - Verifikasi pembayaran otomatis
- 🌐 **Multi-language Support** - Dukungan bahasa Indonesia

---

## 🚀 Instalasi

### 📋 Prerequisites
Pastikan sistem Anda memiliki:
- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.0
- **MySQL** >= 8.0
- **Git**

### ⚡ Quick Start

```bash
# 1. Clone repository
git clone https://github.com/rab781/WIB-FISH-FARM-website.git
cd WIB-FISH-FARM-website

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Configure database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wib_fish_farm
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 6. Run migrations
php artisan migrate

# 7. Seed database (optional)
php artisan db:seed

# 8. Build assets
npm run build

# 9. Start development server
php artisan serve
```

### 🔧 Konfigurasi Tambahan

#### RajaOngkir API Setup
```env
RAJA_ONGKIR_API_KEY=your_raja_ongkir_api_key
```

#### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="no-reply@wibfishfarm.com"
MAIL_FROM_NAME="WIB Fish Farm"
```

---

## 🏗️ Struktur Proyek

```
WIB-FISH-FARM-website/
├── 📁 app/
│   ├── 📁 Console/Commands/     # Artisan commands
│   ├── 📁 Http/Controllers/     # Controllers
│   │   ├── 📁 Admin/           # Admin controllers
│   │   └── ...                 # Customer controllers
│   ├── 📁 Models/              # Eloquent models
│   └── 📁 Services/            # Business logic services
├── 📁 resources/
│   ├── 📁 views/               # Blade templates
│   │   ├── 📁 admin/          # Admin views
│   │   ├── 📁 customer/       # Customer views
│   │   └── 📁 layouts/        # Layout templates
│   ├── 📁 css/                # Stylesheets
│   └── 📁 js/                 # JavaScript files
├── 📁 public/
│   ├── 📁 Images/             # Static images
│   ├── 📁 css/                # Compiled CSS
│   └── 📁 uploads/            # User uploads
├── 📁 database/
│   ├── 📁 migrations/         # Database migrations
│   └── 📁 seeders/            # Database seeders
└── 📁 routes/                 # Route definitions
```

---

## 🛠️ Teknologi Stack

<div align="center">

### Backend
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)

### Frontend
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC34A?style=flat-square&logo=alpine.js&logoColor=white)
![Blade](https://img.shields.io/badge/Blade-FF2D20?style=flat-square&logo=laravel&logoColor=white)

### Tools & Services
![RajaOngkir](https://img.shields.io/badge/RajaOngkir-007ACC?style=flat-square)
![SweetAlert2](https://img.shields.io/badge/SweetAlert2-7066E0?style=flat-square)
![AOS](https://img.shields.io/badge/AOS-FF6B6B?style=flat-square)

</div>

---

## 🎯 API Reference

### Authentication Endpoints
```http
POST /api/login          # User login
POST /api/register       # User registration  
POST /api/logout         # User logout
```

### Product Endpoints
```http
GET    /api/products           # Get all products
GET    /api/products/{id}      # Get specific product
POST   /api/products           # Create product (Admin)
PUT    /api/products/{id}      # Update product (Admin)
DELETE /api/products/{id}      # Delete product (Admin)
```

### Order Endpoints
```http
GET  /api/orders              # Get user orders
POST /api/orders              # Create new order
GET  /api/orders/{id}         # Get specific order
PUT  /api/orders/{id}/status  # Update order status (Admin)
```

### Utility Endpoints
```http
GET /api/ongkir/destinations  # Get shipping destinations
GET /api/cart/count          # Get cart item count
```

---

## 📊 Database Schema

### Tabel Utama
- `users` - Data pengguna dan admin
- `produk` - Katalog produk ikan hias
- `pesanan` - Data pesanan pelanggan
- `detail_pesanan` - Detail item pesanan
- `keranjang` - Shopping cart items
- `ulasan` - Review dan rating produk
- `expenses` - Data pengeluaran bisnis
- `notifications` - Sistem notifikasi

---

## 🔧 Command Line Tools

```bash
# Check expired orders dan cancel otomatis
php artisan app:check-expired-orders

# Generate laporan bulanan
php artisan report:generate monthly

# Backup database
php artisan backup:run

# Clear application cache
php artisan optimize:clear
```

---

## 📱 Screenshots

<div align="center">
  <img src="docs/screenshots/homepage.png" alt="Homepage" width="45%">
  <img src="docs/screenshots/product-catalog.png" alt="Product Catalog" width="45%">
  <img src="docs/screenshots/admin-dashboard.png" alt="Admin Dashboard" width="45%">
  <img src="docs/screenshots/order-tracking.png" alt="Order Tracking" width="45%">
</div>

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

---

## 🚀 Deployment

### Production Setup
```bash
# Optimize untuk production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### Environment Variables untuk Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your_production_host

# Mail
MAIL_MAILER=smtp
# ... other production configs
```

---

## 🤝 Contributing

Kami menyambut kontribusi dari developer lain! Berikut cara berkontribusi:

1. **Fork** repository ini
2. **Clone** fork Anda: `git clone https://github.com/yourusername/WIB-FISH-FARM-website.git`
3. **Create branch** baru: `git checkout -b feature/amazing-feature`
4. **Commit** changes: `git commit -m 'Add amazing feature'`
5. **Push** to branch: `git push origin feature/amazing-feature`
6. **Open Pull Request**

### 📝 Coding Standards
- Ikuti PSR-12 coding standards
- Gunakan meaningful commit messages
- Tambahkan tests untuk fitur baru
- Update documentation jika diperlukan

---

## 📞 Support & Contact

<div align="center">

### 🏢 WIB Fish Farm
📍 **Alamat:** Jalan Danau Toba, Kecamatan Sumbersari, Kabupaten Jember  
📧 **Email:** info@wibfishfarm.com  
📱 **Instagram:** [@wibfishfarm](https://www.instagram.com/wibfishfarm/)  
🌐 **Website:** [wibfishfarm.com](https://wibfishfarm.com)

### 👨‍💻 Developer
**GitHub:** [@rab781](https://github.com/rab781)

</div>

---

## 📄 License

Project ini dilisensikan under **MIT License** - lihat file [LICENSE](LICENSE) untuk detail.

---

## 🙏 Acknowledgments

- **Laravel Community** - Framework yang luar biasa
- **Tailwind CSS** - Utility-first CSS framework  
- **RajaOngkir** - Shipping cost calculation API
- **SweetAlert2** - Beautiful alert dialogs
- **AOS Library** - Animate on scroll library
- **Font Awesome** - Icon library

---

<div align="center">
  <sub>Built with ❤️ by WIB Fish Farm Team</sub>
  <br>
  <sub>© 2024 WIB Fish Farm. All rights reserved.</sub>
</div>
