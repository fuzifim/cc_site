<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Theme; 
use Route; 
use Redirect; 
use WebService;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
	protected $_domain; 
	protected $_channel; 
	protected $_theme; 
	protected $_parame; 
	
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    public function report(Exception $e)
    {
        return parent::report($e);
    }
    public function render($request, Exception $e)
    {
		if(config('app.debug') === false)
        {   
            return response()->view('404');
        }
        return parent::render($request, $e);
    }
}
