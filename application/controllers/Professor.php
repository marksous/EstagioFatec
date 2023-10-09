<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Professor extends CI_Controller{

    //Atributos da classe
    private $json;
	private $resultado;	

    //Atributos privados da classe
	private $idProfessor;
    private $nome;
    private $usuario;  
    private $senha;
    private $estatus;
	private $idCurso; 
	       
	//Getters dos atributos
    public function getIdProfessor(){
		return $this->idProfessor;
	}
    
    public function getNome(){
        return $this->nome;
    }

    public function getUsuario(){
		return $this->usuario;
	}

    public function getSenha(){
		return $this->senha;
	}

	public function getEstatus(){
		return $this->estatus;
	}

	public function getIdCurso(){
		return $this->estatus;
	}

    //Setters dos atributos
    public function setIdProfessor($idProfessorFront){
		$this->idProfessor = $idProfessorFront;
	}

    public function setNome($nomeFront){
        $this->nome = $nomeFront;
    }

	public function setUsuario($usuarioFront){
		$this->usuario = $usuarioFront;
	}

    public function setSenha($senhaFront){
		$this->senha = $senhaFront;
	}

	public function setEstatus($estatusFront){
		$this->estatus = $estatusFront;
	}   
	
	public function setIdCurso($idCursoFront){
		$this->estatus = $idCursoFront;
	}

    //Métodos da classe
	//Cadastrar o Professor
    public function inserirProfessor(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("idProfessor" => '0', 
		               "nome"        => '0',
                       "usuario"     => '0',
                       "senha"       => '0',
                       "estatus"     => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
			$this->setIdProfessor($resultado->idProfessor);            
            $this->setNome($resultado->nome);
            $this->setUsuario($resultado->usuario);
            $this->setSenha($resultado->senha);
			$this->setEstatus($resultado->estatus);
            
			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - PROFESSOR CADASTRADO CORRETAMENTE (MODEL)
				02 - HOUVE ALGUM PROBLEMA DE SALVAMENTO DOS DADOS (MODEL)
                03 - ID DO PROFESSOR NÃO INFORMADO OU ZERADO (CONTROLLER)				
                04 - NOME NÃO INFORMADO (CONTROLLER)
				05 - USUÁRIO NÃO INFORMADO (CONTROLLER)
                06 - SENHA NÃO INFORMADA (CONTROLLER)
                07 - STATUS NÃO CONDIZ COM O PERMITIDO (CONTROLLER)                
                08 - PROFESSOR JÁ SE ENCONTRA CADASTRADO NA BASE (MODEL)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)                
			*/

            if (trim($this->getIdProfessor()) == "" || $this->getIdProfessor() == 0){
				$retorno = array('codigo' => 3,
			 				     'msg'    => 'ID do professor não informado ou zerado.');
			}elseif (trim($this->getNome()) == "" || $this->getNome() == 0){
				$retorno = array('codigo' => 4,
			 				     'msg'    => 'Nome do professor não informado.');
            }elseif (trim($this->getUsuario()) == ""){
                $retorno = array('codigo' => 5,
                                    'msg'    => 'Usuário não informado.');                    
            }elseif (trim($this->getSenha()) == ""){
                $retorno = array('codigo' => 6,
                                    'msg'    => 'Senha não informada.');                                                                 
			}elseif ($this->getEstatus() != "D" && $this->getEstatus() != ""){
				$retorno = array('codigo' => 7,
			 				     'msg'    => 'Status não condiz com o permitido.');
            
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_professor');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_professor->inserirProfessor($this->getIdProfessor(), $this->getNome(), 
				           $this->getUsuario(), $this->getSenha(), $this->getEstatus());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de inserção, 
							              verifique.');			
		}

		echo json_encode($retorno);
	
    }  

	//Consultar Professor
    public function consultarProfessor(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("idProfessor" => '0', 
		               "nome"        => '0',
                       "usuario"     => '0',
                       "estatus"     => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
			$this->setIdProfessor($resultado->idProfessor);            
            $this->setNome($resultado->nome);
            $this->setUsuario($resultado->usuario);            
			$this->setEstatus($resultado->estatus);

			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - PROFESSOR CONSULTADO CORRETAMENTE (MODEL)
				02 - NÃO HOUVE RETORNO NA CONSULTA DOS DADOS (MODEL)				
				04 - STATUS NÃO CONDIZ COM O PERMITIDO (CONTROLLER)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

			if ($this->getEstatus() != "D" && $this->getEstatus() != ""){
				$retorno = array('codigo' => 4,
			 				     'msg'    => 'Status não condiz com o permitido.');
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_professor');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_professor->consultarProfessor($this->getIdProfessor(), $this->getNome(), 
				           $this->getUsuario(), $this->getEstatus());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de consulta, 
							              verifique.');			
		}

		echo json_encode($retorno);
	
    }

	// //Alterar Professor
    public function alterarProfessor(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("idProfessor" => '0',
                       "nome"        => '0',
					   "usuario"     => '0',
                       "senha"       => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
            $this->setIdProfessor($resultado->idProfessor);
			$this->setNome($resultado->nome);
			$this->setUsuario($resultado->usuario);		
            $this->setSenha($resultado->senha);		

			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - PROFESSOR ALTERADO CORRETAMENTE (MODEL)
				02 - HOUVE ALGUM PROBLEMA DE ALTERAÇÃO DOS DADOS (MODEL)			
				03 - ID DO PROFESSOR NÃO INFORMADO OU ZERADO (CONTROLLER)
				04 - NOME DO PROFESSOR NÃO INFORMADO (CONTROLLER)
                05 - USUARIO NÃO INFORMADO (CONTROLLER)	
                06 - SENHA NÃO INFORMADA (CONTROLLER)			
                07 - PROFESSOR NÃO CADASTRADO NA BASE DE DADOS (MODEL)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

            if(strlen($this->getIdProfessor()) == 0){
                $retorno = array('codigo' => 3,
			 				     'msg'    => 'ID do professor não informado.');
            }elseif(strlen($this->getNome()) == 0){
				$retorno = array('codigo' => 4,
			 				     'msg'    => 'Nome do professor não informado.');			
            }elseif(strlen($this->getUsuario()) == 0){
                $retorno = array('codigo' => 5,
                                 'msg'    => 'Usuario não informado.');			
            }elseif(strlen($this->getSenha()) == 0){
                $retorno = array('codigo' => 6,
                                 'msg'    => 'Senha não informada.');			
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_professor');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_professor->alterarProfessor($this->getIdProfessor(), $this->getNome(), 
				           $this->getUsuario(), $this->getSenha());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método 
							              de consulta, verifique.');			
		}

		echo json_encode($retorno);
	
    }

	//"Apagar" Professor
    public function apagarProfessor(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("idProfessor" => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
			//Fazendo os setters
			$this->setIdProfessor($resultado->idProfessor);

			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - PROFESSOR "APAGADO" CORRETAMENTE (MODEL)
				02 - HOUVE ALGUM PROBLEMA NA "EXCLUSÃO" DO PROFESSOR (MODEL)			
				03 - ID DO PROFESSOR NÃO INFORMADO OU ZERADO (CONTROLLER)				
				04 - ID DO PROFESSOR INFORMADO NÃO ESTA CADASTRADO NA BASE DE DADOS (MODEL)
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

            if(strlen($this->getIdProfessor()) == 0){
				$retorno = array('codigo' => 3,
                                 'msg'    => 'ID do professor não informado.');
			}else{			
				//Realizo a instância da Model
				$this->load->model('M_professor');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_professor->apagarProfessor($this->getIdProfessor());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de consulta, 
							              verifique.');			
		}

		echo json_encode($retorno);
	
    }

    //Vincular Professor ao Curso
    public function vincularProfessorCurso(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("idProfessor" => '0',
                       "idCurso"    => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){          
			//Fazendo os setters
			$this->setIdProfessor($resultado->idProfessor);
			$this->setIdCurso($resultado->idCurso);
            
			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - PROFESSOR VINCULADO CORRETAMENTE (MODEL)
				02 - HOUVE ALGUM PROBLEMA NA VINCULAÇÃO DO PROFESSOR (MODEL)			
				03 - ID DO PROFESSOR NÃO INFORMADO OU ZERADO (CONTROLLER)				
				04 - ID DO PROFESSOR INFORMADO NÃO ESTÁ CADASTRADO NA BASE DE DADOS (MODEL)
                05 - ID DO CURSO NÃO INFORMADO OU ZERADO (CONTROLLER)
                06 - ID DO CURSO NÃO ESTÁ CADASTRADO NA BASE DE DADOS (MODEL)
				07 - VÍNCULO JÁ CADASTRADO
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

            if(strlen($this->getIdProfessor()) == 0){
				$retorno = array('codigo' => 3,
                                 'msg'    => 'ID do professor não informado.');
            }elseif(strlen($this->getIdCurso() == 0)){
                $retorno = array('codigo' => 5,
                                 'msg'    => 'ID do curso não informado.');
            }else{			
				//Realizo a instância da Model
				$this->load->model('M_professor');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_professor->vincularProfessorCurso($this->getIdProfessor(), 
				           $this->getIdCurso());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de 
							              consulta, verifique.');			
		}

		echo json_encode($retorno);
	
    }

	//Vincular Professor ao Curso
    public function desativarVinculoProfessor(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("idProfessor" => '0',
                       "idCurso"    => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
            //verificar se o curso está cadastrado
            //realizar a instância do objeto curso
            

			//Fazendo os setters
			$this->setIdProfessor($resultado->idProfessor);
			$this->setIdCurso($resultado->idCurso);
            
			/*
				** VALIDAÇÕES NECESSÁRIAS RETORNOS POSSÍVEIS **
				01 - PROFESSOR VINCULADO CORRETAMENTE (MODEL)
				02 - HOUVE ALGUM PROBLEMA NA VINCULAÇÃO DO PROFESSOR (MODEL)			
				03 - ID DO PROFESSOR NÃO INFORMADO OU ZERADO (CONTROLLER)				
				04 - ID DO PROFESSOR INFORMADO NÃO ESTÁ CADASTRADO NA BASE DE DADOS (MODEL)
                05 - ID DO CURSO NÃO INFORMADO OU ZERADO (CONTROLLER)
                06 - ID DO CURSO NÃO ESTÁ CADASTRADO NA BASE DE DADOS (MODEL)
				07 - VÍNCULO JÁ CADASTRADO
				99 - OS CAMPOS INFORMADOS PELO FRONTEND NÃO REPRESENTAM O MÉTODO (CONTROLLER)
			*/

            if(strlen($this->getIdProfessor()) == 0){
				$retorno = array('codigo' => 3,
                                 'msg'    => 'ID do professor não informado.');
            }elseif(strlen($this->getIdCurso() == 0)){
                $retorno = array('codigo' => 5,
                                 'msg'    => 'ID do curso não informado.');
            }else{			
				//Realizo a instância da Model
				$this->load->model('M_professor');
	
				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_professor->desativarVinculoProfessor($this->getIdProfessor(), $this->getIdCurso());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método de 
							              consulta, verifique.');			
		}

		echo json_encode($retorno);
	
    }

	 //Login do Professor 
	 public function logarProfessor(){
		//Dados vindos do FrontEnd		
        $json = file_get_contents('php://input');		
        $resultado = json_decode($json);

		//Array com os dados que deverão vir do Front
		$lista = array("usuarioProfessor" => '0',
                        "senhaProfessor"  => '0');

		//Verificação dos parâmetros passados na Helper
		if (verificarParam($resultado, $lista) == 1){
            //Fazendo os setters
			$this->setUsuario($resultado->usuarioProfessor);
			$this->setSenha($resultado->senhaProfessor);

			if(strlen($this->getUsuario()) == 0){
                $retorno = array('codigo' => 3,
			 				     'msg'    => 'Usuário do professor não informado.');
            }elseif(strlen($this->getSenha()) == 0){
				$retorno = array('codigo' => 4,
			 				     'msg'    => 'Senha do professor não informada.');			
            }else{			
				//Realizo a instância da Model
				$this->load->model('M_professor');

				//Atributo $retorno recebe array com informações da validação do acesso
				$retorno = $this->M_professor->logarProfessor($this->getUsuario(), $this->getSenha());	
			}				
		}else{
			$retorno = array('codigo' => 99,
		                     'msg'    => 'Os campos vindos do FrontEnd não representam o método 
							              de consulta, verifique.');			
		}

		echo json_encode($retorno);
		
	}
}