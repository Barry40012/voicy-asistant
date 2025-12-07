# 🪣 Créer le Bucket "audios" dans Supabase Storage

## 📋 Étapes pour créer le bucket

Tu es sur la page Storage. Voici ce qu'il faut faire :

### 1. Cliquer sur "Create a file bucket"

Sur la page où tu es, tu vois :
- **"Create a file bucket"** (bouton ou lien)
- Clique dessus

### 2. Remplir le formulaire

Tu verras un formulaire avec plusieurs champs :

#### A. Name (Nom du bucket)
- **Entre exactement** : `audios`
- ⚠️ **Important** : En minuscules, sans espaces, sans caractères spéciaux
- C'est le nom que le code utilise

#### B. Public bucket (Bucket public)
- ❌ **DÉCOCHE cette case**
- Le bucket doit être **PRIVÉ** (pour la sécurité des fichiers audio)
- Si tu laisses coché, n'importe qui avec l'URL pourra accéder aux fichiers

#### C. File size limit (Limite de taille)
- Tu peux laisser par défaut
- Ou mettre `50` MB (ou plus selon tes besoins)
- C'est la taille maximale d'un fichier

#### D. Allowed MIME types (Types MIME autorisés) - Optionnel
- Tu peux laisser vide
- Ou ajouter : `audio/ogg`, `audio/mpeg`, `audio/wav`, `audio/mp3`
- Cela limite les types de fichiers acceptés

### 3. Créer le bucket

1. Vérifie que :
   - ✅ Name : `audios`
   - ✅ Public bucket : **DÉCOCHÉ** (privé)
2. Clique sur **"Create bucket"** ou **"Save"**

### 4. Vérifier que c'est créé

Après la création, tu devrais voir :
- Le bucket **"audios"** dans la liste des buckets
- Statut : **Private** (pas Public)

---

## ✅ Checklist

- [ ] Bucket créé avec le nom exact : `audios`
- [ ] Public bucket : **DÉCOCHÉ** (privé)
- [ ] Bucket visible dans la liste Storage

---

## 🎯 Une fois créé

Une fois le bucket créé, tu auras toutes les informations nécessaires pour configurer le `.env` !

Il ne restera plus que :
- Le Database Password (celui que tu as créé lors de la création du projet)

---

**Dis-moi quand le bucket est créé !** 🚀

