<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'name' => ($isUpdate ? 'sometimes|' : 'required|').'string|max:255',
            'event_date' => ($isUpdate ? 'sometimes|' : 'required|').'date',
        ];
    }
}
