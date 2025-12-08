<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
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
        .email-content h2 {
            color: #3b82f6;
            font-size: 20px;
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .email-content p {
            margin-bottom: 15px;
        }
        .email-content a {
            color: #0ea5e9;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 2px solid #0ea5e9;
            transition: all 0.3s;
        }
        .email-content a:hover {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }
        .email-content ul, .email-content ol {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        .email-content li {
            margin-bottom: 8px;
        }
        .email-content strong {
            color: #1f2937;
            font-weight: 700;
        }
        .email-content em {
            color: #6b7280;
            font-style: italic;
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
        .unsubscribe {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .unsubscribe a {
            color: #9ca3af;
            text-decoration: underline;
            font-size: 12px;
        }
        .unsubscribe a:hover {
            color: #6b7280;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #0ea5e9;
            font-size: 20px;
            text-decoration: none;
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
            <div class="tagline">Automatisez vos messages vocaux WhatsApp</div>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="email-content">
                {!! $content !!}
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <p><strong>Voicy Assistant</strong></p>
            <p>Votre assistant intelligent pour automatiser vos messages vocaux WhatsApp</p>
            
            <div class="social-links">
                <a href="{{ route('welcome') }}" title="Site web">🌐</a>
            </div>
            
            <div class="unsubscribe">
                <p style="font-size: 11px; color: #9ca3af; margin-bottom: 10px;">
                    Vous recevez cet email car vous êtes abonné à notre newsletter.
                </p>
                <a href="{{ $unsubscribeUrl }}">Se désabonner de la newsletter</a>
            </div>
            
            <p style="margin-top: 20px; font-size: 11px; color: #9ca3af;">
                © {{ date('Y') }} Voicy Assistant. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>
