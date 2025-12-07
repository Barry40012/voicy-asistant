# 🔧 Installer l'Extension PostgreSQL pour PHP

## ❌ Problème

L'erreur `could not find driver` signifie que PHP n'a pas l'extension PostgreSQL installée.

## ✅ Solution : Installer l'extension pgsql

### Étape 1 : Vérifier la version de PHP

```bash
php -v
```

Note ta version PHP (ex: `PHP 8.2.12`)

### Étape 2 : Trouver le fichier php.ini

```bash
php --ini
```

Cela affichera le chemin vers ton `php.ini` (ex: `C:\php\php.ini`)

### Étape 3 : Ouvrir php.ini

Ouvre le fichier `php.ini` dans un éditeur de texte (Notepad++, VS Code, etc.)

### Étape 4 : Activer l'extension PostgreSQL

Dans le fichier `php.ini`, cherche la ligne :

```ini
;extension=pdo_pgsql
```

Et décommente-la (enlève le `;` au début) :

```ini
extension=pdo_pgsql
```

Cherche aussi :

```ini
;extension=pgsql
```

Et décommente-la aussi :

```ini
extension=pgsql
```

### Étape 5 : Vérifier que les DLL existent

Les fichiers DLL doivent être dans le dossier `ext` de PHP :
- `php_pdo_pgsql.dll`
- `php_pgsql.dll`

Si ces fichiers n'existent pas, il faut télécharger PHP avec les extensions PostgreSQL.

### Étape 6 : Redémarrer le serveur

Si tu utilises un serveur local (XAMPP, WAMP, etc.), redémarre-le.

### Étape 7 : Vérifier que ça fonctionne

```bash
php -m | findstr pgsql
```

Tu devrais voir :
- `pdo_pgsql`
- `pgsql`

---

## 🔄 Alternative : Utiliser XAMPP/WAMP avec PostgreSQL

Si tu utilises XAMPP ou WAMP, ils incluent souvent les extensions PostgreSQL.

### Pour XAMPP :

1. Ouvre `C:\xampp\php\php.ini`
2. Décommente :
   ```ini
   extension=pdo_pgsql
   extension=pgsql
   ```
3. Redémarre Apache

### Pour WAMP :

1. Clique sur l'icône WAMP
2. PHP > PHP extensions
3. Coche `php_pdo_pgsql` et `php_pgsql`
4. Redémarre WAMP

---

## 🆘 Si les DLL n'existent pas

Si les fichiers DLL n'existent pas dans le dossier `ext`, tu dois :

1. Télécharger PHP avec les extensions PostgreSQL
2. Ou télécharger les DLL depuis PECL
3. Ou utiliser un package PHP complet (XAMPP, WAMP, Laragon)

---

## ✅ Vérification finale

Une fois l'extension installée, teste :

```bash
php artisan migrate
```

Ça devrait fonctionner !

---

**Dis-moi quelle version de PHP tu utilises et comment tu l'as installé (XAMPP, WAMP, ou autre), et je t'aiderai plus précisément !** 🚀

