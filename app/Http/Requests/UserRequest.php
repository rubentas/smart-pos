<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true; // semua user bisa akses, tapi batasi di controller
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules()
  {
    $userId = $this->route('user') ? $this->route('user')->id : null;

    return [
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $userId,
      'password' => $userId ? 'nullable|min:8' : 'required|min:8',
      'role_id' => 'required|exists:roles,id',
    ];
  }
}
