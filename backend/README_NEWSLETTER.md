# 📧 Système de Newsletter - Voicy Assistant

## 🎯 Vue d'ensemble

Le système de newsletter permet aux administrateurs d'envoyer des emails à tous les abonnés actifs de la plateforme. Les emails sont envoyés de manière asynchrone via Laravel Queue pour une meilleure performance.

## ✨ Fonctionnalités

- ✅ Création et envoi de newsletters depuis l'interface admin
- ✅ Envoi asynchrone via Laravel Queue
- ✅ Support HTML dans le contenu
- ✅ Suivi des statistiques (envoyés, échecs, taux de succès)
- ✅ Historique des newsletters envoyées
- ✅ Lien de désinscription dans chaque email
- ✅ Template email professionnel et responsive

## 🚀 Utilisation

### 1. Accéder à l'interface

1. Connectez-vous en tant qu'administrateur
2. Allez dans **Admin → Envoyer Newsletter**
3. Remplissez le formulaire :
   - **Sujet** : Le sujet de l'email
   - **Contenu** : Le message (HTML supporté)

### 2. Exemple de contenu HTML

```html
<h1>Nouvelle fonctionnalité disponible !</h1>

<p>Nous sommes ravis de vous annoncer une nouvelle fonctionnalité...</p>

<p><strong>Voici ce qui est nouveau :</strong></p>
<ul>
    <li>Analyse audio améliorée</li>
    <li>Support multilingue</li>
    <li>Interface utilisateur modernisée</li>
</ul>

<p>Connectez-vous dès maintenant pour découvrir ces nouveautés !</p>
```

### 3. Envoyer la newsletter

1. Cliquez sur **"Envoyer la Newsletter"**
2. Confirmez l'envoi
3. La newsletter sera envoyée automatiquement à tous les abonnés actifs

## ⚙️ Configuration en Production

### Prérequis

- Supervisor installé sur le serveur
- Configuration email dans `.env`

### Installation automatique

```bash
sudo ./scripts/setup-queue-worker.sh
```

Le script vous demandera :
- Le chemin absolu du projet
- L'utilisateur qui exécute PHP (généralement `www-data`)
- Le nombre de workers en parallèle (recommandé: 2)

### Installation manuelle

Voir le guide complet : [GUIDE_QUEUE_WORKER_PRODUCTION.md](GUIDE_QUEUE_WORKER_PRODUCTION.md)

### Vérification

```bash
./scripts/check-queue-worker.sh
```

## 📊 Statistiques

L'interface affiche :
- **Total destinataires** : Nombre d'abonnés actifs
- **Envoyés** : Nombre d'emails envoyés avec succès
- **Échecs** : Nombre d'emails qui ont échoué
- **Taux de succès** : Pourcentage d'emails envoyés avec succès

## 🔧 Configuration Email

Assurez-vous que votre fichier `.env` contient :

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@voicy-assistant.com
MAIL_FROM_NAME="Voicy Assistant"
```

## 🐛 Dépannage

### Les emails ne sont pas envoyés

1. **Vérifier que le worker tourne** :
```bash
sudo supervisorctl status
```

2. **Vérifier les logs** :
```bash
tail -f storage/logs/queue-worker.log
```

3. **Tester l'envoi d'email** :
```bash
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('your-email@example.com')->subject('Test'); });
```

### Le worker ne démarre pas

1. Vérifier les permissions :
```bash
ls -la storage/logs/
```

2. Vérifier la configuration Supervisor :
```bash
sudo supervisorctl reread
sudo supervisorctl update
```

3. Voir les logs de Supervisor :
```bash
sudo tail -f /var/log/supervisor/supervisord.log
```

## 📝 Notes importantes

- ⚠️ **Après chaque déploiement**, redémarrez le worker :
```bash
sudo supervisorctl restart voicy-queue-worker:*
```

- ⚠️ **Le worker doit tourner en permanence** pour traiter les emails
- ⚠️ **Les emails sont envoyés en arrière-plan**, il peut y avoir un délai selon le nombre d'abonnés

## 🎨 Personnalisation du template

Le template email se trouve dans : `resources/views/emails/newsletter.blade.php`

Vous pouvez personnaliser :
- Les couleurs
- Le logo
- Le style
- Le footer

## ✅ Checklist de déploiement

- [ ] Supervisor installé et configuré
- [ ] Worker configuré et démarré
- [ ] Configuration email dans `.env`
- [ ] Test d'envoi d'email réussi
- [ ] Permissions sur `storage/logs/` correctes
- [ ] Script de redémarrage du worker ajouté au déploiement

## 📚 Documentation complémentaire

- [Guide de configuration du worker](GUIDE_QUEUE_WORKER_PRODUCTION.md)
- [Documentation Laravel Queue](https://laravel.com/docs/queues)
- [Documentation Supervisor](http://supervisord.org/)

