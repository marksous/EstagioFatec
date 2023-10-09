<?php
defined('BASEPATH') or exit('No direct script access allowed');

//Incluir a classe que precisaremos instanciar 
include_once("M_curso.php");

class M_professor extends CI_Model {
	public function inserirProfessor($idProfessor, $nome, $usuario, $senha, $estatus){
        //verificar se o aluno já se encontra na base de dados
        $retornoProfessor = $this->consultarSoProfessor($idProfessor);

        if ($retornoProfessor['codigo'] == 2){
            //Instrução de inserção dos dados
            //Na senha utilizaremos a criptografia MD5
            $sql = "insert into professor (id_professor, nome, usuario, senha, estatus)
                    values ($idProfessor, '$nome', '$usuario', md5('$senha'),  '$estatus')";
            
            $this->db->query($sql);

            //Verificar se a inserção ocorreu com sucesso
            if($this->db->affected_rows() > 0){
                $dados = array('codigo' => 1,
                                'msg'   => 'Professor cadastrado corretamente.');			
            }else{
                $dados = array('codigo' => 2,
                            'msg'    => 'Houve algum problema na inserção na tabela de professor.');
            }
        }else{
            $dados = array('codigo' => 8,
                            'msg'   => 'Professor já se encontra cadastrado na base de dados.');
        }
        
		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}

	public function consultarProfessor($idProfessor, $nome, $usuario, $estatus){
		//--------------------------------------------------
		//Função que servirá para quatro tipos de consulta:
		//  * Para todos os professores;
		//  * Para um determinado professor;
		//  * Para um determinado Status;
		//  * Para nome do professor;
		//--------------------------------------------------

		//Query para consultar dados de acordo com parâmetros passados		
		$sql = "select * from professor 
		        where estatus = '$estatus' ";

        if(($idProfessor) != ''){
            $sql = $sql . "and id_professor = $idProfessor ";
        }                

		if(trim($usuario) != '') {
			$sql = $sql . "and usuario = '$usuario' ";
		}

		if(trim($nome) != ''){
			$sql = $sql . "and nome like '%$nome%' ";
		}

		$retorno = $this->db->query($sql);
		
		//Verificar se a consulta ocorreu com sucesso
		if($retorno->num_rows() > 0){
			$dados = array('codigo' => 1,
		                   'msg' => 'Consulta efetuada com sucesso.',
		                   'dados' => $retorno->result());
			
		}else{
			$dados = array('codigo' => 2,
		                   'msg' => 'Dados não encontrados.');
		}
		
		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}

	public function consultarSoProfessor($idProfessor){
		//--------------------------------------------------
		//Função que servirá somente para verificar se o curso está na base de dados		

		//Query para consultar dados de acordo com parâmetros passados		
		$sql = "select * from professor 
		        where id_professor = '$idProfessor' 
				  and estatus  = ''";		

		$retorno = $this->db->query($sql);
		
		//Verificar se a consulta ocorreu com sucesso
		if($retorno->num_rows() > 0){
			$dados = array('codigo' => 1,
		                   'msg'    => 'Consulta efetuada com sucesso.');
			
		}else{
			$dados = array('codigo' => 2,
		                   'msg'    => 'Dados não encontrados.');
		}
		
		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}

	public function alterarProfessor($idProfessor, $nome, $usuario, $senha){
		    //verificar se o professor já se encontra na base de dados
        $retornoProfessor = $this->consultarSoProfessor($idProfessor);

        if ($retornoProfessor['codigo'] == 1){

            //Instrução de inserção dos dados
            $sql = "update professor set nome = '$nome', usuario = '$usuario', senha = md5('$senha')
                    where id_professor = $idProfessor";
            
            $this->db->query($sql);

            //Verificar se a atualização ocorreu com sucesso
            if($this->db->affected_rows() > 0){
                $dados = array('codigo' => 1,
                                'msg'    => 'Dados do professor atualizados corretamente.');
                
            }else{
                $dados = array('codigo' => 2,
                                'msg'   => 'Houve algum problema na atualização do professor.');
            }            
		}else{
			$dados = array('codigo' => 5,
						   'msg'    => 'O ID do professor passado não está cadastrado na base de dados.');
		}
		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}

	public function apagarProfessor($idProfessor){		
		//Verificar se o professor está cadastrado na base de dados
		$retornoProfessor = $this->consultarSoProfessor($idProfessor);

		if ($retornoProfessor['codigo'] == 1){

			//Instrução de inserção dos dados
			$sql = "update professor set estatus = 'D'
				    where id_professor = $idProfessor";
			
			$this->db->query($sql);

			//Verificar se a atualização ocorreu com sucesso
			if($this->db->affected_rows() > 0){
				$dados = array('codigo' => 1,
							   'msg'    => 'Professor desativado corretamente.');
				
			}else{
				$dados = array('codigo' => 2,
							    'msg'   => 'Houve algum problema na desativação do professor.');
			}
		}else{
			$dados = array('codigo' => 4,
						   'msg'    => 'O ID do professor informado não está cadastrado na base de dados.');
		}
		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}	

