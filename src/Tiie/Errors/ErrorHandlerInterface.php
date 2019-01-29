<?php
namespace Tiie\Errors;

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
    public function response($error, \Tiie\Http\Request $request) : \Tiie\Response\ResponseInterface;
}
