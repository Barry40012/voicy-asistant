# 🔍 Guide de Débogage : Token HuggingFace

## 🚨 Problème : "Token API invalide" (400 Bad Request)

Si vous recevez toujours cette erreur, suivez ces étapes pour identifier le problème.

## 📋 Étape 1 : Vérifier le Token dans la Base de Données

### Option A : Via Tinker (Recommandé)

```bash
php artisan tinker
```

Puis dans tinker :
```php
$provider = \App\Models\AIProvider::where('name', 'huggingface')->first();
echo "Environment: " . $provider->environment . "\n";
echo "Credentials: " . json_encode($provider->credentials, JSON_PRETTY_PRINT) . "\n";
$token = $provider->getCredential('api_key');
echo "Token récupéré: " . ($token ? substr($token, 0, 10) . '...' : 'VIDE') . "\n";
echo "Longueur du token: " . strlen($token ?? '') . "\n";
```

### Option B : Vérifier les Logs

```bash
tail -f storage/logs/laravel.log
```

Cherchez les lignes contenant "Testing AI Provider" pour voir ce qui est récupéré.

## 📋 Étape 2 : Tester le Token Manuellement

### Méthode 1 : Script PHP

```bash
php scripts/test-huggingface-token.php VOTRE_TOKEN_COMPLET_ICI
```

Remplacez `VOTRE_TOKEN_COMPLET_ICI` par votre token complet (celui qui commence par `hf_`).

### Méthode 2 : Via cURL

```bash
curl https://huggingface.co/api/whoami -H "Authorization: Bearer VOTRE_TOKEN_ICI"
```

Si ça fonctionne, vous verrez votre nom d'utilisateur HuggingFace.

## 📋 Étape 3 : Vérifier le Format du Token

Un token HuggingFace valide :
- ✅ Commence par `hf_`
- ✅ Fait 40-50 caractères
- ✅ Ne contient pas d'espaces
- ✅ Contient uniquement des lettres, chiffres et underscores

Exemple valide : `hf_ABC123...XYZ789` (remplacez par votre token réel de 40-50 caractères)

## 📋 Étape 4 : Vérifier Comment le Token est Stocké

Le token peut être stocké de plusieurs façons dans la base de données :

1. **Directement** : `{"api_key": "hf_..."}`
2. **Avec préfixe test** : `{"test_api_key": "hf_...", "api_key": "hf_..."}`
3. **Avec préfixe live** : `{"live_api_key": "hf_...", "api_key": "hf_..."}`

La méthode `getCredential('api_key')` cherche dans cet ordre :
1. `test_api_key` (si environment = 'test')
2. `live_api_key` (si environment = 'live')
3. `api_key` (fallback)

## 🔧 Solution : Réinitialiser le Token

### Méthode 1 : Via l'Interface

1. Allez sur `/admin/ai-providers`
2. Cliquez sur "Configurer" pour HuggingFace
3. **Effacez complètement** le champ "Token API (Test)"
4. Allez sur [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
5. **Créez un nouveau token** :
   - Nom : "Voicy Assistant"
   - Type : "Read"
   - Cliquez sur "Generate token"
6. **Copiez le token** (cliquez sur l'icône de copie)
7. **Collez-le** dans le champ "Token API (Test)"
8. **Vérifiez** qu'il n'y a pas d'espaces avant/après
9. Cliquez sur "Enregistrer"
10. Testez à nouveau

### Méthode 2 : Via Tinker (Si l'interface ne fonctionne pas)

```bash
php artisan tinker
```

```php
$provider = \App\Models\AIProvider::where('name', 'huggingface')->first();
$provider->credentials = [
    'api_key' => 'VOTRE_TOKEN_ICI',
    'test_api_key' => 'VOTRE_TOKEN_ICI',
];
$provider->save();
echo "Token mis à jour!\n";
```

## 🐛 Problèmes Courants

### Problème 1 : Token avec Espaces

**Symptôme** : Token semble correct mais échoue

**Solution** :
```php
// Dans tinker
$provider = \App\Models\AIProvider::where('name', 'huggingface')->first();
$token = trim($provider->getCredential('api_key'));
$provider->credentials['api_key'] = $token;
$provider->credentials['test_api_key'] = $token;
$provider->save();
```

### Problème 2 : Token Incomplet

**Symptôme** : Token fait moins de 20 caractères

**Solution** : Recréez le token et copiez-le complètement

### Problème 3 : Mauvais Environnement

**Symptôme** : Token configuré mais non trouvé

**Solution** :
```php
// Dans tinker
$provider = \App\Models\AIProvider::where('name', 'huggingface')->first();
$provider->environment = 'test'; // ou 'live'
$provider->save();
```

## ✅ Checklist de Vérification

- [ ] Token commence par `hf_`
- [ ] Token fait 40-50 caractères
- [ ] Token copié complètement (pas seulement les premiers caractères)
- [ ] Pas d'espaces avant/après le token
- [ ] Token testé manuellement avec curl ou le script PHP
- [ ] Token stocké correctement dans la base de données
- [ ] Environnement correct (test/live)
- [ ] Token a la permission "Read" minimum

## 🆘 Si Rien ne Fonctionne

1. **Créez un nouveau compte HuggingFace** (si possible)
2. **Générez un nouveau token**
3. **Testez-le manuellement** avec curl
4. **Si le test manuel fonctionne**, le problème vient de la configuration dans Laravel
5. **Vérifiez les logs** : `tail -f storage/logs/laravel.log`

## 📞 Support

Si le problème persiste après avoir suivi toutes ces étapes :
1. Vérifiez les logs complets
2. Testez le token manuellement
3. Vérifiez la configuration dans la base de données
4. Contactez le support avec les informations des logs

