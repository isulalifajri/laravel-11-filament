<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Belajar menggunakna laravel filament pada laravel 11

https://filamentphp.com/docs/3.x/panels/installation

jalankan ini: 
composer require filament/filament:"^3.3" -W
php artisan filament:install --panels

idnya ->  admin

akses filament : 
http://laravel-11-filament.test/admin/login

php artisan make:filament-user -> membuat user untuk login, kemudian isi sesuai dg yang kita inginkan dan login dengan email dan pwd yang sudah kita buat

jalankan: php artisan migrate:fresh --seed

php artisan make:filament-resource Customer(ini bisa di ubah) -> membuat crud pada filament

kalau ada error di intelephense, 
tekan ini: ctrl+shift+p, ini menggunakn vs code ya dan windows
kemudian ketik ini: Intelephense: Index workspace -> enter

jangan lupa samakan url di env dan url di browser,

jika ada image jangan lupa jalankan: php artisan storage:link

http://laravel-11-filament.test/admin/facilities
ini kan url admin nya otomatis dari filament, nah mau mengubah menjadi seperti ini
http://laravel-11-filament.test/backend/facilities buka file adminpanelprovider dan ubah path nya menjadi backend

*Relationship manager

php artisan make:filament-relation-manager CategoryResource posts title

di CategoryResource pada fucntion getRelation tambahkan in : PostRelationManager::class

copy isi form pada postResource dan salin ke postsRelationManager -> hapus category_id 

php artisan migrate:refresh --step=1 //artinya merefresh tabel yang terakhir ditambahkan //rollback 1 step yang terbaru

#Authorization

php artisan make:policy CategoryPolicy --model=Category

#navigation Grub// atau membuat submenu

protected static ?string $navigationGroup = namagGroup; -> ini di copy ke resource yang ingin dimasukkan kedalam groupnya. misalkan post resource dan category resource. copy code itu di post dan category resource tepat di bawah navagation icon.

protected static ?int $navigationSort = null; -> mengatur urutan menu

contoh jika di post resource gini -> protected static ?int $navigationSort = 1; -> maka menunya akan paling atas

#statsWidget

php artisan make:filament-widget TestWidget

kemudian pilih stats overview-> enter -> jika suruh pilih resource tekan enter dan pilih admin panel
itu nanti akan muncul di dashboard.
kemudian pada folder filament->widgets file teswidget, tambahkan ini di return nya : 
Stat::make('New Users', User::count())
->description('New users that have joined')   -> nanti akan memunculkan jumlah usernya di dashboard

kemudian pada adminpanelprovider, di widgets, tambahkan ini : TestWidget::class,

#Charts

php artisan make:filament-widget TestChartWidget
choose -> chart -> enter -> enter lagi -> admin panel -> line chart

composer require flowframe/laravel-trend

jangan lupa ini di aktifkan di php.ini

extension=intl
