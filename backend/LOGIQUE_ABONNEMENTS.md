# 📋 Logique des Abonnements - Voicy Assistant

## 🎯 Principe Fondamental

**Un seul abonnement actif à la fois par utilisateur.**

C'est la logique standard utilisée par toutes les plateformes SaaS (Netflix, Spotify, etc.).

---

## 🔄 Comment ça fonctionne ?

### Scénario 1 : Passer de Free à Pro

1. **Utilisateur** : A l'abonnement Free actif
2. **Action** : Clique sur "S'abonner" pour le plan Pro
3. **Paiement** : Effectue le paiement sur Flutterwave
4. **Résultat** :
   - ✅ Free est automatiquement **désactivé** (`status = 'cancelled'`)
   - ✅ Pro est automatiquement **activé** (`status = 'active'`)
   - ✅ Redirection automatique vers le dashboard
   - ✅ Badge dans la navigation passe de "Free" à "Pro"

### Scénario 2 : Passer de Pro à Starter

1. **Utilisateur** : A l'abonnement Pro actif (5000 audios/mois)
2. **Action** : Clique sur "S'abonner" pour le plan Starter
3. **Paiement** : Effectue le paiement
4. **Résultat** :
   - ✅ Pro est automatiquement **désactivé** (`status = 'cancelled'`)
   - ✅ Starter est automatiquement **activé** (`status = 'active'`)
   - ✅ Les limites passent de 5000 à 500 audios/mois
   - ✅ Badge passe de "Pro" à "Starter"

### Scénario 3 : Passer de Pro à Free

1. **Utilisateur** : A l'abonnement Pro actif
2. **Action** : Clique sur "Activer gratuitement" pour le plan Free
3. **Résultat** :
   - ✅ Pro est automatiquement **désactivé** (`status = 'cancelled'`)
   - ✅ Free est automatiquement **activé** (`status = 'active'`)
   - ✅ Les limites passent de 5000 à 50 audios/mois
   - ✅ Badge passe de "Pro" à "Free"
   - ⚠️ **Note** : Le paiement Pro reste dans l'historique mais l'abonnement est désactivé

### Scénario 4 : Réactiver un ancien abonnement

**Question** : Si j'ai déjà payé Pro, puis je passe à Free, puis je veux revenir à Pro, dois-je repayer ?

**Réponse** : Oui, tu dois repayer. Chaque abonnement est indépendant :
- Quand tu passes de Pro à Free, Pro est désactivé
- Pour revenir à Pro, tu dois créer un **nouvel abonnement Pro** et payer à nouveau
- Tu ne peux pas "réactiver" un ancien abonnement Pro qui a été désactivé

---

## ❌ Ce qui n'est PAS possible

### ❌ Avoir plusieurs abonnements actifs en même temps

**Exemple** : Tu ne peux pas avoir à la fois :
- Free (50 audios/mois) **ET**
- Pro (5000 audios/mois)

**Pourquoi ?** : Ce serait illogique. Si tu as Pro, tu as déjà 5000 audios, donc Free n'apporte rien.

### ❌ "Réactiver" un ancien abonnement sans payer

**Exemple** : 
- Tu as payé Pro en janvier
- Tu passes à Free en février
- Tu ne peux pas "réactiver" Pro en mars sans payer à nouveau

**Pourquoi ?** : Chaque abonnement est lié à une période spécifique (1 mois). Une fois désactivé, il est terminé.

---

## ✅ Ce qui EST possible

### ✅ Changer de plan à tout moment

Tu peux passer de n'importe quel plan à n'importe quel autre plan :
- Free → Starter → Pro → Free → Pro
- Aucune restriction

### ✅ Voir l'historique de tous tes abonnements

Tous tes abonnements (actifs et désactivés) sont conservés dans l'historique :
- Tu peux voir quand tu as eu Pro
- Tu peux voir quand tu as eu Starter
- Tu peux voir tous tes paiements

---

## 🔧 Implémentation Technique

### Code qui gère la désactivation automatique

```php
// Dans SubscriptionController::callback() et activatePaymentFromCheck()

// Désactiver tous les autres abonnements actifs
Subscription::where('user_id', $user_id)
    ->where('id', '!=', $new_subscription_id)
    ->where('status', 'active')
    ->update(['status' => 'cancelled']);

// Activer le nouvel abonnement
$new_subscription->update([
    'status' => 'active',
    'started_at' => now(),
    'expires_at' => now()->addMonth(),
]);
```

### Méthode pour récupérer l'abonnement actif

```php
// Dans User::activeSubscription()

public function activeSubscription()
{
    return $this->subscriptions()
        ->where('status', 'active')
        ->with('plan')
        ->orderBy('created_at', 'desc') // Le plus récent
        ->first();
}
```

---

## 📊 Exemple Concret

### Historique d'un utilisateur

```
01/12/2025 10:00 - Abonnement Free créé → ✅ Actif
01/12/2025 15:00 - Abonnement Pro créé → ✅ Actif (Free désactivé)
02/12/2025 10:00 - Abonnement Free créé → ✅ Actif (Pro désactivé)
05/12/2025 14:00 - Abonnement Pro créé → ✅ Actif (Free désactivé)
```

**Résultat** : L'utilisateur a toujours **un seul abonnement actif** à la fois.

---

## 🎯 Résumé

1. ✅ **Un seul abonnement actif** à la fois
2. ✅ **Changement automatique** : quand tu achètes un nouveau plan, l'ancien est désactivé
3. ✅ **Pas de réactivation gratuite** : pour revenir à un plan payant, tu dois repayer
4. ✅ **Historique conservé** : tous tes abonnements sont dans l'historique
5. ✅ **Flexibilité totale** : tu peux changer de plan à tout moment

---

**C'est la logique standard des plateformes SaaS modernes !** 🚀

