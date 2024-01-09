<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('client')->group(function () {
    Route::middleware('scope:admin,client')
        ->post('/clients/{cnpj}/balance', function (string $cnpj, Request $request) {
        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id'
            ]
        ]);

        $user = \App\Models\User::find($userId);

        if (!userIsAdmin($userId) || $cnpj !== $userId->cnpj) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'cnpj' => $cnpj,
            'balance' => 1000,
        ]);
    });

    Route::middleware('scopes:admin,update-balance')
        ->post('/clients/{cnpj}/change-balance', function (string $cnpj, Request $request) {
            $request->validate([
                'user_id' => [
                    'required',
                    'exists:users,id'
                ],
                'cnpj' => [
                    'required',
                    'exists:users,cnpj'
                ],
            ]);

            if ($request->get('cnpj') !== $cnpj) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }

            if (! userIsAdmin($userId)) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }
    });
});
