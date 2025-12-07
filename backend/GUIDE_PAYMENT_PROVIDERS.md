# 💳 Guide : Configuration des Providers de Paiement

## 🔍 Vérifier l'état des providers

Exécute cette commande pour voir l'état de tous les providers :

```bash
php artisan payment:check-providers
```

Cette commande affiche :
- ✅ Les providers actifs
- ⭐ Le provider par défaut
- 🔑 Le nombre de clés configurées

---

## ⚙️ Configurer un provider depuis l'admin

### 1. Accéder à la page de gestion
- Va sur `/admin/payment-providers`
- Tu verras la liste de tous les providers

### 2. Configurer Flutterwave (ou autre provider)
1. Clique sur **"Configurer"** pour le provider Flutterwave
2. Remplis les champs :
   - **Clé secrète** : `FLWSECK_TEST-...` (depuis ton dashboard Flutterwave)
   - **Clé publique** : `FLWPUBK_TEST-...` (depuis ton dashboard Flutterwave)
   - **Secret webhook** : (optionnel pour les tests)
3. **IMPORTANT** : Coche la case **"Activer ce provider"**
4. **IMPORTANT** : Coche la case **"Provider par défaut"** si tu veux l'utiliser par défaut
5. Clique sur **"Enregistrer les modifications"**

### 3. Vérifier la configuration
Après avoir sauvegardé, exécute :
```bash
php artisan payment:check-providers
```

Tu devrais voir :
- ✅ **Actif** : Oui
- ⭐ **Défaut** : Oui
- 🔑 **Clés configurées** : 2/3 ou 3/3

---

## 🚨 Problèmes courants

### Erreur : "Aucun provider de paiement configuré"

**Causes possibles :**
1. Aucun provider n'est activé
2. Aucun provider n'est marqué comme défaut
3. Aucune clé API n'est configurée

**Solution :**
1. Va sur `/admin/payment-providers`
2. Clique sur "Configurer" pour Flutterwave
3. Vérifie que :
   - ✅ "Activer ce provider" est coché
   - ⭐ "Provider par défaut" est coché
   - 🔑 Au moins une clé API est remplie
4. Sauvegarde

### Le provider n'est pas utilisé

**Vérification :**
```bash
php artisan payment:check-providers
```

Si le provider par défaut n'est pas celui que tu veux :
1. Va sur `/admin/payment-providers`
2. Configure le provider souhaité
3. Coche "Provider par défaut" (cela décochera automatiquement les autres)

---

## 📝 Notes importantes

- **Un seul provider peut être par défaut** à la fois
- **Plusieurs providers peuvent être actifs** en même temps
- **Les clés API sont sensibles** : ne les partage jamais
- **Environnement Test vs Live** : utilise "Test" pour les tests, "Live" pour la production

---

## ✅ Checklist de configuration

Avant de tester les paiements, vérifie que :

- [ ] Le provider est **activé** (`is_active = true`)
- [ ] Le provider est **marqué comme défaut** (`is_default = true`)
- [ ] Au moins **une clé API est configurée** (secret_key ou public_key)
- [ ] L'**environnement** est correct (Test pour les tests, Live pour la production)
- [ ] Les **clés sont valides** (pas d'espaces, pas de caractères invalides)

---

## 🔧 Commandes utiles

```bash
# Vérifier l'état des providers
php artisan payment:check-providers

# Vider le cache (si les changements ne sont pas pris en compte)
php artisan config:clear
php artisan cache:clear
```

---

**Une fois configuré, les utilisateurs pourront payer avec ce provider !** 🎉

