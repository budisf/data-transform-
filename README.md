
## Pengenalan

System ini merupakan system untuk melakukan transaksi pembelian barang. Terdapat dua user role di system tersebut yaitu customer dan admin. Pada kesempatan ini saya berfokus pada pengembangan di sisi customer. Untuk Create product saya telah buatkan seeder yang bisa dijalankan di terminal laravel. Untuk menjalankan sistem ini beberapa tool yang harus dipersiapkan yaitu :

- docker
- laradock
- postman
- windows terminal
- wsl 2 

Silahkan kunjungi link berikut untuk link image docker 

https://hub.docker.com/r/budisf/dbotest/tags

<b>Atau</b>

Silahkan start docker dan buat folder dengan nama docker terus jalankan di command prompt.

git clone https://github.com/Laradock/laradock.git

Jika sudah silahkan masuk ke dalam folder laradock terus lakukan command berikut untuk linux. Kita copy file env-example untuk jadi environment filenya.

cp env-example .env

kemudian masuk ke path

cd laradock/nginx/sites
ls -l

kemudian lalu ketik perintah berikut untuk copy file laravel.conf.example. Sesuaikan namanya dengan nama proyek anda.

cp laravel.conf.example dborucika.conf

setelah itu edit file dborucika.conf dengan cara

nano dborucika.conf

Silahkan ubah dibagian ini.

server_name laravel.test;
root /var/www/laravel/public;

menjadi

server_name dborucika.test;
root /var/www/dborucika/public;

Setelah semuanya sudah dilakukan maka saatnya kita menginstall nginx, mysql, phpmyadmn dan workspace di container kita

sudo docker-compose up -d nginx mysql phpmyadmin redis workspace

setelah selesai

kita masuk ke container kita 

sudo docker-compose exec --user=laradock workspace bash

kemudian clone repository dengan perintah

git clone https://github.com/budisf/dborucika.git

Setelah selesai masuk ke folder dborucika dengan perintah cd dborucika untuk menatur environtment laravel

kemudian ketik 

code . 

untuk membuka text editor visual studio, kemudian copy .env-example menjadi .env. lalu ubah 

APP_URL=http://testdbo.test atau APP_URL=http://localhost

menjadi APP_URL=http://dborucika.test sesuaikan dengan configurasi di nginx yang telah kita atur di file dborucika.conf sebelumnya.

lalu sesuaikan pengaturan mysql 

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

menjadi 

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=default
DB_USERNAME=default
DB_PASSWORD=secret

sesuaikan dengan configurasi mysql di file docker-compase.yml dan .env yang berada di folder laradock. Kemudian buka 

C:\windows\system32\drivers\etc\hosts

untuk windows 

cd /etc/
sudo nano hosts

untuk windows 

dan tambahkan 127.0.0.1 dborucika.test

jika sudah kembali ke folder laradock dan jalankan perintah berikut untuk restart nginx.

docker-compose restart nginx

setelah selesai, buka browsur untuk mengecek apakah laravel sudah berjalan

http://dborucika.test

jika berjalan lancar buka postman untuk menguji API.

Berikut link dokumentasi API nya

https://documenter.getpostman.com/view/7342285/UVCCdiWo


Terimakasih 






