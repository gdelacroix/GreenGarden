<?php
require_once 'require/dao.php';

class Fournisseur {
    private $dao;
    private $Id_Fournisseur;
    private $Nom_Fournisseur;

    public function __construct($id_fournisseur = null, $nom_fournisseur = null) {
        $this->dao = new dao("localhost", "greengarden");
        $this->Id_Fournisseur = $id_fournisseur;
        $this->Nom_Fournisseur = $nom_fournisseur;
    }

    // Getters
    public function getId_Fournisseur() {
        return $this->Id_Fournisseur;
    }

    public function getNom_Fournisseur() {
        return $this->Nom_Fournisseur;
    }

    // Setters
    public function setId_Fournisseur($id_fournisseur) {
        $this->Id_Fournisseur = $id_fournisseur;
    }

    public function setNom_Fournisseur($nom_fournisseur) {
        $this->Nom_Fournisseur = $nom_fournisseur;
    }

    // Récupérer tous les fournisseurs
    public static function getAllFournisseurs() {
        $params = array();
        $dao = new dao("localhost", "greengarden");
        return $dao->select("t_d_fournisseur", "", $params, "Nom_Fournisseur","","Fournisseur");
    }

    // Récupérer un fournisseur par ID
    public function getFournisseurById($id) {

        $params = array(
            ':id' => $id
        );
        return $this->dao->select("t_d_fournisseur", "Id_Fournisseur = :id", $params,"","","Fournisseur");

      
    }


    
}
?>