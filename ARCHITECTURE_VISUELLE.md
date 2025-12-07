# 🏗️ Architecture Visuelle - Voicy Assistant

## 📐 Vue d'Ensemble du Système

```
┌─────────────────────────────────────────────────────────────────┐
│                        UTILISATEUR FINAL                         │
│              (Envoie vocal via WhatsApp Business)                │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                    WHATSAPP BUSINESS CLOUD API                    │
│                          (Meta/Facebook)                         │
└────────────────────────────┬────────────────────────────────────┘
                             │ Webhook (message audio)
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                      BACKEND LARAVEL 11                          │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  /webhooks/whatsapp                                       │  │
│  │  → Reçoit webhook Meta                                    │  │
│  │  → Télécharge audio (Graph API)                           │  │
│  │  → Upload Supabase Storage                                │  │
│  │  → Crée record "audios"                                   │  │
│  │  → Dispatch Job "ProcessAudioJob"                        │  │
│  └──────────────────────────────────────────────────────────┘  │
│                             │                                    │
│                             ▼                                    │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  QUEUE (Redis)                                            │  │
│  │  ┌────────────────────────────────────────────────────┐  │  │
│  │  │  ProcessAudioJob                                    │  │  │
│  │  │  1. Appel Whisper API → Transcription              │  │  │
│  │  │  2. Appel LLM API → Résumé + Actions + Réponse      │  │  │
│  │  │  3. Sauvegarde audio_analyses                       │  │  │
│  │  │  4. Notification client                             │  │  │
│  │  │  5. Si auto-reply → Envoi réponse via Graph API    │  │  │
│  │  └────────────────────────────────────────────────────┘  │  │
│  └──────────────────────────────────────────────────────────┘  │
│                             │                                    │
│                             ▼                                    │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  SERVICES                                                 │  │
│  │  • WhatsAppService (Graph API)                            │  │
│  │  • AudioService (Supabase Storage)                       │  │
│  │  • AIService (Whisper + LLM)                             │  │
│  │  • PaymentService (Stripe/Flutterwave/etc.)              │  │
│  └──────────────────────────────────────────────────────────┘  │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                    SUPABASE (PostgreSQL)                         │
│  • users                                                         │
│  • plans                                                         │
│  • subscriptions                                                 │
│  • whatsapp_connections                                          │
│  • audios                                                        │
│  • audio_analyses                                                │
│  • payments                                                      │
│  • logs                                                          │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                    SUPABASE STORAGE                              │
│  Bucket: "audios"                                                │
│  • Fichiers audio (.ogg, .mp3, etc.)                            │
│  • URLs signées (sécurité)                                       │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Flux de Données Principal (Réception Vocal)

```
1. UTILISATEUR ENVOIE VOCAL
   └─> WhatsApp Business Cloud API

2. META ENVOIE WEBHOOK
   └─> POST /webhooks/whatsapp
       {
         "entry": [{
           "messaging": [{
             "message": {
               "type": "audio",
               "media_id": "xxx"
             }
           }]
         }]
       }

3. BACKEND TRAITE WEBHOOK
   ├─> Valide signature Meta
   ├─> Récupère access_token (whatsapp_connections)
   ├─> Télécharge audio via Graph API
   ├─> Upload vers Supabase Storage
   └─> Crée record "audios" (status: uploaded)

4. JOB DISPATCHÉ (Queue)
   └─> ProcessAudioJob::dispatch($audio)

5. WORKER TRAITE JOB
   ├─> Télécharge audio depuis Supabase
   ├─> Appel Whisper API → Transcription
   ├─> Appel LLM API → Résumé + Actions + Réponse
   ├─> Sauvegarde audio_analyses
   └─> Update audios (status: done)

6. NOTIFICATION CLIENT
   └─> Dashboard : Nouvelle analyse disponible

7. (OPTIONNEL) AUTO-REPLY
   └─> Si activé → Envoi réponse via Graph API
```

---

## 💳 Flux de Paiement

```
1. CLIENT CHOISIT PLAN
   └─> Frontend : Page plans

2. REDIRECTION PSP
   └─> Stripe / Flutterwave / Orange Money / MTN

3. PAIEMENT EFFECTUÉ
   └─> PSP traite paiement

4. WEBHOOK PSP
   └─> POST /webhooks/payments
       {
         "provider": "stripe",
         "payment_id": "xxx",
         "status": "succeeded",
         "amount": 9.00
       }

5. BACKEND VALIDE
   ├─> Vérifie signature webhook
   ├─> Valide paiement avec PSP
   ├─> Crée record "payments"
   └─> Crée/Update "subscriptions" (status: active)

6. ACTIVATION COMPTE
   └─> Client peut utiliser le service
