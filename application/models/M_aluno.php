<?php
defined('BASEPATH') or exit('No direct script access allowed');

//Incluir a classe que precisaremos instanciar 
include_once("M_curso.php");

class M_aluno extends CI_Model {
	public function inserirAluno($ra, $idCurso, $nome, $estatus){
        //verificar se o curso está cadastrado
        //realizar a instância do objeto curso
        $curso = new M_Curso();        

        //chamar o método de verificação
        $retornoCurso = $curso->consultarSoCurso($idCurso);

		if ($retornoCurso['codigo'] == 1){
            //verificar se o aluno já se encontra na base de dados
            $retornoAluno = $this->consultarSoAluno($ra);

            if ($retornoAluno['codigo'] == 2){
                //Instrução de inserção dos dados
                $sql = "insert into aluno (ra, id_curso, nome, estatus)
                        values ('$ra', $idCurso, '$nome',  '$estatus')";
                
                $this->db->query($sql);

                //Verificar se a inserção ocorreu com sucesso
                if($this->db->affected_rows() > 0){
                    $dados = array('codigo' => 1,
                                    'msg'   => 'Aluno cadastrado corretamente.');			
                }else{
                    $dados = array('codigo' => 2,
                                'msg'    => 'Houve algum problema na inserção na tabela de aluno.');
                }
            }else{
                $dados = array('codigo' => 8,
                                'msg'   => 'Aluno já se encontra cadastrado na base de dados.');
            }
        }else{
            $dados = array('codigo' => 7,
                            'msg'    => 'Curso informado não cadastrado na base de dados.');
        }
		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}

	public function consultarAluno($ra, $idCurso, $nome, $estatus){
		//--------------------------------------------------
		//Função que servirá para quatro tipos de consulta:
		//  * Para todos os aluno;
		//  * Para um determinado aluno;
		//  * Para um determinado Status;
		//  * Para nome do aluno;
		//--------------------------------------------------

		//Query para consultar dados de acordo com parâmetros passados		
		$sql = "select * from aluno 
		        where estatus = '$estatus' ";

        if(($ra) != ''){
            $sql = $sql . "and ra = '$ra' ";
        }                

		if(trim($idCurso) != '' && trim($idCurso) != '0') {
			$sql = $sql . "and id_curso = '$idCurso' ";
		}

		if(trim($nome) != ''){
			$sql = $sql . "and nome like '%$nome%' ";
		}

		$retorno = $this->db->query($sql);
		
		//Verificar se a consulta ocorreu com sucesso
		if($retorno->num_rows() > 0){
			//$linha = $retorno->row();
			echo $retorno->row("nome");
			exit;
			
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

	public function consultarSoAluno($ra){
		//--------------------------------------------------
		//Função que servirá somente para verificar se o curso está na base de dados		

		//Query para consultar dados de acordo com parâmetros passados		
		$sql = "select * from aluno 
		        where ra = '$ra' 
				  and estatus  = ''";		

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

	public function alterarAluno($ra, $idCurso, $nome){
		//Verificar se o curso está cadastrado na base de dados
		//realizar a instância do objeto curso
        $curso = new M_Curso();        

        //chamar o método de verificação
        $retornoCurso = $curso->consultarSoCurso($idCurso);

		if ($retornoCurso['codigo'] == 1){
            //verificar se o aluno já se encontra na base de dados
            $retornoAluno = $this->consultarSoAluno($ra);

            if ($retornoAluno['codigo'] == 1){

			    //Instrução de inserção dos dados
                $sql = "update aluno set nome = '$nome', id_curso = $idCurso
                        where ra = $ra";
                
                $this->db->query($sql);

                //Verificar se a atualização ocorreu com sucesso
                if($this->db->affected_rows() > 0){
                    $dados = array('codigo' => 1,
                                   'msg'    => 'Dados do aluno atualizados corretamente.');
                    
                }else{
                    $dados = array('codigo' => 2,
                                    'msg'   => 'Houve algum problema na atualização do aluno.');
                }
            }else{
                $dados = array('codigo' => 6,
                               'msg'    => 'O RA informado não está cadastrado na base de dados.');
            }
		}else{
			$dados = array('codigo' => 5,
						   'msg'    => 'O ID do curso passado não está cadastrado na base de dados.');
		}
		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}

	public function apagarAluno($ra){		
		//Verificar se o curso está cadastrado na base de dados
		$retornoAluno = $this->consultarSoAluno($ra);

		if ($retornoAluno['codigo'] == 1){

			//Instrução de inserção dos dados
			$sql = "update aluno set estatus = 'D'
				    where ra = $ra";
			
			$this->db->query($sql);

			//Verificar se a atualização ocorreu com sucesso
			if($this->db->affected_rows() > 0){
				$dados = array('codigo' => 1,
							   'msg'    => 'Aluno desativado corretamente.');
				
			}else{
				$dados = array('codigo' => 2,
							    'msg'   => 'Houve algum problema na desativação do aluno.');
			}
		}else{
			$dados = array('codigo' => 4,
						   'msg'    => 'O RA informado não está cadastrado na base de dados.');
		}
		//Envia o array $dados com as informações tratadas
		//acima pela estrutura de decisão if
		return $dados;
	}
	
}

