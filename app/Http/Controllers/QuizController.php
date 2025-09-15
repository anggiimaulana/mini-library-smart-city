<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Resources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    // Static quiz data
    private $quizData = [
        [
            'id' => 1,
            'question' => 'Apa definisi Smart City menurut konsep Indonesia?',
            'options' => [
                'A' => 'Kota yang menggunakan teknologi canggih saja',
                'B' => 'Konsep pengembangan kota yang mengintegrasikan TIK untuk meningkatkan kualitas layanan publik dan kehidupan masyarakat',
                'C' => 'Kota yang memiliki internet cepat',
                'D' => 'Kota dengan gedung-gedung tinggi'
            ],
            'correct' => 'B'
        ],
        [
            'id' => 2,
            'question' => 'Berapa jumlah pilar Smart City Indonesia?',
            'options' => [
                'A' => '4 pilar',
                'B' => '5 pilar',
                'C' => '6 pilar',
                'D' => '7 pilar'
            ],
            'correct' => 'C'
        ],
        [
            'id' => 3,
            'question' => 'Manakah yang BUKAN termasuk 6 pilar Smart City Indonesia?',
            'options' => [
                'A' => 'Smart Governance',
                'B' => 'Smart Transportation',
                'C' => 'Smart Economy',
                'D' => 'Smart Environment'
            ],
            'correct' => 'B'
        ],
        [
            'id' => 4,
            'question' => 'Smart Governance berfokus pada?',
            'options' => [
                'A' => 'Pengelolaan ekonomi kota',
                'B' => 'Tata kelola pemerintahan yang efektif dan partisipatif',
                'C' => 'Pengelolaan lingkungan',
                'D' => 'Pembangunan infrastruktur'
            ],
            'correct' => 'B'
        ],
        [
            'id' => 5,
            'question' => 'Yang dimaksud dengan Smart Branding adalah?',
            'options' => [
                'A' => 'Pemasaran produk digital',
                'B' => 'Pembangunan citra dan identitas kota',
                'C' => 'Promosi wisata saja',
                'D' => 'Iklan kota di media sosial'
            ],
            'correct' => 'B'
        ],
        [
            'id' => 6,
            'question' => 'Smart Economy bertujuan untuk?',
            'options' => [
                'A' => 'Meningkatkan daya saing ekonomi melalui inovasi dan teknologi',
                'B' => 'Mengurangi pajak',
                'C' => 'Membangun pusat perbelanjaan',
                'D' => 'Menarik investor asing saja'
            ],
            'correct' => 'A'
        ],
        [
            'id' => 7,
            'question' => 'Smart Living meliputi aspek?',
            'options' => [
                'A' => 'Kualitas hidup, kesehatan, dan keamanan masyarakat',
                'B' => 'Pembangunan rumah mewah',
                'C' => 'Fasilitas hiburan saja',
                'D' => 'Pusat perbelanjaan modern'
            ],
            'correct' => 'A'
        ],
        [
            'id' => 8,
            'question' => 'Smart Society fokus pada?',
            'options' => [
                'A' => 'Pembangunan gedung tinggi',
                'B' => 'Partisipasi masyarakat dan modal sosial dalam pembangunan kota',
                'C' => 'Media sosial kota',
                'D' => 'Komunitas online saja'
            ],
            'correct' => 'B'
        ],
        [
            'id' => 9,
            'question' => 'Smart Environment berkaitan dengan?',
            'options' => [
                'A' => 'Pengelolaan lingkungan berkelanjutan dan ramah lingkungan',
                'B' => 'Pembangunan taman saja',
                'C' => 'Daur ulang sampah',
                'D' => 'Penanaman pohon'
            ],
            'correct' => 'A'
        ],
        [
            'id' => 10,
            'question' => 'Manakah contoh implementasi Smart City yang tepat di Indramayu?',
            'options' => [
                'A' => 'Sistem pelayanan publik digital dan monitoring lingkungan',
                'B' => 'Membangun gedung tinggi',
                'C' => 'Menambah jumlah kendaraan',
                'D' => 'Membuat jalan tol'
            ],
            'correct' => 'A'
        ]
    ];

    public function showQuiz()
    {
        // UBAH: Hapus auth check di sini, biarkan JavaScript handle
        return view('pages.quiz', [
            'title' => 'Smart City Quiz - Mini Library',
            'slug' => 'quiz'
        ]);
    }

    // TAMBAH: Method baru untuk API endpoint yang terproteksi
    public function getQuizData(Request $request)
    {
        try {
            $visitor = auth('visitor')->user();

            return response()->json([
                'success' => true,
                'questions' => $this->quizData,
                'total_questions' => count($this->quizData),
                'passing_score' => 70,
                'user_attempts' => $visitor->quiz_attempt ?? 0,
                'user_score' => $visitor->quiz_score ?? 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load quiz data'
            ], 500);
        }
    }

    public function submitQuiz(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array|size:10',
            'answers.*' => 'required|in:A,B,C,D'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $visitor = auth('visitor')->user();
            if (!$visitor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized, visitor not found'
                ], 401);
            }

            $answers = $request->answers;

            // Calculate score
            $correctAnswers = 0;
            $results = [];

            foreach ($this->quizData as $question) {
                $userAnswer = $answers[$question['id']] ?? null;
                $isCorrect = $userAnswer === $question['correct'];

                if ($isCorrect) {
                    $correctAnswers++;
                }

                $results[] = [
                    'question_id' => $question['id'],
                    'question' => $question['question'],
                    'user_answer' => $userAnswer,
                    'correct_answer' => $question['correct'],
                    'is_correct' => $isCorrect
                ];
            }

            $score = ($correctAnswers / count($this->quizData)) * 100;
            $passed = $score >= 70;

            // Update visitor data
            if ($visitor) {
                $visitor = Visitor::find($visitor->id);
                $visitor->quiz_attempt = ($visitor->quiz_attempt ?? 0) + 1;
                $visitor->quiz_score = max($visitor->quiz_score ?? 0, $score);
                $visitor->save();
            }


            // Update overall progress if passed
            if ($passed) {
                $this->updateVisitorProgress($visitor->id);
            }

            return response()->json([
                'success' => true,
                'score' => $score,
                'correct_answers' => $correctAnswers,
                'total_questions' => count($this->quizData),
                'passed' => $passed,
                'results' => $results,
                'attempts' => $visitor->quiz_attempt
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit quiz'
            ], 500);
        }
    }

    private function updateVisitorProgress($visitorId)
    {
        $totalResources = Resources::count();
        $visitor = Visitor::find($visitorId);

        $completedResources = $visitor->progress()
            ->where('is_completed', true)
            ->count();

        $quizCompleted = ($visitor->quiz_score ?? 0) >= 70 ? 1 : 0;

        $totalItems = $totalResources + 1;
        $completedItems = $completedResources + $quizCompleted;

        $percentage = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;

        $visitor->update(['progress' => $percentage]);
    }
}
