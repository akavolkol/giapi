<?php

namespace App\Mail;

use App\Models\Weather;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GitHubUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var Weather
     */
    protected $weather;

    /**
     * @var array
     */
    protected $user;

    /**
     * GitHubUser constructor.
     * @param array $user
     * @param string $text
     * @param Weather|null $weather
     */
    public function __construct(array $user, string $text = '', Weather $weather = null)
    {
        $this->text = $text;
        $this->weather = $weather;
        $this->user = $user;
        $this->to($user['email'], $user['name']);
        $this->subject = "Hi, {$user['name']}";
    }

    /**
     * @return GitHubUser
     */
    public function build()
    {
        return $this
            ->text('emails.message', [
                'text' => $this->text,
                'weather' => $this->weather,
                'user' => $this->user
            ]);
    }
}
