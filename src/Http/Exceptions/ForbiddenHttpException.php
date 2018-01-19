<?php

namespace SF\Http\Exceptions;

class ForbiddenHttpException extends HttpException
{

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(403, $message, $code, $previous);
    }

}