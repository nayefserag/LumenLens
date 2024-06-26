<?php
namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class RoleController extends Controller
{
    public function createRole(Request $request)
    {
        $role = Role::create($request->all());
        return response()->json(['message' => 'Role created successfully!', 'data' => $role], 201);
    }

    public function allRoles()
    {
        $roles = Role::all();
        if ($roles->count() < 1) {
            return response()->json('No roles found!');
        }
        return response()->json(['roles' => $roles], 200);
    }

    public function deleteRole($role_id)
    {
        $role = Role::find($role_id);
        $role->delete();
        return response()->json('Role deleted successfully!', 200);
    }
    public function updateRole(Request $request, $role_id)
    {
        $role = Role::find($role_id);
        $role->update($request->all());
        return response()->json(['message' => 'Role updated successfully!', 'data' => $role], 201);
    }

    public function changeRoleForUser(Request $request , $user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $role = Role::where('role', $request->input('role'))->first();
        if (!$role) {
            return response()->json(['error' => 'This Role not found'], 404);
        }
        $user->role_id = $role->id;
        $user->save();
        return response()->json(['message' => "`{$user->name}` is now a `{$role->role}`"], 200);
    }

}
