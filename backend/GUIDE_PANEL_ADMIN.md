# 🎛️ Guide du Panel Administrateur

## 📋 Vue d'ensemble

Le panel administrateur te permet de gérer toute la plateforme Voicy Assistant depuis une interface centralisée.

## 🔐 Accès au Panel

### Prérequis
1. **Ton compte doit avoir le rôle `admin` ou `super_admin`**
2. Voir le guide : `CREER_COMPTE_ADMIN.md` pour définir ton rôle

### Accès
- **URL** : `/admin`
- **Lien dans la navigation** : Visible uniquement si tu es admin

---

## 📊 Dashboard Principal (`/admin`)

### Statistiques en temps réel

- **Total Utilisateurs** : Nombre total d'utilisateurs inscrits
- **Abonnements Actifs** : Nombre d'abonnements actifs
- **Audios Traités** : Nombre total d'audios traités
- **Revenus Totaux** : Montant total des paiements réussis

### Graphique des revenus
- Visualisation des revenus des 7 derniers jours
- Barres colorées pour chaque jour

### Activités récentes
- **Paiements récents** : 10 derniers paiements réussis
- **Nouveaux utilisateurs** : 10 derniers utilisateurs inscrits
- **Audios récents** : 10 derniers audios traités

---

## 👥 Gestion des Utilisateurs (`/admin/users`)

### Liste des utilisateurs
- **Informations affichées** :
  - Nom et email
  - Statut de vérification email
  - Rôle (user/admin/super_admin)
  - Nombre d'abonnements
  - Nombre d'audios
  - Date d'inscription

### Détails utilisateur (`/admin/users/{id}`)
- **Informations complètes** :
  - Profil utilisateur
  - Liste des abonnements
  - Historique des paiements
  - Statistiques des audios

---

## 📦 Gestion des Plans (`/admin/plans`)

### Vue des plans
- **Informations affichées** :
  - Nom du plan
  - Prix mensuel
  - Description
  - Limites (audios/mois, durée max)
  - Nombre d'abonnements actifs
  - Statut (actif/inactif)

---

## 🎫 Gestion des Abonnements (`/admin/subscriptions`)

### Liste des abonnements
- **Informations affichées** :
  - Utilisateur
  - Plan souscrit
  - Statut (actif/pending/expiré)
  - Dates de début et d'expiration
  - Date de création

---

## 💳 Gestion des Paiements (`/admin/payments`)

### Statistiques
- **Total** : Montant total de tous les paiements
- **Réussis** : Montant des paiements réussis
- **En attente** : Montant des paiements en attente
- **Échoués** : Montant des paiements échoués

### Liste des paiements
- **Informations affichées** :
  - Utilisateur
  - Montant et devise
  - Provider (Flutterwave, etc.)
  - Statut (succeeded/pending/failed)
  - Date du paiement

---

## 🎵 Gestion des Audios (`/admin/audios`)

### Liste des audios
- **Informations affichées** :
  - Utilisateur propriétaire
  - Numéro expéditeur
  - Taille du fichier
  - Statut (traité/en cours/erreur/en attente)
  - Date de création
  - Lien vers les détails

---

## 📝 Logs Système (`/admin/logs`)

### Consultation des logs
- **Types de logs** :
  - `webhook_whatsapp` : Webhooks reçus de WhatsApp
  - `webhook_payment` : Webhooks de paiement
  - `audio_processed` : Traitement d'audios
  - Autres événements système

### Informations affichées
- Type de log
- Adresse IP
- Date et heure
- Payload (détails JSON)

---

## 🎨 Interface

### Navigation latérale
- **Sidebar fixe** avec toutes les sections
- **Indicateur visuel** de la section active
- **Lien retour** vers le dashboard utilisateur

### Design
- **Interface moderne** avec Tailwind CSS
- **Responsive** : Adapté mobile et desktop
- **Couleurs cohérentes** : Badges colorés selon les statuts

---

## 🔒 Sécurité

### Middleware Admin
- **Protection automatique** : Seuls les admins peuvent accéder
- **Vérification du rôle** : `admin` ou `super_admin` requis
- **Redirection** : 403 si non autorisé

---

## 📈 Statistiques Clés

### Dashboard
- Vue d'ensemble de la plateforme
- Indicateurs de performance
- Tendance des revenus

### Filtres et recherche
- **Pagination** : Navigation entre les pages
- **Tri** : Par date (plus récent en premier)

---

## 🚀 Prochaines fonctionnalités (à venir)

- [ ] Export des données (CSV/Excel)
- [ ] Filtres avancés par date
- [ ] Recherche par nom/email
- [ ] Actions en masse
- [ ] Notifications d'alertes
- [ ] Graphiques avancés
- [ ] Gestion des rôles utilisateurs
- [ ] Modification des plans depuis l'admin

---

## 💡 Conseils d'utilisation

1. **Surveille régulièrement** le dashboard pour les tendances
2. **Vérifie les paiements** en attente quotidiennement
3. **Consulte les logs** en cas de problème
4. **Gère les utilisateurs** qui ont besoin d'aide
5. **Suis les abonnements** qui expirent bientôt

---

**Le panel admin est maintenant opérationnel ! 🎉**

Pour toute question, consulte la documentation ou contacte le support.

