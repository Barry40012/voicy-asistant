# 🔧 Solution : Erreur DNS Supabase

## ❌ Problème

L'erreur `could not translate host name` signifie que ton ordinateur ne peut pas résoudre le nom d'hôte Supabase.

## ✅ Solutions

### Solution 1 : Vérifier la connexion internet

1. Vérifie que tu es connecté à internet
2. Essaie de ping le hostname :

```bash
ping db.yxorlhhcmjiabbjtqlwo.supabase.co
```

Si ça ne fonctionne pas, il y a un problème de connexion ou de DNS.

### Solution 2 : Vérifier le .env

Ouvre `E:\Project_Voicy_Assistant\backend\.env` et vérifie que :

```env
DB_HOST=db.yxorlhhcmjiabbjtqlwo.supabase.co
```

**⚠️ IMPORTANT** :
- Pas de `https://` avant
- Pas d'espaces avant/après le `=`
- Le hostname est exactement : `db.yxorlhhcmjiabbjtqlwo.supabase.co`

### Solution 3 : Vérifier que le projet Supabase est actif

1. Va dans Supabase Dashboard
2. Vérifie que ton projet n'est pas en **pause**
3. Si le projet est en pause, clique sur **"Resume"** ou **"Restore"**

### Solution 4 : Utiliser le Connection Pooling (Alternative)

Parfois le hostname direct ne fonctionne pas. Essaie avec le **Connection Pooling** :

Dans Supabase Dashboard :
1. Va dans **Settings > Database**
2. Cherche **"Connection pooling"**
3. Utilise le hostname du **Connection Pooling** (souvent différent)

### Solution 5 : Vérifier le firewall/antivirus

Parfois le firewall ou l'antivirus bloque les connexions PostgreSQL. Essaie de :
1. Désactiver temporairement le firewall
2. Ou ajouter une exception pour PostgreSQL

### Solution 6 : Utiliser l'IP directement (si disponible)

Si Supabase fournit une IP directe, utilise-la dans le `.env` :

```env
DB_HOST=xxx.xxx.xxx.xxx
```

---

## 🧪 Test de connexion

### Test 1 : Ping le hostname

```bash
ping db.yxorlhhcmjiabbjtqlwo.supabase.co
```

Si ça ne fonctionne pas, il y a un problème de DNS ou de connexion.

### Test 2 : Vérifier dans Supabase

1. Va dans Supabase Dashboard
2. **Settings > Database**
3. Vérifie le **Connection string**
4. Compare avec ton `.env`

### Test 3 : Tester avec psql (si installé)

Si tu as `psql` installé :

```bash
psql -h db.yxorlhhcmjiabbjtqlwo.supabase.co -U postgres -d postgres
```

---

## 🔍 Vérifications à faire

1. ✅ Extensions PostgreSQL installées (déjà fait)
2. ⚠️ Vérifier le `.env` (hostname correct)
3. ⚠️ Vérifier que le projet Supabase est actif
4. ⚠️ Vérifier la connexion internet
5. ⚠️ Vérifier le firewall

---

**Commence par vérifier que le projet Supabase est actif dans le Dashboard, puis teste le ping !** 🚀

