# 🧪 Guide : Tester la Newsletter en Local

Ce guide explique comment tester le système de newsletter en local avant le déploiement en production.

## 📋 Prérequis

1. Configuration email dans `.env`
2. Laravel configuré avec une queue (database ou redis)

## ⚙️ Configuration

### 1. Configurer l'email dans `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@voicy-assistant.test
MAIL_FROM_NAME="Voicy Assistant"
```

**Option 1 : Mailtrap (Recommandé pour les tests)**

1. Créez un compte gratuit sur [Mailtrap.io](https://mailtrap.io)
2. Créez une inbox de test
3. Copiez les identifiants dans votre `.env`

**Option 2 : Gmail (Pour tester avec un vrai email)**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
```

> ⚠️ Pour Gmail, vous devez créer un "Mot de passe d'application" dans les paramètres de sécurité de votre compte Google.

### 2. Configurer la queue

Dans votre `.env`, assurez-vous que :

```env
QUEUE_CONNECTION=database
```

### 3. Créer la table des jobs

```bash
php artisan queue:table
php artisan migrate
```

## 🚀 Tester l'envoi

### Méthode 1 : Worker manuel (Recommandé pour les tests)

1. **Démarrer le worker dans un terminal** :

```bash
php artisan queue:work
```

2. **Dans un autre terminal, créer et envoyer une newsletter** :

- Allez sur `http://localhost:8000/admin/newsletter/create`
- Remplissez le formulaire
- Cliquez sur "Envoyer la Newsletter"

3. **Observer le worker** :

Vous verrez les jobs être traités en temps réel dans le terminal du worker.

### Méthode 2 : Traitement immédiat (Pour test rapide)

Si vous voulez tester sans worker, vous pouvez modifier temporairement `QUEUE_CONNECTION` :

```env
QUEUE_CONNECTION=sync
```

⚠️ **Attention** : En mode `sync`, les emails sont envoyés immédiatement, ce qui peut ralentir votre application si vous avez beaucoup d'abonnés.

## 📧 Vérifier les emails

### Avec Mailtrap

1. Allez sur votre dashboard Mailtrap
2. Ouvrez votre inbox de test
3. Vous verrez tous les emails envoyés

### Avec Gmail

1. Vérifiez votre boîte de réception Gmail
2. Vérifiez aussi les spams si nécessaire

## 🧪 Scénarios de test

### Test 1 : Newsletter simple

1. Créez une newsletter avec un sujet simple
2. Contenu : "Test de newsletter"
3. Envoyez à 1-2 abonnés de test
4. Vérifiez la réception

### Test 2 : Newsletter avec HTML

1. Créez une newsletter avec du HTML :
```html
<h1>Nouvelle fonctionnalité !</h1>
<p>Découvrez notre nouvelle fonctionnalité...</p>
<ul>
    <li>Fonctionnalité 1</li>
    <li>Fonctionnalité 2</li>
</ul>
```
2. Vérifiez le rendu dans l'email

### Test 3 : Newsletter à plusieurs destinataires

1. Créez plusieurs abonnés de test
2. Envoyez une newsletter
3. Vérifiez que tous reçoivent l'email

### Test 4 : Désinscription

1. Cliquez sur le lien de désinscription dans un email
2. Vérifiez que l'abonné est désactivé
3. Envoyez une nouvelle newsletter
4. Vérifiez que l'abonné désinscrit ne reçoit plus d'emails

## 🐛 Dépannage

### Les emails ne sont pas envoyés

1. **Vérifier la configuration email** :
```bash
php artisan tinker
>>> config('mail.mailers.smtp')
```

2. **Tester l'envoi direct** :
```bash
php artisan tinker
>>> Mail::raw('Test email', function($msg) { 
    $msg->to('votre-email@example.com')->subject('Test'); 
});
```

3. **Vérifier les logs** :
```bash
tail -f storage/logs/laravel.log
```

### Le worker ne traite pas les jobs

1. **Vérifier que la queue est configurée** :
```bash
php artisan queue:work --once
```

2. **Vérifier les jobs en attente** :
```bash
php artisan tinker
>>> DB::table('jobs')->count()
```

3. **Vider la queue si nécessaire** :
```bash
php artisan queue:flush
```

### Erreur "Class 'App\Jobs\SendNewsletterJob' not found"

1. Vérifier que le fichier existe : `app/Jobs/SendNewsletterJob.php`
2. Vider le cache :
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## ✅ Checklist de test

- [ ] Configuration email dans `.env`
- [ ] Table `jobs` créée (`php artisan migrate`)
- [ ] Worker démarré (`php artisan queue:work`)
- [ ] Newsletter créée depuis l'admin
- [ ] Email reçu dans Mailtrap/Gmail
- [ ] Template email s'affiche correctement
- [ ] Lien de désinscription fonctionne
- [ ] Statistiques affichées correctement
- [ ] Logs sans erreur

## 🎯 Résumé

Pour tester en local :

1. Configurez l'email dans `.env` (Mailtrap recommandé)
2. Configurez `QUEUE_CONNECTION=database`
3. Créez la table : `php artisan migrate`
4. Démarrez le worker : `php artisan queue:work`
5. Envoyez une newsletter depuis l'admin
6. Vérifiez la réception dans Mailtrap/Gmail

Une fois que tout fonctionne en local, vous êtes prêt pour la production ! 🚀

