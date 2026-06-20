# 🛍️ Stubborn - Site e-commerce

## 📋 Description
Projet réalisé dans le cadre de la formation Développeur Web et Web Mobile.  
Le site permet la vente de sweat-shirts de la marque Stubborn.

**Marque** : Stubborn  
**Slogan** : Don't compromise on your look  
**Adresse** : Piccadilly Circus, London W1J 0DA, Royaume-Uni

---

## 🚀 Technologies utilisées

| Technologie | Version |
|-------------|---------|
| Symfony | 8.1 |
| PHP | 8.4 |
| MySQL | 8.0 |
| Bootstrap | 5.3 |
| Stripe | API test |

---

## ⚙️ Fonctionnalités

- ✅ Authentification (login / register)
- ✅ Page d'accueil avec 3 produits en vedette
- ✅ Catalogue des produits avec filtres par prix
- ✅ Page détail produit avec sélection de taille
- ✅ Panier (ajout, suppression, calcul total)
- ✅ Paiement test avec Stripe (sandbox)
- ✅ Back-office admin (CRUD produits)
- ✅ Menu dynamique selon connexion

---

## 👑 Compte administrateur (test)

| Email            | Password          | Rôle       |
|----------------  |-----------------  |----------  |
| `mila@gmail.com` | `ECnkbPqUAZ@h3sY` | ROLE_ADMIN |

> **Ou** : créer un compte et ajouter `["ROLE_ADMIN"]` dans la table `user`.

---

## 📦 Installation et lancement

```bash
# 1. Cloner le projet
git clone https://github.com/DsRiri/striborn-ecommerce.git
cd striborn-ecommerce

# 2. Installer les dépendances
composer install

# 3. Configurer .env (DATABASE_URL)

# 4. Créer la base de données
symfony console doctrine:database:create

# 5. Mettre à jour le schéma
symfony console doctrine:schema:update --force

# 6. Charger les produits
symfony console doctrine:fixtures:load

# 7. Lancer le serveur
symfony serve