```

---

## 🔐 Architecture de Sécurité

```
┌─────────────────────────────────────────────────────────────┐
│                    COUCHES DE SÉCURITÉ                      │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  1. WEBHOOKS                                                 │
│     • Validation signature Meta (X-Hub-Signature-256)        │
│     • Validation signature PSP                               │
│                                                              │
│  2. AUTHENTIFICATION                                         │
│     • Laravel Breeze (sessions)                             │
│     • Middleware auth                                        │
│     • CSRF protection                                        │
│                                                              │
│  3. DONNÉES SENSIBLES                                       │
│     • Tokens WhatsApp → Laravel Encryption                   │
│     • Service Role Key → .env (jamais commit)               │
│     • Fichiers audio → URLs signées Supabase                │
│                                                              │
│  4. API RATE LIMITING                                        │
│     • Limite appels IA                                       │
│     • Limite webhooks                                        │
│                                                              │
│  5. AUDIT                                                    │
│     • Table "logs" pour toutes actions                       │
│     • Traçabilité complète                                  │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## 📦 Structure des Dossiers (Backend)

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── WebhookController.php        # Webhooks Meta + PSP
│   │   │   ├── AudioController.php          # Gestion audios
│   │   │   ├── SubscriptionController.php   # Abonnements
│   │   │   └── AdminController.php          # Dashboard admin
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Plan.php
│   │   ├── Subscription.php
│   │   ├── WhatsAppConnection.php
│   │   ├── Audio.php
│   │   ├── AudioAnalysis.php
│   │   ├── Payment.php
│   │   └── Log.php
│   ├── Services/
│   │   ├── WhatsAppService.php              # Graph API client
│   │   ├── AudioService.php                 # Supabase Storage
│   │   ├── AIService.php                    # Whisper + LLM
│   │   └── PaymentService.php               # PSP abstrait
│   │       ├── Adapters/
│   │       │   ├── StripeAdapter.php
│   │       │   ├── FlutterwaveAdapter.php
│   │       │   ├── OrangeMoneyAdapter.php
│   │       │   └── MTNMoMoAdapter.php
│   └── Jobs/
│       └── ProcessAudioJob.php              # Traitement audio
├── database/
│   └── migrations/
│       ├── 2024_01_01_create_plans_table.php
│       ├── 2024_01_02_create_subscriptions_table.php
│       ├── 2024_01_03_create_whatsapp_connections_table.php
│       ├── 2024_01_04_create_audios_table.php
│       ├── 2024_01_05_create_audio_analyses_table.php
│       ├── 2024_01_06_create_payments_table.php
│       └── 2024_01_07_create_logs_table.php
├── routes/
│   ├── web.php                              # Routes Blade
│   └── api.php                              # Routes API (si besoin)
├── resources/
│   └── views/
│       ├── layouts/
│       ├── dashboard/
│       ├── audios/
│       └── admin/
└── config/
    ├── whatsapp.php                         # Config WhatsApp
    ├── ai.php                               # Config IA
    └── payments.php                          # Config PSP
```

---

## 🔌 Intégrations Externes

```
┌─────────────────────────────────────────────────────────────┐
│                    SERVICES EXTERNES                        │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  WHATSAPP BUSINESS CLOUD API                                 │
│  • Graph API v18.0                                           │
│  • Webhooks                                                  │
│  • Media download                                             │
│  • Send messages                                             │
│                                                              │
│  SUPABASE                                                    │
│  • PostgreSQL (database)                                     │
│  • Storage (fichiers audio)                                  │
│                                                              │
│  IA SERVICES                                                 │
│  • Whisper (transcription)                                  │
│    - HuggingFace API                                         │
│    - OU OpenAI Whisper API                                  │
│  • LLM (résumé/actions/réponses)                             │
│    - HuggingFace (modèles open-source)                      │
│    - OU OpenAI GPT                                           │
│                                                              │
│  PAYMENT PROVIDERS                                           │
│  • Stripe (cartes internationales)                          │
│  • Flutterwave (cartes + Mobile Money)                     │
│  • Orange Money API (Guinée)                                │
│  • MTN MoMo API (Guinée)                                    │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## 📊 Base de Données - Relations

```
users (1) ──< (N) subscriptions
users (1) ──< (N) whatsapp_connections
users (1) ──< (N) audios
users (1) ──< (N) payments
users (1) ──< (N) logs

plans (1) ──< (N) subscriptions

audios (1) ──< (1) audio_analyses
```

---

## 🎯 Points d'Entrée Principaux

1. **Webhooks** (entrants)
   - `/webhooks/whatsapp` → Messages Meta
   - `/webhooks/payments` → Callbacks PSP

2. **Dashboard Client** (authentifié)
   - `/dashboard` → Vue d'ensemble
   - `/dashboard/audios` → Liste vocaux
   - `/dashboard/audios/{id}` → Détail audio
   - `/dashboard/whatsapp` → Connexion WhatsApp
   - `/dashboard/subscription` → Gestion abonnement

3. **Dashboard Admin** (super-admin)
   - `/admin` → Vue d'ensemble
   - `/admin/users` → Gestion utilisateurs
   - `/admin/logs` → Logs webhooks
   - `/admin/metrics` → Métriques

---

**Cette architecture est modulaire et scalable** 🚀

