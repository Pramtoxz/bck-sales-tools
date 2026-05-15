<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AppsheetStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

  public function rules(): array
{
    return [
        'Appsheet' => 'required|array|min:1',
        'Appsheet.*.Chat ID 1' => 'nullable|string|max:20',
        'Appsheet.*.Task' => 'nullable|string|max:255',
        'Appsheet.*.Due Date' => 'nullable|string', // ← Ubah dari 'date' ke 'string'
        'Appsheet.*.Due Gap (Days)' => 'nullable', // ← Hilangkan 'integer'
        'Appsheet.*.Description' => 'nullable|string',
        'Appsheet.*.Assigned Persons' => 'nullable|string',
        'Appsheet.*.Notes' => 'nullable|string',
    ];
}

    public function attributes(): array
    {
        return [
            'Appsheet' => 'Data Appsheet',
            'Appsheet.*.Chat ID 1' => 'Chat ID',
            'Appsheet.*.Task' => 'Task',
            'Appsheet.*.Due Date' => 'Due Date',
            'Appsheet.*.Due Gap (Days)' => 'Due Gap',
            'Appsheet.*.Description' => 'Description',
            'Appsheet.*.Assigned Persons' => 'Assigned Persons',
            'Appsheet.*.Notes' => 'Notes',
        ];
    }

    public function messages(): array
    {
        return [
            'Appsheet.required' => 'Data Appsheet wajib diisi',
            'Appsheet.array' => 'Data Appsheet harus berupa array',
            'Appsheet.min' => 'Data Appsheet minimal harus ada 1 item',
            'Appsheet.*.Chat ID 1.required' => 'Chat ID wajib diisi',
            'Appsheet.*.Task.required' => 'Task wajib diisi',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'data' => [
                    'errors' => $validator->errors()
                ],
            ], 422)
        );
    }
}