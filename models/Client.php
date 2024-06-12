<?php 

/**
 * Cette classe est la definition de la table client, elle contient
 * toutes les actions possibles sur la table client 
 * 
 * properties :
 * @var string $prenom le prenom du client;
 * @var string $nom le nom du client;
 * @var string $genre du client;
 * @var string $telephone le numero de telephone du client;
 * 
 * methods :
 * 
 * 
 */

 include "Person.php";
class Client extends Person{
    
    private $id;
    private $agence;
    private $conn = null;
    private $table = "t_client";

    // properties
    private $create_at;

    public function __construct($db , $prenom, $nom, $genre, $telephone) {
        parent::__construct($prenom, $nom, $genre, $telephone);
        if ($this->conn === null) {
            $this->conn = $db;
        }
    }

    /**
     * ajout d'un client, cette methode permet d'ajouter une ligne dans la table 
     * client qui consiste a la creation de d'un compte client
     */
    public function create() {
        $req = "INSERT INTO $this->table(id,prenom, nom, genre, telephone, create_at, agence) 
        VALUES(:id, :prenom, :nom, :genre, :telephone, NOW(), :agence)";

        try {
            $stm = $this->conn->prepare($req);
            return $stm->execute(
                [
                    ":id"=> null,
                    ":prenom" => $this->prenom,
                    ":nom" => $this->nom,
                    ":genre" => $this->genre,
                    ":telephone" => $this->telephone,
                    ":agence" => $this->agence,
                ]
            );

        } catch (PDOException $e) {
            die(json_encode(["error" => " Erreur liee a la base de donnee : " . $e->getMessage()], JSON_PRETTY_PRINT));
        }
    }

    /** selectionner l'identifiant du dernier client ajouter */
    public function dernierClientId(){
        $req = "SELECT id FROM $this->table ORDER BY id DESC LIMIT 1";

        try {
            $stm = $this->conn->prepare($req);
            $stm->execute();
            return $stm;

        } catch (PDOException $e) {
            die(json_encode(["error" => "Lecture dernier client erreur : " . $e->getMessage()],JSON_PRETTY_PRINT));
        }
    }

    /** 
     * lire la liste de tout les clients
     * cette methode permet de lire toutes les lignes existantes dans la table client
     * accompanger des informations sur l'agence d'abonnement
     */
    public function clientAgence() {
        $req = "SELECT c.prenom, c.nom, c.genre, c.telephone, a.nom, a.adresse
        FROM $this->table c INNER JOIN t_agence a
        ON c.agence = a.id
        ORDER BY c.created_at DESC";

        try {
            $stm = $this->conn->prepare($req);
            $stm->execute();
            return $stm;

        } catch (PDOException $e) {
            die(json_encode(["error" => "Lecture client erreur : " . $e->getMessage()],JSON_PRETTY_PRINT));
        }
    }

    /** la liste des clients avec les informations sur les comptes */
    public function clientCompte() {
        $req = "SELECT c.prenom, c.nom, c.genre, c.telephone, co.id, co.typec, co.solde
        FROM $this->table c INNER JOIN t_compte co
        ON c.id = co.client
        ORDER BY c.created_at DESC";

        try {
            $stm = $this->conn->prepare($req);
            $stm->execute();
            return $stm;

        } catch (PDOException $e) {
            die(json_encode(["error" => "Lecture client-compte erreur : " . $e->getMessage()],JSON_PRETTY_PRINT));
        }
    }

    /** 
     * Cette methode permet de lire les informations d'un seule client
     * en fonction de son identifiant unique
     */
    public function read_a_client() {
        $req = "SELECT c.prenom, c.nom, c.genre, c.telephone, a.nom, a.adresse
        FROM $this->table c INNER JOIN t_agence a
        ON c.agence = a.id
        WHERE c.id = :id";
        
        try {
            $stm = $this->conn->prepare($req);
            $stm->execute([':id' => $this->id]);
            return $stm;
        } catch (PDOException $e) {
            die(json_encode(["error" => "Lecture d'un client erreur : " . $e->getMessage()],JSON_PRETTY_PRINT));
        }
    }

    /**
     * cette methode update permet de changer une propriete du client a partir de sont identifiant 
     * unique
     */
    public function update() {
        $sql = "UPDATE $this->table SET nom=:nom, prenom=:prenom, genre=:genre,telephone=:telephone 
        WHERE id=:id";
        
        $values = [
            ":nom" => $this->nom,
            ":prenom" => $this->prenom,
            ":genre" => $this->genre,
            ":telephone" => $this->telephone,
            ":id" => $this->id
        ];

        try {
            $stm = $this->conn->prepare($sql);
            return $stm->execute($values);

        } catch (PDOException $e) {
            die(json_encode(["error" => "Update client erreur : ".$e->getMessage()],JSON_PRETTY_PRINT));
        }
    }


    /**
     * cette méthode permet de supprimer un compte client
     */
    public function delete() {
        $sql = "DELETE FROM $this->table WHERE id=:id";
        try {
            $stm = $this->conn->prepare($sql);
            return $stm->execute([":id" => $this->id]);

        } catch (PDOException $e) {
            die(json_encode(["error" => "Erreur suppresion client : ".$e->getMessage()],JSON_PRETTY_PRINT));
        }
    }


    /**
     * Set the value of agence
     *
     * @return  self
     */ 
    public function setAgence($agence)
    {
        $this->agence = $agence;

        return $this;
    }

    

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
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
