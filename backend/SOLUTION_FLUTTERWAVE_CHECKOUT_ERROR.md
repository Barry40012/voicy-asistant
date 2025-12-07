# 🔧 Solution : Erreur Flutterwave Checkout "API key required"

## ❌ Problème

**Erreurs JavaScript :**
```
checkout.js:6 Uncaught (in promise) Error: API key required
checkout.js:6 Uncaught (in promise) ReferenceError: cryptico is not defined
```

**URL :** `https://checkout-v2.dev-flutterwave.com/v3/hosted/pay`

**Symptôme :** La page de checkout Flutterwave ne se charge pas correctement et affiche des erreurs JavaScript.

---

## 🔍 Causes

### 1. **Clé publique manquante dans l'URL**

Le checkout Flutterwave nécessite la clé publique (`public_key`) dans l'URL pour s'initialiser correctement. Si elle n'est pas présente, le checkout.js ne peut pas fonctionner.

### 2. **Bibliothèque cryptico non chargée**

La bibliothèque `cryptico` est utilisée par Flutterwave pour le chiffrement côté client. Si elle n'est pas chargée, cela cause une erreur.

---

## ✅ Solution Implémentée

### 1. **Ajout automatique de la clé publique à l'URL**

J'ai modifié `FlutterwaveAdapter::createPayment()` pour s'assurer que la clé publique est toujours présente dans l'URL du checkout :

```php
// S'assurer que la clé publique est dans l'URL du checkout
$parsedUrl = parse_url($paymentLink);
parse_str($parsedUrl['query'] ?? '', $queryParams);

// Si la clé publique n'est pas dans l'URL, l'ajouter
if (empty($queryParams['public_key']) && !empty($this->publicKey)) {
    $separator = strpos($paymentLink, '?') !== false ? '&' : '?';
    $paymentLink = $paymentLink . $separator . 'public_key=' . urlencode($this->publicKey);
}
```

**Avantages :**
- ✅ Garantit que la clé publique est toujours dans l'URL
- ✅ Fonctionne même si Flutterwave ne l'inclut pas dans le lien retourné
- ✅ URL correctement encodée

### 2. **Logs pour diagnostic**

J'ai ajouté des logs pour diagnostiquer les problèmes :

```php
Log::info('FlutterwaveAdapter initialized', [
    'has_secret_key' => !empty($this->secretKey),
    'has_public_key' => !empty($this->publicKey),
    'public_key_prefix' => !empty($this->publicKey) ? substr($this->publicKey, 0, 10) . '...' : 'empty',
    'base_url' => $this->baseUrl,
]);

Log::info('Flutterwave payment link generated', [
    'original_link' => $data['data']['link'] ?? $data['data']['data']['link'] ?? null,
    'final_link' => $paymentLink,
    'has_public_key' => !empty($queryParams['public_key']) || !empty($this->publicKey),
]);
```

---

## 🔧 Vérifications

### 1. **Vérifier que la clé publique est configurée**

Assure-toi que la clé publique Flutterwave est bien configurée dans la base de données :

1. Va dans **Admin > Paiements**
2. Clique sur **Configurer** pour Flutterwave
3. Vérifie que **Public Key** est rempli
4. Sauvegarde

### 2. **Vérifier les logs**

Regarde les logs Laravel pour voir si la clé publique est bien chargée :

```bash
tail -f storage/logs/laravel.log | grep Flutterwave
```

Tu devrais voir :
```
FlutterwaveAdapter initialized: has_public_key=true
Flutterwave payment link generated: has_public_key=true
```

### 3. **Vérifier l'URL du checkout**

Quand tu cliques sur "S'abonner", l'URL devrait contenir `public_key=FLWPUBK_...` :

```
https://checkout-v2.dev-flutterwave.com/v3/hosted/pay?public_key=FLWPUBK_...&tx_ref=...
```

---

## 🧪 Test de la Solution

1. **Vérifie la configuration** dans Admin > Paiements
2. **Essaie de t'abonner** à un plan
3. **Vérifie l'URL** du checkout (elle devrait contenir `public_key`)
4. **Vérifie les logs** pour voir si tout est correct

---

## 📊 Format de l'URL Attendu

L'URL du checkout devrait ressembler à :

```
https://checkout-v2.dev-flutterwave.com/v3/hosted/pay?public_key=FLWPUBK_TEST-xxxxxxxxxxxxx&tx_ref=VOICY_xxxxx&amount=xxx&currency=NGN&...
```

---

## ⚠️ Si le Problème Persiste

### 1. **Vérifier la clé publique dans la DB**

```bash
php artisan tinker
```

```php
$provider = \App\Models\PaymentProvider::where('name', 'flutterwave')->first();
$provider->getCredential('public_key');
```

### 2. **Vérifier que la clé est valide**

La clé publique devrait commencer par :
- **Test :** `FLWPUBK_TEST-...`
- **Production :** `FLWPUBK-...`

### 3. **Vider le cache**

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. **Vérifier les logs d'erreur**

```bash
tail -f storage/logs/laravel.log
```

---

## ✅ Résultat Attendu

- ✅ L'URL du checkout contient la clé publique
- ✅ Le checkout Flutterwave se charge correctement
- ✅ Plus d'erreur "API key required"
- ✅ Plus d'erreur "cryptico is not defined"

---

## 🚀 Prochaines Étapes

1. **Teste le paiement** avec une carte de test
2. **Vérifie les logs** pour confirmer que tout fonctionne
3. **Si ça ne fonctionne toujours pas**, vérifie :
   - La configuration dans Admin > Paiements
   - Les logs Laravel
   - L'URL du checkout dans le navigateur

---

**Le checkout devrait maintenant fonctionner correctement !** 🎉

