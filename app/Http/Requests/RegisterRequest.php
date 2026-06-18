<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

/** OWASP ASVS V5 Input Validation + V2.1 Password Rules | SSDF PW.5 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name'     => ['required','string','min:2','max:100','regex:/^[\pL\s\-\']+$/u'],
            'email'    => ['required','email:rfc,dns','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&\#^()\-_=+])[A-Za-z\d@$!%*?&\#^()\-_=+]{8,}$/'],
        ];
    }
    public function messages(): array {
        return [
            'name.regex'     => 'Name may only contain letters, spaces, hyphens, and apostrophes.',
            'email.unique'   => 'An account with this email already exists.',
            'password.regex' => 'Password must have uppercase, lowercase, number and special character.',
        ];
    }
}
