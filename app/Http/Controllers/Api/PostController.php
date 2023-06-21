<?php
namespace App\Http\Controllers\Api;

use App\Repositories\PostRepository;
use App\Models\Post;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Exception;
use App\Exceptions\ApiException;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    private PostRepository $repository;
    
    public function __construct(PostRepository $postRepository)
    {
        $this->repository = $postRepository;
    }
    
    public function index()
    {
        $this->checkPermission('viewAny', Post::class);
        $posts = $this->repository->all();
        return response()->json([
            'success' => true,
            'payload' => [
                'items' => $posts
            ]
        ]);
    }

    public function store(StorePostRequest $request)
    {
        $this->checkPermission('create', Post::class);
        $addData = $request->validated();
        $user = auth()->user();
        $addData['created_by'] = $user->id;
        $elem = $this->repository->create($addData);
        return response()->json([
            'success' => true,
            'payload' => [
                'elem' => $elem
            ]
        ], 201);
    }
    
    public function show(int $id)
    {
        $elem = $this->findOrFalseResponse($id);
        if ($elem instanceof JsonResponse){
            return $elem;
        }
        $this->checkPermission('view', $elem);
        return response()->json([
            'success' => true,
            'payload' => [
                'elem' => $elem
            ]
        ]);
    }

    public function update(UpdatePostRequest $request, int $id)
    {
        $elem = $this->findOrFalseResponse($id);
        if ($elem instanceof JsonResponse) {
            return $elem;
        }
        $this->checkPermission('update', $elem);
        $elem = $this->repository->update($elem, $request->validated());
        return response()->json([
            'success' => true,
            'payload' => [
                'elem' => $elem
            ]
        ]);
    }
    
    public function destroy(int $id)
    {
        $elem = $this->findOrFalseResponse($id);
        if ($elem instanceof JsonResponse) {
            return $elem;
        }
        $this->checkPermission('delete', $elem);
        $this->repository->delete($elem);
        return response()->json([
            'success' => true,
            'payload' => [
                'elem' => $elem
            ]
        ], 204);
    }

    /**
     * Если элемента не было найдено, возвращает готовый 404 ответ
     * @param int $id
     */
    private function findOrFalseResponse(int $id)
    {
        $elem = $this->repository->find($id);
        if (!$elem) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'code' => 'not_found',
                        'message' => 'Поста с указанным id не найдено'
                    ]
                ]
            ], 404);
        }
        return $elem;
    }
}
