<?php
/**
 * @author    eBusiness360 – Maxime LESGUILLIER aka Maxcode
 * @copyright Copyright © 2026 eBusiness360 – Maxime LESGUILLIER aka Maxcode. Tous droits réservés.
 * @license   Licence propriétaire — voir le fichier LICENSE
 * @package   Maxcode_SitemapExcludeCms
 */
declare(strict_types=1);

namespace Maxcode\SitemapExcludeCms\Test\Unit\Plugin\Sitemap;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Sitemap\Model\ResourceModel\Cms\Page as SitemapCmsPage;
use Maxcode\SitemapExcludeCms\Plugin\Sitemap\ExcludeFlaggedPages;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ExcludeFlaggedPagesTest extends TestCase
{
    private ResourceConnection&MockObject $resourceConnection;
    private AdapterInterface&MockObject $connection;
    private Select&MockObject $select;
    private SitemapCmsPage&MockObject $subject;
    private ExcludeFlaggedPages $plugin;

    /** @var array<int, array{0: string, 1: mixed}> */
    private array $whereCalls = [];

    protected function setUp(): void
    {
        $this->resourceConnection = $this->createMock(ResourceConnection::class);
        $this->connection = $this->createMock(AdapterInterface::class);
        $this->select = $this->createMock(Select::class);
        $this->subject = $this->createMock(SitemapCmsPage::class);

        $this->select->method('from')->willReturnSelf();
        $this->select->method('where')->willReturnCallback(
            function (string $condition, $value = null): Select {
                $this->whereCalls[] = [$condition, $value];
                return $this->select;
            }
        );
        $this->connection->method('select')->willReturn($this->select);
        $this->resourceConnection->method('getTableName')->willReturnArgument(0);

        $this->plugin = new ExcludeFlaggedPages($this->resourceConnection);
    }

    public function testRemovesFlaggedPagesAndKeepsTheOthers(): void
    {
        $this->resourceConnection->method('getConnection')->willReturn($this->connection);
        $this->connection->method('fetchCol')->willReturn(['5']);

        $result = $this->plugin->afterGetCollection($this->subject, $this->pages([2, 5, 9]));

        self::assertSame([2, 9], array_keys($result));
    }

    public function testLeavesTheListUntouchedWhenNothingIsFlagged(): void
    {
        $this->resourceConnection->method('getConnection')->willReturn($this->connection);
        $this->connection->method('fetchCol')->willReturn([]);

        $pages = $this->pages([2, 5, 9]);

        self::assertSame($pages, $this->plugin->afterGetCollection($this->subject, $pages));
    }

    public function testDoesNotQueryTheDatabaseForAnEmptyList(): void
    {
        $this->resourceConnection->expects(self::never())->method('getConnection');

        self::assertSame([], $this->plugin->afterGetCollection($this->subject, []));
    }

    public function testOnlyChecksThePagesOfTheSitemapAndTheFlag(): void
    {
        $this->resourceConnection->method('getConnection')->willReturn($this->connection);
        $this->connection->method('fetchCol')->willReturn([]);

        $this->plugin->afterGetCollection($this->subject, $this->pages([2, 5, 9]));

        self::assertContains(['exclude_from_sitemap = ?', 1], $this->whereCalls);
        self::assertContains(['page_id IN (?)', [2, 5, 9]], $this->whereCalls);
    }

    /**
     * @param int[] $ids
     * @return array<int, DataObject>
     */
    private function pages(array $ids): array
    {
        $pages = [];
        foreach ($ids as $id) {
            $pages[$id] = new DataObject(['id' => $id, 'url' => 'page-' . $id]);
        }

        return $pages;
    }
}
