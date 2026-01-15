# Recordatorio de Despliegue - Sistema de Colas (Emails Rápidos)

Este proyecto tiene configurado el sistema de colas para envío asíncrono de correos (LeadNotification y DebugAdfMail).
Esto permite que el usuario reciba una respuesta instantánea en la web mientras el correo se envía en segundo plano.

## Pasos para activar en Producción (Servidor)

1. **Actualizar Código y Base de Datos:**
   ```bash
   git pull origin master
   php artisan migrate  # Esto creará la tabla 'jobs' y 'failed_jobs'
   ```

2. **Configurar Entorno (.env):**
   Editar el archivo `.env` en el servidor:
   ```bash
   nano .env
   ```
   Cambiar:
   ```env
   QUEUE_CONNECTION=sync
   ```
   Por:
   ```env
   QUEUE_CONNECTION=database
   ```
   
   Luego limpiar caché:
   ```bash
   php artisan config:clear
   ```

3. **Iniciar el Worker (Prueba Temporal):**
   Para probar si funciona, ejecuta en una terminal:
   ```bash
   php artisan queue:work
   ```
   *(Deja la terminal abierta y prueba enviar un lead desde la web).*

4. **Configurar Supervisor (Permanente):**
   Para que el worker corra siempre en segundo plano (daemon), instala y configura Supervisor.

   **Instalación:**
   ```bash
   sudo apt-get install supervisor
   ```

   **Configuración:**
   Crear archivo `/etc/supervisor/conf.d/itca-worker.conf`:
   ```ini
   [program:itca-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /var/www/itca/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
   autostart=true
   autorestart=true
   stopasgroup=true
   killasgroup=true
   user=www-data
   numprocs=2
   redirect_stderr=true
   stdout_logfile=/var/www/itca/storage/logs/worker.log
   stopwaitsecs=3600
   ```

   **Activar:**
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start itca-worker:*
   ```
