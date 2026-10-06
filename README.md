<div align="center">

**English** · [Français](README.fr.md)

# Maxcode Sitemap Exclude CMS for Magento 2

**One switch on each CMS page to keep it out of your XML sitemap. Free, no core override, nothing to configure.**

![License: free EULA](https://img.shields.io/badge/license-free%20EULA-1E4FA3)
![Magento 2.4](https://img.shields.io/badge/Magento-2.4.x-F26322)
![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4)
![Packagist](https://img.shields.io/packagist/v/maxcode/module-sitemap-exclude-cms?color=0F1E4D)

![The "Exclude from XML sitemap" switch on a CMS page](docs/images/cms-page-switch.png)

</div>

## The problem

Magento's XML sitemap lists **every active CMS page**. The only ones it leaves out are three
"utility" pages: the home page, the 404 page and the "cookies disabled" page.

Everything else ends up in the sitemap you hand to Google: the "account not approved" page of a
third-party module, confirmation pages, internal pages, test pages. As of Magento 2.4.8 there is
**no setting** to remove one of them, neither in *Marketing > SEO & Search > Site Map* nor on the
page itself.

## What it does

- **A switch on every CMS page**, in the *Search Engine Optimization* section: *Exclude from XML sitemap*.
- **Flagged pages disappear from every sitemap**, in all store views, at the next generation.
- **No core override:** one plugin on the sitemap's CMS pages query. Magento's own rules (inactive
  pages, utility pages, store view assignment) are left untouched and keep following Magento upgrades.
- **Safe by default:** one column, `cms_page.exclude_from_sitemap`, set to `0`. Nothing changes
  until you flag a page.
- **Translated** into English and French.
- **Unit tests** included.

<!-- Captures à ajouter : docs/images/sitemap-before.png et docs/images/sitemap-after.png
| Sitemap before | Sitemap after |
|---|---|
| ![Sitemap before](docs/images/sitemap-before.png) | ![Sitemap after](docs/images/sitemap-after.png) |
-->

## Installation

```bash
composer require maxcode/module-sitemap-exclude-cms
bin/magento module:enable Maxcode_SitemapExcludeCms
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

**Why `module:enable` first?** The module adds a column (`cms_page.exclude_from_sitemap`) through
Magento's declarative schema. `setup:upgrade` builds the schema from the modules it already knows
when it starts, and a module that Composer has just installed is not yet listed in
`app/etc/config.php`. Without `module:enable`, the first `setup:upgrade` only registers the module
and a second run is needed to create the column. This is how Magento loads its module list, so it
applies to any new module that ships a `db_schema.xml` (observed on 2.4.8-p5). Check the result with
`bin/magento setup:db:status`: it must answer "All modules are up to date". If `app/etc/config.php`
already lists the module (for instance because it is committed and deployed with it), a single
`setup:upgrade` is enough.

No static content to deploy: the module only adds a field to an existing admin form.

Requirements: Magento Open Source or Adobe Commerce 2.4.x, PHP 8.2 or later.
Tested on Magento Open Source 2.4.8-p5 with PHP 8.3.

## Usage

1. Go to **Content > Elements > Pages** and edit a page.
2. In **Search Engine Optimization**, set **Exclude from XML sitemap** to **Yes**, then save.
3. Regenerate the sitemap: **Marketing > SEO & Search > Site Map > Generate**, or wait for the
   scheduled generation.

> **Good to know.** The module only manages the sitemap. It does not add `noindex` and does not
> block crawling. A page already known to Google stays in its index until you tell Google otherwise
> (a `noindex` directive, or a removal request in Search Console).

## For developers

| Piece | Role |
|---|---|
| `etc/db_schema.xml` | adds `cms_page.exclude_from_sitemap` (`smallint`, not null, default `0`) |
| `view/adminhtml/ui_component/cms_page_form.xml` | the toggle, in the `search_engine_optimisation` fieldset |
| `Plugin/Sitemap/ExcludeFlaggedPages` | `afterGetCollection` on `Magento\Sitemap\Model\ResourceModel\Cms\Page` |

The plugin receives the pages Magento found eligible for a store view, asks the database which of
them are flagged (`WHERE exclude_from_sitemap = 1 AND page_id IN (…)`, one query per store view and
per generation) and removes them. No `preference`, no copy of Magento code.

Modules that replace Magento's sitemap generator altogether are not covered.

To stop using the module, run `bin/magento module:disable Maxcode_SitemapExcludeCms`: flagged pages
come back in the sitemap at the next generation. The column is harmless and can stay in place.

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## License and support

Free for any number of Magento installations that you own or operate. You receive the full source
code and may adapt it to your needs; redistribution is not allowed. See [LICENSE](LICENSE)
(free-module EULA).

Provided as is, **without support or update commitment**. For questions and paid services:
[ebusiness360.fr](https://www.ebusiness360.fr/contact/).
