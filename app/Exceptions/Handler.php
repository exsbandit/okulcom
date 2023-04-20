<?php

namespace App\Exceptions;

use App\Exceptions\v1\Order\OrderCreateException;
use App\Exceptions\v1\User\UserDetailException;
use App\Exceptions\v1\Order\OrderStatusException;
use App\Helpers\Response;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Request $request, Throwable $e) {
            return $this->render($request, $e);
        });

    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception|Throwable $e
     * @return ResponseAlias
     */
    public function render($request, Exception|Throwable $e)
    {
        switch (true) {
            case ($e instanceof ModelNotFoundException):
                $parameters = request()->route()->parameters();
                $key = array_key_last($parameters);

                //model.plan_service not found
                $message = 'message.not_found.model';
                $code = ResponseAlias::HTTP_NOT_FOUND;
                $errors = [];

                break;
            case ($e instanceof NotFoundHttpException):
                $message = 'message.not_found.url';
                $code = ResponseAlias::HTTP_NOT_FOUND;
                $errors = [];

                break;
            case ($e instanceof ValidationException):
                $message = 'message.failed.validation';
                $code = ResponseAlias::HTTP_FORBIDDEN;
                $errors = $e->validator->errors()->toArray();

                break;
            case ($e instanceof UserDetailException):
            case ($e instanceof OrderCreateException):
            case ($e instanceof OrderStatusException):
                $errors = [];
                $message = $e->getMessage();
                $code = 403;
                break;
            default:
                $message = 'ERROR';
                $code = ResponseAlias::HTTP_NOT_FOUND;
                $errors = [];

                if (env("APP_DEBUG")) {
                    dd($e, 'APP_DEBUG'); // Dev ortamında test edilmesi için kalacak.
                }
                break;
        }

        return Response::handlerFail($message, [], $code, $e);

    }
}
