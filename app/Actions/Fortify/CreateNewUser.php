<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Log::info('Datos recibidos para crear usuario', [
            'campos' => array_keys($input),
            'role' => $input['role'] ?? 'No proporcionado'
        ]);
        
        try {
            $validator = Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => $this->passwordRules(),
                'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
                'role' => ['nullable', 'string'],
            ]);
            
            if ($validator->fails()) {
                Log::error('Fallos en la validación', ['errores' => $validator->errors()->toArray()]);
                throw new ValidationException($validator);
            }
            
            Log::info('Validación exitosa, creando usuario');
            
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            Log::info('Usuario creado correctamente', ['usuario_id' => $user->id]);
            
            // Asignar rol si se proporcionó uno y existe
            if (!empty($input['role'])) {
                try {
                    // Verificar si el rol existe
                    $roleExists = Role::where('name', $input['role'])->exists();
                    if (!$roleExists) {
                        Log::warning('El rol proporcionado no existe', ['rol' => $input['role']]);
                        // Crear el rol si no existe
                        Role::create(['name' => $input['role']]);
                        Log::info('Rol creado automáticamente', ['rol' => $input['role']]);
                    }
                    
                    $user->assignRole($input['role']);
                    Log::info('Rol asignado correctamente', ['rol' => $input['role']]);
                } catch (\Exception $e) {
                    Log::error('Error al asignar rol', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'role' => $input['role'] ?? 'No disponible'
                    ]);
                }
            } else {
                Log::warning('No se proporcionó rol para el usuario');
                // Asignar rol predeterminado 'user'
                try {
                    if (!Role::where('name', 'user')->exists()) {
                        Role::create(['name' => 'user']);
                    }
                    $user->assignRole('user');
                    Log::info('Rol predeterminado "user" asignado');
                } catch (\Exception $e) {
                    Log::error('Error al asignar rol predeterminado', ['error' => $e->getMessage()]);
                }
            }

            return $user;
        } catch (ValidationException $e) {
            Log::error('Error de validación', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error inesperado al crear usuario', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
