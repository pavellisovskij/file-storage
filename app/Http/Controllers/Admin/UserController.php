<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Constants\StorageSizesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Admin/User/Index', [
            'users' => User::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user): Response
    {
        return Inertia::render('Admin/User/Edit', [
            'user' => $user,
            'maxStorage' => StorageSizesEnum::MAX->value / 1024 / 1024
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return RedirectResponse|\Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user): \Illuminate\Http\Response|RedirectResponse
    {
        if ($this->userService->update($user, $request->validated()))
            return Inertia::location(route('admin.users.index'));
        else
            return back()->withInput($request->all());
    }
}
