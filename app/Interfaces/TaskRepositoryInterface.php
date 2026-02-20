<?php

namespace App\Interfaces;

interface TaskRepositoryInterface
{
    public function all();
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id); 
    public function paginate(array $filters = []);
}
