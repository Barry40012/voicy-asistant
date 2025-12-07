# 💳 Guide : Email de Reçu après Paiement

## ✅ Ce qui a été fait

1. **Email de reçu automatique** : Après un paiement réussi, un email de reçu est envoyé automatiquement
2. **Activation de l'abonnement** : L'abonnement est activé automatiquement après le paiement
3. **Badge sur le dashboard** : Le badge du plan apparaît automatiquement sur le dashboard
4. **Logs améliorés** : Tous les événements sont loggés pour le diagnostic

---

## 🔍 Vérifications après un paiement

### 1. Vérifier que l'abonnement est activé

Après le paiement, vérifie sur le dashboard :
- ✅ **Badge du plan** en haut du dashboard (gradient bleu-violet-rose)
- ✅ **Badge dans le menu** à côté de ton nom (⭐ Plan Pro)
- ✅ **Message de succès** en vert en haut

### 2. Vérifier l'email de reçu

1. **Vérifie ta boîte email** (et le dossier Spam)
2. **Cherche un email** avec le sujet : "Reçu de paiement - Voicy Assistant"
3. **L'email contient** :
   - Détails du paiement (montant, date, référence)
   - Détails de l'abonnement (plan, date d'expiration)
   - Informations de facturation

### 3. Vérifier les logs

Si l'email n'arrive pas, vérifie les logs :

```bash
# Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 100 | Select-String -Pattern "Payment receipt|email sent|Failed to send"
```

Cherche :
- `Payment receipt email sent successfully` → Email envoyé ✅
- `Failed to send payment receipt email` → Erreur d'envoi ❌

---

## 🚨 Problèmes courants

### Problème 1 : L'email n'arrive pas

**Causes possibles :**
1. L'email est dans les **spams**
2. Gmail met du temps à synchroniser
3. Erreur d'envoi (vérifie les logs)

**Solutions :**
1. Vérifie le dossier **Spam**
2. Vérifie sur **tous tes appareils** (téléphone, ordinateur)
3. Vérifie les **logs** pour voir les erreurs
4. Attends quelques minutes (Gmail peut prendre du temps)

### Problème 2 : Le badge n'apparaît pas

**Causes possibles :**
1. L'abonnement n'est pas activé
2. Le cache du navigateur
3. La page n'est pas rafraîchie

**Solutions :**
1. **Rafraîchis la page** (F5 ou Ctrl+R)
2. **Vide le cache** du navigateur
3. **Vérifie dans la base de données** que l'abonnement est actif :
   ```bash
   php artisan tinker
   ```
   ```php
   $user = App\Models\User::where('email', 'ton_email@example.com')->first();
   $sub = $user->subscriptions()->where('status', 'active')->first();
   if ($sub) {
       echo "Abonnement actif: " . $sub->plan->name;
   } else {
       echo "Aucun abonnement actif";
   }
   ```

### Problème 3 : L'abonnement n'est pas activé

**Causes possibles :**
1. Le callback Flutterwave n'a pas fonctionné
2. Le statut du paiement n'est pas "succeeded"
3. L'ID de l'abonnement est manquant dans les métadonnées

**Solutions :**
1. **Vérifie les logs** pour voir si le callback a été reçu
2. **Vérifie le statut du paiement** dans la base de données
3. **Active manuellement** si nécessaire :
   ```bash
   php artisan subscriptions:activate-pending
   ```

---

## 🧪 Test complet

### Étape 1 : Faire un paiement

1. Va sur **Dashboard > Mon Abonnement**
2. Choisis un plan (Starter ou Pro)
3. Clique sur **"S'abonner"**
4. Utilise la **carte de test** Flutterwave
5. Complète le paiement

### Étape 2 : Vérifier après le paiement

Après le paiement, tu devrais voir :

1. **Redirection automatique** vers le Dashboard
2. **Message de succès** en vert :
   - "🎉 Félicitations ! Votre abonnement [Nom] est maintenant actif !"
   - "Un reçu a été envoyé à votre adresse email"
3. **Badge du plan** en haut du dashboard
4. **Badge dans le menu** à côté de ton nom
5. **Email de reçu** dans ta boîte email

### Étape 3 : Vérifier l'email

1. Ouvre ta boîte email
2. Cherche l'email de reçu
3. Vérifie les détails du paiement et de l'abonnement

---

## 📝 Checklist

Après un paiement, vérifie que :

- [ ] Le message de succès apparaît sur le dashboard
- [ ] Le badge du plan apparaît en haut du dashboard
- [ ] Le badge apparaît dans le menu à côté de ton nom
- [ ] L'email de reçu arrive (vérifie aussi les spams)
- [ ] Les logs montrent "Payment receipt email sent successfully"
- [ ] L'abonnement est marqué comme "active" dans la base de données

---

## 🔧 Commandes utiles

```bash
# Vérifier les logs d'email
Get-Content storage/logs/laravel.log -Tail 100 | Select-String -Pattern "Payment receipt"

# Activer manuellement les abonnements en attente
php artisan subscriptions:activate-pending

# Vider le cache
php artisan config:clear
php artisan cache:clear
```

---

## ✅ Résumé

**Le système fonctionne automatiquement :**
- ✅ Paiement réussi → Abonnement activé
- ✅ Abonnement activé → Email de reçu envoyé
- ✅ Abonnement activé → Badge affiché sur le dashboard

**Si quelque chose ne fonctionne pas :**
1. Vérifie les **logs** pour voir les erreurs
2. Vérifie le dossier **Spam** pour l'email
3. **Rafraîchis la page** pour voir le badge
4. Utilise les **commandes de diagnostic** ci-dessus

---

**Teste maintenant et dis-moi si tout fonctionne !** 🚀

