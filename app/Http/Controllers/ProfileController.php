<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * Este método muestra el formulario de edición del perfil del usuario.
     * Además, pasa la configuración de la empresa asociada al usuario, como el logo.
     */
    public function edit(Request $request): View
    {
        // Obtenemos el usuario actual.
        $user = $request->user();

        // Obtenemos la empresa asociada al usuario.
        $empresa = $user->empresa;  // Asumimos que la relación 'empresa' existe en el modelo User.

        // Retornamos la vista de edición del perfil y pasamos tanto al usuario como a la empresa.
        return view('profile.edit', [
            'user' => $user,
            'empresa' => $empresa,  // Pasamos la empresa a la vista para acceder a su configuración.
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * Este método actualiza la información del perfil del usuario, incluyendo el correo electrónico.
     * Si el correo electrónico se actualiza, también se borra la fecha de verificación.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Llenamos los atributos del usuario con los datos validados del formulario.
        $request->user()->fill($request->validated());

        // Si el correo electrónico fue modificado, borramos la fecha de verificación.
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Guardamos los cambios en el perfil del usuario.
        $request->user()->save();

        // Redirigimos a la vista de edición del perfil con un mensaje de éxito.
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * Este método permite la eliminación de la cuenta del usuario después de confirmar la contraseña.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validamos que el usuario ingrese su contraseña para confirmar la eliminación.
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Obtenemos el usuario.
        $user = $request->user();

        // Deslogueamos al usuario.
        Auth::logout();

        // Eliminamos la cuenta del usuario.
        $user->delete();

        // Invalidadmos la sesión y regeneramos el token de CSRF.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirigimos al usuario a la página principal después de eliminar la cuenta.
        return Redirect::to('/');
    }
}
