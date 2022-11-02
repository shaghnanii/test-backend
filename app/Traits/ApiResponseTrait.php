<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

trait ApiResponseTrait
{
    public function sendResponse($data, $action, $model = null, $message = ''): JsonResponse
    {
        if ($model)
            $model = class_basename($model);

        switch ($action) {
            // for index method
            case 'index':
            {
                try {

                    if ($data) {
                        return response()->json(['message' => $message, 'data' => $data]);
                    } else {
                        return response()->json(['message' => "No " . $model . " Found"], 404);
                    }
                } catch (Exception $exception) {
                    return response()->json(['message' => $exception->getMessage()], 500);
                }
            }
            // for store method
            case 'store':
            {
                try {
                    if ($data) {
                        DB::commit();
                        return response()->json(['message' => $model . ' created.', 'data' => $data]); //, 'data' => $data
                    } else {
                        DB::rollBack();
                        return response()->json(['message' => 'Failed to create ' . $model], 422);
                    }
                } catch (Exception $exception) {
                    DB::rollBack();
                    return response()->json(['message' => $exception->getMessage()], 500);
                }
            }
            // for show method
            case 'show':
            {
                try {
                    if ($data) {
                        return response()->json(['message' => $message, 'data' => $data]);
                    } else {
                        return response()->json(['message' => "No " . $model . " found with the provided ID"], 404);
                    }
                } catch (Exception $exception) {
                    return response()->json(['message' => $exception->getMessage()], 500);
                }
            }
            // for update method
            case 'update':
            {
                try {
                    if ($data) {
                        DB::commit();
                        return response()->json(['message' => $model . " updated", 'data'=>$data]); //, 'data' => $data
                    } else {
                        return response()->json(['message' => 'Failed to update ' . $model], 422);
                    }
                } catch (Exception $exception) {
                    return response()->json(['message' => $exception->getMessage()], 500);
                }
            }
            // for destroy method
            case 'destroy':
            {
                try {
                    if ($data) {
                        return response()->json(['message' => $model . ' deleted successfully']); //, 'data' => $data
                    } else {
                        return response()->json(['message' => "No " . $model . " data found"], 404);
                    }
                } catch (Exception $exception) {
                    return response()->json(['message' => $exception->getMessage()], 500);
                }
            }
            default:
                return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    // error response handler
    public function response500($message): JsonResponse
    {
        try {
            return response()->json(['message' => $message], 500);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    // error response handler
    public function response404($message): JsonResponse
    {
        try {
            return response()->json(['message' => $message], 404);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    // success response handler
    public function response200($message, $data = ''): JsonResponse
    {
        try {
            return response()->json(['message' => $message, 'data' => $data]);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function response401($message): JsonResponse
    {
        try {
            return response()->json(['message' => $message], 401);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function response409($message, $data = ''): JsonResponse
    {
        try {
            return response()->json(['message' => $message, 'data' => $data], 409);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
