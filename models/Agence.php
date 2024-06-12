<?php

class Agence {
    private $id;
    private $nom;
    private $adresse;
    private $conn = null;
    private $table = "t_agence";

    public function __construct($db) {
        if ($this->conn === null) {
            $this->conn = $db;
        }
    }

    /** ajouter une agence */
    public function create() {
        $req = "INSERT INTO $this->table(nom, adresse, create_at) 
        VALUES(:nom, :adresse, NOW())";

        try {
            $stm = $this->conn->prepare($req);
            return $stm->execute(
                [
                    ":nom" => $this->nom,
                    ":adresse" => $this->adresse,
                ]
            );

        } catch (PDOException $e) {
            die(json_encode(["error" => " Erreur ajout agence : " . $e->getMessage()], JSON_PRETTY_PRINT));
        }
    }

    /** lire toutes les agences */
    public function read() {
        $req = "SELECT * FROM $this->table";
        try {
            $stm = $this->conn->prepare($req);
            $stm->execute();
            return $stm;

        } catch (PDOException $e) {
            die(json_encode(["error" => "Lecture agence erreur : " . $e->getMessage()],JSON_PRETTY_PRINT));
        }
    }

    /** update d'une agence */
    public function update() {
        $sql = "UPDATE $this->table SET nom=:nom, adresse=:adresse 
        WHERE id=:id";
        
        $values = [
            ":nom" => $this->nom,
            ":adresse" => $this->adresse,
            ":id" => $this->id
        ];

        try {
            $stm = $this->conn->prepare($sql);
            return $stm->execute($values);

        } catch (PDOException $e) {
            die(json_encode(["error" => "Update agence erreur : ".$e->getMessage()],JSON_PRETTY_PRINT));
        }
    }

    /** delete une agence */
    public function delete()
    {
        $sql = "DELETE FROM t_agence WHERE id = :id";
        try {
            $stm = $this->conn->prepare($sql);
            return $stm->execute([':id' =>$this->id]);

        } catch (PDOException $e) {
            die(json_encode(["error" => "Delete agence erreur : ".$e->getMessage()],JSON_PRETTY_PRINT));
        }
    }


    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Set the value of adresse
     *
     * @return  self
     */ 
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

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