<?php

namespace App\Http\Requests\Episode;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateEpisodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'audio_file' => ['sometimes', File::types(['mp3', 'wav'])
                ->max('10mb'),
            ],
        ];
    }
}
