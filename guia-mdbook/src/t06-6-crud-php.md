# O que é DAO?

DAO (Data Access Object) é um padrão de projeto (design pattern) utilizado para abstrair e encapsular o acesso a bancos de dados. Em PHP, a DAO é responsável por gerenciar a comunicação entre a aplicação e o banco de dados, separando a lógica de negócios da lógica de acesso aos dados.

Em termos simples, o DAO cria uma camada intermediária entre o código da aplicação e as consultas SQL, tornando o código mais modular, reutilizável e fácil de manter.

## Importância do DAO no PHP

- Separa a lógica de negócios da lógica de acesso ao banco de dados

  - Mantém o código mais organizado, evitando misturar regras de negócio com consultas SQL dentro dos controladores ou das views.

- Facilita a manutenção e escalabilidade

  - Como o acesso ao banco de dados está centralizado, qualquer alteração na estrutura das tabelas ou nas consultas pode ser feita em um único lugar, sem afetar o restante da aplicação.

- Melhora a reutilização do código

  - As operações de CRUD (Create, Read, Update, Delete) podem ser reaproveitadas em diferentes partes da aplicação sem necessidade de reescrever código.

- Aumenta a segurança

  - O uso de prepared statements dentro da DAO ajuda a prevenir ataques de SQL Injection.

- Facilita a troca de banco de dados

  - Se for necessário mudar de MySQL para PostgreSQL, por exemplo, basta modificar a implementação do DAO sem impactar outras partes do sistema.

## Vamos usar o exemplo do nosso projeto

**Arquivo (`database.php`)**

```php
<?php
class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $config = require __DIR__ . '/db-config.php';

        if (!isset($config['database']['host'], $config['database']['port'], $config['database']['username'], $config['database']['password'], $config['database']['dbname'])) {
            throw new Exception("Configuração do banco de dados incompleta");
        }

        $dsn = "mysql:host={$config['database']['host']};port={$config['database']['port']};dbname={$config['database']['dbname']};charset=utf8mb4";

        try {
            $this->conn = new PDO($dsn, $config['database']['username'], $config['database']['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            throw new Exception("Falha na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
```

_Aqui está a conexão com o banco de dados, utilizando o mesmo banco mencionado anteriormente como exemplo._

**Arquivo (`user-dao.php`)**

```php
<?php

require_once __DIR__ . '/../../database.php';

class UserDao
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createUser($username, $email, $password_hash, $phone)
    {
        $query = "INSERT INTO users (username, email, password_hash, phone) VALUES (:username, :email, :password_hash, :phone)";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([':username' => $username,':email' => $email,':password_hash' => $password_hash, ':phone' => $phone]);
    }

    public function checkEmailExists($email): bool
    {
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ? true : false;
    }

    public function getUserAuthDataByEmail($email)
    {
        $query = "SELECT id, username, email, password_hash FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function getUserProfileById($id)
    {
        $query = "SELECT id, username, email, phone, bio, profile_pic_url, count_followers, count_following FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    public function getUserNameById($id)
    {

        $query = "SELECT username FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getUserProfilePhotoById($id)
    {
        $query = "SELECT profile_pic_url FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateUser($username, $email, $bio, $phone, $id): bool
    {
        $query = "UPDATE users SET username = :username, email = :email, bio = :bio, phone = :phone WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([":username"  => $username,":email" => $email,":bio"=> $bio,":phone" => $phone,":id"=> $id ]);
        return true;
    }
    public function deleteUser($id): bool
    {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);

        return true;
    }

    public function updateProfileImage($id, $profile_pic_url): bool {
        $query = "UPDATE users SET profile_pic_url = :profile_pic_url WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([":profile_pic_url" =>$profile_pic_url,":id" => $id ]);
        return true;
    }


    public function updateProfilePic($profilePicUrl, $id): bool {
        $query = "UPDATE users SET profile_pic_url = :profile_pic_url WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([":profile_pic_url" => $profilePicUrl, ":id" => $id]);
    }

    public function updateUserFollowerCount($userId, $followers_count) {
        $query = "UPDATE users SET count_followers = :followers_count WHERE id = :userId";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':followers_count' => $followers_count,':userId' => $userId]);
    }
}

```

