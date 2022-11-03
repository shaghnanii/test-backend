<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    public function index()
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

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
