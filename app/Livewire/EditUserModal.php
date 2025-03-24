<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class EditUserModal extends ModalComponent
{
    public $userId;
    public $name;
    public $email;
    public $selectedRole;
    public $availableRoles = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'selectedRole' => 'required',
    ];

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($userId)
    {
        $this->userId = $userId;
        $user = User::findOrFail($userId);
        $this->name = $user->name;
        $this->email = $user->email;
        
        // Get all available roles
        $this->availableRoles = Role::all();
        
        // Get current user role
        $userRole = $user->roles->first();
        $this->selectedRole = $userRole ? $userRole->id : null;
    }

    public function updateUser()
    {
        // Add dynamic validation rule for email
        $this->rules['email'] = [
            'required',
            'email',
            'max:255',
            Rule::unique('users')->ignore($this->userId),
        ];
        
        $this->validate();

        try {
            $user = User::findOrFail($this->userId);
            $user->name = $this->name;
            $user->email = $this->email;
            $user->save();
            
            // Sync user role (if selection has changed)
            if ($this->selectedRole) {
                $user->syncRoles([$this->selectedRole]);
            }
            
            $this->dispatch('userUpdated');
            $this->dispatch('show-notification', message: 'Usuario actualizado correctamente');
            $this->closeModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-user-modal');
    }
}