---

### Observações

```php
public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
```

- Inicializa a conexão com o banco de dados, utilizando um singleton da classe `Database`.
- Armazena essa conexão na propriedade `$db`.

---

```php
 public function createUser($username, $email, $password_hash, $phone)
    {
        $query = "INSERT INTO users (username, email, password_hash, phone) VALUES (:username, :email, :password_hash, :phone)";
        $stmt = $this->db->prepare($query);

        return $stmt->execute([':username' => $username,':email' => $email,':password_hash' => $password_hash, ':phone' => $phone]);
    }
```

- Insere um novo usuário na tabela `users`.
- Usa prepared statements para evitar SQL Injection.
- Retorna `true` se a inserção for bem-sucedida, caso contrário, `false`.

---

```php
public function checkEmailExists($email): bool
    {
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ? true : false;
    }
```

- Consulta o banco de dados para verificar se já existe um usuário com o e-mail informado.
- Retorna `true` se encontrar o e-mail, `false` caso contrário.

---

```php
public function getUserAuthDataByEmail($email)
    {
        $query = "SELECT id, username, email, password_hash FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
```

- Retorna os dados necessários para autenticação (ID, username, e-mail e hash da senha) do usuário com o e-mail especificado.

---

```php
public function getUserProfileById($id)
    {
        $query = "SELECT id, username, email, phone, bio, profile_pic_url, count_followers, count_following FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }
```

- Retorna os detalhes do perfil do usuário, incluindo nome, e-mail, telefone, biografia, foto de perfil e contagens de seguidores/seguidos.

---

```php
 public function getUserNameById($id)
    {

        $query = "SELECT username FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
```

- Retorna apenas o nome de usuário com base no ID fornecido.

---

```php
public function getUserProfilePhotoById($id)
    {
        $query = "SELECT profile_pic_url FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
```

- Retorna a URL da foto de perfil do usuário com base no ID fornecido.

---

```php
public function updateUser($username, $email, $bio, $phone, $id): bool
    {
        $query = "UPDATE users SET username = :username, email = :email, bio = :bio, phone = :phone WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([":username"  => $username,":email" => $email,":bio"=> $bio,":phone" => $phone,":id"=> $id ]);
        return true;
    }
```

- Atualiza o nome de usuário, e-mail, biografia e telefone do usuário com base no ID fornecido.
- Retorna `true` se a atualização for bem-sucedida.

---

```php
public function deleteUser($id): bool
    {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);

        return true;
    }
```

- Exclui um usuário da tabela com base no ID fornecido.
- Retorna `true` após a execução da exclusão.

---

```php
public function updateProfileImage($id, $profile_pic_url): bool {
        $query = "UPDATE users SET profile_pic_url = :profile_pic_url WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([":profile_pic_url" =>$profile_pic_url,":id" => $id ]);
        return true;
    }
```

- Atualiza a URL da foto de perfil do usuário com base no ID.
- Retorna `true` se a atualização for bem-sucedida.

---

```php
public function updateUserFollowerCount($userId, $followers_count) {
        $query = "UPDATE users SET count_followers = :followers_count WHERE id = :userId";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':followers_count' => $followers_count,':userId' => $userId]);
    }
```

- Atualiza a quantidade de seguidores de um usuário com base no ID.
- Retorna `true` se a atualização for bem-sucedida.

---

## Principais Funções da Classe

- Gerenciamento de usuários: Criar, atualizar e excluir usuários.
- Autenticação: Buscar dados para login e verificar se o e-mail já está cadastrado.
- Manipulação de perfil: Atualizar e recuperar informações do perfil do usuário, como nome, foto e biografia.
- Relacionamento entre usuários: Atualizar a contagem de seguidores.

