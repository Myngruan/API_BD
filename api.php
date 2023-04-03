<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Sistema de cadastro e busca</title>
</head>
<body>
<div class="container">
    <h1>Cadastro de usuário</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required><br><br>
        <label for="data_nascimento">Data de Nascimento:</label>
        <input type="date" id="data_nascimento" name="data_nascimento" max="<?php echo date('Y-m-d'); ?>" required>
        <input type="submit" value="Cadastrar">
    </form>
    <h1>Busca usuário </h1>
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="busca_cpf">CPF: </label>
        <input type="text" id="busca_cpf" name="cpf" required>
        <input type="submit" value="Buscar">
    </form>
    <br></br>
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="busca_nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <input type="submit" value="Buscar">
    </form>
</div>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['data_nascimento'];

    $usuarios = [
        "nome" => $nome,
        "cpf" => $cpf,
        "data_nascimento" => $data_nascimento
    ];

    cadastrar_usuario($usuarios);
}elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_GET['cpf'])) {
        $cpf = $_GET['cpf'];
        busca_usuario_cpf($cpf);
    }elseif (isset($_GET['nome'])) {
        $usuarios = busca_usuarios_nome($_GET['nome']);
        if (count($usuarios) == 0) {
            echo '<p>Usuário não encontrado!</p>';
        } else {
            echo  '<pre>' . json_encode($usuarios, JSON_PRETTY_PRINT) . '</pre>';
        }
    }
}

function cadastrar_usuario($dados_usuario){
    $usuarios = array();
    if (file_exists('usuarios.json')) {
        $usuarios = json_decode(file_get_contents('usuarios.json'), true);
    }
    array_push($usuarios, $dados_usuario);
    file_put_contents('usuarios.json', json_encode($usuarios, JSON_PRETTY_PRINT));
    echo '<pre>' . json_encode(array('message' => 'Usuario cadastrado com sucesso')) . '</pre>';
    return;
}

function busca_usuario_cpf($cpf){
    $usuarios = array();
    if(file_exists('usuarios.json')){
        $usuarios = json_decode(file_get_contents('usuarios.json'), true);
    }
    for($i = 0; $i < count($usuarios); $i++) {
        if ($usuarios[$i]['cpf'] == $cpf) {
            $usuario = $usuarios[$i];
            echo '<pre>' . json_encode($usuario, JSON_PRETTY_PRINT) . '</pre>';
            return;
        }
    }
    
    echo '<p>Usuário não encontrado!</p>';
}

function busca_usuarios_nome($nome){
    if(file_exists('usuarios.json')){
        $usuarios = json_decode(file_get_contents('usuarios.json'), true);

        $usuarios_filtrados = array_filter($usuarios, function($usuario) use ($nome) {
            return strpos(strtolower($usuario['nome']), strtolower($nome)) !== false;
        });

        return $usuarios_filtrados;
    }
    return array();
}
?>
</body>
</html>

