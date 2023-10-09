<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Funcoes extends CI_Controller{
    	       
    //Métodos da classe
    //Chamada da view de login
	public function index(){
		$this->load->view('index');	
	}

    //Chamada para a home
    public function abrirHome(){
        // echo 'oi';
        $this->load->view('menu');	
    }
	
}