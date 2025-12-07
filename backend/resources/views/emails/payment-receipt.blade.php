<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de paiement - {{ config('app.name', 'Voicy Assistant') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #0ea5e9 0%, #a855f7 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #111827;
            margin-bottom: 20px;
        }
        .receipt-box {
            background-color: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        .receipt-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 15px;
        }
        .receipt-details {
            display: table;
            width: 100%;
        }
        .receipt-row {
            display: table-row;
            margin-bottom: 12px;
        }
        .receipt-label {
            display: table-cell;
            font-weight: 600;
            color: #6b7280;
            padding: 8px 0;
            width: 40%;
        }
        .receipt-value {
            display: table-cell;
            color: #111827;
            padding: 8px 0;
            text-align: right;
        }
        .receipt-divider {
            border-top: 1px solid #e5e7eb;
            margin: 15px 0;
        }
        .total-box {
            background-color: #0ea5e9;
            color: #ffffff;
            padding: 20px;
            border-radius: 6px;
            margin-top: 20px;
        }
        .total-label {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .total-amount {
            font-size: 28px;
            font-weight: 700;
        }
        .subscription-info {
            background-color: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .subscription-info h3 {
            color: #065f46;
            font-size: 18px;
            margin: 0 0 10px 0;
        }
        .subscription-info p {
            color: #047857;
            margin: 5px 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #0ea5e9;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 20px 15px;
            }
            .receipt-label, .receipt-value {
                display: block;
                width: 100%;
                text-align: left;
            }
            .receipt-value {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div style="padding: 20px;">
        <div class="email-container">
            <!-- Header -->
            <div class="header">
                <h1>{{ config('app.name', 'Voicy Assistant') }}</h1>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">
                    Bonjour <strong>{{ $payment->user->name }}</strong>,
                </div>

                <p style="color: #6b7280; margin-bottom: 25px;">
                    Nous vous confirmons la réception de votre paiement. Votre abonnement a été activé avec succès !
                </p>

                <!-- Receipt Box -->
                <div class="receipt-box">
                    <div class="receipt-title">📄 Reçu de Paiement</div>
                    
                    <div class="receipt-details">
                        <div class="receipt-row">
                            <div class="receipt-label">Numéro de transaction :</div>
                            <div class="receipt-value"><strong>#{{ $payment->provider_payment_id ?? $payment->id }}</strong></div>
                        </div>
                        <div class="receipt-row">
                            <div class="receipt-label">Date de paiement :</div>
                            <div class="receipt-value">{{ $payment->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        <div class="receipt-row">
                            <div class="receipt-label">Méthode de paiement :</div>
                            <div class="receipt-value">{{ ucfirst($payment->provider) }}</div>
                        </div>
                        <div class="receipt-row">
                            <div class="receipt-label">Statut :</div>
                            <div class="receipt-value">
                                <span style="color: #10b981; font-weight: 600;">✓ Payé</span>
                            </div>
                        </div>
                        <div class="receipt-divider"></div>
                        <div class="receipt-row">
                            <div class="receipt-label">Plan souscrit :</div>
                            <div class="receipt-value"><strong>{{ $subscription->plan->name }}</strong></div>
                        </div>
                        <div class="receipt-row">
                            <div class="receipt-label">Montant :</div>
                            <div class="receipt-value">
                                <strong>{{ number_format($payment->amount, 2, ',', ' ') }} {{ $payment->currency }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="total-box">
                        <div class="total-label">Montant total payé</div>
                        <div class="total-amount">
                            {{ number_format($payment->amount, 2, ',', ' ') }} {{ $payment->currency }}
                        </div>
                    </div>
                </div>

                <!-- Subscription Info -->
                <div class="subscription-info">
                    <h3>🎉 Votre abonnement est actif !</h3>
                    <p><strong>Plan :</strong> {{ $subscription->plan->name }}</p>
                    <p><strong>Date de début :</strong> {{ $subscription->started_at->format('d/m/Y') }}</p>
                    <p><strong>Date d'expiration :</strong> {{ $subscription->expires_at->format('d/m/Y') }}</p>
                    @if($subscription->plan->allowed_audio_per_month)
                        <p><strong>Audios par mois :</strong> {{ $subscription->plan->allowed_audio_per_month }}</p>
                    @endif
                </div>

                <p style="color: #6b7280; margin-top: 25px;">
                    Vous pouvez maintenant profiter de toutes les fonctionnalités de votre plan. Connectez-vous à votre compte pour commencer.
                </p>

                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{ route('dashboard') }}" class="button">Accéder à mon compte</a>
                </div>

                <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
                    Si vous avez des questions, n'hésitez pas à nous contacter. Nous sommes là pour vous aider !
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0;">
                    © {{ date('Y') }} {{ config('app.name', 'Voicy Assistant') }}. Tous droits réservés.
                </p>
                <p style="margin: 10px 0 0 0;">
                    Cet email a été envoyé automatiquement, merci de ne pas y répondre.
                </p>
            </div>
        </div>
    </div>
</body>
</html>

