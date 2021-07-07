<?php

namespace Config;

use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var string[]
	 */
	public $ruleSets = [
		Rules::class,
		FormatRules::class,
		FileRules::class,
		CreditCardRules::class,
		\Myth\Auth\Authentication\Passwords\ValidationRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array<string, string>
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------

	public $category = [
		'id' => [
			'rules' => 'required|alpha_numeric',
		],
		'nama' => [
			'rules'  => 'required|regex_match[/^[\w\s ,.]+$/]',
			'errors' => [
				'required' => 'Wajib diisi!',
			]
		],
		'deskripsi' => [
			'rules'  => 'required|regex_match[/^[\w\s ,.]+$/]',
			'errors' => [
				'required' => 'Wajib diisi!',
			]
		],
	];

	public $buku = [
		'id' => [
			'rules' => 'required|alpha_numeric',
		],
		'judul' => [
			'rules'  => 'required|regex_match[/^[\w\s ,.]+$/]',
			'errors' => [
				'required' => 'Wajib diisi!',
			]
		],
		'penulis' => [
			'rules'  => 'required|regex_match[/^[\w\s ,.]+$/]',
			'errors' => [
				'required' => 'Wajib diisi!',
			]
		],
		'penerbit' => [
			'rules'  => 'required|regex_match[/^[\w\s ,.]+$/]',
			'errors' => [
				'required' => 'Wajib diisi!',
			]
		],
		'category_id' => [
			'rules' => 'required|alpha_numeric',
		],
		'deskripsi' => [
			'rules'  => 'permit_empty|regex_match[/^[\w\s ,.]+$/]',
		],
	];
}
