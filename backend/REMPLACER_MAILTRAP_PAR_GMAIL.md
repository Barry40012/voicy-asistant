# 🔄 Remplacer Mailtrap par Gmail dans .env

## ✅ Oui, tu dois REMPLACER

Dans le fichier `.env`, tu as actuellement les lignes Mailtrap :

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=327090bf3498d3
MAIL_PASSWORD=19c9b948132409
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@voicy-assistant.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 🔄 Remplace-les par Gmail

**Supprime** ces lignes Mailtrap et **remplace** par :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=barryyoussouf400@gmail.com
MAIL_PASSWORD=spedjbcurkdeobtn
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=barryyoussouf400@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 📋 Action

1. **Ouvre** : `E:\Project_Voicy_Assistant\backend\.env`

2. **Trouve** les lignes qui commencent par `MAIL_`

3. **Remplace TOUTES** les lignes MAIL par celles de Gmail ci-dessus

4. **Sauvegarde** le fichier

5. **Vide le cache** (déjà fait) :
   ```bash
   php artisan config:clear
   ```

## ⚠️ Important

- **On ne peut pas avoir les deux** en même temps
- **Remplace complètement** les lignes Mailtrap par Gmail
- **Une seule configuration** MAIL à la fois

## 🔄 Si tu veux revenir à Mailtrap plus tard

Tu peux toujours remettre les lignes Mailtrap si tu veux tester en développement.

---

**Remplace les lignes MAIL dans le .env maintenant !** 🚀

