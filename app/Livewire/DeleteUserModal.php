<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Documento;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeleteUserModal extends ModalComponent
{
    public $userId;
    public $canDelete = true;
    public $errorMessage = '';

    public static function modalMaxWidth(): string
    {
        return 'lg';
    }

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->checkCanDelete();
    }

    public function checkCanDelete()
    {
        // Can't delete self
        if (Auth::id() == $this->userId) {
            $this->canDelete = false;
            $this->errorMessage = 'No puedes eliminar tu propio usuario.';
            return;
        }

        // Can't delete the only admin
        $user = User::find($this->userId);
        if ($user && $user->hasRole('admin')) {
            $adminCount = User::role('admin')->count();
            if ($adminCount <= 1) {
                $this->canDelete = false;
                $this->errorMessage = 'No puedes eliminar el único usuario con rol de administrador.';
                return;
            }
        }

        // No se puede eliminar un usuario si tiene documentos asociados
        $documentCount = Documento::where('id_user', $this->userId)->count();
        if ($documentCount > 0) {
            $this->canDelete = false;
            $this->errorMessage = 'No se puede eliminar este usuario porque tiene documentos asociados. Debes reasignar o eliminar los documentos primero.';
            return;
        }
    }

    public function deleteUser()
    {
        if (!$this->canDelete) {
            return;
        }

        try {
            $user = User::findOrFail($this->userId);
            $user->delete();
            
            $this->dispatch('userDeleted');
            $this->dispatch('show-notification', message: 'Usuario eliminado correctamente');
            $this->closeModal();
            
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            session()->flash('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.delete-user-modal');
    }
}
