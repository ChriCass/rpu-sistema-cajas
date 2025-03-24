<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserRegistrationController extends Controller
{
    /**
     * Constructor para aplicar middleware de autenticación y rol
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin'])->except(['forceCreateAdminRole']);
    }

    /**
     * Mostrar el formulario de registro de usuarios
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Procesar el registro de un nuevo usuario
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $role = $request->role;
        
        // Verificar si el rol existe, si no, crearlo
        if (!Role::where('name', $role)->exists()) {
            Role::create(['name' => $role]);
        }
        
        $user->assignRole($role);
        
        return redirect('/dashboard')
            ->with('success', 'Usuario creado correctamente');
    }

    /**
     * Mostrar los roles disponibles para pruebas
     */
    public function checkRoles()
    {
        $roles = Role::all();
        if ($roles->count() > 0) {
            return 'Roles encontrados: ' . $roles->pluck('name')->implode(', ');
        } else {
            // Crear roles básicos si no existen
            Role::create(['name' => 'admin']);
            Role::create(['name' => 'user']);
            return 'No se encontraron roles. Se han creado roles básicos: admin, user';
        }
    }

    /**
     * Probar la creación de un usuario (función para pruebas)
     */
    public function testUserCreation()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test'.time().'@example.com',
            'password' => Hash::make('password'),
        ]);
        
        if ($user) {
            try {
                $user->assignRole('admin');
                return 'Usuario creado correctamente con rol admin: ' . $user->id;
            } catch (\Exception $e) {
                return 'Usuario creado, pero error al asignar rol: ' . $e->getMessage();
            }
        } else {
            return 'Error al crear usuario';
        }
    }

    /**
     * Crear el rol de administrador de manera forzada
     */
    public function forceCreateAdminRole()
    {
        try {
            // Verificar si ya existe el rol admin
            if (!Role::where('name', 'admin')->exists()) {
                Role::create(['name' => 'admin']);
                // Hacer admin al usuario actual si está autenticado
                if (auth()->check()) {
                    $user = User::find(auth()->id());
                    $user->assignRole('admin');
                }
                return 'Rol admin creado correctamente y asignado al usuario actual';
            } else {
                // Si existe, asignar al usuario actual
                if (auth()->check()) {
                    $user = User::find(auth()->id());
                    if (!$user->hasRole('admin')) {
                        $user->assignRole('admin');
                        return 'Rol admin asignado al usuario actual';
                    }
                }
                return 'El rol admin ya existe. Usuario actual ya tiene el rol.';
            }
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
