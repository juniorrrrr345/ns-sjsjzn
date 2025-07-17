# 🚀 Guide de Déploiement - Application PHP E-commerce

## ⚠️ Important : Pourquoi Netlify ne fonctionne pas

Votre application utilise **PHP + MySQL** et ne peut **PAS** être déployée sur Netlify car :
- Netlify = Sites statiques uniquement (HTML/CSS/JS)
- Votre app = Application dynamique PHP avec base de données

## ✅ Solutions de Déploiement Recommandées

### 🎯 Option 1: 000WebHost (Gratuit et Simple)

**Étapes détaillées :**

1. **Inscription**
   ```
   - Allez sur : https://www.000webhost.com/
   - Cliquez "Get Free Hosting"
   - Créez votre compte
   ```

2. **Configuration du site**
   ```
   - Nom du site : votre-nom-site
   - URL : votre-nom-site.000webhostapp.com
   ```

3. **Upload des fichiers**
   ```
   - Accès File Manager dans le panneau
   - Dossier de destination : public_html/
   - Uploadez TOUS vos fichiers (sauf .git/)
   ```

4. **Configuration de la base de données**
   ```
   - Panneau → Database → Create Database
   - Nom : ecommerce (ou similaire)
   - Importez votre fichier ecommerce.sql
   ```

5. **Mise à jour des credentials**
   ```php
   // Dans admin_login.php et products_api.php
   $host = 'localhost';
   $db   = 'id123456_ecommerce';  // Nom fourni par 000webhost
   $user = 'id123456_user';       // User fourni par 000webhost  
   $pass = 'votre_mot_de_passe';  // Password fourni par 000webhost
   ```

### 🎯 Option 2: Heroku (Plus professionnel)

**Prérequis :**
- Compte Heroku
- Heroku CLI installé

**Étapes :**

1. **Créer l'application**
   ```bash
   heroku create votre-app-name
   heroku addons:create cleardb:ignite
   ```

2. **Configuration PHP**
   ```bash
   # Créer composer.json si absent
   echo '{}' > composer.json
   
   # Créer index.php à la racine
   echo '<?php include_once("index.html"); ?>' > index.php
   ```

3. **Variables d'environnement**
   ```bash
   # Récupérer URL de la DB
   heroku config:get CLEARDB_DATABASE_URL
   
   # Configurer les variables
   heroku config:set DB_HOST=hostname
   heroku config:set DB_NAME=database_name
   heroku config:set DB_USER=username
   heroku config:set DB_PASS=password
   ```

### 🎯 Option 3: Railway (Moderne et simple)

1. **Connexion**
   ```
   - Allez sur : https://railway.app/
   - Connectez votre GitHub
   ```

2. **Nouveau projet**
   ```
   - New Project → Deploy from GitHub repo
   - Sélectionnez votre repo
   ```

3. **Ajouter MySQL**
   ```
   - Add service → Database → MySQL
   - Variables automatiquement configurées
   ```

## 📋 Checklist avant déploiement

- [ ] Base de données créée et configurée
- [ ] Fichier `ecommerce.sql` importé
- [ ] Credentials DB mis à jour dans les fichiers PHP
- [ ] Test de connexion admin (`admin` / `admin123`)
- [ ] Vérification que tous les fichiers sont uploadés

## 🔧 Modifications nécessaires pour le déploiement

### 1. Fichier de configuration centralisé

Créez `config.php` :
```php
<?php
// Configuration selon l'environnement
if (isset($_ENV['DATABASE_URL'])) {
    // Heroku
    $url = parse_url($_ENV['DATABASE_URL']);
    $host = $url["host"];
    $user = $url["user"];
    $pass = $url["pass"];
    $db = substr($url["path"], 1);
} else {
    // Développement local ou 000webhost
    $host = 'localhost';
    $db   = 'ecommerce';
    $user = 'root';
    $pass = '';
}
?>
```

### 2. Mise à jour des fichiers PHP

Remplacez les credentials hardcodés par :
```php
require_once 'config.php';
$conn = new mysqli($host, $user, $pass, $db);
```

## 🚨 Actions Immédiates

1. **Arrêtez Netlify** - Supprimez le repo de Netlify
2. **Choisissez une plateforme** ci-dessus
3. **Suivez le guide** correspondant
4. **Testez** votre application

## 📞 Support

Si vous rencontrez des problèmes :
- Vérifiez les logs d'erreur de votre hébergeur
- Testez la connexion DB en local d'abord
- Assurez-vous que PHP et MySQL sont bien configurés

---

**🎯 Recommandation finale :** Utilisez **000WebHost** pour commencer (gratuit, simple) puis migrez vers **Railway** ou **Heroku** pour plus de professionnalisme.