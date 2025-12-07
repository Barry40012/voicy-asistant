# Guide de Déploiement en Production

## Configuration SSL pour les Requêtes HTTP

### Comportement Automatique

Le système détecte automatiquement l'environnement et ajuste la vérification SSL :

- **Environnement Local** (`APP_ENV=local`) : La vérification SSL est **désactivée** pour éviter les erreurs de certificat sur Windows
- **Environnement Production** (`APP_ENV=production`) : La vérification SSL est **activée** pour la sécurité

### Configuration pour la Production

#### 1. Vérifier le fichier `.env`

Assurez-vous que votre fichier `.env` en production contient :

```env
APP_ENV=production
APP_DEBUG=false
```

#### 2. Certificats SSL sur le Serveur

En production, votre serveur doit avoir les certificats SSL correctement configurés :

**Sur Linux (Ubuntu/Debian) :**
```bash
# Mettre à jour les certificats CA
sudo apt-get update
sudo apt-get install ca-certificates

# Vérifier que les certificats sont à jour
sudo update-ca-certificates
```

**Sur Windows Server :**
- Les certificats CA sont généralement déjà installés
- Si vous avez des problèmes, téléchargez le bundle de certificats depuis [curl.se](https://curl.se/ca/cacert.pem)

#### 3. Configuration PHP cURL

Si vous rencontrez encore des problèmes SSL en production, vous pouvez configurer le chemin vers le bundle de certificats :

**Option A : Dans `php.ini`**
```ini
curl.cainfo = "/etc/ssl/certs/ca-certificates.crt"  # Linux
curl.cainfo = "C:\php\extras\ssl\cacert.pem"        # Windows
```

**Option B : Dans le code (non recommandé sauf si nécessaire)**

Si vous devez forcer un chemin spécifique, vous pouvez modifier temporairement le code dans `AIService.php` :

```php
$httpClient = Http::withOptions([
    'verify' => '/chemin/vers/cacert.pem',  // Chemin vers le bundle de certificats
]);
```

### Vérification en Production

#### Test 1 : Vérifier l'environnement
```bash
php artisan tinker
>>> app()->environment()
=> "production"
```

#### Test 2 : Tester la connexion SSL
```bash
curl -I https://api.openai.com/v1/models
```

Si vous obtenez une erreur SSL, installez/mettez à jour les certificats CA.

#### Test 3 : Tester depuis l'interface Admin

1. Allez dans **Administration > Providers IA**
2. Cliquez sur **Configurer** pour OpenAI
3. Cliquez sur **Tester**
4. Vous devriez voir : "✅ Connexion réussie !"

### Dépannage

#### Erreur : "SSL certificate problem: unable to get local issuer certificate"

**Solution 1 : Mettre à jour les certificats CA**
```bash
# Linux
sudo apt-get update && sudo apt-get install ca-certificates
sudo update-ca-certificates

# Windows
# Télécharger cacert.pem depuis https://curl.se/ca/cacert.pem
# Configurer dans php.ini
```

**Solution 2 : Vérifier la configuration PHP**
```bash
php -i | grep curl.cainfo
```

**Solution 3 : Vérifier que APP_ENV=production**
```bash
grep APP_ENV .env
```

### Sécurité

⚠️ **IMPORTANT** : Ne jamais désactiver la vérification SSL en production !

- La vérification SSL protège contre les attaques "man-in-the-middle"
- En production, `app()->environment('local')` retourne `false`, donc la vérification SSL est automatiquement activée
- Ne modifiez jamais le code pour forcer `'verify' => false` en production

### Résumé

| Environnement | APP_ENV | Vérification SSL | Sécurité |
|--------------|---------|------------------|----------|
| Local        | `local` | Désactivée       | ⚠️ OK pour dev uniquement |
| Production   | `production` | Activée      | ✅ Sécurisé |

Le système gère automatiquement cette configuration selon l'environnement détecté.


