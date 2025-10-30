<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;
use Auth;
use Illuminate\Validation\Rule;

class PostRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
    {
		return [
			'title' => 'required',
            'post_type_id' => 'required',
			'body' => [Rule::requiredIf(in_array($this->post_type_id, [POST_TYPE_BERITA, POST_TYPE_CERITA]))],
		];
	}	
	
	/**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'title.required' => 'Judul berita harus diisi..',
			'body.required'  => 'Isi berita harus diisi..',
		];
	}	
}
