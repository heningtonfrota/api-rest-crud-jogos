<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @OA\POST(
     *  tags={"User"},
     *  summary="Cria um novo usuário",
     *  path="/api/register",
     *  @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *             required={"email","password","name","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Test"),
     *             @OA\Property(property="email", type="string", example="test@example.com"),
     *             @OA\Property(property="password", type="string", example="@123456789"),
     *             @OA\Property(property="password_confirmation", type="string", example="@123456789"),
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="User created",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="User created successfully!")
     *    )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Incorrect fields",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The name has already been taken. (and 2 more errors)"),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'User created successfully'], 201);
    }

    /**
     * @OA\POST(
     *  tags={"User"},
     *  summary="Obter um token de usuário de autenticação",
     *  description="This endpoints return a new token user authentication for use on protected endpoints",
     *  path="/api/login",
     *  @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"email","password","device_name"},
     *              @OA\Property(property="email", type="string", example="test@example.com"),
     *              @OA\Property(property="password", type="string", example="@123456789"),
     *              @OA\Property(property="device_name", type="string", example="APP"),
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Token generated",
     *    @OA\JsonContent(
     *       @OA\Property(property="plainTextToken", type="string", example="2|MZEBxLy1zulPtND6brlf8GOPy57Q4DwYunlibXGj")
     *    )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Incorrect credentials",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The provided credentials are incorrect."),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name);

        return $token;
    }
}
