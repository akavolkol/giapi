<?php

namespace App\Presenters;

/**
 * @OA\Schema(
 *  schema="UserPresenter",
 *  @OA\Property(
 *      property="avatar",
 *      type="string",
 *      example="http://sample.com/img.jpg"
 *  ),
 *  @OA\Property(
 *      property="avatarThumbnail",
 *      type="string",
 *      example="http://sample.com/img.jpg"
 *  ),
 * )
 */
class User extends Presenter
{
    const MOD_REGISTERED = 0;

    public function present($attribute)
    {
        if ($this->getModifier() === self::MOD_REGISTERED) {
            $data = [
                'avatar' => $attribute->avatar,
                'avatarThumbnail' => $attribute->avatarThumbnail,
            ];
        } else {
            $data = parent::present($attribute->toArray());
        }
        return $data;
    }
}
