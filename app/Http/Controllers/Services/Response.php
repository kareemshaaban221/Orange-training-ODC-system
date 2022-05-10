<?php

namespace App\Http\Controllers\Services;

trait Response {

    protected function badRequestResponse($msg) {
        return response()->json([
            'message' => $msg,
            'status' => 400
        ], 400);
    }

    protected function notFoundResponse($user) {
        return response()->json([
            'message' => 'This '. ucwords($user) .' Is Not Found!',
            'status' => 404,
        ], 404);
    }

    protected function goodResponse(array $data) {
        $data['status'] = 200;
        return response()->json($data, 200);
    }

    protected function notAuthorizedResponse() {
        return response()->json([
            'message' => 'Not Authorized!',
            'status' => 401
        ], 401);
    }

    protected function alreadyExistsResponse($data) {
        $data['status'] = 409;
        return response()->json($data, 409);
    }

    protected function forbiddenResponse($msg) {
        return response()->json([
            'message' => $msg,
            'status' => 403
        ], 403);
    }

    protected function createdResponse($data, $createdObj, $msg = NULL) {
        $data['message'] = $msg ? $msg : ucwords($createdObj) . ' Has Been Created Successfully!';
        $data['status'] = 201;

        return response()->json($data, 201);
    }

}
