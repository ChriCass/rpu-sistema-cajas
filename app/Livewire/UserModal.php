<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class UserModal extends ModalComponent
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $roles = [];
    public $availableRoles = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|confirmed|min:8',
        'roles' => 'array',
    ];

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount()
    {
        // Get all available roles
        $this->availableRoles = Role::all();
    }

    public function createUser()
    {
        $this->validate();

        try {
            $user = new User();
            $user->name = $this->name;
            $user->email = $this->email;
            $user->password = Hash::make($this->password);
            $user->save();
            
            // Assign roles if selected
            if (!empty($this->roles)) {
                $user->syncRoles($this->roles);
            }
            
            $this->resetForm();
            session()->flash('message', 'Usuario creado correctamente.');
            $this->dispatch('userCreated');
            $this->closeModal();
            
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            session()->flash('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->roles = [];
    }

    public function render()
    {
        return view('livewire.user-modal');
    }
}
