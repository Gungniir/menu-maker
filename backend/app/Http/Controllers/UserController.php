<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    public function __construct() {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(User::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email:rfc,dns|max:255|unique:users',
            'password' => 'required|string|min:6|max:255',
        ]);

        $user = User::forceCreate([
            'name' => $input['name'],
            'email' => $input['email']
        ]);

        $user->password = Hash::make($input['password']);

        $user->saveOrFail();

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $input = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email:rfc,dns|max:255|unique:users',
            'password' => 'required|nullable|string|min:6|max:255',
            'telegram_id' => 'required|nullable|numeric|max:9223372036854775807|min:-9223372036854775808'
        ]);

        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'telegram_id' => $input['telegram_id']
        ]);

        if (!is_null($input['password'])) {
            $user->password = Hash::make($input['password']);
        }

        $user->saveOrFail();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        $force = $request->boolean('force');
        $this->authorize('forceDelete', $user);

        if ($force) {
            $user->forceDelete();
        } else {
            $user->delete();
        }

        return response()->json('success');
    }
}
