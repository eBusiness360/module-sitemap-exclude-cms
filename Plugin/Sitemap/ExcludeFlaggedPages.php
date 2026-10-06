<?php
/**
 * @author    eBusiness360 – Maxime LESGUILLIER aka Maxcode
 * @copyright Copyright © 2026 eBusiness360 – Maxime LESGUILLIER aka Maxcode. Tous droits réservés.
 * @license   Licence propriétaire — voir le fichier LICENSE
 * @package   Maxcode_SitemapExcludeCms
 */
declare(strict_types=1);

namespace Maxcode\SitemapExcludeCms\Plugin\Sitemap;

use Magento\Framework\App\ResourceConnection;
use Magento\Sitemap\Model\ResourceModel\Cms\Page as SitemapCmsPage;

/**
 * Retire du plan de site XML les pages CMS dont la case « Exclure du plan de site XML » est cochée.
 *
 * Le plugin se branche sur la requête des pages CMS du plan de site (et non sur une copie du code du
 * cœur) : les règles natives de Magento (pages inactives, pages utilitaires, filtre par vue de magasin)
 * restent appliquées telles quelles et suivent les montées de version.
 */
class ExcludeFlaggedPages
{
    public function __construct(
        private readonly ResourceConnection $resourceConnection
    ) {
    }

    /**
     * @param SitemapCmsPage $subject
     * @param array<int|string, mixed> $result Pages éligibles, indexées par identifiant de page
     * @return array<int|string, mixed>
     */
    public function afterGetCollection(SitemapCmsPage $subject, array $result): array
    {
        if (!$result) {
            return $result;
        }

        $connection = $this->resourceConnection->getConnection();
        $flaggedIds = $connection->fetchCol(
            $connection->select()
                ->from($this->resourceConnection->getTableName('cms_page'), ['page_id'])
                ->where('exclude_from_sitemap = ?', 1)
                ->where('page_id IN (?)', array_keys($result))
        );

        return $flaggedIds ? array_diff_key($result, array_flip($flaggedIds)) : $result;
    }
}
