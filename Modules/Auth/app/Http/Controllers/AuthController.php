<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Services\RegisterService;
use Modules\Auth\Services\LoginService;
use Modules\Auth\Services\LogoutService;

class AuthController extends Controller
{
    protected RegisterService $registerService;
    protected LoginService $loginService;
    protected LogoutService $logoutService;

    public function __construct(
        RegisterService $registerService,
        LoginService $loginService,
        LogoutService $logoutService
    ) {
        $this->registerService = $registerService;
        $this->loginService = $loginService;
        $this->logoutService = $logoutService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth::create');
    }

    /**
     * Register a new user
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        \Log::info('AuthController: Registration request received', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data' => $request->validated()
        ]);
        
        try {
            $result = $this->registerService->register($request->validated());
            
            \Log::info('AuthController: Registration successful', ['user_id' => $result['user']['id']]);
            
            return response()->json($result, 201);
        } catch (\Exception $e) {
            // Log error for debugging (not shown to user)
            \Log::error('AuthController: User registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->validated()
            ]);
            
            return response()->json([
                'message' => 'ثبت نام با مشکل مواجه شد. لطفاً دوباره تلاش کنید.'
            ], 500);
        }
    }

    /**
     * Login user
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->loginService->login($request->validated());
            
            return response()->json($result, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'ورود ناموفق بود',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log error for debugging (not shown to user)
            \Log::error('User login failed: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'ورود با مشکل مواجه شد. لطفاً دوباره تلاش کنید.'
            ], 500);
        }
    }

    /**
     * Logout user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $result = $this->logoutService->logout($request->user());
            
            return response()->json($result, 200);
        } catch (\Exception $e) {
            // Log error for debugging (not shown to user)
            \Log::error('User logout failed: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'خروج با مشکل مواجه شد. لطفاً دوباره تلاش کنید.'
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('auth::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('auth::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
