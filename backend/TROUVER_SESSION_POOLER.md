# 🔍 Trouver le Connection String du Session Pooler

## 📋 Étapes pour trouver le Session Pooler

### Option 1 : Dans la section "Connection pooling"

Sur la page Database Settings où tu es :

1. Cherche la section **"Connection pooling configuration"**
2. Tu vois **"Shared Pooler"** - clique dessus ou cherche un lien **"Connection string"**
3. Il devrait y avoir un onglet ou une section pour voir le Connection String

### Option 2 : Dans "Connect to your project"

1. Va dans **Settings** (icône engrenage en bas à gauche)
2. Clique sur **"Database"** (tu y es déjà)
3. En haut de la page, cherche **"Connect to your project"** ou un bouton similaire
4. Clique dessus
5. Tu devrais voir plusieurs onglets :
   - **Direct connection** (celui que tu as déjà)
   - **Session Pooler** ou **Connection Pooling** ← **C'EST CELUI-CI**
   - **Transaction Pooler**

### Option 3 : Utiliser le format standard

Si tu ne trouves pas le Connection String exact, le format standard du Session Pooler est :

```
postgresql://postgres.yxorlhhcmjiabbjtqlwo:[PASSWORD]@aws-0-[REGION].pooler.supabase.com:5432/postgres
```

Où `[REGION]` dépend de ta région Supabase.

Pour trouver ta région :
1. Va dans **Settings > General**
2. Cherche **"Region"** ou **"Project region"**
3. Note la région (ex: `eu-central-1`, `us-east-1`, etc.)

Ensuite, le hostname serait :
- `aws-0-eu-central-1.pooler.supabase.com` (si région EU Central)
- `aws-0-us-east-1.pooler.supabase.com` (si région US East)
- etc.

---

## 🎯 Action immédiate

1. **Cherche un onglet ou un lien "Session Pooler"** dans la page Database Settings
2. **Ou va dans Settings > General** et note la **Region** du projet
3. **Donne-moi la région** et je te donnerai le hostname exact

---

## 🔄 Alternative : Utiliser Transaction Pooler

Si tu ne trouves pas le Session Pooler, tu peux utiliser le **Transaction Pooler** (port 6543) :

Le format serait :
```
DB_HOST=aws-0-[REGION].pooler.supabase.com
DB_PORT=6543
```

---

**Cherche "Session Pooler" ou "Connection Pooling" dans Database Settings, ou donne-moi la région de ton projet (Settings > General) !** 🚀

