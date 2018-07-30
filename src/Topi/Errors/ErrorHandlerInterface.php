<?php
namespace Topi\Errors;

interface ErrorHandlerInterface
{
    /**
     * Handle error.
     */
    public function handle($error);

    /**
     * Handle error and return response.
     */
    public function response($error, \Topi\Http\Request $request) : \Topi\Response\ResponseInterface;
}
