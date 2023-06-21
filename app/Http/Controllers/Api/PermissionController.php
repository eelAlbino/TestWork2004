<?php

namespace App\Http\Controllers\Api;

use App\Models\Permission;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Permission::all();
        return response()->json([
            'success' => true,
            'payload' => [
                'items' => $items
            ]
        ]);
    }
}