    public function vincularProfessorCurso($idProfessor, $idCurso){	
		//Verificar se o curso está cadastrado na base de dados
		//realizar a instância do objeto curso
        $curso = new M_Curso();  	

		//Verificar se o professor está cadastrado na base de dados
		$retornoProfessor = $this->consultarSoProfessor($idProfessor);
	
		if ($retornoProfessor['codigo'] == 1){
			
			//Consultar curso
		 	$retornoCurso  = $curso->consultarSoCurso($idCurso);
			
		 	if ($retornoCurso['codigo'] == 1){

		 		$retornoVinculo = $this->consultarVinculoProfessor($idProfessor, $idCurso);
				if ($retornoVinculo['codigo'] == 2){

					//Instrução de inserção dos dados
					$sql = "insert into cursoprof (id_professor, id_curso) values
							($idProfessor, $idCurso)";
					
					$this->db->query($sql);

					//Verificar se a atualização ocorreu com sucesso
					if($this->db->affected_rows() > 0){
						$dados = array('codigo' => 1,
									'msg'    => 'Curso e professor vinculados corretamente.');
						
					}else{
						$dados = array('codigo' => 2,
										'msg'   => 'Houve algum problema no vínculo.');
					}
				}else{
					$dados = array('codigo' => 7,
								   'msg'    => 'Vinculo já cadastrado.');
				}
			}else{
				$dados = array('codigo' => 6,
								'msg'    => 'Curso não cadastrado na base dados.');
			}

		}else{
			$dados = array('codigo' => 6,
							'msg'    => 'Professor não cadastrado na base dados.');
		}
		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}

	private function consultarVinculoProfessor($idProfessor, $idCurso){
		//Query para consultar dados de acordo com parâmetros passados		
		$sql = "select * from cursoprof 
				where id_professor = $idProfessor 
				  and id_curso = $idCurso
				  and estatus  = ''";		

		$retorno = $this->db->query($sql);

		//Verificar se a consulta ocorreu com sucesso
		if($retorno->num_rows() > 0){
			$dados = array('codigo' => 1,
					'msg'    => 'Consulta efetuada com sucesso.');

		}else{
			$dados = array('codigo' => 2,
						   'msg'    => 'Dados não encontrados.');
		}

		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}

	public function desativarVinculoProfessor($idProfessor, $idCurso){
		//Verificar se o curso está cadastrado na base de dados
		//realizar a instância do objeto curso
        $curso = new M_Curso();  	
	
		//Verificar se o professor está cadastrado na base de dados
		$retornoProfessor = $this->consultarSoProfessor($idProfessor);

		if ($retornoProfessor['codigo'] == 1){
			
		 	//Consultar curso
		  	$retornoCurso  = $curso->consultarSoCurso($idCurso);
			
		  	if ($retornoCurso['codigo'] == 1){

		  		$retornoVinculo = $this->consultarVinculoProfessor($idProfessor, $idCurso);
		 		if ($retornoVinculo['codigo'] == 1){

		 			//Instrução de inserção dos dados
		 			$sql = "update cursoprof set estatus = 'D'
		 			        where id_professor = $idProfessor
		 					  and id_curso =  $idCurso";
					
		 			$this->db->query($sql);

		 			//Verificar se a atualização ocorreu com sucesso
		 			if($this->db->affected_rows() > 0){
		 				$dados = array('codigo' => 1,
		 							'msg'    => 'Vinculo desativado corretamente.');
						
		 			}else{
		 				$dados = array('codigo' => 2,
		 								'msg'   => 'Houve algum problema na desativação.');
		 			}
		 		}else{
		 			$dados = array('codigo' => 7,
		 						   'msg'    => 'Vinculo não cadastrado.');
		 		}
		 	}else{
		 		$dados = array('codigo' => 6,
		 						'msg'    => 'Curso não cadastrado na base dados.');
		 	}

		}else{
		 	$dados = array('codigo' => 6,
		 					'msg'    => 'Professor não cadastrado na base dados.');
		}
		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}

	public function logarProfessor($usuarioProfessor, $senhaProfessor){
		//--------------------------------------------------
		//Função que servirá para quatro tipos de consulta:
		//  * Para todos os professores;
		//  * Para um determinado professor;
		//  * Para um determinado Status;
		//  * Para nome do professor;
		//--------------------------------------------------

		//Query para consultar dados de acordo com parâmetros passados		
		$sql = "select * from professor 
		        where usuario = '$usuarioProfessor' 
				  and senha = md5('$senhaProfessor')";

        $retorno = $this->db->query($sql);
		
		//Verificar se a consulta ocorreu com sucesso
		if($retorno->num_rows() > 0){
			$dados = array('codigo' => 1,
		                   'msg' => 'Consulta efetuada com sucesso.');
			
		}else{
			$dados = array('codigo' => 2,
		                   'msg' => 'Dados não encontrados.');
		}
		
		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}
	
}

