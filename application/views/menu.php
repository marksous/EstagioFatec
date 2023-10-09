<?php 

  // session_start();
  // $idUsuario = $_SESSION[id_usuario];
  // if (!$_SESSION['logado']) {
  //   header('location:../index.php');
  // }

?>

<!DOCTYPE html>
<html>

<head>
  <title>Menu Principal</title>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.min.css"); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url("assets/css/sweetalert2.min.css"); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url("assets/css/style.css"); ?>" type="text/css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
  integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" 
  crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    .content {
      height: 78vh;
      width: 85vw;
      overflow-y: auto;
      padding: 5vh 1vw;
    }
  </style>
</head>

<body>
  <div class="container-fluid d-flex align-items-center justify-content-center bg-primary">
    <div class="content bg-light">

      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#subnav">
          <span class="navbar-toggler-icon"></span>      
        </button>
        <div class="collapse navbar-collapse" id="subnav">
          <ul class="navbar-nav">
            <a class="nav-link active" href="Aluno.php">
              Alunos
            </a>
            <a class="nav-link" href="Professor.php">
              Professor
            </a>
            <a class="nav-link" href="Curso.php">
              Curso
            </a>
            
            <a class="nav-link" href="../index.php">
              Sair
            </a>
          </ul>
        </div>
      </nav>

      <h1>Boas vindas ao sistema!</h1>
      <a href="cadastro.php" class="btn btn-success text-light">
        Novo usuario
      </a>

      <div class="table-responsive">
        <table class="table table-condensed table-bordered">
          <thead>
            <tr>
              <th>RA</th>
              <th>Nome</th>
            </tr>
          </thead>
          <tbody id="conteudoUsuario"></tbody>
        </table>
      </div>

    </div>
  </div>
</body>

<script src="<?php echo base_url("assets/js/jquery-3.6.0.min.js"); ?>" type="text/javascript"></script>
<script src="<?php echo base_url("assets/js/sweetalert2.all.min.js"); ?>" type="text/javascript"></script>
<script src="<?php echo base_url("assets/js/bootstrap.min.js"); ?>" type="text/javascript"></script>

<script type="text/javascript" charset="utf-8">
  async function carregarDados(){
    const config = {
        method: "post",
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          ra:'',
          idCurso:'0',
					nome:'', 
		      estatus:''
        })
      };
    const request = await fetch('../aluno/consultarAluno', config);
    const response = await request.json();

    alert(`status: ${JSON.stringify(response)}`);
    
    for (const item of response.dados) {
      alert(item.ra);
        conteudoUsuario.innerHTML += `
          <tr class="linha-tabela" style="border: 5px solid red">
            <td>${item.ra}</td>
            <td>${item.nome}</td>
          </tr>`;
    }
  }


$(document).ready(function() {
  carregarDados();
});
</script>

</html>