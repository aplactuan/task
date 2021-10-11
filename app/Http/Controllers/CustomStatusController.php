<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomStatusResource;
use App\Models\CustomStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomStatusController extends Controller
{
    public function index(Request $request)
    {
        return CustomStatusResource::collection($request->user()->custom_statuses);
    }

    public function store(Request $request)
    {
       //to do: add a validation if user have the same status name already
       if ($request->user()->custom_statuses()
           ->where('name', trim($request->name))
           ->first()) {
           return [];
       }

       $customStatus = $request->user()->custom_statuses()->create([
           'name' => $request->name,
           'value' => Str::camel($request->name)
       ]);

       return new CustomStatusResource($customStatus);
    }
}
