<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        $roles = Role::withCount('users')->orderBy('name')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $data['permissions'] = $request->permissions ?? [];

        $role = Role::create($data);
        $this->auditoriaService->registrar('crear', 'roles', $role->id, null, $role->toArray());
        return redirect()->route('admin.roles.index')->with('success', 'Rol creado exitosamente.');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $antiguo = $role->toArray();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $data['permissions'] = $request->permissions ?? [];

        $role->update($data);
        $this->auditoriaService->registrar('modificar', 'roles', $role->id, $antiguo, $role->toArray());
        return redirect()->route('admin.roles.index')->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroy(Role $role)
    {
        if (!$role->editable) {
            return redirect()->route('admin.roles.index')->with('error', 'No puedes eliminar el rol de Administrador.');
        }
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', 'No puedes eliminar un rol que tiene usuarios asignados.');
        }
        $this->auditoriaService->registrar('eliminar', 'roles', $role->id, $role->toArray(), null);
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Rol eliminado.');
    }
}
