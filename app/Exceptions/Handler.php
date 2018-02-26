<?php

namespace App\Exceptions;

use Exception;
use App\Traits\RestTrait;
use App\Traits\RestExceptionHandlerTrait;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use RestTrait;
    use RestExceptionHandlerTrait;
	
	/**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
       /* 
		if($this->isApiCall($request) || $request->ajax() || $request->wantsJson()) {
            return $this->getJsonResponseForException($request, $exception);
        }
		//var_dump($exception instanceof ValidationException);exit;
		//dd($exception);exit;
		
		if ($exception instanceof \Illuminate\Validation\ValidationException)
		{
			return parent::render($request, $exception);
		} 
		else if (view()->exists('errors.error'))
		{
			$exception = $this->convertExceptionToResponse_manual($exception);
			
			return response()->view('errors.error', ['exception' => $exception, 'user_layout' => 'template_v1']);
			//return redirect()->route('page-not-found')->with('exception', $exception);
		}
		*/
		return parent::render($request, $exception);
    }
	
	protected function convertExceptionToResponse_manual(Exception $e)
    {
        $e = FlattenException::create($e);

        $handler = new SymfonyExceptionHandler(config('app.debug'));

        return $handler->getContent($e);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->to('');
    }
}
