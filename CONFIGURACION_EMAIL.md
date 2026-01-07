# Configuración de Email para Notificaciones de Inscripciones

## Variables de Entorno Necesarias

Agrega las siguientes variables a tu archivo `.env`:

### Para Desarrollo Local (Mailtrap recomendado)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@itca.com.ar
MAIL_FROM_NAME="${APP_NAME}"
MAIL_TO_ADMIN=federico.lyonnet@gmail.com
```

### Para Producción (Gmail como ejemplo)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_app_password_gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@itca.com.ar
MAIL_FROM_NAME="ITCA"
MAIL_TO_ADMIN=federico.lyonnet@gmail.com
```

## Pasos para Configurar

### 1. Desarrollo Local con Mailtrap

1. Crea una cuenta gratuita en [Mailtrap.io](https://mailtrap.io)
2. Ve a "Inboxes" > "SMTP Settings"
3. Selecciona "Laravel" como integración
4. Copia las credenciales a tu `.env`

### 2. Desarrollo Local con MailHog (Alternativa)

Si prefieres usar MailHog localmente:

```bash
# Instalar MailHog (si usas Docker)
docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog

# Configuración en .env
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@itca.com.ar
MAIL_FROM_NAME="${APP_NAME}"
MAIL_TO_ADMIN=federico.lyonnet@gmail.com
```

Luego accede a http://localhost:8025 para ver los emails.

### 3. Producción

Para producción, necesitas un servicio SMTP real:

- **Gmail**: Requiere "App Password" (no tu contraseña normal)
- **SendGrid**: Servicio profesional de emails
- **Mailgun**: Otro servicio profesional
- **SMTP del hosting**: Si tu hosting proporciona SMTP

## Ejecutar Migración

Después de configurar el `.env`, ejecuta la migración:

```bash
php artisan migrate
```

## Probar el Sistema

1. Completa un formulario de inscripción en la página
2. Verifica que el lead se guarde en la base de datos
3. Revisa tu bandeja de entrada (o Mailtrap/MailHog) para ver el email

## Notas Importantes

- El email se envía a la dirección configurada en `MAIL_TO_ADMIN`
- Si el envío de email falla, el lead se guarda igual (no se pierde la información)
- Los errores de email se registran en `storage/logs/laravel.log`
- El email incluye todos los datos del interesado y de la cursada seleccionada
