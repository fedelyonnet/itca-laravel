<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ITCA - Acceso Desarrollo">
    <title>Acceso Desarrollo | ITCA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/public.css', 'resources/js/app.js'])
</head>
<body>
    <div style="min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #010C42 0%, #02115A 100%); color: white; padding: 20px; text-align: center;">
        <div style="max-width: 400px; width: 100%;">
            <h1 style="font-family: 'Montserrat', sans-serif; font-size: clamp(32px, 8vw, 48px); font-weight: 700; margin: 0 0 20px; line-height: 1.2; color: white;">
                Modo Desarrollo
            </h1>
            <p style="font-family: 'Inter', sans-serif; font-size: 16px; margin: 0 0 30px; color: rgba(255, 255, 255, 0.9); line-height: 1.5;">
                Este sitio está en desarrollo. Por favor ingresa tus credenciales de administrador para continuar.
            </p>
            
            <form action="{{ route('dev-login.store') }}" method="POST" style="background: rgba(255, 255, 255, 0.1); padding: 30px; border-radius: 16px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);">
                @csrf
                
                <div style="margin-bottom: 20px; text-align: left;">
                    <label for="email" style="display: block; font-family: 'Montserrat', sans-serif; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.1); color: white; font-family: 'Inter', sans-serif;">
                    @error('email')
                        <span style="color: #ff6b6b; font-size: 13px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 30px; text-align: left;">
                    <label for="password" style="display: block; font-family: 'Montserrat', sans-serif; margin-bottom: 8px; font-weight: 600; font-size: 14px;">Contraseña</label>
                    <input type="password" id="password" name="password" required
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.1); color: white; font-family: 'Inter', sans-serif;">
                </div>

                <button type="submit" 
                    style="width: 100%; padding: 14px; background-color: white; color: #010C42; border: none; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s ease;">
                    Ingresar
                </button>
            </form>
        </div>
    </div>
</body>
</html>
