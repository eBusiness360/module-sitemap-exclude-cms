# Changelog — Maxcode_SitemapExcludeCms

Toutes les évolutions notables du module sont consignées ici.
Format : [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/), versionnage [SemVer](https://semver.org/lang/fr/).

## [1.0.0] — 2026-10-06 — Première version

### Ajouté
- Case « Exclure du plan de site XML » dans l'onglet *Optimisation pour les moteurs de recherche* de chaque
  page CMS (colonne `cms_page.exclude_from_sitemap`, schéma déclaratif).
- Plugin sur le fournisseur de pages CMS du plan de site : les pages cochées sont retirées de tous les plans
  de site, pour toutes les vues de magasin. Aucune copie de code du cœur Magento : la compatibilité suit le cœur.
- Traduction française embarquée.
- Licence : CLUF Maxcode des modules gratuits (fichier `LICENSE`), identique à Maxcode_AdminBranding.
- Documentation : `README.md` (anglais) et `README.fr.md`.
