<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$ci = &get_instance();
		$ci->load->helper('jwt', 'cookie');

		$token = $ci->input->cookie('jwt_token', TRUE);
		if (!$token) {
			return        $this->load->view('login');
		}

		$user = validate_jwt($token);
		if (!$user['id']) {
			return        $this->load->view('login');
		}

		$this->load->view('home', ['user' => $user]);
	}
	public function login()
	{
		$this->load->view('login');
	}
	public function signup()
	{
		$this->load->view('signup');
	}
}
