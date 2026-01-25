<?php

namespace App\Repositories;

use Core\Database;

USE PDO ;

class ClassRepo {

public PDO $pdo ;

public function __construct(){

$this->pdo = Database::getInstance()->getConnection();

}
public function addClass($ClassName , $ClassYear){

$stmt = $this->pdo->prepare('INSERT INTO classe ("name" , "year" ) values (:name , :year)');

$stmt->execute([

':name' =>$ClassName,
':year' =>$ClassYear
]);

}


public function findClassByName($ClassName){

$stmt = $this->pdo->prepare('SELECT name FROM classe WHERE name = :ClassName LIMIT 1');

$stmt->execute([

':ClassName' =>$ClassName
]);

return $stmt->fetch();

}






    public function getAllClasses(){
        $sql = "SELECT * FROM classe ORDER BY year DESC, name ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getAllClassesWithStats(){
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM users WHERE classe_id = c.id AND role = 'STUDENT') as student_count,
                (SELECT first_name || ' ' || last_name FROM users WHERE classe_id = c.id AND role = 'TEACHER' LIMIT 1) as principal_teacher
                FROM classe c 
                ORDER BY c.year DESC, c.name ASC";
        return $this->pdo->query($sql)->fetchAll();
    }
    public function getAvailableClassesForPrincipal() {
        $sql = "SELECT * FROM classe c 
                WHERE NOT EXISTS (SELECT 1 FROM users u WHERE u.classe_id = c.id AND u.role = 'TEACHER')
                ORDER BY year DESC, name ASC";
        return $this->pdo->query($sql)->fetchAll();
    }
}
