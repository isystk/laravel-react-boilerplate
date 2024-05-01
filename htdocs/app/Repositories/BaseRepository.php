<?php

namespace App\Repositories;

abstract class BaseRepository
{

    protected mixed $model;

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
        $record = $this->findById($id);

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
        $record = $this->findById($id);

        if (null === $record) {
            throw new \RuntimeException('An unexpected error occurred.');
        }

        $record->delete();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * @return string
     */
    abstract protected function model(): string;

}
