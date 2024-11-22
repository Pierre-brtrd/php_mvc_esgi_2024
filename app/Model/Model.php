<?php

namespace App\Model;

use App\Core\Db;

abstract class Model
{
    protected ?string $table = null;
    private ?Db $db = null;

    public function findAll(): array|static
    {
        $query = $this->runQuery("SELECT * FROM {$this->table}");

        return $this->findHydrate($query->fetchAll());
    }

    public function find(int $id): bool|static
    {
        return $this->findHydrate(
            $this->runQuery("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id])->fetch()
        );
    }

    public function findBy(array $filters): array|static
    {
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $markers = [];
        $values = [];

        foreach ($filters as $key => $value) {
            $markers[] = "$key = :$key";
            $values[$key] = $value;
        }

        $sql .= implode(' AND ', $markers);

        return $this->findHydrate(
            $this->runQuery($sql, $values)->fetchAll()
        );
    }

    public function create(): bool|\PDOStatement
    {
        $fields = [];
        $markers = [];
        $values = [];

        foreach ($this as $key => $value) {
            if ($key !== 'table' && $key !== 'db' && $value !== null) {
                $fields[] = $key;
                $markers[$key] = ":$key";

                if ($value instanceof \DateTime) {
                    $value = $value->format('Y-m-d H:i:s');
                } elseif (is_array($value)) {
                    $value = json_encode($value);
                }

                $values[$key] = $value;
            }
        }

        $sql = "INSERT INTO {$this->table} " . '(' . implode(', ', $fields) . ') VALUES (' . implode(', ', $markers) . ')';

        return $this->runQuery($sql, $values);
    }

    public function update(): bool|\PDOStatement
    {
        $fields = [];
        $values = [];

        foreach ($this as $key => $value) {
            if ($key !== 'table' && $key !== 'db' && !empty($value)) {
                $fields[] = "$key = :$key";

                if ($value instanceof \DateTime) {
                    $value = $value->format('Y-m-d H:i:s');
                } elseif (is_array($value)) {
                    $value = json_encode($value);
                }

                $values[$key] = $value;
            }
        }

        /** @var Poste $this */
        $values['id'] = $this->getId();

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . ' WHERE id = :id';

        return $this->runQuery($sql, $values);
    }

    public function delete(): bool|\PDOStatement
    {
        /** @var Poste $this */
        return $this->runQuery("DELETE FROM {$this->table} WHERE id = :id", ['id' => $this->getId()]);
    }

    public function hydrate(array|object $data): static
    {
        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);

            if (method_exists($this, $setter)) {
                if ($key === 'createdAt' || $key === 'updatedAt') {
                    $value = new \DateTime($value);
                } elseif ($key === 'roles') {
                    $value = json_decode($value);
                }

                $this->$setter($value);
            }
        }

        return $this;
    }

    public function findHydrate(mixed $data): array|static|bool
    {
        if (is_array($data) && count($data) > 1) {
            $data = array_map(
                fn(object $item): static => (new static)->hydrate($item),
                $data
            );

            return $data;
        } elseif (!empty($data) && is_array($data)) {
            return [(new static)->hydrate($data[0])];
        } elseif(!empty($data)) {
            return (new static)->hydrate($data);
        }else {
            return $data;
        }
    }

    protected function runQuery(string $sql, array $attributs = []): bool|\PDOStatement
    {
        $this->db = Db::getInstance();

        if (!empty($attributs)) {
            $query = $this->db->prepare($sql);
            $query->execute($attributs);

            return $query;
        }

        return $this->db->query($sql);
    }
}