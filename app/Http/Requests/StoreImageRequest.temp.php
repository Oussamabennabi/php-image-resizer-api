<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {   
        
        $rules = [
            'image'=> ["required"],
            // 50% | 50 | 50.5% | 50.5
            'w'=> ['required','regex:/^\d+(\.\d+)?%?$/'], 
            'h'=> ['required','regex:/^\d+(\.\d+)?%?$/'], 
            "album_id"=> "exists:\App\Modals\Album, id",
        ];

        $image = $this->all()['image'] ?? false;
        if($image && $image instanceof UploadedFile) {
            $rules['image'][] = 'file';
        } else {
            $rules['image'][] = 'url';
        }

        echo "<pre>";
        var_dump($image);
        echo "</pre>";
        exit;

        return $rules;

    }
}

