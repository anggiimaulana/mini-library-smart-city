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
            $visitor = auth('visitor')->user(); // 👈 pakai guard visitor

            $totalResources = Resources::count();

            $completedCount = VisitorProgress::where('visitor_id', $visitor->id)
                ->where('is_completed', true)
                ->count();

            $percentage = $totalResources > 0
                ? round(($completedCount / $totalResources) * 100)
                : 0;

            return response()->json([
                'total_references' => $totalResources,
                'completed_count' => $completedCount,
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
            $visitor = auth('visitor')->user(); // 👈 pakai guard visitor

            $totalResources = Resources::count();
            $completedCount = VisitorProgress::where('visitor_id', $visitor->id)
                ->where('is_completed', true)
                ->count();

            if ($completedCount < $totalResources) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please complete all references before requesting a certificate'
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
        $completedCount = VisitorProgress::where('visitor_id', $visitorId)
            ->where('is_completed', true)
            ->count();

        $percentage = $totalResources > 0 ? round(($completedCount / $totalResources) * 100) : 0;

        \App\Models\Visitor::where('id', $visitorId)
            ->update(['progress' => $percentage]);
    }
}
