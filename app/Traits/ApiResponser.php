<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser{

    protected function successResponse($data, $message = null, $code = Response::HTTP_OK)
	{
		return response()->json([
			'status'=> $code,
			'message' => $message,
			'data' => $data
		], $code);
	}
    protected function successCreation($data, $message = null, $code = Response::HTTP_CREATED)
	{
		return response()->json([
			'status'=> $code,
			'message' => $message,
			'data' => $data
		], $code);
	}

	public function errorResponse($message = null, $code)
	{
		return response()->json([
			'status'=> $code,
			'message' => $message,
			'data' => []
		], $code);
	}

}
