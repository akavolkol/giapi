<?php

namespace App\Http\Controllers\API;

use App\Auth\Auth;
use App\Http\Requests\{Auth as AuthRequest, User as UserRequest};
use App\Models\User as UserModel;
use App\Presenters\Error;
use App\Presenters\User as UserPresenter;
use Intervention\Image\ImageManagerStatic as Image;

class User extends RestController
{
    /**
     * @OA\Post(
     *  path="/users",
     *  description="Add new user (sign up)",
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
     *      description="User successfully added",
     *      @OA\JsonContent(
     *          @OA\Property(property="user", ref="#/components/schemas/UserPresenter"),
     *          @OA\Property(property="token", ref="#/components/schemas/JWT")
     *      )
     *   )
     * )
     * @param UserRequest $request
     * @param UserPresenter $userPresenter
     * @param Auth $auth
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
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
     *  description="Authorize user by credentials",
     *  @OA\RequestBody(
     *      @OA\JsonContent(
     *          @OA\Parameter(
     *              name="email",
     *              @OA\Schema(
     *                  type="string",
     *              ),
     *          ),
     *          @OA\Parameter(
     *              name="password",
     *              @OA\Schema(
     *                  type="string",
     *              )
     *          ),
     *     ),
     *  ),
     *  @OA\Response(
     *      response="200",
     *      description="User is authorized",
     *      @OA\JsonContent(
     *          @OA\Property(property="user", ref="#/components/schemas/UserPresenter"),
     *          @OA\Property(property="token", ref="#/components/schemas/JWT")
     *      )
     *  ),
     *  @OA\Response(
     *      response="403",
     *      description="Wrong credentials",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", example="Wrong credentials"),
     *      )
     *  ),
     *  @OA\Response(
     *      response="422",
     *      description="Validation failed",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", example="Validation failed"),
     *          @OA\Property(
     *              property="fields",
     *              type="object",
     *              @OA\Property(
     *                  property="email",
     *                  type="array",
     *                  @OA\Items(
     *                      type="string",
     *                      example="The email field is required."
     *                  )
     *              )
     *          )
     *      )
     *  ),
     * )
     *
     * @param AuthRequest $request
     * @param UserPresenter $userPresenter
     * @param Auth $auth
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function auth(AuthRequest $request, UserPresenter $userPresenter, Auth $auth)
    {
        $request->validate();
        $email = $request->get('email');
        $password = $request->get('password');
        $user = UserModel::where('email', $email)->first();
        if (!password_verify($password, $user->password)) {
            return $this->response((new Error('Wrong credentials'))->present(), 403);
        }
        $userPresenter->setModifier(UserPresenter::MOD_REGISTERED);
        return $this->response([
            'user' => $userPresenter->present($user),
            'token' => $auth->generateToken($user),
        ]);
    }
}
