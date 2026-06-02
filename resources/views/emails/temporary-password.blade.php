<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contraseña Temporal</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            color: rgba(255,255,255,0.9);
            margin: 8px 0 0 0;
            font-size: 14px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333333;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .message {
            color: #555555;
            line-height: 1.6;
            margin-bottom: 25px;
            font-size: 15px;
        }
        .password-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            margin: 30px 0;
        }
        .password-label {
            color: rgba(255,255,255,0.9);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .password {
            background-color: rgba(255,255,255,0.2);
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            padding: 15px 25px;
            border-radius: 8px;
            letter-spacing: 3px;
            font-family: 'Courier New', monospace;
            display: inline-block;
            margin-top: 10px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 14px 35px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .instructions {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .instructions h3 {
            color: #333333;
            margin: 0 0 15px 0;
            font-size: 16px;
        }
        .instructions ol {
            margin: 0;
            padding-left: 20px;
            color: #555555;
        }
        .instructions li {
            margin-bottom: 8px;
            line-height: 1.5;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
            font-size: 14px;
        }
        .warning strong {
            display: block;
            margin-bottom: 5px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            color: #6c757d;
            font-size: 13px;
            margin: 5px 0;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Contraseña Temporal</h1>
            <p>FiftyOne - Tu tienda de moda</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                ¡Hola, {{ $user->name }}!
            </div>

            <div class="message">
                Recibimos tu solicitud para recuperar tu contraseña. Hemos generado una <strong>contraseña temporal</strong> para que puedas acceder a tu cuenta.
            </div>

            <!-- Password Box -->
            <div class="password-box">
                <div class="password-label">Tu contraseña temporal es:</div>
                <div class="password">{{ $tempPassword }}</div>
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <h3>📋 Instrucciones:</h3>
                <ol>
                    <li>Haz clic en el botón de abajo para ir a la página de inicio de sesión</li>
                    <li>Ingresa tu email: <strong>{{ $user->email }}</strong></li>
                    <li>Usa la contraseña temporal mostrada arriba</li>
                    <li>Una vez dentro, ve a "Mi Cuenta" y cambia tu contraseña</li>
                </ol>
            </div>

            <!-- Button -->
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">
                    Iniciar Sesión Ahora
                </a>
            </div>

            <!-- Warning -->
            <div class="warning">
                <strong>⚠️ Importante:</strong>
                Por tu seguridad, te recomendamos cambiar esta contraseña temporal inmediatamente después de iniciar sesión.
            </div>

            <div class="message" style="margin-top: 25px;">
                Si no solicitaste este cambio, por favor contacta con nuestro equipo de soporte de inmediato.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>FiftyOne</strong> - Ropa Oversize Colombia</p>
            <p>
                <a href="{{ url('/') }}">Visitar tienda</a> | 
                <a href="{{ url('/contacto') }}">Contacto</a> | 
                <a href="{{ url('/faq') }}">Ayuda</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px;">
                Este es un correo automático, por favor no respondas a este mensaje.
            </p>
        </div>
    </div>
</body>
</html>
