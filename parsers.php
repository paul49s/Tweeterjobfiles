<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Parsers extends MX_Controller
{
	function index()
	{
		$this->load->library('codebird/codebird');
		$this->load->model('parsers_model');
		@ob_end_flush();
		ob_implicit_flush(TRUE);
		$this->parsers_model->parse();
		
	}
	
	function tweet()
	{
		$this->load->library('codebird/codebird');
		$this->load->model('parsers_model');
		@ob_end_flush();
		ob_implicit_flush(TRUE);
		$this->parsers_model->tweet49s();
		$this->parsers_model->tweetILB();
			
	}
}