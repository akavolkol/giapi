<?php

namespace App\Presenters;

/**
 * @OA\Schema(
 *  schema="ErrorPresenter",
 *  @OA\Property(
 *      property="message",
 *      type="string",
 *      example="Something happens"
 *  ),
 * )
 */
class Error extends Presenter
{
    /**
     * @var string
     */
    protected $message;

    /**
     * Error constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * {@inheritDoc}
     */
    public function present($data = []): array
    {
        return array_merge(
            [
                'message' => $this->message,
            ],
            $data
        );
    }
}
