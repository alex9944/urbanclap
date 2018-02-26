<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
//use Symfony\Component\Debug\Exception\FlattenException;
//use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

trait RestExceptionHandlerTrait
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        switch(true) {
            case $this->isModelNotFoundException($e):
                $retval = $this->modelNotFound();
                break;
            default:
                $retval = $this->badRequest();
        }
		
		// If the app is in debug mode
        if (config('app.debug'))
        {
            // Add the exception class name, message and stack trace to response
            $retval['exception'] = get_class($e); // Reflection might be better here
            $retval['message'] = $e->getMessage();
            $retval['trace'] = $e->getTrace();
			//$retval['trace'] = $this->convertExceptionToResponse($e);
        }

        return response()->json($retval);
    }
	
	/*protected function convertExceptionToResponse(Exception $e)
    {
        $e = FlattenException::create($e);

        $handler = new SymfonyExceptionHandler(config('app.debug'));

        return $handler->getHtml($e);
    }*/

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message='Bad request', $statusCode=400)
    {
        return $this->jsonResponse(array('msg' => $message, 'status' => 0), 200);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modelNotFound($message='Record not found', $statusCode=404)
    {
        return $this->jsonResponse(array('msg' => $message, 'status' => 0), 200);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload=null, $statusCode=404)
    {
        return $payload = $payload ?: [];

        //return response()->json($payload);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

}