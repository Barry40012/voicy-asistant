# 🔄 Guide : Bascule entre les Plans - Voicy Assistant

## ✅ OUI, c'est 100% logique et normal !

**Tu peux basculer entre n'importe quels plans à tout moment sur le même compte.**

C'est exactement comme Netflix, Spotify, ou n'importe quelle plateforme SaaS moderne.

---

## 🎯 Exemple Concret de Bascule

### Scénario : Utilisateur qui change de plan plusieurs fois

**Jour 1 - 10:00** : Utilisateur s'inscrit → **Free actif** (50 audios/mois)

**Jour 1 - 15:00** : Utilisateur achète Pro → **Pro actif** (5000 audios/mois)
- ✅ Free est automatiquement désactivé
- ✅ Pro est activé
- ✅ Badge passe de "Free" à "Pro"

**Jour 5 - 14:00** : Utilisateur achète Starter → **Starter actif** (500 audios/mois)
- ✅ Pro est automatiquement désactivé
- ✅ Starter est activé
- ✅ Badge passe de "Pro" à "Starter"
- ⚠️ **Note** : Pro reste dans l'historique mais n'est plus actif

**Jour 10 - 09:00** : Utilisateur veut revenir à Pro → **Pro actif** (5000 audios/mois)
- ✅ Starter est automatiquement désactivé
- ✅ **Nouvel abonnement Pro créé** (tu dois repayer)
- ✅ Pro est activé
- ✅ Badge passe de "Starter" à "Pro"

**Jour 15 - 16:00** : Utilisateur passe à Free → **Free actif** (50 audios/mois)
- ✅ Pro est automatiquement désactivé
- ✅ Free est activé
- ✅ Badge passe de "Pro" à "Free"

---

## 📊 Historique dans la Base de Données

```
Abonnements de l'utilisateur :
├── ID 1: Free   → status: cancelled (désactivé le 01/12 15:00)
├── ID 2: Pro    → status: cancelled (désactivé le 05/12 14:00)
├── ID 3: Starter → status: cancelled (désactivé le 10/12 09:00)
├── ID 4: Pro    → status: cancelled (désactivé le 15/12 16:00)
└── ID 5: Free   → status: active ✅ (actif maintenant)
```

**Résultat** : Un seul abonnement actif (Free), mais tous les autres sont dans l'historique.

---

## ✅ Ce qui est POSSIBLE

### ✅ Basculer entre tous les plans
- Free → Pro → Starter → Free → Pro
- Aucune restriction
- Aucune limite de changements

### ✅ Voir l'historique complet
- Tous tes abonnements sont conservés
- Tous tes paiements sont dans l'historique
- Tu peux voir quand tu as eu chaque plan

### ✅ Changer à tout moment
- Pas besoin d'attendre la fin du mois
- Changement immédiat après paiement
- L'ancien plan est désactivé automatiquement

---

## ❌ Ce qui n'est PAS possible

### ❌ Avoir plusieurs plans actifs en même temps
**Exemple** : Tu ne peux pas avoir Free ET Pro actifs en même temps.

**Pourquoi ?** : Ce serait illogique. Si tu as Pro (5000 audios), tu n'as pas besoin de Free (50 audios).

### ❌ Réactiver un ancien abonnement sans payer
**Exemple** : 
- Tu as payé Pro en janvier
- Tu passes à Free en février
- Tu ne peux pas "réactiver" Pro en mars sans payer à nouveau

**Pourquoi ?** : Chaque abonnement est lié à une période spécifique (1 mois). Une fois désactivé, il est terminé.

---

## 🔧 Comment ça fonctionne techniquement ?

### Code qui gère la bascule automatique

```php
// Quand un nouvel abonnement est activé :

// 1. Désactiver TOUS les autres abonnements actifs
Subscription::where('user_id', $user_id)
    ->where('id', '!=', $new_subscription_id)
    ->where('status', 'active')
    ->update(['status' => 'cancelled']);

// 2. Activer le nouvel abonnement
$new_subscription->update([
    'status' => 'active',
    'started_at' => now(),
    'expires_at' => now()->addMonth(),
]);
```

**Résultat** : Un seul abonnement actif à la fois, automatiquement.

---

## 💡 Questions Fréquentes

### Q1 : Si j'ai Pro et je passe à Starter, est-ce que je perds mes 5000 audios ?

**R :** Oui, mais c'est normal. Quand tu passes à Starter, tu as maintenant 500 audios/mois. C'est le principe d'un changement de plan.

### Q2 : Si je reviens à Pro plus tard, dois-je repayer ?

**R :** Oui, tu dois repayer. Chaque abonnement est indépendant. Tu ne peux pas "réactiver" un ancien abonnement.

### Q3 : Est-ce que je peux avoir Pro ET Starter en même temps ?

**R :** Non, un seul abonnement actif à la fois. C'est la logique standard des SaaS.

### Q4 : Est-ce que mes anciens abonnements sont perdus ?

**R :** Non, ils sont conservés dans l'historique. Tu peux voir tous tes abonnements passés.

---

## 🎯 Résumé

✅ **OUI, c'est logique et normal** de basculer entre les plans

✅ **OUI, tu peux** avoir Pro, puis Starter, puis Pro, puis Free, etc.

✅ **OUI, c'est automatique** : l'ancien plan est désactivé automatiquement

✅ **OUI, c'est comme Netflix/Spotify** : un seul plan actif à la fois

---

## 🚀 Exemple Réel

**Utilisateur "Jean" :**
- Semaine 1 : Free (50 audios/mois)
- Semaine 2 : Pro (5000 audios/mois) - Free désactivé
- Semaine 3 : Starter (500 audios/mois) - Pro désactivé
- Semaine 4 : Pro (5000 audios/mois) - Starter désactivé, nouveau paiement Pro

**Résultat** : Jean a toujours un seul plan actif, mais il peut changer à tout moment !

---

**C'est la logique standard des plateformes SaaS modernes !** 🎉

