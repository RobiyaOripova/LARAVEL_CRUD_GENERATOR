<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Repositories\DTO\SendOtpDTO;
use App\Http\Repositories\UserRepository;
use App\Http\Resources\UserResource;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class UserController extends Controller
{
    public function signIn(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $phone = $request->get('phone');

        $user = User::query()->where('phone', $phone)->first();
        if (! in_array($user->role, User::ROLE_ADMINS)) {
            throw new AccessDeniedException('You have not permission', 403);
        }
        if (Auth::attempt(['phone' => $phone, 'password' => $request->get('password')])) {
            $data['token'] = $user->createToken('Admin', [
                $user->role,
            ])->accessToken;
            $data['user'] = $user;

            return successResponse('success', $data);
        }

        return errorResponse('Phone or password incorrect', [], null, 401);
    }

    public int $successStatus = 200;

    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @header Authorization Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZmQ4MTk0MTk5NTIzZGJmNmQxZGE0MTAzNjlmN2VlNGU1MjRmMWRhNDcwMmIwYWQwMDIxOTAwMzU0NjEzZjg4ODhhZGRlZDEwNzJmYWY3NDAiLCJpYXQiOjE2NjcyOTMxNjAuNzY0OTUxLCJuYmYiOjE2NjcyOTMxNjAuNzY0OTUyLCJleHAiOjE2Njk4ODUxNjAuNzYzMTQxLCJzdWIiOiIxIiwic2NvcGVzIjpbImFkbWluIl19.RRLTeY9TC-0l-ymu_64Cvm2WFQJ1j8guYBntjG5LLy-xhstr8ddBsyqQxSRMMONOcf00qwZemcdaXlYW4tLPxKo3lQWTd-z4_NzbgsU9xIUiNxF44Edoq5lBx9dg558oztOLI2dFAVbZuUk3K6KRMQYJ3ZT7pCzsIWRfhXR9YTYH7pQ-fhe2hfUPN_sHX4VlO8Cwow_rYWzCGYtYUHBozh3o5-bfs9xJOQbhoy2b1djXsg7j4do8dHvt9oeaL71x2mXf3YktnhN80RbBbGNLLlz6Ox-XpnfMG1RnemA0NcUmXOAoMUExzMvNyAjBjaYP0FhIhP3MHNRuYHnzMaSMbIjYgVW-CrpagrHca2Z-uiEnP_c_HBhXxyqhV6oJDgmCx5hJpLIXrwho5gVcx-fujEkM23M65Ty6ugZzkTJEEA3z93ytanKzhC_wK5XvvzgpwKRMpmGWtpNUV7gNdKNzWXY4YVOctou-rDShQiZvN1pqXI7hH6UjAjUuckVI60ncS_Hq_9r2IXWwtAeH82mmRgOcC-JUEw-ko772RGhvnOPEXy1bneC16hoKuekZ_EfMVqYFkCVOB-I2rsfOclbfBgQZgkmM4F59ODI70r2PCvjO96hxlB7o2Q4WKamwjOrI0KJvPb29ZOp9mO8yCOR8SaD2kS0auCljloL-ofwGfBs
     *
     * @queryParam search nullable search
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = QueryBuilder::for($this->userRepository->modelClass);
        $roles = $request->get('roles');
        if (! empty($roles)) {
            $query->select('users.*');
            $query->leftJoin('roles', 'roles.user_id', '=', 'users.id');
            $query->whereIn('roles.role', explode(',', $roles));
        }

        return UserResource::collection($query->paginate($request->get('per_page')));
    }

    public function show(Request $request, User $user)
    {
        $query = QueryBuilder::for($user);
        $this->userRepository->sortFilterInclude($request, $query);

        return new UserResource($query->first());
    }

    /**
     * User create
     *
     * @return Builder|Model
     */
    public function create(Request $request)
    {
        // $roles = implode(',', User::ROLE_ADMINS);
        $request->validate([
            'username' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'nullable|unique:users',
            'email' => 'nullable|email|unique:users',
            'country_id' => 'required',
            'role' => 'required',
            'password' => 'required',
            'status' => 'required|in:1,0',
        ]);

        $user = User::query()->create([
            'username' => $request->get('username'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'photo_id' => $request->get('photo_id'),
            'country_id' => $request->get('country_id'),
            'status' => $request->get('status'),
            'password' => bcrypt($request->get('password')),

        ]);
        $user->role()->create([
            'user_id' => $user->id,
            'role' => $request->get('role'),
        ]);

        return $user;
    }

    /**
     * User update
     */
    public function update(Request $request, $id): JsonResponse
    {
        // $roles = implode(',', User::ROLE_ADMINS);
        $request->validate([
            'role' => 'required',
            'status' => 'required|in:0,1,2,3,4',
        ]);

        $user = User::query()->findOrFail($id);
        $data = $request->only('username', 'first_name', 'last_name', 'phone', 'email', 'country_id', 'photo_id', 'status', 'favourite');
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->get('password'));
        }
        $user->update($data);
        if ($request->has('role')) {
            DB::table('roles')->where(['user_id' => $id])->update([
                'role' => $request->get('role'),
            ]);
        }
        if (! empty($request->get('append'))) {
            $user->append(explode(',', $request->get('append')));
        }
        if (! empty($request->get('include'))) {
            $user->load(explode(',', $request->get('include')));
        }

        return response()->json($user, $this->successStatus);
    }

    public function destroy(int $id): array
    {

        $model = User::class::findOrFail($id);
        DB::table('roles')->where(['user_id' => $id])->delete();
        $model->delete();

        return [
            'status' => 'success',
            'data' => $model,
        ];
    }

    //    /**
    //     * User sign-in
    //     *
    //     * @bodyParam username string required username Example: admin
    //     * @bodyParam password string required password Example: qwerty
    //     * @return JsonResponse
    //     * @throws GuzzleException
    //     * @var Request $request
    //     */
    //    public function signIn(Request $request)
    //    {
    //        $request->validate([
    //            "username" => "required|string",
    //            "password" => "required|string",
    //        ]);
    //        if (Auth::attempt(['username' => $request->get("username"), 'password' => $request->get("password")])) {
    //            $sendOtpDTO = new SendOtpDTO();
    //            $sendOtpDTO->to = $this->userRepository->validatePhone(Auth::user()->phone);
    //            return $this->userRepository->sendOTP($sendOtpDTO);
    //        }
    //        return errorResponse("Username or password incorrect", [], null, 401);
    //    }

    public function confirmAdmin(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'code' => 'required|integer',
        ]);

        return $this->userRepository->confirmAdminSignIn($request);

    }

    /**
     * User get-me
     *
     * @return UserResource
     *
     * @var Request
     */
    public function details(Request $request)
    {
        $query = QueryBuilder::for($this->userRepository->modelClass::findOrFail(Auth::id()));
        $this->userRepository->sortFilterInclude($request, $query);

        return new UserResource($query->first());
    }

    /**
     * User get-me for clients
     *
     * @return UserResource
     *
     * @var Request
     */
    public function userDetails(Request $request)
    {
        $query = QueryBuilder::for($this->userRepository->modelClass::findOrFail(Auth::id()));
        $this->userRepository->sortFilterInclude($request, $query);

        return new UserResource($query->first());
    }

    public function logout(Request $request): JsonResponse
    {
        DB::table('oauth_access_tokens')->where('user_id', Auth::id())->update(['revoked' => true]);

        return successResponse('Successfully logged out');
    }

    public function userLogout(Request $request): JsonResponse
    {
        $request->validate(['device_token' => 'required']);
        $deviceToken = $request->get('device_token');
        $this->userRepository->revokeToken($deviceToken);

        return successResponse('Successfully logged out');
    }

    /**
     * Update current user
     *
     * @header Authorization Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZmQ4MTk0MTk5NTIzZGJmNmQxZGE0MTAzNjlmN2VlNGU1MjRmMWRhNDcwMmIwYWQwMDIxOTAwMzU0NjEzZjg4ODhhZGRlZDEwNzJmYWY3NDAiLCJpYXQiOjE2NjcyOTMxNjAuNzY0OTUxLCJuYmYiOjE2NjcyOTMxNjAuNzY0OTUyLCJleHAiOjE2Njk4ODUxNjAuNzYzMTQxLCJzdWIiOiIxIiwic2NvcGVzIjpbImFkbWluIl19.RRLTeY9TC-0l-ymu_64Cvm2WFQJ1j8guYBntjG5LLy-xhstr8ddBsyqQxSRMMONOcf00qwZemcdaXlYW4tLPxKo3lQWTd-z4_NzbgsU9xIUiNxF44Edoq5lBx9dg558oztOLI2dFAVbZuUk3K6KRMQYJ3ZT7pCzsIWRfhXR9YTYH7pQ-fhe2hfUPN_sHX4VlO8Cwow_rYWzCGYtYUHBozh3o5-bfs9xJOQbhoy2b1djXsg7j4do8dHvt9oeaL71x2mXf3YktnhN80RbBbGNLLlz6Ox-XpnfMG1RnemA0NcUmXOAoMUExzMvNyAjBjaYP0FhIhP3MHNRuYHnzMaSMbIjYgVW-CrpagrHca2Z-uiEnP_c_HBhXxyqhV6oJDgmCx5hJpLIXrwho5gVcx-fujEkM23M65Ty6ugZzkTJEEA3z93ytanKzhC_wK5XvvzgpwKRMpmGWtpNUV7gNdKNzWXY4YVOctou-rDShQiZvN1pqXI7hH6UjAjUuckVI60ncS_Hq_9r2IXWwtAeH82mmRgOcC-JUEw-ko772RGhvnOPEXy1bneC16hoKuekZ_EfMVqYFkCVOB-I2rsfOclbfBgQZgkmM4F59ODI70r2PCvjO96hxlB7o2Q4WKamwjOrI0KJvPb29ZOp9mO8yCOR8SaD2kS0auCljloL-ofwGfBs
     *
     * @return JsonResponse
     */
    public function updateAdmin(Request $request)
    {
        return $this->userRepository->updateAdmin($request);
    }
}
