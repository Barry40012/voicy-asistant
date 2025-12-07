# 🔍 Debug Flutterwave - Page de paiement qui ne charge pas

## Problème

La page Flutterwave se charge (`https://checkout-v2.dev-flutterwave.com/v3/hosted/pay`) mais ne montre pas les champs pour insérer la carte.

## Causes possibles

### 1. **Lien incomplet**
Le lien retourné par Flutterwave peut être incomplet ou nécessiter des paramètres supplémentaires.

### 2. **Montant invalide**
- Le montant peut être `0` ou `null`
- Flutterwave ne charge pas la page si le montant est invalide

### 3. **Devise non supportée**
- La devise `XOF` peut ne pas être supportée en mode test
- Essayer avec `NGN` (Naira) pour les tests

### 4. **Clés API incorrectes**
- Vérifier que les clés sont bien en mode TEST
- Les clés doivent commencer par `FLWSECK_TEST-` et `FLWPUBK_TEST-`

## Solutions

### Solution 1 : Vérifier les logs

1. **Ouvre** : `backend/storage/logs/laravel.log`
2. **Cherche** : `Flutterwave payment response`
3. **Vérifie** :
   - Le `status_code` (doit être `200`)
   - Le `payment_link` retourné
   - Les erreurs éventuelles

### Solution 2 : Vérifier le montant

Dans la base de données, vérifie que les plans ont un `price_monthly` > 0 :

```sql
SELECT id, name, price_monthly, price_currency FROM plans;
```

### Solution 3 : Changer la devise pour les tests

Modifie temporairement la devise en `NGN` (Naira) pour les tests :

Dans `SubscriptionController.php`, ligne 55 :
```php
$plan->price_currency ?? 'NGN', // Utiliser NGN pour les tests
```

### Solution 4 : Vérifier le lien retourné

Le lien devrait être quelque chose comme :
```
https://checkout.flutterwave.com/v3/hosted/pay/{public_key}?tx_ref=VOICY_...
```

Si le lien est `https://checkout-v2.dev-flutterwave.com/v3/hosted/pay`, il manque peut-être des paramètres.

## Test manuel

1. **Va dans** : Dashboard > Mon Abonnement
2. **Clique sur** : "S'abonner avec ma carte Visa"
3. **Ouvre la console du navigateur** (F12)
4. **Vérifie** :
   - Les erreurs JavaScript
   - Les requêtes réseau
   - L'URL complète de redirection

## Prochaines étapes

1. Vérifie les logs Laravel
2. Vérifie le montant du plan dans la base de données
3. Essaie avec la devise `NGN` au lieu de `XOF`
4. Partage les logs si le problème persiste

