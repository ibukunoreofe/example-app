<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PermissionsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/permissions",
     *     tags={"Permissions"},
     *     summary="Get all permissions and roles assigned to me",
     *     description="It will return all permissions and roles assigned to the logged in users",
     *     operationId="listAllPermissions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *           @OA\JsonContent(
     *           ref="App\Models\Book"
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var $user User **/
        $user = auth()->user();

        return [
          "all_roles" => $user->getRoleNames(),
          "all_permissions" => $user->getAllPermissions()
        ];
    }

    /**
     * @OA\Post(
     *      path="/permissions",
     *      operationId="createRoleAndPermission",
     *      tags={"Permissions"},
     *      summary="Create a role and permission",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="role", type="string", description="The name of the role like users, admins, editors", example="users", nullable=false, default="users"),
     *                  @OA\Property(
     *                     property="permissions",
     *                     description="permissions to assign to the above role",
     *                     type="array",
     *                     @OA\Items(type="string"),
     *                     example={"create book", "edit book", "delete book"}
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Permissions have been created",
     *          @OA\JsonContent(
     *                  ref="App\Models\Book"
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      ),
     *  )
     *
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|string|max:50',
            'permissions' => 'required|array|max:50',
            'permissions.*' => 'required|string|max:50',
        ]);

        /** @var $user User **/
        $user = auth()->user();
        $role = Role::findOrCreate( $request->input('role') );
        if( !$user->hasRole($role) )
        {
            $user->assignRole( $role );
        }

        //create permission in database
        foreach ($request->input('permissions') as $permission)
            Permission::findOrCreate($permission);

        $role->syncPermissions($request->input('permissions'));

        return response($this->index(), ResponseAlias::HTTP_CREATED)
            ->header('Location', route('permissions.show', ['permission' => $role->name]));
    }

/**
     * @OA\Get (
     *      path="/permissions/{role}",
     *      operationId="showPermission",
     *      tags={"Permissions"},
     *      summary="Show permissions on role",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="role",
     *          in="path",
     *          required=true,
     *          description="name of the role you want to display",
     *          @OA\Schema(type="string"),
     *          example="users",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Permissions related to role",
     *          @OA\JsonContent(
     *                  ref="App\Models\Book"
     *          )
     *      ),
     *  )
     *
     * Display the specified resource.
     */
    public function show(string $role)
    {
        /** @var $user User **/
        $user = auth()->user();

        if( $user->hasRole($role) )
        {
            return Role::findByName($role )->getAllPermissions();
        }
        return response([ "message" => "You do not have this role ($role)!"], ResponseAlias::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Put(
     *      path="/permissions/{role}",
     *      operationId="updatePermission",
     *      tags={"Permissions"},
     *      summary="Update permissions on role",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="role",
     *          in="path",
     *          required=true,
     *          description="name of the role you are about to update",
     *          @OA\Schema(type="string"),
     *          example="users",
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="permissions",
     *                      description="permissions to assign to the above role",
     *                      type="object",
     *                      example={"0": "create book", "1": "edit book", "2": "delete book"},
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Permissions have been created",
     *          @OA\JsonContent(
     *                  ref="App\Models\Book"
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *      ),
     *  )
     *
     * Array input only works with Json media type. Others shows it but doesn't work properly
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $role)
    {
        $request->validate([
            'permissions' => 'required|array|max:50',
            'permissions.*' => 'required|string|max:50',
        ]);

        /** @var $user User **/
        $user = auth()->user();

        if( $user->hasRole($role) )
        {
            //create permission in database
            foreach ($request->input('permissions') as $permission)
                Permission::findOrCreate($permission);

            // Please, note that sync here will delete other permissions if they are not in the
            // request. and it already exists.
            // If you don't want to do that, you need to add them if they don't exist.
            Role::findByName($role )->syncPermissions($request->input('permissions'));
        }
        return $this->show($role);
    }

 /**
     * @OA\Delete(
     *      path="/permissions/{permission}",
     *      operationId="deletePermission",
     *      tags={"Permissions"},
     *      summary="Delete permission from user role",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="permission",
     *          in="path",
     *          required=true,
     *          description="name of the permission you are about to delete",
     *          @OA\Schema(type="string"),
     *          example="create book",
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="role", type="string", description="The name of the role like users, admins, editors", example="users", nullable=false, default="users"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Permissions have been deleted",
     *          @OA\JsonContent(
     *                  ref="App\Models\Book"
     *          )
     *      ),
     *  )
     *
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $permission)
    {
        $request->validate([
            'role' => 'required|string|max:50',
        ]);

        /** @var $user User **/
        $user = auth()->user();
        $role = Role::findByName($request->input('role'));
        if( $user->hasRole($role) )
        {
            $role->revokePermissionTo($permission);
        }
        return \response()->noContent();
    }
}
