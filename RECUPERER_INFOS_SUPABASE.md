# 📝 Récupérer les Informations Supabase pour le .env

## ✅ Ce que tu as déjà
- Projet Supabase créé
- Page Database Settings ouverte

## 📋 Informations à récupérer MAINTENANT

### 1. Database Password (Mot de passe)
- C'est le mot de passe que tu as créé lors de la création du projet
- Si tu l'as oublié, tu peux le réinitialiser dans Database Settings
- **Note-le** : `_________________`

### 2. Database Host (Host de connexion)
Dans la page Database Settings, cherche :
- **Connection string** ou **Connection info**
- Ou va dans **Settings > Database > Connection string**
- Tu verras quelque chose comme : `postgresql://postgres:[PASSWORD]@db.xxxxx.supabase.co:5432/postgres`
- **Note le HOST** : `db.xxxxx.supabase.co` (remplace xxxxx par ton ID)

### 3. Project URL et API Keys
1. Dans le menu de gauche, clique sur **Settings** (icône engrenage)
2. Clique sur **"API"** (pas Database)
3. Tu verras :
   - **Project URL** : `https://xxxxx.supabase.co`
   - **API Keys** :
     - **anon public** : (on n'en a pas besoin)
     - **service_role secret** : ⚠️ **C'EST CETTE CLÉ QU'IL FAUT**
     - Clique sur **"Reveal"** pour voir la clé complète
     - **Note cette clé complète**

### 4. Vérifier le Bucket Storage
1. Dans le menu de gauche, clique sur **"Storage"**
2. Tu devrais voir le bucket **"audios"**
3. Si tu ne le vois pas, crée-le :
   - Clique sur **"New bucket"**
   - Nom : `audios`
   - Public : ❌ NON (décocher)
   - Clique sur **"Create bucket"**

---

## 📝 Formulaire à remplir

Remplis ce formulaire avec tes informations :

```
1. Database Password : _________________
2. Database Host : db._________________.supabase.co
3. Project URL : https://_________________.supabase.co
4. Service Role Key : eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
5. Bucket "audios" créé ? OUI / NON
```

---

## 🔍 Où trouver chaque information

### Database Host
- **Settings > Database**
- Cherche "Connection string" ou "Host"
- Format : `db.xxxxx.supabase.co`

### Project URL
- **Settings > API**
- Section "Project URL"
- Format : `https://xxxxx.supabase.co`

### Service Role Key
- **Settings > API**
- Section "API Keys"
- Trouve "service_role secret"
- Clique sur "Reveal"
- Copie la clé complète (commence par `eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...`)

---

Une fois que tu as toutes ces infos, on configure le .env ensemble ! 🚀

