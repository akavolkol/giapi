<?php

namespace App\Http\Controllers\API;

use App\Auth\Auth;
use App\Http\Requests\{Auth as AuthRequest, User as UserRequest};
use App\Models\User as UserModel;
use App\Presenters\User as UserPresenter;
use Intervention\Image\ImageManagerStatic as Image;

class User extends RestController
{
    /**
     * @OA\Post(
     *  path="/users",
     *  @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="avatar",
     *                  description="Photo of an user",
     *                  type="string",
     *                  format="binary",
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  format="email",
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  type="string",
     *                  format="password",
     *              ),
     *          )
     *     )
     *  ),
     *  @OA\Response(
     *      response="200",
     *      description="Add new user",
     *      @OA\JsonContent(
     *          @OA\Property(property="user", ref="#/components/schemas/UserPresenter"),
     *          @OA\Property(property="token", type="string", example="dfsd")
     *      )
     *   )
     * )
     * @param User $request
     * @param UserPresenter $userPresenter
     * @param Auth $auth
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(UserRequest $request, UserPresenter $userPresenter, Auth $auth)
    {
        $request->validate();
        $email = $request->get('email');
        $password = $request->get('password');
        $user = new UserModel(compact('email', 'password'));
        $avatar = $request->file('avatar');
        if ($avatar) {
            $name = $avatar->hashName();
            $thumbnailName = sprintf("thumb_%s", $avatar->hashName());
            $disk = $this->container->filesystem->disk('public');
            $disk->put($name, $avatar->get());
            $disk->put(
                $thumbnailName,
                Image::make($avatar->get())
                    ->resize(50, 50, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->stream()
            );
            $user->avatar = $disk->url($name);
            $user->avatarThumbnail = $disk->url($thumbnailName);
        }
        $user->save();
        $userPresenter->setModifier(UserPresenter::MOD_REGISTERED);
        return $this->response(
            [
                'user' => $userPresenter->present($user),
                'token' => $auth->generateToken($user),
            ],
            201
        );
    }

    /**
     * @OA\Post(
     *  path="/auth",
     *  @OA\RequestBody(
     *     @OA\JsonContent(
     *  @OA\Parameter(
     *      name="email",
     *      @OA\Schema(
     *          type="string",
     *      ),
     *  ),
     *  @OA\Parameter(
     *      name="password",
     *
     *      @OA\Schema(
     *          type="string",
     *      )
     *  ),
     *     ),
     *     ),
     *  @OA\Response(
     *      response="200",
     *      description="Authorize user by credentials",
     *      @OA\JsonContent(
     *          @OA\Property(property="user", ref="#/components/schemas/UserPresenter"),
     *          @OA\Property(property="token", ref="#/components/schemas/JWT", type="string", example="dfsd")
     *      )
     *  ),
     *  @OA\Response(
     *      response="403",
     *      description="Wrong credentials",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", example="Wrong credentials"),
     *      )
     *  )
     * )
     *
     * @param AuthRequest $request
     * @param UserPresenter $userPresenter
     * @param Auth $auth
     * @return \Illuminate\Http\JsonResponse
     */
    public function auth(AuthRequest $request, UserPresenter $userPresenter, Auth $auth)
    {
        $request->validate();
        $email = $request->get('email');
        $password = $request->get('password');
        $user = UserModel::where('email', $email)->first();
        if (!password_verify($password, $user->password)) {
            return $this->response(['message' => 'Wrong credentials'], 403);
        }
        $userPresenter->setModifier(UserPresenter::MOD_REGISTERED);
        return $this->response([
            'user' => $userPresenter->present($user),
            'token' => $auth->generateToken($user),
        ]);
    }
}
