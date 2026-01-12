@echo off
echo Limpiando cache de Laravel...
php -d extension=pdo_mysql -d extension=mbstring -d extension=openssl -d extension=fileinfo -d extension=curl artisan optimize:clear
echo.
echo Cache limpiado exitosamente!
