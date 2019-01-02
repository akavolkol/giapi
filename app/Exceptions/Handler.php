<?php

namespace App\Exceptions;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Presenters\Error;

class Handler extends ExceptionHandler
{
    /**
     * {@inheritDoc}
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        ExpiredException::class
    ];

    /**
     * @var array
     */
    protected $statusMatching = [
        ExpiredException::class => 403,
        SignatureInvalidException::class => 422,
    ];

    /**
     * {@inheritDoc}
     */
    public function render($request, Exception $exception)
    {
        $error = parent::render($request, $exception);
        if ($request->isJson()) {
            $code = $error->getStatusCode();
            if ($code === 500) {
                $code = $this->resolveHttpStatus($exception);
            }
            return $exception->response ?? new JsonResponse(
                (new Error($exception->getMessage()))->present(),
                $code
            );
        }
        return $error;
    }

    /**
     * @param \Throwable $exception
     * @return int
     */
    protected function resolveHttpStatus(\Throwable $exception): int
    {
        return $this->statusMatching[get_class($exception)] ?? 500;
    }
}
