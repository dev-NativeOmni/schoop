<?php

namespace App\Services\Lms;

use App\Models\LmsQuiz;
use App\Models\LmsQuizAnswer;
use App\Models\LmsQuizAttempt;
use App\Models\LmsQuizQuestion;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Support\Facades\DB;

class LmsQuizService
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
        private readonly LmsProgressService $progressService,
        private readonly LmsNotificationService $notificationService,
        private readonly LmsActivityLogger $logger,
    ) {
        //
    }

    public function createQuiz(array $data): LmsQuiz
    {
        return DB::transaction(function () use ($data) {
            $schoolId = $this->tenantContext->activeSchoolId();
            $data['school_id'] = $schoolId;

            $quiz = LmsQuiz::create($data);
            $this->logger->log('quiz_create', "Quiz '{$quiz->title}' created.", $quiz);

            return $quiz;
        });
    }

    public function updateQuiz(LmsQuiz $quiz, array $data): LmsQuiz
    {
        return DB::transaction(function () use ($quiz, $data) {
            $quiz->update($data);
            $this->logger->log('quiz_update', "Quiz '{$quiz->title}' updated.", $quiz);

            return $quiz;
        });
    }

    public function addQuestion(int $quizId, array $data): LmsQuizQuestion
    {
        return DB::transaction(function () use ($quizId, $data) {
            $schoolId = $this->tenantContext->activeSchoolId();
            $data['school_id'] = $schoolId;
            $data['quiz_id'] = $quizId;

            if (! isset($data['sort_order'])) {
                $maxSort = LmsQuizQuestion::where('quiz_id', $quizId)->max('sort_order');
                $data['sort_order'] = $maxSort !== null ? $maxSort + 1 : 1;
            }

            $question = LmsQuizQuestion::create($data);

            return $question;
        });
    }

    public function updateQuestion(LmsQuizQuestion $question, array $data): LmsQuizQuestion
    {
        return DB::transaction(function () use ($question, $data) {
            $question->update($data);

            return $question;
        });
    }

    public function deleteQuestion(LmsQuizQuestion $question): void
    {
        DB::transaction(function () use ($question) {
            $question->delete();
        });
    }

    public function startAttempt(int $quizId, int $studentId): LmsQuizAttempt
    {
        return DB::transaction(function () use ($quizId, $studentId) {
            $schoolId = $this->tenantContext->activeSchoolId();
            $quiz = LmsQuiz::findOrFail($quizId);

            $attemptsCount = LmsQuizAttempt::where('quiz_id', $quizId)
                ->where('student_id', $studentId)
                ->count();

            if ($quiz->max_attempts > 0 && $attemptsCount >= $quiz->max_attempts) {
                throw new \RuntimeException("Anda telah mencapai batas maksimum percobaan ({$quiz->max_attempts}) untuk kuis ini.");
            }

            $attempt = LmsQuizAttempt::create([
                'school_id' => $schoolId,
                'quiz_id' => $quizId,
                'student_id' => $studentId,
                'attempt_number' => $attemptsCount + 1,
                'started_at' => now(),
                'status' => 'started',
            ]);

            $this->logger->log('quiz_attempt_start', "Started quiz attempt #{$attempt->attempt_number} for '{$quiz->title}'.", $attempt);

            return $attempt;
        });
    }

    public function submitAttempt(int $attemptId, array $submittedAnswers): LmsQuizAttempt
    {
        return DB::transaction(function () use ($attemptId, $submittedAnswers) {
            $attempt = LmsQuizAttempt::findOrFail($attemptId);
            if ($attempt->status !== 'started') {
                throw new \RuntimeException('Percobaan kuis ini sudah selesai atau tidak valid.');
            }

            $schoolId = $attempt->school_id;
            $quiz = $attempt->quiz;
            $questions = $quiz->questions;

            $totalWeight = 0;
            $earnedWeight = 0;

            foreach ($questions as $question) {
                $totalWeight += $question->score_weight;

                $studentAnswer = $submittedAnswers[$question->id] ?? null;
                $isCorrect = false;

                // Strip whitespaces and perform case-insensitive checking for short answers
                $correctClean = trim(strtolower($question->correct_answer));
                $studentClean = trim(strtolower($studentAnswer ?? ''));

                if ($question->question_type === 'short_answer') {
                    $isCorrect = ($correctClean === $studentClean);
                } else {
                    $isCorrect = ($question->correct_answer === $studentAnswer);
                }

                $scoreObtained = $isCorrect ? $question->score_weight : 0;
                if ($isCorrect) {
                    $earnedWeight += $question->score_weight;
                }

                LmsQuizAnswer::create([
                    'school_id' => $schoolId,
                    'quiz_attempt_id' => $attempt->id,
                    'quiz_question_id' => $question->id,
                    'student_answer' => $studentAnswer,
                    'is_correct' => $isCorrect,
                    'score_obtained' => $scoreObtained,
                ]);
            }

            $finalScore = $totalWeight > 0 ? ($earnedWeight / $totalWeight) * 100 : 0;
            $finalScore = round($finalScore, 2);
            $isPassed = $finalScore >= $quiz->passing_score;

            $attempt->update([
                'completed_at' => now(),
                'score' => $finalScore,
                'is_passed' => $isPassed,
                'status' => 'completed',
            ]);

            // Auto-complete the lesson progress
            $this->progressService->markAsComplete($attempt->student_id, $quiz->lesson_id);

            // Send internal notifications
            $this->notificationService->notifyQuizAttemptCompleted($attempt);

            $this->logger->log('quiz_attempt_submit', "Quiz attempt #{$attempt->attempt_number} for '{$quiz->title}' submitted. Score: {$finalScore}.", $attempt);

            return $attempt;
        });
    }
}
