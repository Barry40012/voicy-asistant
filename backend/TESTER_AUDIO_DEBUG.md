# 🔍 Guide de Débogage - Test Audio

## Problème : Audio uploadé mais pas de résultats

Si vous avez uploadé un audio mais ne voyez rien dans "Mes Audios", suivez ces étapes :

### 1. Vérifier les Logs

```bash
cd backend
tail -f storage/logs/laravel.log
```

Puis réessayez d'uploader un audio. Vous verrez les messages de log en temps réel.

### 2. Vérifier la Configuration de la Queue

Par défaut, Laravel utilise `sync` (traitement immédiat). Vérifiez dans `.env` :

```env
QUEUE_CONNECTION=sync
```

Si c'est `database`, vous devez lancer le worker :

```bash
php artisan queue:work
```

### 3. Vérifier la Configuration Supabase

Vérifiez que dans `.env` vous avez :

```env
SUPABASE_URL=https://votre-projet.supabase.co
SUPABASE_SERVICE_KEY=votre-service-key
SUPABASE_BUCKET=audios
```

### 4. Vérifier les Audios dans la Base de Données

```bash
php artisan tinker
```

Puis dans tinker :

```php
\App\Models\Audio::count() // Nombre d'audios
\App\Models\Audio::latest()->first() // Dernier audio
```

### 5. Tester l'Upload Supabase Manuellement

```bash
php artisan tinker
```

```php
$service = app(\App\Services\AudioService::class);
$content = file_get_contents('chemin/vers/votre/audio.mp3');
$path = $service->uploadAudio($content, 'test.mp3');
echo $path; // Devrait retourner le chemin
```

### 6. Vérifier les Erreurs dans la Vue

Si vous êtes redirigé vers la page de détails mais ne voyez rien :
- Vérifiez le statut de l'audio (en attente, en cours, erreur)
- Regardez les messages d'erreur affichés
- Rafraîchissez la page

### 7. Tester le Traitement Manuellement

```bash
php artisan tinker
```

```php
$audio = \App\Models\Audio::latest()->first();
\App\Jobs\ProcessAudioJob::dispatch($audio);
```

Puis vérifiez les logs pour voir les erreurs.

---

## Solutions Courantes

### Problème : "Failed to retrieve audio content"

**Cause** : L'audio n'a pas été uploadé correctement vers Supabase.

**Solution** :
1. Vérifiez la configuration Supabase dans `.env`
2. Vérifiez que le bucket "audios" existe
3. Vérifiez que la service key est correcte

### Problème : "Transcription failed"

**Cause** : Erreur avec l'API OpenAI.

**Solution** :
1. Vérifiez que OpenAI est configuré dans Admin > Providers IA
2. Vérifiez que la clé API est valide
3. Testez la connexion avec le bouton "Tester"

### Problème : Audio reste en "uploaded" ou "processing"

**Cause** : Le queue worker ne tourne pas (si QUEUE_CONNECTION=database).

**Solution** :
```bash
php artisan queue:work
```

Ou changez dans `.env` :
```env
QUEUE_CONNECTION=sync
```

---

## Commandes Utiles

```bash
# Voir les jobs en attente
php artisan queue:work --once

# Vider la queue
php artisan queue:flush

# Voir les logs en temps réel
tail -f storage/logs/laravel.log

# Vider le cache
php artisan config:clear
php artisan cache:clear
```

---

**Si le problème persiste, partagez les logs avec moi !**

