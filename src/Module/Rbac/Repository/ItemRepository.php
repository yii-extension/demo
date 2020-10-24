<?php

declare(strict_types=1);

namespace App\Module\Rbac\Repository;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use RuntimeException;
use App\Module\Rbac\ActiveRecord\ItemAR;
use App\Module\Rbac\Form\ItemForm;
use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecordInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\Exception;

final class ItemRepository
{
    private ConnectionInterface $db;
    private LoggerInterface $logger;
    private ItemAR $item;
    private ?ActiveQuery $itemQuery = null;

    public function __construct(ConnectionInterface $db, LoggerInterface $logger, ItemAR $item)
    {
        $this->db = $db;
        $this->logger = $logger;
        $this->item = $item;
        $this->itemQuery();
    }

    public function findItemAll(): array
    {
        return $this->itemQuery->all();
    }

    public function findItemAllAsArray(): array
    {
        return $this->itemQuery->asArray()->all();
    }

    public function findItemByCondition(array $condition): ?ActiveRecordInterface
    {
        return $this->itemQuery->findOne($condition);
    }

    public function findItemById(string $condition): ?ActiveRecordInterface
    {
        return $this->findItemByCondition(['id' => $condition]);
    }

    public function loadData(ActiveRecordInterface $item, ItemForm $itemForm): void
    {
        $itemForm->setAttribute('name', $item->getAttribute('name'));
        $itemForm->setAttribute('description', $item->getAttribute('description'));
        $itemForm->setAttribute(
            'type',
            $item->getAttribute('type') === 'role' ? 1 : 2
        );
    }

    public function create(ItemForm $itemForm): bool
    {
        if ($this->item->getIsNewRecord() === false) {
            throw new RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        /** @psalm-suppress UndefinedInterfaceMethod */
        $transaction = $this->db->beginTransaction();

        try {
            $this->insert($itemForm);

            if (!$this->item->save()) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();

            $result = true;
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->logger->log(LogLevel::WARNING, $e->getMessage());
            throw $e;
        }

        return $result;
    }

    public function update(ActiveRecordInterface $item, ItemForm $itemForm): bool
    {
        /** @var ItemAR $item */
        $item->name($itemForm->getAttributeValue('name'));
        $item->description($itemForm->getAttributeValue('description'));
        $item->type($itemForm->getAttributeValue('type'));
        $item->updatedAt();

        return $item->save();
    }

    private function itemQuery(): ActiveQueryInterface
    {
        if ($this->itemQuery === null) {
            $this->itemQuery = new ActiveQuery(ItemAR::class, $this->db);
        }

        return $this->itemQuery;
    }

    private function insert(ItemForm $itemForm): void
    {
        $this->item->name($itemForm->getAttributeValue('name'));
        $this->item->description($itemForm->getAttributeValue('description'));
        $this->item->type($itemForm->getAttributeValue('type'));
        $this->item->createdAt();
        $this->item->updatedAt();
    }
}
