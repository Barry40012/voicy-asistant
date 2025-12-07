# 🚀 Guide Étape par Étape - Création Projet Supabase

## 📋 ÉTAPE 1 : Créer un Compte

1. Sur https://supabase.com
2. Clique sur **"Start your project"** ou **"Sign in"**
3. Si tu n'as pas de compte :
   - Clique sur **"Sign up"**
   - Connecte-toi avec GitHub, Google, ou email
4. Si tu as déjà un compte : **"Sign in"**

---

## 📋 ÉTAPE 2 : Créer un Nouveau Projet

1. Une fois connecté, tu es sur le **Dashboard**
2. Clique sur le bouton **"New Project"** (en haut à droite)
3. Tu arrives sur la page de création de projet

---

## 📋 ÉTAPE 3 : Remplir les Informations du Projet

### A. Informations de base

1. **Organization** : 
   - Si tu as déjà une organisation, sélectionne-la
   - Sinon, crée-en une (gratuit)

2. **Name** : 
   - Donne un nom à ton projet (ex: `voicy-assistant` ou `voicy-app`)
   - Ce nom peut être changé plus tard

3. **Database Password** :
   - ⚠️ **TRÈS IMPORTANT** : Choisis un mot de passe fort
   - **NOTE-LE BIEN** (tu en auras besoin pour le .env)
   - Exemple : `MonMotDePasse123!@#`
   - Tu peux utiliser un gestionnaire de mots de passe

4. **Region** :
   - Choisis la région la plus proche de toi
   - Pour la Guinée, choisis une région en Europe (ex: `West EU` ou `North EU`)

5. **Pricing Plan** :
   - Sélectionne **"Free"** (gratuit)
   - Suffisant pour commencer (500 MB DB + 1 GB Storage)

6. Clique sur **"Create new project"**

---

## 📋 ÉTAPE 4 : Attendre la Création (2-3 minutes)

1. Tu verras un écran de chargement
2. Le projet se crée automatiquement
3. ⏳ **Patiente 2-3 minutes** (c'est normal)
4. Une fois terminé, tu seras redirigé vers le Dashboard du projet

---

## 📋 ÉTAPE 5 : Récupérer les Informations de Connexion

Une fois le projet créé, tu es sur le Dashboard. Voici ce qu'il faut récupérer :

### A. Informations Base de Données

1. Va dans **Settings** (icône engrenage en bas à gauche)
2. Clique sur **"Database"** dans le menu
3. Tu verras plusieurs sections :

   **Connection string** :
   - Trouve **"Connection string"** ou **"URI"**
   - Format : `postgresql://postgres:[YOUR-PASSWORD]@db.xxxxx.supabase.co:5432/postgres`
   - **Note** :
     - **Host** : `db.xxxxx.supabase.co` (remplace xxxxx par ton ID)
     - **Port** : `5432`
     - **Database** : `postgres`
     - **Username** : `postgres`
     - **Password** : Celui que tu as créé à l'étape 3

   **Connection pooling** (optionnel) :
   - Tu peux aussi utiliser le "Connection pooling" si besoin
   - Pour l'instant, utilise la connexion directe

### B. Informations API (pour Storage)

1. Toujours dans **Settings**
2. Clique sur **"API"** dans le menu
3. Tu verras :

   **Project URL** :
   - Format : `https://xxxxx.supabase.co`
   - **Note cette URL** (c'est ton `SUPABASE_URL`)

   **API Keys** :
   - **anon public** : Clé publique (on ne l'utilise pas pour le Storage)
   - **service_role secret** : ⚠️ **C'EST CETTE CLÉ QU'IL FAUT**
   - Clique sur **"Reveal"** pour voir la clé complète
   - **Note cette clé** (c'est ton `SUPABASE_SERVICE_KEY`)
   - ⚠️ **NE JAMAIS EXPOSER CETTE CLÉ** (c'est la clé admin)

---

## 📋 ÉTAPE 6 : Créer le Bucket Storage

1. Dans le menu de gauche, clique sur **"Storage"**
2. Tu verras une page avec la liste des buckets (vide au début)
3. Clique sur **"New bucket"** (bouton en haut à droite)
4. Remplis le formulaire :

   **Name** :
   - Entre : `audios`
   - (exactement comme ça, en minuscules)

   **Public bucket** :
   - ❌ **DÉCOCHE** cette case
   - Le bucket doit être **PRIVÉ** (pour la sécurité)

   **File size limit** :
   - Laisse par défaut ou mets `50` MB
   - (tu peux augmenter plus tard si besoin)

   **Allowed MIME types** (optionnel) :
   - Tu peux laisser vide ou ajouter : `audio/ogg`, `audio/mpeg`, `audio/wav`

5. Clique sur **"Create bucket"**

6. ✅ Le bucket `audios` est maintenant créé !

---

## 📋 ÉTAPE 7 : Vérifier que Tout est OK

### Checklist :

- ✅ Projet créé
- ✅ Bucket `audios` créé (Storage > tu vois le bucket)
- ✅ Project URL notée (Settings > API)
- ✅ Service Role Key notée (Settings > API)
- ✅ Database password notée (celui que tu as créé)
- ✅ Database host noté (Settings > Database)

---

## 📋 ÉTAPE 8 : Configurer le .env

Maintenant, ouvre ton fichier `.env` dans :
`E:\Project_Voicy_Assistant\backend\.env`

Et ajoute/modifie ces lignes :

```env
# ============================================
# SUPABASE - BASE DE DONNÉES
# ============================================
DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=ton_mot_de_passe_que_tu_as_créé

# ============================================
# SUPABASE - STORAGE (pour les fichiers audio)
# ============================================
SUPABASE_URL=https://xxxxx.supabase.co
SUPABASE_SERVICE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9... (la clé complète)
SUPABASE_BUCKET=audios
```

**⚠️ IMPORTANT** :
- Remplace `xxxxx` par ton vrai Project ID (dans l'URL)
- Remplace `ton_mot_de_passe_que_tu_as_créé` par le mot de passe de l'étape 3
- Remplace la clé `SUPABASE_SERVICE_KEY` par la vraie clé (service_role)

---

## 📋 ÉTAPE 9 : Tester la Connexion

Ouvre ton terminal et exécute :

```bash
cd E:\Project_Voicy_Assistant\backend
php artisan migrate
```

Si ça fonctionne :
- ✅ Tu verras les tables créées
- ✅ Dans Supabase Dashboard > **Table Editor**, tu verras tes tables (plans, subscriptions, etc.)

Si ça ne fonctionne pas :
- Vérifie les credentials dans `.env`
- Vérifie que le projet Supabase est actif (pas en pause)

---

## 🎯 Résumé des Informations à Noter

Pendant la création, note ces informations :

1. **Database Password** : `_________________`
2. **Project URL** : `https://xxxxx.supabase.co`
3. **Database Host** : `db.xxxxx.supabase.co`
4. **Service Role Key** : `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...`

---

## 🆘 En cas de Problème

### Le projet ne se crée pas
- Vérifie ta connexion internet
- Réessaie après quelques minutes
- Vérifie que tu n'as pas atteint la limite de projets gratuits

### Je ne trouve pas les Settings
- Clique sur l'icône **engrenage** en bas à gauche
- Ou utilise le menu latéral

### Je ne vois pas le bucket
- Va dans **Storage** (menu gauche)
- Clique sur **"New bucket"** si tu ne l'as pas encore créé

---

**Une fois que tu as créé le projet et noté toutes les infos, dis-moi et on configure le .env ensemble !** 🚀

