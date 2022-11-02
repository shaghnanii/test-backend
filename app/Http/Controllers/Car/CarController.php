<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\CarIndexRequest;
use App\Http\Requests\Car\CarStoreRequest;
use App\Http\Requests\Car\CarUpdateRequest;
use App\Models\Car;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    use ApiResponseTrait;

    public function index(CarIndexRequest $request)
    {
        try {
            $cars = Car::query()->with('category')->get();
            return $this->sendResponse($cars, 'index', Car::class);
        }
        catch (\Exception $exception)
        {
            return $this->response500($exception->getMessage());
        }
    }

    public function store(CarStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $car = Car::query()->create($request->only((new Car)->getFillable()));

            return $this->sendResponse($car, 'store', Car::class);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            return $this->response500($exception->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $car = Car::query()->with('category')->find($id);
            return $this->sendResponse($car, 'show',Car::class);
        }
        catch (\Exception $exception)
        {
            return $this->response500($exception->getMessage());
        }
    }

    public function update(CarUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $car = Car::query()->find($id);
            if (!$car)
                return $this->response404("No car found with the provided id.");
            $car->update($request->only((new Car)->getFillable()));

            DB::commit();
            return $this->sendResponse($car, 'update',Car::class);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            return $this->response500($exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $car = Car::query()->find($id);
            if (!$car)
                return $this->response404("No car found with the provided id.");

            $car->delete();

            DB::commit();
            return $this->sendResponse($car, 'destroy',Car::class);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            return $this->response500($exception->getMessage());
        }
    }
}
