<?php
namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * Интерфейс для классов типа Репозиторий
 */
interface RepositoryInterface
{
    public function all();

    public function create(array $data): ?Model;

    public function find(int $id): ?Model;

    public function update(Model $elem, array $data): Model;

    public function delete(Model $elem): bool;

}
