<?php

namespace App\Domain\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseEloquentRepository implements BaseRepository
{

    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->model());
    }


    /**
     * @param array $data
     * @return mixed
     */
    // @phpstan-ignore-next-line
    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     */
    // @phpstan-ignore-next-line
    public function update(int $id, array $data): mixed
    {
        $record = $this->getById($id);

        if (null === $record) {
            throw new \RuntimeException('An unexpected error occurred.');
        }

        $record->update($data);
        return $record;
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        $record = $this->getById($id);

        if (null === $record) {
            throw new \RuntimeException('An unexpected error occurred.');
        }

        $record->delete();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * @return string
     */
    abstract protected function model(): string;

}
