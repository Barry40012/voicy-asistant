# 🔧 Dépannage : Erreur Token HuggingFace

## ❌ Erreur : "Token API invalide ou expiré"

### Vérifications à faire

#### 1. Vérifier que le token est complet

Le token HuggingFace doit :
- Commencer par `hf_`
- Faire environ 40-50 caractères
- Être copié **complètement** (pas seulement la partie visible)

**Comment copier correctement** :
1. Allez sur [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. Trouvez votre token "Voicy Assistant"
3. Cliquez sur l'**icône de copie** à côté du token (pas sur le token lui-même)
4. Collez-le dans le champ "Clé API (Test)"

#### 2. Vérifier les permissions du token

Le token doit avoir au minimum la permission **"Read"** :
1. Allez sur [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. Vérifiez la colonne "Permissions" de votre token
3. Si ce n'est pas "Read" ou "Write", créez un nouveau token avec "Read"

#### 3. Vérifier que le token n'est pas expiré

Les tokens HuggingFace ne expirent généralement pas, mais vérifiez :
1. Allez sur [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. Vérifiez la date "Last Refreshed Date"
3. Si le token est très ancien, créez-en un nouveau

#### 4. Vérifier le format dans l'administration

Dans `/admin/ai-providers` → Configurer HuggingFace :
- Le champ "Clé API (Test)" doit contenir le token complet
- Pas d'espaces avant ou après
- Pas de sauts de ligne

### Solutions

#### Solution 1 : Recréer le token

1. Allez sur [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. Supprimez l'ancien token "Voicy Assistant"
3. Créez un nouveau token :
   - Nom : "Voicy Assistant"
   - Type : **"Read"** (ou "Write" si vous voulez plus de permissions)
4. Copiez le nouveau token
5. Collez-le dans `/admin/ai-providers` → Configurer HuggingFace

#### Solution 2 : Vérifier manuellement le token

Testez le token directement avec curl :

```bash
curl https://huggingface.co/api/whoami \
  -H "Authorization: Bearer VOTRE_TOKEN_ICI"
```

Si ça fonctionne, vous devriez voir :
```json
{"name":"votre-nom-utilisateur",...}
```

Si ça ne fonctionne pas, le token est invalide.

#### Solution 3 : Vérifier les logs

Regardez les logs Laravel pour plus de détails :

```bash
tail -f storage/logs/laravel.log
```

Cherchez les lignes contenant "HuggingFace test failed" pour voir l'erreur exacte.

### Erreurs courantes

#### "401 Unauthorized"
- **Cause** : Token invalide ou mal copié
- **Solution** : Recréez le token et copiez-le complètement

#### "403 Forbidden"
- **Cause** : Token sans permissions
- **Solution** : Créez un token avec permission "Read" minimum

#### "429 Too Many Requests"
- **Cause** : Trop de requêtes
- **Solution** : Attendez quelques minutes et réessayez

### Test manuel

Pour tester si votre token fonctionne :

1. **Via curl** :
```bash
curl https://huggingface.co/api/whoami -H "Authorization: Bearer VOTRE_TOKEN"
```

2. **Via PHP (tinker)** :
```bash
php artisan tinker
```
```php
$token = "VOTRE_TOKEN_ICI";
$response = \Illuminate\Support\Facades\Http::withHeaders([
    'Authorization' => "Bearer {$token}"
])->get('https://huggingface.co/api/whoami');
dd($response->json());
```

### Checklist

- [ ] Token commence par `hf_`
- [ ] Token fait 40-50 caractères
- [ ] Token copié complètement (pas seulement les premiers caractères)
- [ ] Token a la permission "Read" minimum
- [ ] Pas d'espaces avant/après le token dans le champ
- [ ] Environnement sélectionné : "Test"
- [ ] Token enregistré correctement

### Si rien ne fonctionne

1. Créez un **nouveau compte HuggingFace** (si possible)
2. Générez un **nouveau token**
3. Configurez-le dans l'administration
4. Testez à nouveau

Si le problème persiste, vérifiez que votre connexion internet fonctionne et que vous pouvez accéder à `huggingface.co`.

