# 🔍 Récupérer le Bon Hostname Supabase

## ❌ Problème

Le hostname `db.yxorlhhcmjiabbjtqlwo.supabase.co` ne fonctionne pas.

## ✅ Solution : Récupérer le bon hostname depuis Supabase

### Étape 1 : Aller dans Supabase Dashboard

1. Va sur https://supabase.com
2. Connecte-toi
3. Sélectionne ton projet

### Étape 2 : Vérifier que le projet est actif

1. Vérifie que le projet n'est pas en **pause**
2. Si le projet est en pause, clique sur **"Resume"** ou **"Restore"**
3. Attends que le projet soit complètement actif (2-3 minutes)

### Étape 3 : Récupérer le Connection String

1. Va dans **Settings** (icône engrenage en bas à gauche)
2. Clique sur **"Database"**
3. Cherche la section **"Connection string"** ou **"Connection info"**

Tu devrais voir plusieurs options :

#### Option A : Connection String (URI)
Format : `postgresql://postgres:[PASSWORD]@db.xxxxx.supabase.co:5432/postgres`

#### Option B : Connection Pooling
Format : `postgresql://postgres.xxxxx:[PASSWORD]@aws-0-eu-central-1.pooler.supabase.com:6543/postgres`

#### Option C : Direct Connection
Format : `postgresql://postgres:[PASSWORD]@db.xxxxx.supabase.co:5432/postgres`

### Étape 4 : Extraire le bon hostname

**IMPORTANT** : Utilise le hostname qui est dans le **Connection string** que Supabase te donne.

Exemples possibles :
- `db.yxorlhhcmjiabbjtqlwo.supabase.co` (direct)
- `aws-0-eu-central-1.pooler.supabase.com` (pooling)
- Ou un autre format selon ta région

### Étape 5 : Vérifier le format

Le hostname doit être :
- ✅ Sans `https://` ou `http://`
- ✅ Sans le port `:5432` à la fin
- ✅ Juste le nom d'hôte pur

Exemple correct :
```
DB_HOST=db.yxorlhhcmjiabbjtqlwo.supabase.co
```

OU (si Connection Pooling) :
```
DB_HOST=aws-0-eu-central-1.pooler.supabase.com
```

---

## 🔍 Vérifications dans Supabase

### 1. Vérifier le statut du projet

Dans le Dashboard, vérifie :
- Le projet est **"Active"** (pas "Paused")
- Pas de message d'erreur

### 2. Vérifier la région

Dans **Settings > General**, vérifie la **Region** du projet.

### 3. Récupérer le Connection String exact

Dans **Settings > Database > Connection string**, copie le **URI** complet.

Format attendu :
```
postgresql://postgres:[PASSWORD]@HOSTNAME:5432/postgres
```

Extrais le **HOSTNAME** (la partie entre `@` et `:5432`).

---

## 🧪 Test après modification

Une fois que tu as le bon hostname :

1. Modifie le `.env` avec le bon hostname
2. Teste le ping :
   ```bash
   ping LE_BON_HOSTNAME
   ```
3. Si le ping fonctionne, teste les migrations :
   ```bash
   php artisan migrate
   ```

---

## ⚠️ Si le projet est en pause

Si ton projet Supabase est en pause :

1. Va dans le Dashboard
2. Clique sur **"Resume"** ou **"Restore"**
3. Attends 2-3 minutes que le projet soit actif
4. Réessaie ensuite

---

**Va dans Supabase Dashboard > Settings > Database et copie-moi le Connection string exact que tu vois !** 🚀

