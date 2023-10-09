<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Curso extends CI_Controller{

    //Atributos da classe
    private $json;
	private $resultado;	

    //Atributos privados da classe
	private $idCurso;
    private $descricao;
    private $estatus;  
	       
	//Getters dos atributos
	public function getIdCurso(){
		return $this->idCurso;
	}

	public function getDescricao(){
		return $this->descricao;
	}

	public function getEstatus(){
		return $this->estatus;
	}

    //Setters dos atributos
	public function setIdCurso($idCursoFront){
		$this->idCurso = $idCursoFront;
	}

    public function setDescricao($descFront){
		$this->descricao = $descFront;
	}

	public function setEstatus($estatusFront){
		$this->estatus = $estatusFront;
	}  
	       
    //Métodos da classe
	

	//Cadastrar o Curso
    public function inserirCurso(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("descricao" => '0', 
		               "estatus"   => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
			$this->setDescricao($resultado->descricao);
			$this->setEstatus($resultado->estatus);

			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - CURSO CADASTRADO CORRETAMENTE (MODEL)
				02 - HOUVE ALGUM PROBLEMA DE SALVAMENTO DOS DADOS (MODEL)
				03 - DESCRIÇÃO NÃO INFORMADA (CONTROLLER)
				04 - STATUS NÃO CONDIZ COM O PERMITIDO (CONTROLLER)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

			if (trim($this->getDescricao()) == ""){
				$retorno = array('codigo' => 3,
			 				     'msg'    => 'Descrição não informada.');
			}elseif ($this->getEstatus() != "D" && $this->getEstatus() != ""){
				$retorno = array('codigo' => 4,
			 				     'msg'    => 'Status não condiz com o permitido.');
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_curso');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_curso->inserirCurso($this->getDescricao(), $this->getEstatus());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de inserção, verifique.');			
		}

		echo json_encode($retorno);
	
    }  

	//Consultar Curso(s)
    public function consultarCurso(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("idCurso"   => '0',
					   "descricao" => '0', 
		               "estatus"   => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
			$this->setIdCurso($resultado->idCurso);
			$this->setDescricao($resultado->descricao);
			$this->setEstatus($resultado->estatus);

			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - CURSO CONSULTADO CORRETAMENTE (MODEL)
				02 - NÃO HOUVE RETORNO NA CONSULTA DOS DADOS (MODEL)				
				04 - STATUS NÃO CONDIZ COM O PERMITIDO (CONTROLLER)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

			if ($this->getEstatus() != "D" && $this->getEstatus() != ""){
				$retorno = array('codigo' => 4,
			 				     'msg'    => 'Status não condiz com o permitido.');
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_curso');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_curso->consultarCurso($this->getIdCurso(), $this->getDescricao(), 
				                                          $this->getEstatus());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de consulta, verifique.');			
		}

		echo json_encode($retorno);
	
    }

	//Alterar Curso
    public function alterarCurso(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("idCurso"   => '0',
					   "descricao" => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
			$this->setIdCurso($resultado->idCurso);
			$this->setDescricao($resultado->descricao);			

			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - CURSO ALTERADO CORRETAMENTE (MODEL)
				02 - HOUVE ALGUM PROBLEMA DE ALTERAÇÃO DOS DADOS (MODEL)			
				03 - ID DO CURSO NÃO INFORMADO OU ZERADO (CONTROLLER)
				04 - DESCRIÇÃO DO CURSO NÃO INFORMADA (CONTROLLER)
				05 - O ID DO CURSO INFORMADO NÃO ESTA CADASTRADO NA BASE DE DADOS (MODEL)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

			if ($this->getIdCurso() == "" || $this->getIdCurso() == 0){
				$retorno = array('codigo' => 3,
			 				     'msg'    => 'ID do curso não informado ou zerado.');
			}elseif(strlen($this->getDescricao()) == 0){
				$retorno = array('codigo' => 4,
			 				     'msg'    => 'Descrição do curso não informada.');			
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_curso');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_curso->alterarCurso($this->getIdCurso(), $this->getDescricao());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de consulta, verifique.');			
		}

		echo json_encode($retorno);
	
    }

	//"Apagar" Curso
    public function apagarCurso(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("idCurso" => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
			$this->setIdCurso($resultado->idCurso);

			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - CURSO "APAGADO" CORRETAMENTE (MODEL)
				02 - HOUVE ALGUM PROBLEMA NA "EXCLUSÃO" DO CURSO (MODEL)			
				03 - ID DO CURSO NÃO INFORMADO OU ZERADO (CONTROLLER)				
				04 - O ID DO CURSO INFORMADO NÃO ESTA CADASTRADO NA BASE DE DADOS (MODEL)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

			if ($this->getIdCurso() == "" || $this->getIdCurso() == 0){
				$retorno = array('codigo' => 3,
			 				     'msg'    => 'ID do curso não informado ou zerado.');
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_curso');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_curso->apagarCurso($this->getIdCurso());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de consulta, verifique.');			
		}

		echo json_encode($retorno);
	
    }
}