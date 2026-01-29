<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview de Mails - ITCA</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #1a202c;
            margin-top: 0;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #718096;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 14px;
        }
        select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }
        select:hover {
            border-color: #cbd5e0;
        }
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .btn:active {
            transform: translateY(0);
        }
        .info-box {
            background: #edf2f7;
            border-left: 4px solid #667eea;
            padding: 16px;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .info-box p {
            margin: 0;
            color: #4a5568;
            font-size: 14px;
            line-height: 1.6;
        }
        .info-box strong {
            color: #2d3748;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Preview de Mails</h1>
        <p class="subtitle">Visualiza cómo se verán los mails sin necesidad de enviarlos</p>
        
        <div class="info-box">
            <p><strong>💡 Tip:</strong> Seleccioná una cursada para ver cómo se renderiza el mail de carrito abandonado con datos reales. El preview es 100% idéntico a cómo se verá en Gmail, Outlook, etc.</p>
        </div>
        
        <form action="{{ route('mail.preview.abandoned-cart') }}" method="GET" target="_blank">
            <div class="form-group">
                <label for="cursada_id">Seleccionar Cursada:</label>
                <select name="cursada_id" id="cursada_id" required>
                    <option value="">-- Elegir una cursada --</option>
                    @foreach($cursadas as $cursada)
                        <option value="{{ $cursada['id'] }}">{{ $cursada['nombre'] }}</option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn">
                🔍 Ver Preview del Mail
            </button>
        </form>
    </div>
</body>
</html>
