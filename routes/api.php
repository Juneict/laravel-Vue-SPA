<?php

use App\Http\Controllers\ProductControler;
use Illuminate\Support\Facades\Route;

Route::apiResource('product',ProductControler::class);