## Funcionamento

- Utiliza PDO para interagir com o banco de dados, garantindo segurança contra SQL Injection.
- Utiliza prepared statements para evitar falhas de segurança.
- A conexão com o banco é obtida através da classe Database.

## Controller onde ultiliza a classe `UserDao`

**Arquivo (`update-user.php`)**

```php
<?php
session_start();

require_once __DIR__ . "/../../../dir-config.php";
require_once __DIR__ . "/../../dao/user-dao.php";
require_once __DIR__ . "/../../../database.php";
require_once __DIR__ . "/../../utils/upload-handler.php";

$userDao = new UserDao();

if (!isset($_GET["id"])) {
    die("Parâmetro user_id não informado.");
}

$id = $_GET["id"];
$user = $userDao->getUserProfileById($id);

if (!$user) {
    die("Usuário não encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        $name = trim($_POST['username']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $bio = trim($_POST['bio']);

        if (isset($_FILES['profile_pic_url']) && $_FILES['profile_pic_url']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/../../../uploads/avatars/";
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            $dbUpdateCallback = function ($photoUrl) use ($id, $userDao) {
                return $userDao->updateProfilePic($photoUrl, $id);
            };

            $uploadResult = UploadHandler::handleUpload($_FILES['profile_pic_url'], $uploadDir, $allowedTypes, $dbUpdateCallback);

            if (!$uploadResult['success']) {
                die($uploadResult['error']);
            }
        }

        try {
            $userDao->updateUser($name, $email, $bio, $phone, $id);
            $_SESSION['user_name'] = $name;
            header("Location: " . BASE_URL . "view/profile.php?id=$id");
            exit;
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    } else if (isset($_POST['delete'])) {
        try {
            $userDao->deleteUser($id);
            session_destroy();
            header("Location: " . BASE_URL . "view/login.php");
            exit;
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    } else if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: " . BASE_URL . "view/login.php");
        exit;
    }
}

include __DIR__ . "/../../../view/profile-update.php";
```

---

### Principais usos da UserDao no código

```php
$userDao = new UserDao();
```

- Inicializa a classe `UserDao`, permitindo o acesso ao banco de dados para operações relacionadas a usuários.

---

```php
if (!isset($_GET["id"])) {
    die("Parâmetro user_id não informado.");
}

$id = $_GET["id"];
$user = $userDao->getUserProfileById($id);

if (!$user) {
    die("Usuário não encontrado.");
}
```

- Obtém os dados do perfil do usuário com base no ID passado via `GET`.
- Se o usuário não for encontrado, exibe um erro e interrompe a execução.

---

```php
$dbUpdateCallback = function ($photoUrl) use ($id, $userDao) {
                return $userDao->updateProfilePic($photoUrl, $id);
            };
```

- Define uma função de callback para atualizar a URL da foto de perfil no banco de dados.
- Essa função é usada dentro do `UploadHandler::handleUpload()` para salvar a nova imagem

---

```php
$userDao->updateUser($name, $email, $bio, $phone, $id);
            $_SESSION['user_name'] = $name;
            header("Location: " . BASE_URL . "view/profile.php?id=$id");
            exit;
```

- Atualiza o nome, e-mail, telefone e biografia do usuário no banco de dados.
- Se a atualização for bem-sucedida, o nome na sessão também é atualizado e o usuário é redirecionado para seu perfil.

---

```php
$userDao->deleteUser($id);
            session_destroy();
            header("Location: " . BASE_URL . "view/login.php");
            exit;
```

- Remove o usuário do banco de dados.
- Após a exclusão, a sessão é encerrada e o usuário é redirecionado para a página de login.

---

## Conclusão

Este código é um controlador que gerencia a página de atualização do perfil do usuário. Ele utiliza UserDao para:

- Obter dados do usuário
- Atualizar perfil e foto
- Excluir conta
- Gerenciar sessões

Ele manipula as informações do usuário com base nas ações do formulário e atualiza os dados no banco de dados.
