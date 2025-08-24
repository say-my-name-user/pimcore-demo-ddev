<?php

namespace App\Model\Filter;

use Pimcore\Model\Dao\AbstractDao;
use Pimcore\Model\Exception\NotFoundException;

class Dao extends AbstractDao
{
    protected string $tableName = 'filters';
    protected string $filterToColorTableName = 'filter_color';

    /**
     * Get filter model data
     *
     * @param int|null $id
     *
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function getById(?int $id = null): void
    {
        if ($id !== null)  {
            $this->model->setId($id);
        }

        $data = $this->db->fetchAssociative(
            'SELECT * FROM ' . $this->tableName . ' WHERE id = ?',
            [$this->model->getId()]
        );

        if (!$data) {
            throw new NotFoundException("Object with the ID {$this->model->getId()} doesn't exist");
        }

        $this->assignVariablesToModel($data);
        $this->assignColors();
    }

    /**
     * Save filter model data
     *
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function save(): void
    {
        $vars = get_object_vars($this->model);

        $buffer = [];

        $validColumns = $this->getValidTableColumns($this->tableName);

        if (count($vars)) {
            foreach ($vars as $k => $v) {
                if (!in_array($k, $validColumns)) {
                    continue;
                }

                $getter = 'get' . ucfirst($k);
                if (!is_callable([$this->model, $getter])) {
                    continue;
                }

                $value = $this->model->$getter();

                if (is_bool($value)) {
                    $value = (int)$value;
                }

                $buffer[$k] = $value;
            }
        }

        if ($this->model->getId() !== null) {
            $this->db->update($this->tableName, $buffer, ['id' => $this->model->getId()]);
            $this->saveColors();
            return;
        }

        $this->db->insert($this->tableName, $buffer);
        $this->model->setId($this->db->lastInsertId());
        $this->saveColors();
    }

    public function delete(): void
    {
        $this->db->delete($this->tableName, ['id' => $this->model->getId()]);
    }

    /**
     * Assign color relations if exist
     *
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    private function assignColors(): void
    {
        $colors = $this->db->fetchAllAssociative(
            'SELECT * FROM ' . $this->filterToColorTableName . ' WHERE filter_id = ?',
            [$this->model->getId()]
        );

        if (!$colors) {
            return;
        }

        foreach ($colors as $color) {
            $color = \App\Model\Color::getById($color['color_id']);
            $this->model->addColor($color);
        }
    }

    /**
     * Save color relations if exist
     *
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    private function saveColors(): void
    {
        // remove existing relations
        $this->db->delete($this->filterToColorTableName, ['filter_id' => $this->model->getId()]);

        foreach ($this->model->getColors() as $color) {
            $this->db->insert($this->filterToColorTableName, ['filter_id' => $this->model->getId(), 'color_id' => $color->getId()]);
        }
    }
}
