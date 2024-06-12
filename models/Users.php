<?php

include "Person.php";
class Users extends Person{

    private $id;
    private $fonction;
    private $table = "t_users";
    private $conn = null;
    private $create_at;

    public function __construct($db, $prenom, $nom, $genre, $telephone) {
        parent::__construct($prenom, $nom, $genre, $telephone);

        if ($this->conn == null) {
            $this->conn = $db;
        }
    }

    public function create() {
        $req = "INSERT INTO $this->table(prenom, nom, genre, telephone, fonction, create_at) 
        VALUES(:prenom, :nom, :genre, :telephone, :fonction, NOW())";

        try {
            $stm = $this->conn->prepare($req);
            
            return $stm->execute(
                [
                    ":prenom" => $this->prenom,
                    ":nom" => $this->nom,
                    ":genre" => $this->genre,
                    ":telephone" => $this->telephone,
                    ":fonction" => $this->fonction,
                ]
            );

        } catch (PDOException $e) {
            die(json_encode(["error" => " Erreur liee a la base de donnee : " . $e->getMessage()]));
        }
    }

    /**
     * Bloquer Utilisateur
     * cette methode permet de bloquer un utilisateur
     */
    public function bloquer() {
        $req = "UPDATE $this->table SET u_status = 1 WHERE id = :id";
        
        try {
            $stm = $this->conn->prepare($req);
            return $stm->execute([":id" => $this->id]);
            

        } catch (PDOException $e) {
            die(json_encode(["error" => "Bloquer un utilisateur erreur : " . $e->getMessage()], JSON_PRETTY_PRINT));
        }
    }

    /**
     * Debloquer Utilisateur
     * cette methode permet de debloquer un utilisateur
    */
    public function debloquer() {
        $req = "UPDATE $this->table SET u_status = 0 WHERE id = :id";

        try {
            $stm = $this->conn->prepare($req);
            return $stm->execute([":id" => $this->id]);

        } catch (PDOException $e) {
            die(json_encode(["error" => "Debloquer un utilisateur erreur : " . $e->getMessage()], JSON_PRETTY_PRINT));
        }
    }

    /**
     * Get the value of fonction
     */ 
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Set the value of fonction
     *
     * @return  self
     */ 
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}