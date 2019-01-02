<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\SendMessageForGitHubUsers;
use App\Presenters\Error;
use App\Services\GitHub\Repository;
use App\Mail\GitHubUser as GitHubUserMail;
use App\Services\Weather\WeatherService;

class GitHubUser extends RestController
{
    /**
     * @OA\Get(
     *  path="/github/send-email",
     *  security={
     *      {"bearerAuth": {}}
     *  },
     *  @OA\RequestBody(
     *      @OA\JsonContent(
     *          @OA\Property(
     *              property="users",
     *              description="List of user names",
     *              type="array",
     *              @OA\Items(
     *                  type="string",
     *                  example="username"
     *              )
     *          ),
     *          @OA\Property(
     *              property="message",
     *              description="A text message",
     *              type="string",
     *              example="Hi there"
     *          ),
     *     ),
     *  ),
     *  @OA\Response(
     *      response="202",
     *      description="Some additional details may be specified",
     *      @OA\JsonContent(
     *          oneOf={
     *              @OA\Schema(),
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="usernames",
     *                      description="List of user names",
     *                      type="array",
     *                      @OA\Items(
     *                          type="string",
     *                          example="username"
     *                      )
     *                  ),
     *                  @OA\Property(
     *                      property="message",
     *                      ref="#/components/schemas/ErrorPresenter",
     *                      example="Some of the users didn't specify a public email address"
     *                  )
     *              )
     *          },
     *      ),
     *     )
     *  ),
     * )
     *
     * @param Repository $gitHubRepository
     * @param SendMessageForGitHubUsers $request
     * @param WeatherService $weatherService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendEmail(
        Repository $gitHubRepository,
        SendMessageForGitHubUsers $request,
        WeatherService $weatherService
    ) {
        $request->validate();
        $userNames = $request->get('users');
        $message = $request->get('message');
        $usersWithoutEmail = [];
        foreach ($gitHubRepository->usersDetails($userNames) as $user) {
            if ($user['email']) {
                $weather = $weatherService->current($user['location']);
                $this->container->mailer->queue(new GitHubUserMail($user, $message, $weather));
            } else {
                array_push($usersWithoutEmail, $user['login']);
            }
        }
        return $this->response(
            empty($usersWithoutEmail)
                ? []
                : $this
                    ->getErrorPresenter('Some of the users didn\'t specify a public email address')
                    ->present(['usernames' => $usersWithoutEmail]),
            202
        );
    }

    protected function getErrorPresenter(string $message)
    {
        return new Error($message);
    }
}
