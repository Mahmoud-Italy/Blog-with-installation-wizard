<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
//use App\User;
use Response;
use Helper;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /* Soon we'll improve that code */
    /* Symfony API Controller */
    function __construct(Request $request) {
        // try {
        //     if(!Auth::check() && $request->accessToken) {
        //         $usr = User::findOrfail(Helper::whoIs($request->accessToken));
        //         Auth::login($usr);
        //     }
        // } catch (\Exception $e) { }
    }

    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;
    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }
    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        $response = [
        	'statusCode' => self::getStatusCode(),
            'data' => $data,
        ];
        return response()->json($response);
    }
    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $errors
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data = [
        	'statusCode' => $this->getStatusCode(),
            'errors' => $errors,
        ];
        //return new Response($data, $this->getStatusCode(), $headers);
        return response()->json($data);
    }
    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }
    /**
     * Returns a 422 Unprocessable Entity
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondValidationError($message = 'Validation errors')
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }
    /**
     * Returns a 404 Not Found
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondNotFound($message = 'Not found!')
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }
    /**
     * Returns a 500 Something went wrong
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondSomethingWrong($message = 'Internal Server Error!')
    {
        return $this->setStatusCode(500)->respondWithErrors($message);
    }
    /**
     * Returns a 401 Access denied
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondAccessDenied($message = 'Access Denied')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }
    /**
     * Returns a 201 Created
     *
     * @param array $data
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondCreated($data = [])
    {
        return $this->setStatusCode(201)->respond($data);
    }

    /**
     * Returns a 200 Success
     *
     * @param array $data
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondSuccess($data = [])
    {
        return $this->setStatusCode(200)->respond($data);
    }


    public function validateToken($accessToken = '')
    {
     	if ( !$accessToken ) { 
     		return $this->respondUnauthorized('accessToken is required.');
     	} 
        else if (!Helper::whoIs($accessToken)) { 
        	return $this->respondUnauthorized('Invalid accessToken.');
        }
        else {
        	return Helper::whoIs($accessToken);
        }
    }

    function replaceArrayKey($array, $oldKey, $newKey){
        if(!isset($array[$oldKey])){
            return $array;
        }
        $arrayKeys = array_keys($array);
        $oldKeyIndex = array_search($oldKey, $arrayKeys);
        $arrayKeys[$oldKeyIndex] = $newKey;
        $newArray =  array_combine($arrayKeys, $array);
        return $newArray;
    }

    public function getPagination($data)
    {
        $nextPageUrl = $data->nextPageUrl();
        $prevPageUrl = $data->previousPageUrl();
        $lastPage = $data->lastPage();
        $currentPage = $data->currentPage();
        $perPage = $data->perPage();
        $total = $data->total();
        $pagination = ['total'=>$total, 'per_page'=>$perPage, 
                      'current_page'=>$currentPage, 'last_page'=>$lastPage, 
                      'next_page_url'=>$nextPageUrl, 'prev_page_url'=>$prevPageUrl];
        return $pagination;
    }
}
