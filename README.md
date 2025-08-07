### Requirement
- PHP >= 8.2
- composer
- Mysql

---

### Installation
1. Clone repository.
```bash
git clone https://github.com/pram212/eminence-test.git
```
2. setelah clone buka terminal dan masuk ke direktori projekproject directory.
```bash
cd transjaya-test
```
3. install dependencies
```bash 
composer install
```
- buat file .env
```bash
cp .env.example .env
``` 
- generate key application
```bash 
php artisan key:generate
```
- sesuaikan kredensial database anda di file .env
- migrasi database dengan data dummy
```bash
php artisan migrate --seed
```
```bash 
php artisan optimize:clear
```
- jalankan aplikasi
```bash 
php artisan serve
```
- buka http://loclahost:8000 di browser

---

### Credentials
- email : admin@example.com
- password : password

---

### Features
#### 1 Master Kategori COA
#### 2 Mater Chart of Account
#### 3 Transaksi
#### 4 Laporan Profit/Loss
#### 5 Export laporan Profit/Loss
