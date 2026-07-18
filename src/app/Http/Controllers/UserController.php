<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.usuarios.index', compact('roles'));
    }

    public function data()
    {
        $usuarios = User::with('userRole')->orderBy('name')->get();
        return response()->json($usuarios->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'rut' => $u->rut ?? '-',
            'telefono' => $u->telefono ?? '-',
            'role' => $u->userRole?->name ?? $u->role,
            'role_slug' => $u->userRole?->slug ?? $u->role,
        ]));
    }

    public function getJson(User $user)
    {
        $user->load('userRole');
        return response()->json($user);
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $role = Role::find($data['role_id']);
        $data['role'] = $role?->slug ?? 'recepcionista';
        $user = User::create($data);
        $this->auditoriaService->registrar('crear', 'usuarios', $user->id, null, $user->toArray());
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Usuario creado exitosamente.']);
        }
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.usuarios.edit', compact('user', 'roles'));
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $antiguo = $user->toArray();
        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        $role = Role::find($data['role_id']);
        $data['role'] = $role?->slug ?? 'recepcionista';
        $user->update($data);
        $this->auditoriaService->registrar('modificar', 'usuarios', $user->id, $antiguo, $user->toArray());
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Usuario actualizado exitosamente.']);
        }
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'No puedes eliminarte a ti mismo.'], 403);
            }
            return redirect()->route('admin.usuarios.index')->with('error', 'No puedes eliminarte a ti mismo.');
        }
        $this->auditoriaService->registrar('eliminar', 'usuarios', $user->id, $user->toArray(), null);
        $user->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Usuario eliminado.']);
        }
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado.');
    }
}
