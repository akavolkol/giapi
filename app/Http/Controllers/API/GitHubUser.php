<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\SendMessageForGitHubUsers;
use App\Services\GitHub\Repository;
use App\Mail\GitHubUser as GitHubUserMail;
use App\Services\Weather\WeatherService;

class GitHubUser extends RestController
{
    /**
     * @OA\Get(
     *  path="/github/send-email",
     *  @OA\Header(
     *      header="Authorization",
     *      required=true,
     *      @OA\Schema(
     *             type="string"
     *         )
     *  ),
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
     *              example="Hi, there"
     *          ),
     *     ),
     *  ),
     *  @OA\Response(
     *      response="200",
     *      description="Send email",
     *      @OA\JsonContent()
     *  )
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
        foreach ($gitHubRepository->usersDetails($userNames) as $user) {
            $weather = $weatherService->current($user['location']);
            $this->container->mailer->queue(new GitHubUserMail($user, $message, $weather));
        }
        return $this->response([]);
    }
}
