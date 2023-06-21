<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use App\Interfaces\RepositoryInterface;

class RoleRepository implements RepositoryInterface
{
    public function all()
    {
        return Role::all();
    }
    
    public function create(array $data): ?Model
    {
        return Role::create($data);
    }
    
    public function find(int $id): ?Model
    {
        return Role::find($id);
    }
    
    public function update(Model $elem, array $data): Model
    {
        $elem->update($data);
        
        return $elem;
    }
    
    public function delete(Model $elem): bool
    {
        $elem->delete();
        return true;
    }
}