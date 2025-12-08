# ⚡ Démarrage Rapide

## 🧪 Tester en Local (Maintenant)

### 1. Configurer l'email

Dans `.env`, configurez Mailtrap (gratuit pour les tests) :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre-username-mailtrap
MAIL_PASSWORD=votre-password-mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@voicy-assistant.test
MAIL_FROM_NAME="Voicy Assistant"

QUEUE_CONNECTION=database
```

### 2. Créer la table des jobs

```bash
php artisan queue:table
php artisan migrate
```

### 3. Démarrer le worker

```bash
php artisan queue:work
```

### 4. Tester

1. Allez sur `http://localhost:8000/admin/newsletter/create`
2. Créez une newsletter
3. Envoyez-la
4. Vérifiez dans Mailtrap que l'email est reçu

## 🚀 Déployer en Production (Plus tard)

### Une seule commande :

```bash
sudo ./scripts/deploy-production.sh
```

**C'est tout !** Le worker sera configuré automatiquement et démarrera au boot.

### Après chaque déploiement :

Ajoutez cette ligne à votre script de déploiement :

```bash
./scripts/post-deploy.sh
```

## ✅ Vérification

```bash
# Vérifier le worker
sudo supervisorctl status

# Ou via Artisan
php artisan queue:check-worker
```

## 📚 Documentation complète

- **Test local** : `GUIDE_TEST_NEWSLETTER_LOCAL.md`
- **Déploiement** : `DEPLOY.md`
- **Configuration worker** : `GUIDE_QUEUE_WORKER_PRODUCTION.md`
- **Newsletter** : `README_NEWSLETTER.md`

