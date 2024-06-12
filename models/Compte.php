<?php

class Compte {
    private $id;
    private $typec;
    private $solde;
    private $client;
    private $conn = null;
    private $table = "t_compte";

    public function __construct($db) {
        if ($this->conn === null) {
            $this->conn = $db;
        }
    }

    /** ajouter une agence */
    public function create() {
        $req = "INSERT INTO $this->table(typec, solde, client) 
        VALUES(:typec, :solde, :client)";

        try {
            $stm = $this->conn->prepare($req);
            return $stm->execute(
                [
                    ":typec" => $this->typec,
                    ":solde" => $this->solde,
                    ":client" => $this->client,
                ]
            );

        } catch (PDOException $e) {
            die(json_encode(["error" => " Erreur ajout client : " . $e->getMessage()], JSON_PRETTY_PRINT));
        }
    }

    /** update de compte : uniquement la valeur du type */
    public function update() {
        $sql = "UPDATE $this->table SET typec=:typec 
        WHERE id=:id";
        
        $values = [
            ":typec" => $this->typec,
            ":id" => $this->id
        ];

        try {
            $stm = $this->conn->prepare($sql);
            return $stm->execute($values);

        } catch (PDOException $e) {
            die(json_encode(["error" => "Update compte erreur : ".$e->getMessage()],JSON_PRETTY_PRINT));
        }
    }

    /** recuperer le solde d'un compte en fonction de son id */
    public function soldeCompte() {
        $req = "SELECT solde FROM $this->table WHERE id = :id";
        
        try {
            $stm = $this->conn->prepare($req);
            $stm->execute([':id' => $this->id]);
            return $stm;
        } catch (PDOException $e) {
            die(json_encode(["error" => "Lecture solde erreur : " . $e->getMessage()],JSON_PRETTY_PRINT));
        }
    }

    /** effectuer un versement d'un montant dans un compte */
    public function versementOrRetraitOrVirement() {
        $sql = "UPDATE $this->table SET solde=:solde 
        WHERE id=:id";
        
        $values = [
            ":solde" => $this->solde,
            ":id" => $this->id
        ];

        try {
            $stm = $this->conn->prepare($sql);
            return $stm->execute($values);

        } catch (PDOException $e) {
            die(json_encode(["error" => "versement compte erreur : ".$e->getMessage()],JSON_PRETTY_PRINT));
        }
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

    /**
     * Set the value of typec
     *
     * @return  self
     */ 
    public function setTypec($typec)
    {
        $this->typec = $typec;

        return $this;
    }

    /**
     * Set the value of solde
     *
     * @return  self
     */ 
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Set the value of client
     *
     * @return  self
     */ 
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }
}