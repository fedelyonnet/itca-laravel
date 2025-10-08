#!/bin/bash
cd /home/fede/Desktop/itca-laravel
echo "Iniciando servidor en devitca.localhost (sin puerto)..."
echo "Necesitarás ingresar tu contraseña de sudo"
sudo php artisan serve --host=devitca.localhost --port=80
