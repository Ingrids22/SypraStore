<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected function redirectTo(){
        $user = Auth::user(); // Obtener el usuario autenticado
        if ($user && $user->hasRole('cliente')) { // Verificar si el usuario tiene el rol 'cliente'
            return "/carrito/procesopedido";
        }
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'celular' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'photo' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.Customer::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'discount' => 0,
            'photo' => $request->photo,
            'type' => 'standar',
            'status' => 'ACTIVO',
        ]);
    
        event(new Registered($customer));
        Auth::guard('customer')->login($customer);

        Auth::login($customer);

        return redirect(RouteServiceProvider::HOME);
    }

}
