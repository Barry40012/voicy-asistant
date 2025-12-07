# 🔧 Guide de Configuration Supabase

## ✅ Ce qui est déjà fait

- ✅ Service `AudioService` créé (utilise Supabase Storage)
- ✅ Configuration dans `config/services.php`
- ✅ Migrations prêtes pour PostgreSQL

## 📋 Ce qu'il reste à faire

### 1. Installer le package Supabase PHP

```bash
cd E:\Project_Voicy_Assistant\backend
composer require supabase/supabase-php
```

### 2. Créer un compte et projet Supabase

1. Va sur https://supabase.com
2. Crée un compte (gratuit)
3. Crée un nouveau projet
4. Note le nom de ton projet (ex: `abcdefghijklmnop`)

### 3. Récupérer les informations de connexion

Dans ton dashboard Supabase :

#### A. Base de données PostgreSQL

1. Va dans **Settings > Database**
2. Trouve la **Connection string** (URI)
3. Format : `postgresql://postgres:[PASSWORD]@db.xxxxx.supabase.co:5432/postgres`
4. Extrais :
   - **Host** : `db.xxxxx.supabase.co`
   - **Port** : `5432`
   - **Database** : `postgres`
   - **Username** : `postgres`
   - **Password** : Le mot de passe que tu as défini

#### B. API Keys (pour Storage)

1. Va dans **Settings > API**
2. Trouve :
   - **Project URL** : `https://xxxxx.supabase.co`
   - **anon public** : Clé publique (pour client)
   - **service_role secret** : Clé secrète (pour backend) ⚠️ **NE JAMAIS EXPOSER**

### 4. Créer le bucket Storage

1. Va dans **Storage** (menu gauche)
2. Clique sur **New bucket**
3. Nom : `audios`
4. **Public** : ❌ NON (privé pour la sécurité)
5. **File size limit** : 50 MB (ou plus selon tes besoins)
6. Clique sur **Create bucket**

### 5. Configurer le fichier .env

Ouvre `E:\Project_Voicy_Assistant\backend\.env` et ajoute :

```env
# ============================================
# SUPABASE - BASE DE DONNÉES
# ============================================
DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=ton_mot_de_passe_supabase

# ============================================
# SUPABASE - STORAGE (pour les fichiers audio)
# ============================================
SUPABASE_URL=https://xxxxx.supabase.co
SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9... (anon public key)
SUPABASE_SERVICE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9... (service_role key)
SUPABASE_BUCKET=audios
```

**⚠️ IMPORTANT** : Remplace `xxxxx` par ton vrai ID de projet Supabase

### 6. Tester la connexion

#### A. Tester la base de données

```bash
cd E:\Project_Voicy_Assistant\backend
php artisan migrate
```

Si ça fonctionne, tu verras les tables créées dans Supabase (Dashboard > Table Editor)

#### B. Tester le Storage (optionnel)

Tu peux créer un test rapide dans `routes/web.php` :

```php
Route::get('/test-supabase', function () {
    $service = app(\App\Services\AudioService::class);
    // Test upload (optionnel)
    return 'Supabase configuré !';
});
```

### 7. Vérifier dans Supabase Dashboard

1. **Table Editor** : Tu devrais voir tes tables (plans, subscriptions, etc.)
2. **Storage** : Tu devrais voir le bucket `audios`

---

## 🎯 Résumé des étapes

1. ✅ Installer package : `composer require supabase/supabase-php`
2. ✅ Créer projet Supabase
3. ✅ Récupérer credentials (DB + API)
4. ✅ Créer bucket `audios`
5. ✅ Configurer `.env`
6. ✅ Tester avec `php artisan migrate`

---

## 🔍 Vérification

Une fois configuré, tu peux vérifier :

```bash
# Vérifier la connexion DB
php artisan migrate:status

# Vérifier la config
php artisan config:cache
php artisan config:clear
```

---

## ⚠️ Notes importantes

- **Service Role Key** : Ne JAMAIS l'exposer côté client (c'est la clé admin)
- **Anon Key** : Peut être utilisée côté client (mais on ne l'utilise pas ici)
- **Bucket privé** : Les fichiers audio sont privés, on génère des URLs signées pour y accéder
- **Limites gratuites** : 500 MB DB + 1 GB Storage (suffisant pour MVP)

---

## 🆘 En cas de problème

### Erreur de connexion DB
- Vérifie que le host est correct (sans `https://`)
- Vérifie le mot de passe
- Vérifie que le projet Supabase est actif

### Erreur Storage
- Vérifie que le bucket `audios` existe
- Vérifie que `SUPABASE_SERVICE_KEY` est correcte (service_role, pas anon)
- Vérifie que le package est installé : `composer show supabase/supabase-php`

---

**Une fois tout configuré, Supabase sera prêt à stocker tes fichiers audio !** 🚀

