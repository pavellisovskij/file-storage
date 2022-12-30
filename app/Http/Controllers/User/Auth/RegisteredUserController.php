<?php

namespace App\Http\Controllers\User\Auth;

use App\Helpers\Constants\RolesEnum;
use App\Helpers\Constants\StatusEnum;
use App\Helpers\Constants\StorageSizesEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('User/Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => RolesEnum::USER->value,
            'status' => StatusEnum::ACTIVE->value,
            'storage_size' => StorageSizesEnum::STORAGE_SIZE->value
        ]);

        Storage::makeDirectory($user->id . '/thumbnail');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('storage.root');
    }
}
