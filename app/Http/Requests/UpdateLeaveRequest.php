<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'type_conge' => 'required|string|max:255',
            'date_de_depart' => 'required|date',
            'date_de_fin' => 'required|date|after_or_equal:date_de_depart',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {

        return [
            'nom.required' => 'Le nom est requis',
            'type_conge.required' => 'Le type de congé est requis',
            'date_de_depart.required' => 'La date de départ est requise',
            'date_de_depart.date' => 'La date de départ doit être une date valide',
            'date_de_fin.required' => 'La date de fin est requise',
            'date_de_fin.date' => 'La date de fin doit être une date valide',
            'date_de_fin.after_or_equal' => 'La date de fin doit être après ou égale à la date de départ',
            'description.required' => 'La description est requise',
            'description.max' => 'La description ne doit pas dépasser 255 caractères',
        ];
    }
}
