<?php

declare(strict_types=1);

namespace App\Module\Rbac\Repository;

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
    private ItemAR $item;
    private ?ActiveQuery $itemQuery = null;

    public function __construct(ConnectionInterface $db, ItemAR $item)
    {
        $this->db = $db;
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

    public function loadData(ItemForm $itemForm, string $id): void
    {
        /** @var ItemAR $aqClass */
        $aqClass = $this->findItemByCondition(['id' => (int) $id]);

        $itemForm->setAttribute('name', $aqClass->getAttribute('name'));
        $itemForm->setAttribute('description', $aqClass->getAttribute('description'));
        $itemForm->setAttribute(
            'type',
            $aqClass->getAttribute('type') === 'role' ? 1 : 2
        );

        unset($aqClass);
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
            throw $e;
        }

        return $result;
    }

    /**
     * @param ItemForm $itemForm
     * @param string $id
     *
     * @return bool|int
     */
    public function update(ItemForm $itemForm, string $id)
    {
        /** @var ItemAR $aqClass */
        $aqClass = $this->findItemByCondition(['id' => (int) $id]);

        $aqClass->name($itemForm->getAttributeValue('name'));
        $aqClass->description($itemForm->getAttributeValue('description'));
        $aqClass->type($itemForm->getAttributeValue('type'));
        $aqClass->updatedAt();

        return $aqClass->update();
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
