<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/** OWASP ASVS V5 + V12 File Upload Security | SSDF PW.5,PW.7 */
class ProductRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check() && Auth::user()->isAdmin(); }
    public function rules(): array {
        $update = $this->isMethod('PUT') || $this->isMethod('PATCH');
        return [
            'name'        => ['required','string','min:2','max:200'],
            'description' => ['required','string','min:10','max:2000'],
            'price'       => ['required','numeric','min:0.01','max:99999.99'],
            'stock'       => ['required','integer','min:0','max:100000'],
            'category'    => ['required','string','in:electronics,clothing,books,food,home,other'],
            'is_active'   => ['boolean'],
            'image'       => [$update ? 'nullable' : 'required', 'file',
                              'mimes:jpg,jpeg,png,webp',
                              'mimetypes:image/jpeg,image/png,image/webp',
                              'max:2048'],
        ];
    }
    public function messages(): array {
        return [
            'image.mimes'     => 'Only JPG, PNG and WEBP images are allowed.',
            'image.mimetypes' => 'File content must be a valid image.',
            'image.max'       => 'Image must be smaller than 2MB.',
            'category.in'     => 'Please select a valid category.',
        ];
    }
}
