<?php

namespace App\Http\Controllers;

use App\Models\Resources;
use App\Models\VisitorProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgressController extends Controller
{
    public function getUserProgress(Request $request)
    {
        try {
            $visitor = auth('visitor')->user();

            $totalResources = Resources::count();
            $completedResourcesCount = VisitorProgress::where('visitor_id', $visitor->id)
                ->where('is_completed', true)
                ->count();

            // TAMBAH: Quiz completion status
            $quizCompleted = ($visitor->quiz_score ?? 0) >= 70 ? 1 : 0;

            // TOTAL items = resources + quiz
            $totalItems = $totalResources + 1;
            $completedItems = $completedResourcesCount + $quizCompleted;

            $percentage = $totalItems > 0
                ? round(($completedItems / $totalItems) * 100)
                : 0;

            return response()->json([
                'total_references' => $totalResources,
                'completed_count' => $completedResourcesCount,
                'quiz_completed' => $quizCompleted,
                'quiz_score' => $visitor->quiz_score ?? 0,
                'quiz_attempts' => $visitor->quiz_attempts ?? 0, // TAMBAH
                'total_items' => $totalItems,
                'completed_items' => $completedItems,
                'percentage' => $percentage,
                'is_complete' => $percentage === 100
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user progress'
            ], 500);
        }
    }

    public function toggleProgress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'resource_id' => 'required|exists:resources,id',
            'is_completed' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $visitor = auth('visitor')->user();

            $resourceId = $request->resource_id;
            $isCompleted = $request->is_completed;

            $progress = VisitorProgress::where('visitor_id', $visitor->id)
                ->where('resource_id', $resourceId)
                ->first();

            if ($progress) {
                $progress->update([
                    'is_completed' => $isCompleted,
                    'completed_at' => $isCompleted ? now() : null,
                ]);
            } else {
                VisitorProgress::create([
                    'visitor_id' => $visitor->id,
                    'resource_id' => $resourceId,
                    'is_completed' => $isCompleted,
                    'completed_at' => $isCompleted ? now() : null,
                ]);
            }

            // Update visitor's overall progress
            $this->updateVisitorProgress($visitor->id);

            return response()->json([
                'success' => true,
                'message' => $isCompleted
                    ? 'The reference has been marked as read.'
                    : 'The reference has been marked as unread.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update progress'
            ], 500);
        }
    }


    public function getCertificateData(Request $request)
    {
        try {
            $visitor = auth('visitor')->user();

            $totalResources = Resources::count();
            $completedCount = VisitorProgress::where('visitor_id', $visitor->id)
                ->where('is_completed', true)
                ->count();

            // Check if quiz is completed (score >= 70)
            $quizCompleted = ($visitor->quiz_score ?? 0) >= 70;

            // Total items = resources + quiz
            $totalItems = $totalResources + 1;
            $completedItems = $completedCount + ($quizCompleted ? 1 : 0);

            if ($completedItems < $totalItems) {
                $missing = [];
                if ($completedCount < $totalResources) {
                    $missing[] = ($totalResources - $completedCount) . ' references';
                }
                if (!$quizCompleted) {
                    $missing[] = 'quiz (minimum score 70%)';
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Please complete: ' . implode(' and ', $missing)
                ], 400);
            }

            return response()->json([
                'success' => true,
                'certificate_data' => [
                    'name' => $visitor->name,
                    'nim' => $visitor->nim,
                    'major' => $visitor->major?->name,
                    'study_program' => $visitor->studyProgram?->name,
                    'completion_date' => now()->format('Y-m-d'),
                    'total_resources' => $totalResources,
                    'quiz_score' => round($visitor->quiz_score),
                    'total_items' => $totalItems,
                    'completed_items' => $completedItems,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve certificate data'
            ], 500);
        }
    }

    private function updateVisitorProgress($visitorId)
    {
        $totalResources = Resources::count();
        $visitor = \App\Models\Visitor::find($visitorId);

        $completedCount = VisitorProgress::where('visitor_id', $visitorId)
            ->where('is_completed', true)
            ->count();

        // Add quiz completion (if score >= 70)
        $quizCompleted = ($visitor->quiz_score ?? 0) >= 70 ? 1 : 0;

        $totalItems = $totalResources + 1; // +1 for quiz
        $completedItems = $completedCount + $quizCompleted;

        $percentage = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;

        $visitor->update(['progress' => $percentage]);
    }
}
