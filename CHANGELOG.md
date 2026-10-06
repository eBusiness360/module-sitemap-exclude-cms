# Changelog — Maxcode_SitemapExcludeCms

Toutes les évolutions notables du module sont consignées ici.
Format : [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/), versionnage [SemVer](https://semver.org/lang/fr/).

## [1.0.2] — 2026-10-06

### Modifié
- Documentation : capture d'écran de l'interrupteur dans les deux README (`docs/images/`, exclu de l'archive
  Composer).
- Documentation : installation avec `module:enable` avant `setup:upgrade`, et explication du double passage
  nécessaire sans lui (schéma déclaratif d'un module neuf). Aucun changement de code.

## [1.0.1] — 2026-10-06

### Modifié
- `composer.json` : description bilingue anglais / français (affichée telle quelle sur Packagist) et mots-clés.
  Aucun changement de code.

## [1.0.0] — 2026-10-06 — Première version

### Ajouté
- Case « Exclure du plan de site XML » dans l'onglet *Optimisation pour les moteurs de recherche* de chaque
  page CMS (colonne `cms_page.exclude_from_sitemap`, schéma déclaratif).
- Plugin sur le fournisseur de pages CMS du plan de site : les pages cochées sont retirées de tous les plans
  de site, pour toutes les vues de magasin. Aucune copie de code du cœur Magento : la compatibilité suit le cœur.
- Traduction française embarquée.
- Licence : CLUF Maxcode des modules gratuits (fichier `LICENSE`), identique à Maxcode_AdminBranding.
- Documentation : `README.md` (anglais) et `README.fr.md`.
