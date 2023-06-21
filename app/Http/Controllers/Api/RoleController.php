<?php
namespace App\Http\Controllers\Api;

use App\Repositories\RoleRepository;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\PermissionsRoleRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Exception;
use App\Exceptions\ApiException;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    private RoleRepository $repository;
    
    public function __construct(RoleRepository $roleRepository)
    {
        $this->repository = $roleRepository;
    }
    
    public function index()
    {
        $this->checkPermission('viewAny', Role::class);
        $roles = $this->repository->all();
        return response()->json([
            'success' => true,
            'payload' => [
                'items' => $roles
            ]
        ]);
    }

    public function store(StoreRoleRequest $request)
    {
        $this->checkPermission('create', Role::class);
        $elem = $this->repository->create($request->validated());
        return response()->json([
            'success' => true,
            'payload' => [
                'elem' => $elem
            ]
        ], 201);
    }
    
    public function show(int $id)
    {
        $this->checkPermission('view', Role::class);
        $elem = $this->findOrFalseResponse($id);
        if ($elem instanceof JsonResponse){
            return $elem;
        }
        return response()->json([
            'success' => true,
            'payload' => [
                'elem' => $elem
            ]
        ]);
    }

    public function showPermissions(int $id)
    {
        $elem = $this->findOrFalseResponse($id);
        if ($elem instanceof JsonResponse){
            return $elem;
        }
        $this->checkPermission('view', $elem);
        return response()->json([
            'success' => true,
            'payload' => [
                'permissions' => $elem->permissions
            ]
        ]);
    }
    
    public function update(UpdateRoleRequest $request, int $id)
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
    
    public function attachPermissions(PermissionsRoleRequest $request, int $id)
    {
        $elem = $this->findOrFalseResponse($id);
        if ($elem instanceof JsonResponse){
            return $elem;
        }
        $this->checkPermission('attachPermissions', $elem);
        $permissionCodes = $request->input('permissions');
        $permissions = Permission::whereIn('code', $permissionCodes)->get();
        $elem->permissions()->sync($permissions);
        return response()->json([
            'success' => true,
            'payload' => [
                'permissions' => $permissions
            ]
        ], 201);
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
                        'message' => 'Роли с указанным id не найдено'
                    ]
                ]
            ], 404);
        }
        return $elem;
    }
}
