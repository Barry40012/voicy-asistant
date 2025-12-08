<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .email-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }
        .email-header .logo {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .email-header .tagline {
            font-size: 14px;
            opacity: 0.95;
            font-weight: 300;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-content {
            color: #374151;
            font-size: 16px;
            line-height: 1.8;
        }
        .email-content h1 {
            color: #0ea5e9;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .email-content p {
            margin-bottom: 15px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.4);
            transition: all 0.3s;
            border: none;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.6);
        }
        .security-notice {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .security-notice p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
        }
        .security-notice strong {
            color: #78350f;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 10px;
        }
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                width: 100% !important;
                border-radius: 0;
            }
            .email-header, .email-body, .email-footer {
                padding: 25px 20px !important;
            }
            .email-content {
                font-size: 14px !important;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">🎙️ Voicy Assistant</div>
            <div class="tagline">Réinitialisation de mot de passe</div>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="email-content">
                <h1>Bonjour ! 👋</h1>
                
                <p>Tu as demandé à réinitialiser ton mot de passe pour ton compte <strong>Voicy Assistant</strong>.</p>
                
                <p>Clique sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>
                
                <div class="button-container">
                    <a href="{{ $url }}" class="button">
                        🔐 Réinitialiser mon mot de passe
                    </a>
                </div>
                
                <p style="font-size: 14px; color: #6b7280;">
                    Si le bouton ne fonctionne pas, copie et colle ce lien dans ton navigateur :<br>
                    <a href="{{ $url }}" style="color: #0ea5e9; word-break: break-all;">{{ $url }}</a>
                </p>
                
                <div class="security-notice">
                    <p>
                        <strong>🔒 Sécurité :</strong> Ce lien expire dans <strong>60 minutes</strong>. 
                        Si tu n'as pas demandé cette réinitialisation, ignore cet email. 
                        Ton mot de passe ne sera pas modifié.
                    </p>
                </div>
                
                <p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
                    Si tu as des questions, n'hésite pas à nous contacter.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <p><strong>Voicy Assistant</strong></p>
            <p>Votre assistant intelligent pour automatiser vos messages vocaux WhatsApp</p>
            <p style="margin-top: 20px; font-size: 11px; color: #9ca3af;">
                © {{ date('Y') }} Voicy Assistant. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>

