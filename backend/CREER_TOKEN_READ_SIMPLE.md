# 🔑 Créer un Token HuggingFace Simple "Read"

## 🎯 Pourquoi un Token "Read" Simple ?

Les tokens **Fine-grained** peuvent avoir des restrictions qui empêchent leur utilisation. Un token **"Read"** simple est plus universel et fonctionne mieux pour l'API Inference.

## 📝 Étapes pour Créer un Token "Read"

### Étape 1 : Supprimer l'Ancien Token

1. Allez sur [huggingface.co/settings/tokens](https://huggingface.co/settings/tokens)
2. Trouvez votre token "Voicy Assistant" (Fine-grained)
3. Cliquez sur **"Delete"** ou **"Supprimer"** pour le supprimer

### Étape 2 : Créer un Nouveau Token Simple

1. Sur la même page, cliquez sur **"New token"** (Nouveau token)
2. **IMPORTANT** : Ne sélectionnez **PAS** "Fine-grained"
3. Sélectionnez **"Read"** (ou **"Write"** si vous préférez)
4. Nom : **"Voicy Assistant"**
5. Cliquez sur **"Generate token"** (Générer le token)

### Étape 3 : Copier le Token

1. **Copiez immédiatement** le token (cliquez sur l'icône de copie)
2. ⚠️ **Vous ne pourrez plus le voir après !**

### Étape 4 : Tester le Token

```bash
php scripts/test-huggingface-token.php VOTRE_NOUVEAU_TOKEN_ICI
```

Si le test fonctionne, vous verrez : **"✅ CONNEXION RÉUSSIE!"**

### Étape 5 : Mettre à Jour dans la Plateforme

Une fois le token testé et validé :

```bash
php artisan tinker
```

Puis :
```php
$provider = \App\Models\AIProvider::where('name', 'huggingface')->first();
$newToken = 'VOTRE_NOUVEAU_TOKEN_ICI'; // Collez votre nouveau token
$provider->credentials = [
    'api_key' => $newToken,
    'test_api_key' => $newToken,
];
$provider->save();
echo "✅ Token mis à jour!\n";
```

## ✅ Avantages d'un Token "Read" Simple

- ✅ Plus simple à configurer
- ✅ Fonctionne avec tous les endpoints
- ✅ Pas de gestion de permissions complexes
- ✅ Plus fiable pour l'API Inference

## 🆘 Si le Token "Read" ne Fonctionne Pas Non Plus

1. Vérifiez que vous êtes bien connecté à votre compte HuggingFace
2. Vérifiez que votre compte n'est pas suspendu
3. Essayez de vous déconnecter et reconnecter
4. Créez un nouveau compte HuggingFace si nécessaire

