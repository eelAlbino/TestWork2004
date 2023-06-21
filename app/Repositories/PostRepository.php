<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Interfaces\RepositoryInterface;

class PostRepository implements RepositoryInterface
{
    public function all()
    {
        return Post::all();
    }
    
    public function create(array $data): ?Model
    {
        return Post::create($data);
    }
    
    public function find($id): ?Model
    {
        return Post::find($id);
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