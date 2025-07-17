# Guide d'Accès à la Page d'Administration

## Comment accéder à la page admin

### 1. Page de Connexion Admin
Pour accéder au panneau d'administration, vous devez utiliser la page de connexion dédiée :

**URL d'accès :** `login.html`

### 2. Identifiants de Connexion
D'après la base de données, les identifiants par défaut sont :

- **Nom d'utilisateur :** `admin`
- **Mot de passe :** `admin123`

### 3. Processus de Connexion

1. **Accédez à la page de connexion**
   - Ouvrez votre navigateur
   - Naviguez vers : `http://votre-domaine.com/login.html`

2. **Saisissez vos identifiants**
   - Username : `admin`
   - Password : `admin123`

3. **Cliquez sur "Login"**
   - Le système vérifiera vos identifiants
   - En cas de succès, vous serez redirigé vers : `admin-dashboard/index.php`

### 4. Panneau d'Administration
Une fois connecté, vous aurez accès au panneau d'administration complet (`admin_panel.html`) qui inclut :

#### Sections disponibles :
- **📊 Tableau de Bord** - Vue d'ensemble des statistiques
- **🛍️ Produits** - Gestion des produits (ajouter, modifier, supprimer)
- **📦 Commandes** - Gestion des commandes clients
- **👥 Clients** - Gestion de la base clients
- **📊 Inventaire** - Suivi des stocks
- **📈 Analytics** - Analyses et rapports
- **⚙️ Paramètres** - Configuration du système

### 5. Structure de l'Application
```
├── login.html              # Page de connexion admin
├── admin_login.php         # Script de traitement de connexion
├── admin_panel.html        # Interface du panneau d'administration
├── admin_panel.css         # Styles du panneau admin
├── admin_panel.js          # Fonctionnalités JavaScript
└── admin-dashboard/        # Dossier contenant les API et fichiers admin
    ├── index.php          # Page principale après connexion
    └── products_api.php   # API pour la gestion des produits
```

### 6. Sécurité
⚠️ **Important :** Les identifiants par défaut (`admin`/`admin123`) sont visibles dans le code source. Pour une utilisation en production, il est fortement recommandé de :

1. Changer le mot de passe par défaut
2. Implémenter un système de hashage pour les mots de passe
3. Ajouter des mesures de sécurité supplémentaires (limitation des tentatives, HTTPS, etc.)

### 7. Dépannage
- **Erreur de connexion :** Vérifiez que la base de données MySQL est configurée et accessible
- **Page non trouvée :** Assurez-vous que tous les fichiers sont présents dans le bon répertoire
- **Redirection échoue :** Vérifiez que le dossier `admin-dashboard/` existe avec le fichier `index.php`

---

**Note :** Cette application utilise PHP et MySQL. Assurez-vous d'avoir un serveur web configuré (Apache/Nginx) avec PHP et une base de données MySQL contenant la table `admin` avec les identifiants mentionnés.