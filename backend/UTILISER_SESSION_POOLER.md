# 🔧 Solution : Utiliser Session Pooler (IPv4 compatible)

## ❌ Problème identifié

Le message **"Not IPv4 compatible"** signifie que le hostname direct ne fonctionne pas sur un réseau IPv4.

## ✅ Solution : Utiliser Session Pooler

### Étape 1 : Trouver le Session Pooler

Dans Supabase Dashboard > Settings > Database :

1. Cherche la section **"Pooler settings"** ou **"Connection pooling"**
2. Tu devrais voir une option **"Session Pooler"** ou **"Transaction Pooler"**
3. Clique dessus ou cherche le **Connection string** pour le Session Pooler

### Étape 2 : Récupérer le hostname du Session Pooler

Le hostname du Session Pooler est généralement différent, par exemple :
- `aws-0-eu-central-1.pooler.supabase.com`
- `aws-0-us-east-1.pooler.supabase.com`
- Ou un autre format selon ta région

Le port est aussi différent : **6543** au lieu de **5432**

### Étape 3 : Modifier le .env

Ouvre `E:\Project_Voicy_Assistant\backend\.env` et modifie :

**AVANT** (ne fonctionne pas) :
```env
DB_HOST=db.yxorlhhcmjiabbjtqlwo.supabase.co
DB_PORT=5432
```

**APRÈS** (avec Session Pooler) :
```env
DB_HOST=aws-0-eu-central-1.pooler.supabase.com
DB_PORT=6543
```

**⚠️ IMPORTANT** : Remplace `aws-0-eu-central-1.pooler.supabase.com` par le vrai hostname du Session Pooler que tu vois dans Supabase.

---

## 🔍 Comment trouver le Session Pooler

Dans Supabase Dashboard :

1. **Settings > Database**
2. Cherche **"Connection pooling"** ou **"Pooler settings"**
3. Tu devrais voir :
   - **Session mode** (port 5432) - pour les connexions longues
   - **Transaction mode** (port 6543) - pour les connexions courtes
4. Utilise le **Session mode** pour Laravel

Le Connection String devrait ressembler à :
```
postgresql://postgres.yxorlhhcmjiabbjtqlwo:[PASSWORD]@aws-0-eu-central-1.pooler.supabase.com:5432/postgres
```

Extrais le hostname (entre `@` et `:5432` ou `:6543`).

---

## 🧪 Test

Une fois modifié :

1. Teste le ping :
   ```bash
   ping LE_HOSTNAME_DU_POOLER
   ```

2. Si ça fonctionne, teste les migrations :
   ```bash
   php artisan migrate
   ```

---

**Cherche la section "Connection pooling" ou "Pooler settings" dans Settings > Database et donne-moi le Connection String du Session Pooler !** 🚀

