# Carte interactive des agressions à Namur (ou ailleurs...)

## Description
Il s'agit d'une carte sur laquelle les utilisateurs peuvent déposer des points là où une agression a eu lieu en décrivant les faits.
A l'origine, la carte a été créée pour la ville de Namur (en Belgique), mais elle peut être facilement adaptée à d'autres villes.

## Installation

- Créer une base de données et la renseigner dans le .env

- Configurer les autres variables du .env

- Ajouter ces lignes au .env (ces informations serviront à accéder à l'interface d'admin)
```
ADMIN_USERNAME=
ADMIN_PASSWORD=
```

- Installer les dépendances
```
composer install
```

- Exécuter les migrations
```
php artisan migrate
```

## les améliorations possibles

- [ ] Intégrer différentes langues
- [ ] Ajouter un système de protection CSRF
- [ ] Ajouter un flux RSS
- [ ] Améliorer la partie "admin"
- [ ] Améliorer le système de filtre par date
- [ ] Ajouter un système de filtre par type d'agression
- [ ] Centrer la carte en fonction de la localisation de l'utilisateur
- [ ] Autre...

N'hésitez pas à me contacter si vous souhaitez vous investir dans ce projet.

## Documentation générale de Lumen

[Documentation](https://lumen.laravel.com/docs/8.x) 

## Références du projet

Plusieurs articles ont déjà été écrits au sujet de ce projet :

[RTL INFO](https://www.rtl.be/info/regions/namur/un-jeune-cree-une-carte-interactive-reprenant-les-agressions-a-namur-video--1338245.aspx) 

[La Meuse](https://lameuse.sudinfo.be/869317/article/2021-11-16/un-fossois-cree-une-carte-pour-signaler-les-agressions-sur-namur) 

[RTBF](https://www.rtbf.be/info/regions/namur/detail_namur-une-carte-interactive-pour-signaler-des-agressions-developpee-par-un-jeune-de-24-ans?id=10879736) 

[Lavenir](https://www.lavenir.net/cnt/dmf20211109_01634212/insecurite-a-namur-un-jeune-citoyen-cree-une-carte-des-agressions) 
