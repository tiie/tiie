<?php
namespace Elusim\Errors;

interface ErrorHandlerInterface
{
    const PROCESS_EXIT = 'process-exit';
    const PROCESS_CONTINUATION = 'process-continuation';

    /**
     * Handle error.
     */
    public function handle($error);

    /**
     * Handle error and return response.
     */
    public function response($error, \Elusim\Http\Request $request) : \Elusim\Response\ResponseInterface;
}
