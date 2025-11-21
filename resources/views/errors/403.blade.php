<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ITCA - Acceso prohibido">
    <title>403 - Acceso prohibido | ITCA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/public.css', 'resources/js/app.js'])
</head>
<body>
    <div style="min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #010C42 0%, #02115A 100%); color: white; padding: 20px; text-align: center;">
        <div style="max-width: 600px; width: 100%;">
            <h1 style="font-family: 'Montserrat', sans-serif; font-size: clamp(72px, 15vw, 150px); font-weight: 700; margin: 0; line-height: 1; color: white;">
                403
            </h1>
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: clamp(24px, 5vw, 36px); font-weight: 600; margin: 20px 0; color: white;">
                Acceso prohibido
            </h2>
            <p style="font-family: 'Inter', sans-serif; font-size: clamp(16px, 3vw, 18px); margin: 20px 0 40px; color: rgba(255, 255, 255, 0.9); line-height: 1.6;">
                No tienes permiso para acceder a esta página. Si crees que esto es un error, por favor contacta con el administrador.
            </p>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="/" style="display: inline-block; padding: 12px 30px; background-color: white; color: #010C42; text-decoration: none; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 16px; transition: all 0.3s ease;">
                    Ir al Inicio
                </a>
                <a href="javascript:history.back()" style="display: inline-block; padding: 12px 30px; background-color: transparent; color: white; text-decoration: none; border: 2px solid white; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 16px; transition: all 0.3s ease;">
                    Volver Atrás
                </a>
            </div>
        </div>
    </div>
</body>
</html>




