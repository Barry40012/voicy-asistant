# 🔑 Récupérer les Credentials SMTP Mailtrap

## 📍 Où aller exactement

### Étape 1 : Clique sur "Sandboxes" dans le menu de gauche

Tu vois dans le menu :
- Home
- Transactional
- **Sandboxes** ← **CLIQUE ICI**
- Inbound
- etc.

### Étape 2 : Ouvre la "Demo Inbox"

Une fois dans "Sandboxes", tu verras :
- Une "Demo Inbox" (créée par défaut)
- Clique dessus pour l'ouvrir

### Étape 3 : Va dans "SMTP Settings"

Dans la page de l'Inbox, tu verras plusieurs onglets :
- **Messages** (les emails reçus)
- **SMTP Settings** ← **CLIQUE ICI**
- **API** (optionnel)

### Étape 4 : Sélectionne "Laravel"

Dans "SMTP Settings", tu verras :
- Différents frameworks (Laravel, Symfony, etc.)
- **Sélectionne "Laravel"** dans la liste

### Étape 5 : Copie les credentials

Tu verras quelque chose comme :

```
Host: smtp.mailtrap.io
Port: 2525
Username: abc123def456...
Password: xyz789uvw012...
```

**Copie ces 4 valeurs** :
- MAIL_HOST
- MAIL_PORT
- MAIL_USERNAME
- MAIL_PASSWORD

---

## 📋 Chemin complet

```
Dashboard Mailtrap
  ↓
Sandboxes (menu gauche)
  ↓
Demo Inbox (clique dessus)
  ↓
SMTP Settings (onglet)
  ↓
Sélectionne "Laravel"
  ↓
Copie les credentials
```

---

## ⚙️ Après avoir récupéré les credentials

Une fois que tu as les 4 valeurs, donne-moi :
- MAIL_HOST
- MAIL_PORT
- MAIL_USERNAME
- MAIL_PASSWORD

Et je t'aide à les configurer dans le `.env` ! 🚀

---

**Va dans Sandboxes → Demo Inbox → SMTP Settings → Laravel** 📧

