<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser{

    protected function successResponse($data, $message = null, $code = Response::HTTP_OK)
	{
		return response()->json([
			'success' => true,
			'message' => $message,
			'data' => $data
		], $code);
	}
    protected function successCreation($data, $message = null, $code = Response::HTTP_CREATED)
	{
		return response()->json([
			'success' => true,
			'message' => $message,
			'data' => $data
		], $code);
	}

	public function errorResponse($data = [], $message = null, $code = Response::HTTP_INTERNAL_SERVER_ERROR)
	{
		return response()->json([
			'success' => false,
			'message' => $message,
			'data' => $data
		], $code);
	}

}
