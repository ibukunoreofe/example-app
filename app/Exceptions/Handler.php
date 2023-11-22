<?php

namespace App\Exceptions;

use App\Http\Responses\ExpectionFailedResponse;
use App\Http\Responses\PreConditionFailedResponse;
use App\Http\Responses\ServerErrorResponse;
use App\Http\Responses\UrlNotFoundResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @return ExpectionFailedResponse|PreConditionFailedResponse|ServerErrorResponse|UrlNotFoundResponse|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
//        if ($e instanceof NotFoundHttpException || $e instanceof MethodNotAllowedHttpException) {
//            return new UrlNotFoundResponse($e);
//        }

        if($request->wantsJson())
        {
            if ($e instanceof ValidationException) {
                return new PreConditionFailedResponse($e->errors());
            }

            if($e instanceof ThrottleRequestsException) return parent::render($request, $e);

            return new ExpectionFailedResponse(['error' => $e->getMessage()]);
        }else return parent::render($request, $e);
    }
}
