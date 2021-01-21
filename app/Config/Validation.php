<?php namespace Config;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var array
	 */
	public $ruleSets = [
		\CodeIgniter\Validation\Rules::class,
		\CodeIgniter\Validation\FormatRules::class,
		\CodeIgniter\Validation\FileRules::class,
		\CodeIgniter\Validation\CreditCardRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------

	public $user = [
		'name'    	   => 'required|alpha_numeric_space|min_length[3]',
        'email'        => 'required|valid_email|is_unique[users.email]',
        'password'     => 'required|min_length[8]',
        'pass_confirm' => 'required_with[password]|matches[password]'
	];
	public $user_errors = [
		'name' => [
			'required'  => 'Nama wajib diisi.',
			'min_length' => 'Nama minimal 3 karakter',
			'alpha_numeric_space' => 'Nama tidak boleh mengandung karakter'
		],
		'email' => [
			'required'  => 'Email wajib diisi.',
			'valid_email' => 'Harus berupa email',
			'is_unique' => 'Email sudah digunakan'
		],
		'password' => [
			'required'  => 'Password wajib diisi.'
		],
		'pass_confirm' => [
			'matches' => 'Konfirmasi password tidak sesuai',
			'required_with' => 'Konfirmasi password wajib diisi'
		]
	];
}
