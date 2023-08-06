<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Traits\Crud;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\PersonalAccessTokenResult;
use Modules\Playmobile\Entities\PhoneConfirmation;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class UserRepository extends BaseRepository
{
    use Crud;

    public $modelClass = User::class;

    public function getUser($userId = null): User
    {
        if (! is_null(request()->user('api')) && is_null($userId)) {
            $user = request()->user('api');
        } else {
            $user = User::where('id', $userId)->first();
        }
        if (! ($user instanceof User)) {
            throw new \DomainException('User not found');
        }

        return $user;
    }

    public function revokeToken($accessTokenId): void
    {
        DB::table('oauth_access_tokens')->where('id', $accessTokenId)->update(['revoked' => true]);
    }

    public function generateToken(User $user): PersonalAccessTokenResult
    {
        return $user->createToken($user->username ?: $user->email ?: $user->phone ?: 'unknown', [$user->role]);
    }

    public function validatePhone($phone): array|string|null
    {
        $phone = preg_replace('/\D+/', null, $phone);
        if (strlen($phone) < 12 || preg_match('/[a-z-A-Z]/', $phone)) {
            throw new \DomainException('Incorrect phone number');
        }

        return $phone;
    }

    /**
     * @throws GuzzleException
     */
    public function confirmAdminSignIn(Request $request): JsonResponse
    {
        $phone = $request->get('phone');
        $code = $request->get('code');
        $confirmation = PhoneConfirmation::query()->where(['phone' => $phone, 'code' => $code, 'status' => PhoneConfirmation::STATUS_UNCONFIRMED])->first();

        if ($confirmation instanceof PhoneConfirmation) {
            $user = User::query()->where('phone', $phone)->first();
            if (! in_array($user->role, User::ROLE_ADMINS)) {
                throw new AccessDeniedException('You have not permission', 403);
            }
            $confirmation->update(['status' => PhoneConfirmation::STATUS_CONFIRMED]);
            $data['token'] = $user->createToken('Admin', [
                $user->role,
            ])->accessToken;
            $data['user'] = $user;

            return successResponse('success', $data);
        } else {
            return errorResponse('User login or confirmation code mismatch');
        }
    }

    public function updateAdmin(Request $request): JsonResponse
    {
        $user = Auth::user();
        $request->validate([
            'phone' => 'required',
            'username' => 'required',
        ]);
        $data = $request->only(['username', 'phone', 'password', 'photo_id']);
        $data['phone'] = $this->validatePhone($data['phone']);

        if (empty($data['photo_id'])) {
            unset($data['photo_id']);
        }

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
            DB::table('oauth_access_tokens')->where('user_id', $user->id)->update(['revoked' => true]);
            $data['token'] = $this->generateToken($user)->accessToken;
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if (! empty($request->get('append'))) {
            $user->append(explode(',', $request->get('append')));
        }
        if (! empty($request->get('include'))) {
            $user->load(explode(',', $request->get('include')));
        }

        $data['user'] = $user;

        return successResponse('success', $data);
    }

    /**
     * @throws GuzzleException
     */
}
