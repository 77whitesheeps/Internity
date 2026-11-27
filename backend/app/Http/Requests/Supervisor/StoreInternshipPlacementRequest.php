<?php

namespace App\Http\Requests\Supervisor;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreInternshipPlacementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role?->name, ['faculty_advisor', 'administrator'], true);
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:student_profiles,id'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id', 'required_without:new_company_name'],
            'new_company_name' => ['nullable', 'string', 'max:255', 'required_without:company_id'],
            'new_company_address' => ['nullable', 'string', 'max:500'],
            'new_company_email' => ['nullable', 'email', 'max:255'],
            'new_company_contact' => ['nullable', 'string', 'max:30'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'required_hours' => ['required', 'numeric', 'min:1', 'max:2000'],
            'status' => ['required', 'string', Rule::in(['pending', 'active', 'completed', 'cancelled'])],
            'faculty_advisor_id' => ['nullable', 'integer', 'exists:users,id'],
            'industry_mentor_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required_without' => 'Select an existing company or enter a new partner company name.',
            'new_company_name.required_without' => 'Select an existing company or enter a new partner company name.',
            'end_date.after_or_equal' => 'The end date must be on or after the start date.',
        ];
    }

    /**
     * Verify optional initial supervisor selections match their required roles.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($facultyId = $this->input('faculty_advisor_id')) {
                    $valid = User::whereKey($facultyId)
                        ->whereHas('role', fn ($q) => $q->where('name', 'faculty_advisor'))
                        ->exists();

                    if (! $valid) {
                        $validator->errors()->add('faculty_advisor_id', 'The selected user is not a Faculty Advisor.');
                    }
                }

                if ($mentorId = $this->input('industry_mentor_id')) {
                    $valid = User::whereKey($mentorId)
                        ->whereHas('role', fn ($q) => $q->where('name', 'industry_mentor'))
                        ->exists();

                    if (! $valid) {
                        $validator->errors()->add('industry_mentor_id', 'The selected user is not an Industry Mentor.');
                    }
                }
            },
        ];
    }
}
