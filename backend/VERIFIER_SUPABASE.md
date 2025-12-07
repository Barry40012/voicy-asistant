# 🔍 Vérifier la Connexion Supabase

## ❌ Problème

Le hostname `aws-1-eu-west-1.pooler.supabase.com` ne se résout pas.

## 🔍 Causes Possibles

1. **Projet Supabase en pause** (gratuit après inactivité)
2. **Problème de réseau/DNS**
3. **Hostname incorrect dans le .env**

---

## ✅ Solutions

### Solution 1 : Vérifier le Projet Supabase

1. Va sur : https://supabase.com/dashboard
2. **Connecte-toi** à ton compte
3. **Vérifie** que le projet `voicy_assistant` est **actif** (pas en pause)
4. Si le projet est **en pause** :
   - Clique sur **"Restore"** ou **"Resume"**
   - Attends quelques minutes

### Solution 2 : Récupérer le Nouveau Hostname

Si le projet a été restauré, le hostname peut avoir changé :

1. Dans Supabase Dashboard > **Settings** > **Database**
2. Cherche **"Connection pooling"** ou **"Pooler settings"**
3. Récupère le **nouveau hostname** du Transaction Pooler
4. **Mets à jour** le `.env` avec le nouveau hostname

### Solution 3 : Utiliser le Hostname Direct (Alternative)

Si le pooler ne fonctionne pas, utilise le hostname direct :

1. Dans Supabase Dashboard > **Settings** > **Database**
2. Cherche **"Connection string"** ou **"Direct connection"**
3. Le hostname devrait être : `db.yxorlhhcmjiabbjtqlwo.supabase.co`
4. **Modifie le .env** :
   ```env
   DB_HOST=db.yxorlhhcmjiabbjtqlwo.supabase.co
   DB_PORT=5432
   DB_USERNAME=postgres
   ```

---

## 🧪 Tester

1. **Ping le hostname** :
   ```bash
   ping aws-1-eu-west-1.pooler.supabase.com
   ```

2. Si ça ne fonctionne pas, **vérifie le projet Supabase** dans le dashboard

---

**Vérifie d'abord si ton projet Supabase est actif !** 🚀

