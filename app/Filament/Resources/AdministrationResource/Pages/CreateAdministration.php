<?php

namespace App\Filament\Resources\AdministrationResource\Pages;

use App\Filament\Resources\AdministrationResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateAdministration extends CreateRecord
{
    protected static string $resource = AdministrationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        // Extraemos los campos del usuario
        $userName = $this->form->getLivewire()->data['user_name'] ?? null;
        $userEmail = $this->form->getLivewire()->data['user_email'] ?? null;
        $userPassword = $this->form->getLivewire()->data['user_password'] ?? null;

        // Creamos el usuario relacionado
        $user = User::create([
            'name' => $userName,
            'email' => $userEmail,
            'password' => Hash::make($userPassword),
            'role' => 'user',
            'is_active' => true // ← corregido aquí
        ]);

        // Asignamos user_id al administration
        $data['user_id'] = $user->id;

        // Quitamos los campos que no deben ir a la tabla administrations
        unset($data['user_name'], $data['user_email'], $data['user_password']);

        return $data;
    }
}
