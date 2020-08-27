<?php

namespace Hcode\Model;
use \Hcode\DB\Sql;
use \Hcode\Model;
use Rain\Tpl\Exception;

class User extends Model {

    const SESSION = "User";

    public static function login($login, $senha)
    {
        $sql = new Sql();
        $results = $sql->select("SELECT * FROM usuarios WHERE login = :LOGIN", array(
            ":LOGIN"=>$login
        ));

        if(count($results) === 0)
        {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }

        $data = $results[0];

        if (password_verify($senha, $data["senha"])=== true)
        {
            $user = new User();
            $user->setData($data);

            $_SESSION[User::SESSION] = $user->getValues();

            return $user;

        }else {
            throw new \Exception("Usuário inexistente ou senha inválida.");
        }

    }

    public static function verifyLogin($inadmin = true)
    {
        if (
            !isset($_SESSION[User::SESSION])
        ||
            !$_SESSION[User::SESSION]
            ||
            !(int)$_SESSION[User::SESSION]["idUsuario"] > 0
            ||
            (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
        ) {
            header("Location: /admin/login");
            exit();
        }

    }

    public static function logout()
    {
        $_SESSION[User::SESSION] = NULL;

    }

}