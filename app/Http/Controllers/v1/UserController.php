<?php

namespace App\Http\Controllers\v1;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserIndexRequest;
use App\Http\Requests\v1\User\UserShowRequest;
use App\Http\Requests\v1\User\UserStoreRequest;
use App\Http\Requests\v1\User\UserUpdateRequest;
use App\Http\Requests\v1\UserDetail\UserDetailStoreRequest;
use App\Http\Requests\v1\UserDetail\UserDetailUpdateRequest;
use App\Http\Resources\v1\User\UserIndexResource;
use App\Models\Role;
use App\Models\User;
use App\Services\v1\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $users;

    /**
     * CategoryController constructor.
     * @param UserService $users
     */
    public function __construct(UserService $users)
    {
        $this->users = $users;
    }

    /**
     * @param UserIndexRequest $request
     * @return JsonResponse
     */
    public function index(UserIndexRequest $request): JsonResponse
    {
        return Response::run(UserIndexResource::collection($this->users->repository->get()));
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $user = $this->users->repository->create(
            [
                'name' => $request->name ?? '',
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]
        );

        $role = Role::where('type', 'basic')->first();
        $user->assignRole($role);
        return Response::run($user);
    }

    /**
     * @param UserUpdateRequest $request
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request): JsonResponse
    {
        return Response::run($this->users->repository->update(request()->user(), $request->validated()));
    }

    /**
     * return JsonResponse
     */
    public function show(UserShowRequest $request, User $user): JsonResponse
    {
        return Response::run(($this->users->repository->find($user->id, ['detail'])));
    }

    /**
     * return JsonResponse
     */
    public function storeDetail(UserDetailStoreRequest $request): JsonResponse
    {
        return Response::run(UserIndexResource::collection($this->users->repository->updateDetail(request()->user(), $request->validated())));
    }

    /**
     * return JsonResponse
     */
    public function updateDetail(UserDetailUpdateRequest $request): JsonResponse
    {
        return Response::run(UserIndexResource::collection($this->users->repository->updateDetail(request()->user(), $request->validated())));
    }
}
