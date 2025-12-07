# 🔧 Configuration .env - Informations Récupérées

## ✅ Informations déjà notées

- **Project URL** : `https://yxorlhhcmjiabbjtqlwo.supabase.co`
- **Project ID** : `yxorlhhcmjiabbjtqlwo`

## 📋 Informations à récupérer maintenant

### 1. Service Role Key (sur la page API où tu es)

Sur la page API Settings où tu es actuellement :

1. Cherche la section **"API Keys"** ou **"Project API keys"**
2. Tu devrais voir deux clés :
   - **anon public** (on n'en a pas besoin)
   - **service_role secret** ⚠️ **C'EST CETTE CLÉ QU'IL FAUT**
3. Clique sur **"Reveal"** à côté de "service_role secret"
4. **Copie la clé complète** (elle commence par `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...`)
5. Note-la : `_________________`

### 2. Database Host

1. Dans le menu de gauche, clique sur **Settings** (icône engrenage)
2. Clique sur **"Database"**
3. Cherche **"Connection string"** ou **"Connection info"**
4. Tu verras quelque chose comme :
   - `postgresql://postgres:[PASSWORD]@db.yxorlhhcmjiabbjtqlwo.supabase.co:5432/postgres`
   - Ou juste le host : `db.yxorlhhcmjiabbjtqlwo.supabase.co`
5. **Note le HOST** : `db.yxorlhhcmjiabbjtqlwo.supabase.co`

### 3. Database Password

- C'est le mot de passe que tu as créé lors de la création du projet
- Si tu l'as oublié, dans Database Settings, il y a un bouton pour le réinitialiser
- **Note-le** : `_________________`

### 4. Vérifier le Bucket Storage

1. Dans le menu de gauche, clique sur **"Storage"**
2. Tu devrais voir le bucket **"audios"**
3. Si tu ne le vois pas :
   - Clique sur **"New bucket"**
   - Nom : `audios`
   - Public : ❌ NON (décocher)
   - Clique sur **"Create bucket"**

---

## 📝 Formulaire à remplir

Une fois que tu as toutes les infos, remplis ceci :

```
1. Service Role Key : eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
2. Database Host : db.yxorlhhcmjiabbjtqlwo.supabase.co
3. Database Password : _________________
4. Bucket "audios" créé ? OUI / NON
```

---

## 🎯 Une fois que tu as tout, on configure le .env !

