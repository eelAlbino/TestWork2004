<?php
namespace App\Http\Controllers\Api;

use App\Repositories\PostRepository;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\RolesUserRequest;
use App\Http\Controllers\Controller;
use Exception;
use App\Exceptions\ApiException;

class UserController extends Controller
{
    public function showRoles()
    {
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'payload' => [
                'roles' => $user->roles
            ]
        ]);
    }

    public function attachRoles(RolesUserRequest $request)
    {
        $user = auth()->user();
        $roleCodes = $request->input('roles');
        $roles = Role::whereIn('code', $roleCodes)->get();
        $user->roles()->sync($roles);
        return response()->json([
            'success' => true,
            'payload' => [
                'roles' => $roles
            ]
        ], 201);
    }
}
