<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Major;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('pages.register', [
            'title' => 'Register - Smart City Mini Library',
            'slug' => 'register'
        ]);
    }

    public function showLogin()
    {
        return view('pages.login', [
            'title' => 'Login - Smart City Mini Library',
            'slug' => 'login'
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:visitors,nim',
            'major_id' => 'required|exists:majors,id',
            'study_program_id' => 'required|exists:study_programs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $studyProgram = StudyProgram::where('id', $request->study_program_id)
                ->where('major_id', $request->major_id)
                ->first();

            if (!$studyProgram) {
                return response()->json([
                    'success' => false,
                    'message' => 'The study program is not in accordance with the chosen major.'
                ], 422);
            }

            $visitor = Visitor::create([
                'name' => $request->name,
                'nim' => $request->nim,
                'major_id' => $request->major_id,
                'study_program_id' => $request->study_program_id,
                'is_active' => true,
                'progress' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'secret_code' => $visitor->secret_code,
                'user' => $this->formatVisitor($visitor)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret_code' => 'required|string|size:6|exists:visitors,secret_code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The unique code is not valid',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $visitor = Visitor::with(['major', 'studyProgram', 'progress'])
                ->where('secret_code', $request->secret_code)
                ->where('is_active', true)
                ->first();

            if (!$visitor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account is inactive or not found'
                ], 404);
            }

            // 🔥 Hapus semua token lama dulu
            $visitor->tokens()->delete();

            // Buat token baru
            $token = $visitor->createToken('visitor-auth')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $this->formatVisitor($visitor),
                'progress' => $visitor->progress()
                    ->where('is_completed', true)
                    ->pluck('resource_id')
                    ->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login'
            ], 500);
        }
    }

    public function verify(Request $request)
    {
        $token = $request->input('token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 401);
        }

        try {
            $personalAccessToken = PersonalAccessToken::findToken($token);

            if (!$personalAccessToken || !$personalAccessToken->tokenable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid token'
                ], 401);
            }

            $visitor = $personalAccessToken->tokenable;
            $visitor->load(['major', 'studyProgram', 'progress']);

            if (!$visitor->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account inactive'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'user' => $this->formatVisitor($visitor),
                'progress' => $visitor->progress()
                    ->where('is_completed', true)
                    ->pluck('resource_id')
                    ->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during verification'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $visitor = auth('visitor')->user();

            if ($visitor && $request->bearerToken()) {
                $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($request->bearerToken());
                if ($accessToken) {
                    $accessToken->delete();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getMajors()
    {
        try {
            $majors = Major::orderBy('name')->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'data' => $majors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve major data'
            ], 500);
        }
    }

    public function getStudyPrograms($major_id)
    {
        try {
            $studyPrograms = StudyProgram::where('major_id', $major_id)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'data' => $studyPrograms
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve study program data'
            ], 500);
        }
    }

    /**
     * Helper format visitor untuk response
     */
    private function formatVisitor(Visitor $visitor)
    {
        return [
            'id' => $visitor->id,
            'name' => $visitor->name,
            'nim' => $visitor->nim,
            'major' => $visitor->major,
            'study_program' => $visitor->studyProgram,
            'secret_code' => $visitor->secret_code,
        ];
    }
}
