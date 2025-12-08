# 🔐 Guide : Token Fine-grained HuggingFace

## ⚠️ Problème avec les Tokens Fine-grained

Les tokens **Fine-grained** (granulaires) de HuggingFace peuvent avoir des restrictions qui empêchent l'utilisation de certains endpoints comme `/api/whoami`.

## ✅ Solution : Vérifier les Permissions

Pour qu'un token Fine-grained fonctionne avec l'API Inference, il doit avoir :

### Permissions Requises

1. **Inference** → **"Make calls to Inference Providers"** ✅ (OBLIGATOIRE)
2. Optionnel : **Repositories** → **"Read access to contents of all repos"** (pour certains modèles)

### Comment Vérifier

1. Allez sur [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. Trouvez votre token "Assistant vocal"
3. Cliquez dessus pour voir les détails
4. Vérifiez que **"Inference"** → **"Make calls to Inference Providers"** est coché

### Si les Permissions ne sont pas Correctes

1. **Supprimez** l'ancien token
2. **Créez un nouveau token** avec :
   - Type : **Fine-grained**
   - Nom : "Voicy Assistant"
   - **Cochez** : Inference → "Make calls to Inference Providers"
   - **Cochez** : Repositories → "Read access to contents of all repos" (optionnel mais recommandé)
3. **Générez** le token
4. **Copiez** le token complet

## 🔄 Alternative : Token Simple "Read"

Si vous continuez à avoir des problèmes avec Fine-grained, utilisez un token simple :

1. Créez un nouveau token
2. Type : **"Read"** (pas Fine-grained)
3. C'est plus simple et fonctionne pour l'API Inference

## 🧪 Test du Token

Après avoir créé/mis à jour le token, testez-le :

```bash
php scripts/test-huggingface-token.php VOTRE_TOKEN_ICI
```

Ou testez directement dans l'interface : `/admin/ai-providers` → Tester

## 📝 Mise à Jour du Token

Une fois que vous avez un token valide :

```bash
php artisan tinker
```

```php
$provider = \App\Models\AIProvider::where('name', 'huggingface')->first();
$newToken = 'VOTRE_NOUVEAU_TOKEN_ICI';
$provider->credentials = [
    'api_key' => $newToken,
    'test_api_key' => $newToken,
];
$provider->save();
echo "✅ Token mis à jour!\n";
```

## ⚠️ Note Importante

Les tokens Fine-grained peuvent avoir des restrictions supplémentaires. Si vous avez des problèmes persistants, utilisez un token simple "Read" qui est plus universel.

