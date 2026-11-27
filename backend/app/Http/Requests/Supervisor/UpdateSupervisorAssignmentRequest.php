<?php

namespace App\Http\Requests\Supervisor;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateSupervisorAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role?->name, ['faculty_advisor', 'administrator'], true);
    }

    public function rules(): array
    {
        return [
            'faculty_advisor_id' => ['nullable', 'integer', 'exists:users,id'],
            'industry_mentor_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Ensure each selected supervisor actually holds the matching system role.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $facultyId = $this->input('faculty_advisor_id');
                if ($facultyId) {
                    $isFaculty = User::whereKey($facultyId)
                        ->whereHas('role', fn ($q) => $q->where('name', 'faculty_advisor'))
                        ->exists();

                    if (! $isFaculty) {
                        $validator->errors()->add(
                            'faculty_advisor_id',
                            'The selected user is not a Faculty Advisor.'
                        );
                    }
                }

                $mentorId = $this->input('industry_mentor_id');
                if ($mentorId) {
                    $isMentor = User::whereKey($mentorId)
                        ->whereHas('role', fn ($q) => $q->where('name', 'industry_mentor'))
                        ->exists();

                    if (! $isMentor) {
                        $validator->errors()->add(
                            'industry_mentor_id',
                            'The selected user is not an Industry Mentor.'
                        );
                    }
                }
            },
        ];
    }
}
