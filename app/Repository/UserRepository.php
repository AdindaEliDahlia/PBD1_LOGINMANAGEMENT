<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Repository;

use phpDocumentor\Reflection\Types\Null_;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;

class UserRepository
{
    private \PDO $connection;
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): User{
       $statment= $this->connection->prepare("INSERT INTO users(id, name, password) VALUES (?, ?,?)");
       $statment->execute([
           $user->id, $user->name, $user->password
       ]);
        return $user;
    }
    public function findById(string $id): ?User{
        $statement= $this->connection->prepare("SELECT id,name,password FROM users WHERE id = ?");
        $statement->execute([$id]);

        try {
            if($row = $statement->fetch()){
                $user = new User();
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->password = $row['password'];
                return $user;
            }else{
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }
    public function deleteAll():void{
        $this->connection->exec("DELETE from users");
    }
}