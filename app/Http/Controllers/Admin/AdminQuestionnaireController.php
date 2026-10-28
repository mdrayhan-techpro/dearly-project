<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionOption;
use App\Models\UserQuestionnaireAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminQuestionnaireController extends Controller
{
    public function index(Request $request): View
    {
        $dimension = $request->query('dimension');
        $query = Question::with('options');

        if ($dimension) {
            $query->where('dimension', $dimension);
        }

        $questions = $query->orderBy('order_index')->paginate(20)->withQueryString();
        $dimensions = config('dearly.dimensions', []);
        $questionnaires = Questionnaire::all();

        // Funnel completion metrics
        $funnelStats = [
            'total_started' => UserQuestionnaireAttempt::count(),
            'total_completed' => UserQuestionnaireAttempt::where('is_completed', true)->count(),
            'len_20' => UserQuestionnaireAttempt::where('questionnaire_length', 20)->where('is_completed', true)->count(),
            'len_30' => UserQuestionnaireAttempt::where('questionnaire_length', 30)->where('is_completed', true)->count(),
            'len_40' => UserQuestionnaireAttempt::where('questionnaire_length', 40)->where('is_completed', true)->count(),
            'len_50' => UserQuestionnaireAttempt::where('questionnaire_length', 50)->where('is_completed', true)->count(),
        ];

        return view('admin.questions.index', compact('questions', 'dimensions', 'dimension', 'questionnaires', 'funnelStats'));
    }

    public function create(): View
    {
        $dimensions = config('dearly.dimensions', []);
        $questionnaires = Questionnaire::all();

        return view('admin.questions.create', compact('dimensions', 'questionnaires'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dimension' => ['required', 'string'],
            'question_text' => ['required', 'string'],
            'weight' => ['required', 'numeric', 'min:0.1', 'max:5.0'],
            'options' => ['required', 'array', 'size:5'],
            'options.*' => ['required', 'string'],
        ]);

        $maxOrder = Question::max('order_index') ?: 0;

        $question = Question::create([
            'dimension' => $validated['dimension'],
            'question_text' => $validated['question_text'],
            'weight' => $validated['weight'],
            'is_active' => true,
            'order_index' => $maxOrder + 1,
        ]);

        $keys = ['A', 'B', 'C', 'D', 'E'];
        foreach ($validated['options'] as $idx => $optText) {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_key' => $keys[$idx],
                'option_text' => $optText,
                'score_value' => $idx + 1,
                'order_index' => $idx + 1,
            ]);
        }

        AdminAuditLog::log('question.create', 'Question', $question->id, [
            'dimension' => $question->dimension,
            'question_text' => $question->question_text,
        ]);

        return redirect()->route('admin.questions.index')->with('status', 'New discovery question created successfully.');
    }

    public function edit(int $id): View
    {
        $question = Question::with('options')->findOrFail($id);
        $dimensions = config('dearly.dimensions', []);
        $questionnaires = Questionnaire::all();

        return view('admin.questions.edit', compact('question', 'dimensions', 'questionnaires'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $question = Question::with('options')->findOrFail($id);

        $validated = $request->validate([
            'dimension' => ['required', 'string'],
            'question_text' => ['required', 'string'],
            'weight' => ['required', 'numeric', 'min:0.1', 'max:5.0'],
            'order_index' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'options' => ['required', 'array', 'size:5'],
            'options.*' => ['required', 'string'],
        ]);

        $before = [
            'question_text' => $question->question_text,
            'dimension' => $question->dimension,
            'weight' => $question->weight,
            'order_index' => $question->order_index,
            'is_active' => $question->is_active,
        ];

        $question->update([
            'dimension' => $validated['dimension'],
            'question_text' => $validated['question_text'],
            'weight' => $validated['weight'],
            'order_index' => $validated['order_index'] ?? $question->order_index,
            'is_active' => $request->boolean('is_active'),
        ]);

        $keys = ['A', 'B', 'C', 'D', 'E'];
        foreach ($validated['options'] as $idx => $optText) {
            QuestionOption::updateOrCreate(
                ['question_id' => $question->id, 'option_key' => $keys[$idx]],
                ['option_text' => $optText, 'score_value' => $idx + 1, 'order_index' => $idx + 1]
            );
        }

        AdminAuditLog::log('question.update', 'Question', $question->id, [
            'before' => $before,
            'after' => [
                'question_text' => $question->question_text,
                'dimension' => $question->dimension,
                'weight' => $question->weight,
                'order_index' => $question->order_index,
                'is_active' => $question->is_active,
            ],
        ]);

        return redirect()->route('admin.questions.index')->with('status', 'Question updated successfully.');
    }

    public function updateOrder(Request $request, int $id): RedirectResponse
    {
        $request->validate(['order_index' => 'required|integer|min:1']);
        $question = Question::findOrFail($id);
        $oldOrder = $question->order_index;
        $question->update(['order_index' => (int) $request->input('order_index')]);

        AdminAuditLog::log('question.reorder', 'Question', $question->id, [
            'old_order' => $oldOrder,
            'new_order' => $question->order_index,
        ]);

        return back()->with('status', "Question #{$question->id} priority re-assigned to position {$question->order_index}.");
    }

    public function toggleActive(int $id): RedirectResponse
    {
        $question = Question::findOrFail($id);
        $question->update(['is_active' => !$question->is_active]);

        AdminAuditLog::log('question.toggle', 'Question', $question->id, [
            'is_active' => $question->is_active,
        ]);

        return back()->with('status', 'Question status toggled.');
    }
}
