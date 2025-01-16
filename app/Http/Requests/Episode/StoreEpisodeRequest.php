<?php

namespace App\Http\Requests\Episode;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreEpisodeRequest extends FormRequest
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
            'audio_url' => ['required', File::types(['mp3', 'wav'])
                ->max('10mb'),
            ],
        ];
    }
}
