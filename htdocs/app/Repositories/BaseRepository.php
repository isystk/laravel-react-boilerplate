<?php

namespace App\Repositories;
abstract class BaseRepository
{

    protected $model;

    public function __construct()
    {
        $this->model = app($this->model());
    }


    // Create a new record
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // Update a record by ID
    public function update($id, array $data)
    {
        $record = $this->findById($id);

        if ($record) {
            $record->update($data);
            return $record;
        }

        return null;
    }

    // Delete a record by ID
    public function delete($id)
    {
        $record = $this->findById($id);

        if ($record) {
            $record->delete();
            return true;
        }

        return false;
    }

    // Find a record by ID
    public function findById($id)
    {
        return $this->model->find($id);
    }

    abstract public function model();

}
