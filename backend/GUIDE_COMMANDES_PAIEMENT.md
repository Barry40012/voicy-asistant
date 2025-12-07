# 💳 Guide : Commandes de Gestion des Paiements

## 📋 Commandes disponibles

### 1. Diagnostiquer un utilisateur

```bash
php artisan payment:diagnose email@example.com
```

**Exemple :**
```bash
php artisan payment:diagnose barryyoussouf400@gmail.com
```

**Affiche :**
- 📊 Tous les paiements de l'utilisateur
- 📋 Tous les abonnements
- 🔍 Analyse des problèmes

---

### 2. Marquer un paiement comme réussi

```bash
php artisan payment:mark-successful {ID_DU_PAIEMENT}
```

**⚠️ Important :** Remplace `{ID_DU_PAIEMENT}` par un **vrai nombre**, pas le texte `{payment_id}` !

**Exemples :**
```bash
# ✅ Correct
php artisan payment:mark-successful 10
php artisan payment:mark-successful 9
php artisan payment:mark-successful 8

# ❌ Incorrect (ne fonctionne pas)
php artisan payment:mark-successful {payment_id}
php artisan payment:mark-successful payment_id
```

**Cette commande :**
- ✅ Marque le paiement comme "succeeded"
- ✅ Active l'abonnement correspondant
- ✅ Envoie l'email de reçu

**Pour trouver l'ID du paiement :**
1. Utilise `php artisan payment:diagnose email@example.com`
2. Regarde la colonne "ID" dans le tableau des paiements
3. Utilise cet ID dans la commande

---

### 3. Activer tous les abonnements pour un utilisateur

```bash
php artisan payment:activate-subscriptions email@example.com
```

**Exemple :**
```bash
php artisan payment:activate-subscriptions barryyoussouf400@gmail.com
```

**Cette commande :**
- ✅ Trouve tous les paiements réussis
- ✅ Active les abonnements correspondants
- ✅ Envoie les emails de reçu

---

## 🎯 Cas d'usage

### Cas 1 : Un paiement est en "pending" mais tu as bien payé

**Solution :**
```bash
# 1. Voir l'ID du paiement
php artisan payment:diagnose barryyoussouf400@gmail.com

# 2. Activer le paiement (remplace 10 par l'ID réel)
php artisan payment:mark-successful 10
```

### Cas 2 : Plusieurs paiements en "pending"

**Solution :**
```bash
# Active tous les abonnements automatiquement
php artisan payment:activate-subscriptions barryyoussouf400@gmail.com
```

### Cas 3 : Vérifier l'état actuel

**Solution :**
```bash
# Voir tous les paiements et abonnements
php artisan payment:diagnose barryyoussouf400@gmail.com
```

---

## ⚠️ Erreurs courantes

### Erreur : "Invalid text representation for type bigint"

**Cause :** Tu as utilisé `{payment_id}` au lieu d'un vrai nombre

**Solution :** Utilise un vrai ID, par exemple :
```bash
# ❌ Incorrect
php artisan payment:mark-successful {payment_id}

# ✅ Correct
php artisan payment:mark-successful 10
```

### Erreur : "Paiement non trouvé"

**Cause :** L'ID du paiement n'existe pas

**Solution :**
1. Vérifie l'ID avec `php artisan payment:diagnose email@example.com`
2. Utilise le bon ID

---

## 📝 Exemple complet

```bash
# 1. Diagnostiquer
E:\Project_Voicy_Assistant\backend> php artisan payment:diagnose barryyoussouf400@gmail.com

# Résultat : Tu vois que le paiement #9 est en "pending"

# 2. Activer le paiement
E:\Project_Voicy_Assistant\backend> php artisan payment:mark-successful 9

# Résultat : 
# ✅ Paiement marqué comme réussi
# ✅ Abonnement activé
# 📧 Email envoyé

# 3. Vérifier
E:\Project_Voicy_Assistant\backend> php artisan payment:diagnose barryyoussouf400@gmail.com

# Résultat : Tu vois que le paiement #9 est maintenant "✅ Réussi" et l'abonnement est "✅ Actif"
```

---

## ✅ Checklist

Avant d'utiliser une commande :

- [ ] J'ai bien remplacé `{payment_id}` par un **vrai nombre**
- [ ] J'ai vérifié l'ID avec `payment:diagnose`
- [ ] J'ai utilisé le bon email pour les commandes qui le demandent

---

**Rappel :** `{payment_id}` est juste un **exemple** dans la documentation. Tu dois le remplacer par un **vrai ID** ! 🚀

