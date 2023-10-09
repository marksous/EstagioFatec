<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aluno extends CI_Controller{

    //Atributos da classe
    private $json;
	private $resultado;	

    //Atributos privados da classe
	private $ra;
    private $idCurso;
    private $nome;  
    private $estatus; 
	       
	//Getters dos atributos
    public function getRA(){
		return $this->ra;
	}

    public function getIdCurso(){
		return $this->idCurso;
	}

    public function getNome(){
        return $this->nome;
    }

	public function getEstatus(){
		return $this->estatus;
	}

    //Setters dos atributos
    public function setRA($raFront){
		$this->ra = $raFront;
	}

	public function setIdCurso($idCursoFront){
		$this->idCurso = $idCursoFront;
	}

    public function setNome($nomeFront){
        $this->nome = $nomeFront;
    }

	public function setEstatus($estatusFront){
		$this->estatus = $estatusFront;
	}         

    //Métodos da classe
	//Cadastrar o Aluno
    public function inserirAluno(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("ra"      => '0', 
		               "nome"    => '0',
                       "idCurso" => '0',
                       "estatus" => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
			$this->setRA($resultado->ra);
            $this->setIdCurso($resultado->idCurso);
            $this->setNome($resultado->nome);
			$this->setEstatus($resultado->estatus);

			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - ALUNO CADASTRADO CORRETAMENTE (MODEL)
				02 - HOUVE ALGUM PROBLEMA DE SALVAMENTO DOS DADOS (MODEL)
                03 - RA DO ALUNO NÃO INFORMADO OU ZERADO (CONTROLLER)
				04 - ID DO CURSO NÃO INFORMADO OU ZERADO (CONTROLLER)
                05 - NOME NÃO INFORMADO (CONTROLLER)
				06 - STATUS NÃO CONDIZ COM O PERMITIDO (CONTROLLER)
                07 - ID DO CURSO PASSADO NÃO ESTÁ CADASTRADO NA BASE (MODEL)
                08 - ALUNO JÁ SE ENCONTRA CADASTRADO NA BASE (MODEL)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)                
			*/
            if (trim($this->getRA()) == "" || $this->getRA() == 0){
				$retorno = array('codigo' => 3,
			 				     'msg'    => 'RA do Aluno não informado ou zerado.');
			}elseif (trim($this->getIdCurso()) == "" || $this->getIdCurso() == 0){
				$retorno = array('codigo' => 4,
			 				     'msg'    => 'ID do curso não informado ou zerado.');
			}elseif ($this->getEstatus() != "D" && $this->getEstatus() != ""){
				$retorno = array('codigo' => 4,
			 				     'msg'    => 'Status não condiz com o permitido.');
            }elseif(strlen($this->getNome()) == 0){
                $retorno = array('codigo' => 5,
                                 'msg'    => 'Nome do aluno não informado.');	
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_aluno');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_aluno->inserirAluno($this->getRA(), $this->getIdCurso(), $this->getNome(), 
				                                        $this->getEstatus());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de inserção, verifique.');			
		}

		echo json_encode($retorno);
	
    }  

	//Consultar Aluno(s)
    public function consultarAluno(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("ra"      => '0',
                       "idCurso" => '0',
					   "nome"    => '0', 
		               "estatus" => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
            $this->setRA($resultado->ra);
			$this->setIdCurso($resultado->idCurso);
			$this->setNome($resultado->nome);
			$this->setEstatus($resultado->estatus);

			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - ALUNO CONSULTADO CORRETAMENTE (MODEL)
				02 - NÃO HOUVE RETORNO NA CONSULTA DOS DADOS (MODEL)				
				04 - STATUS NÃO CONDIZ COM O PERMITIDO (CONTROLLER)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

			if ($this->getEstatus() != "D" && $this->getEstatus() != ""){
				$retorno = array('codigo' => 4,
			 				     'msg'    => 'Status não condiz com o permitido.');
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_aluno');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_aluno->consultarAluno($this->getRA(), $this->getIdCurso(), $this->getNome(),
				                                          $this->getEstatus());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de consulta, verifique.');			
		}

		echo json_encode($retorno);
	
    }

	//Alterar Curso
    public function alterarAluno(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("ra"      => '0',
                       "idCurso" => '0',
					   "nome"    => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
            $this->setRA($resultado->ra);
			$this->setIdCurso($resultado->idCurso);
			$this->setNome($resultado->nome);		

			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - ALUNO ALTERADO CORRETAMENTE (MODEL)
				02 - HOUVE ALGUM PROBLEMA DE ALTERAÇÃO DOS DADOS (MODEL)			
				03 - ID DO CURSO NÃO INFORMADO OU ZERADO (CONTROLLER)
				04 - NOME DO ALUNO NÃO INFORMADO (CONTROLLER)
				05 - O ID DO CURSO INFORMADO NÃO ESTA CADASTRADO NA BASE DE DADOS (MODEL)
                06 - ALUNO NÃO CADASTRADO NA BASE DE DADOS (MODEL)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

            if(strlen($this->getRA()) == 0){
                $retorno = array('codigo' => 3,
			 				     'msg'    => 'RA do aluno não informado.');
            }elseif ($this->getIdCurso() == "" || $this->getIdCurso() == 0){
				$retorno = array('codigo' => 3,
			 				     'msg'    => 'ID do curso não informado ou zerado.');
			}elseif(strlen($this->getNome()) == 0){
				$retorno = array('codigo' => 4,
			 				     'msg'    => 'Nome do aluno não informado.');			
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_aluno');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_aluno->alterarAluno($this->getRA(), $this->getIdCurso(), $this->getNome());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de consulta, verifique.');			
		}

		echo json_encode($retorno);
	
    }

	//"Apagar" Aluno
    public function apagarAluno(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("ra" => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
			$this->setRA($resultado->ra);

			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - ALUNO "APAGADO" CORRETAMENTE (MODEL)
				02 - HOUVE ALGUM PROBLEMA NA "EXCLUSÃO" DO ALUNO (MODEL)			
				03 - RA DO ALUNO NÃO INFORMADO OU ZERADO (CONTROLLER)				
				04 - RA DO ALUNO INFORMADO NÃO ESTA CADASTRADO NA BASE DE DADOS (MODEL)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

            if(strlen($this->getRA()) == 0){
				$retorno = array('codigo' => 3,
                                 'msg'    => 'RA do aluno não informado.');
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_aluno');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_aluno->apagarAluno($this->getRA());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de consulta, verifique.');			
		}

		echo json_encode($retorno);
	
    }
}
