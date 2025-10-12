# Système de Gestion de Bibliothèque en Ligne

## Description

Application web complète de gestion de bibliothèque permettant aux lecteurs d'emprunter des livres et aux administrateurs de gérer l'inventaire, les utilisateurs et les prêts.

## Fonctionnalités

### Interface Lecteur
- Création de compte avec validation d'email
- Connexion sécurisée avec captcha
- Tableau de bord personnel
- Consultation des livres empruntés
- Gestion du profil utilisateur
- Changement de mot de passe
- Récupération de mot de passe

### Interface Administration
- Gestion des catégories de livres
- Gestion des auteurs
- Gestion des livres (ajout, modification, suppression)
- Gestion des sorties/emprunts
- Gestion des lecteurs inscrits
- Suivi des retours et amendes
- Tableau de bord statistiques

## Technologies Utilisées

- **Backend**: PHP 7.4+
- **Base de données**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 4.0
- **Icônes**: Font Awesome 5.15.4

## Prérequis

- Serveur web (Apache/Nginx)
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Extension PDO PHP activée

## Installation

### 1. Cloner le projet

```bash
git clone [URL_DU_REPOSITORY]
cd library-management-system
```

### 2. Configuration de la base de données

Importez le fichier SQL fourni :

```bash
mysql -u root -p < library.sql
```

### 3. Configuration de la connexion

Modifiez le fichier `includes/config.php` et `admin/includes/config.php` :

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');
define('DB_NAME', 'library');
```

### 4. Permissions des fichiers

Assurez-vous que le fichier `readerid.txt` est accessible en écriture :

```bash
chmod 666 readerid.txt
```

### 5. Démarrage

Placez les fichiers dans le répertoire de votre serveur web et accédez à l'application via :

```
http://localhost/votre-repertoire/
```

## Comptes par défaut

### Administrateur
- **Utilisateur**: admin
- **Mot de passe**: admin123

### Lecteur de test
- **Email**: test@gmail.com
- **Mot de passe**: test123

## Structure du projet

```
library-management/
├── admin/                      # Interface administration
│   ├── includes/              # Fichiers de configuration admin
│   ├── assets/                # Ressources CSS/JS admin
│   ├── add-author.php         # Ajout d'auteur
│   ├── add-book.php           # Ajout de livre
│   ├── add-category.php       # Ajout de catégorie
│   ├── add-issue-book.php     # Enregistrement d'emprunt
│   ├── dashboard.php          # Tableau de bord admin
│   └── ...
├── includes/                   # Fichiers de configuration
│   ├── config.php             # Configuration BD
│   ├── header.php             # En-tête navigation
│   └── footer.php             # Pied de page
├── assets/                     # Ressources CSS/JS/images
│   ├── css/
│   └── js/
├── index.php                   # Page de connexion lecteur
├── signup.php                  # Inscription lecteur
├── dashboard.php               # Tableau de bord lecteur
├── adminlogin.php             # Page de connexion admin
├── captcha.php                # Génération captcha
├── library.sql                # Script de création BD
└── README.md                  # Ce fichier
```

## Structure de la base de données

### Tables principales

- **admin**: Comptes administrateurs
- **tblreaders**: Lecteurs inscrits
- **tblbooks**: Catalogue de livres
- **tblauthors**: Liste des auteurs
- **tblcategory**: Catégories de livres
- **tblissuedbookdetails**: Historique des emprunts

## Sécurité

- Mots de passe hashés avec `password_hash()`
- Protection contre les injections SQL avec PDO
- Validation CAPTCHA sur les formulaires sensibles
- Gestion de sessions sécurisées
- Filtrage et échappement des entrées utilisateur

## Fonctionnalités avancées

### Système d'identifiants automatiques
Les identifiants lecteurs sont générés automatiquement au format `SID001`, `SID002`, etc.

### Validation d'email en temps réel
Vérification AJAX de la disponibilité de l'email lors de l'inscription.

### Gestion des amendes
Calcul automatique des amendes pour retard de retour.

### Système de statut
- Lecteurs: Actif/Bloqué
- Livres: Disponible/Emprunté
- Catégories: Active/Inactive

## Personnalisation

### Modification du thème
Les styles sont centralisés dans `assets/css/style.css`

### Ajout de fonctionnalités
Le code est structuré de manière modulaire pour faciliter l'ajout de nouvelles fonctionnalités.

## Dépannage

### Problème de connexion à la base de données
Vérifiez les paramètres dans `includes/config.php`

### Erreur lors de la génération du CAPTCHA
Assurez-vous que l'extension GD de PHP est activée

### Problème de permissions
Vérifiez que le serveur web a les droits d'écriture sur `readerid.txt`

## Améliorations futures

- [ ] Système de réservation de livres
- [ ] Notifications par email
- [ ] Export des statistiques en PDF
- [ ] API REST pour intégration mobile
- [ ] Système de recommandation de livres
- [ ] Interface de recherche avancée

## Contribuer

Les contributions sont les bienvenues ! Pour contribuer :

1. Forkez le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Poussez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Contact

Pour toute question ou suggestion :
- Email: contact@example.com
- Issues: [GitHub Issues](lien_vers_issues)

## Remerciements

- Bootstrap pour le framework CSS
- Font Awesome pour les icônes
- La communauté PHP pour les bonnes pratiques

---

**Note**: Ce projet est à but éducatif. Pour un usage en production, des mesures de sécurité supplémentaires sont recommandées.