<?php
require_once 'require/dao.php';

class Categorie
{
    private $dao;
    private $Id_Categorie;
    private $Libelle;
    private $Slug;

    public function __construct($id_categorie = null, $libelle = null)
    {
        $this->dao = new dao("localhost", "greengarden");
        $this->Id_Categorie = $id_categorie;
        $this->Libelle = $libelle;
    }

     // Getters
     public function getId_Categorie() {
        return $this->Id_Categorie;
    }

    public function getLibelle() {
        return $this->Libelle;
    }
    public function getSlug() { return $this->Slug; }

    // Setters
    public function setId_Categorie($id_categorie) {
        $this->Id_Categorie = $id_categorie;
    }

    public function setLibelle($libelle) {
        $this->Libelle = $libelle;
    }
    public function setSlug($slug) { $this->Slug = $slug; }

    public function getCategorieById($id)
    {
        $params = array(
            ':id' => $id
        );
        return $this->dao->select("t_d_categorie", "id_categorie = :id", $params,"","","Categorie");
    }

    public function getCategorieBySlug($slug)
    {
        $params = array(
            ':slug' => $slug
        );
        return $this->dao->select("t_d_categorie", "Slug = :slug", $params,"","","Categorie");
    }

   

    public static function getAllCategories()
    {
        $params = array();
        $dao = new dao("localhost", "greengarden");
        return $dao->select("t_d_categorie", "", $params, "Libelle","","Categorie");
    }

    public function insertCategorie() {
        /* $sql = "INSERT INTO t_d_categorie (Libelle) VALUES ('$libelle')";*/

        //     
        $values = array(
            'Libelle' => $this->getLibelle()
        );

        return $this->dao->insert('t_d_categorie', $values);
    }

    public function updateCategorie() {
       
        $data = array(
            'Libelle' => $this->getLibelle()
                    );
                    // Condition de mise à jour
        $where = 'Id_Categorie = ?';
        $params = [$this->getId_Categorie()]; // ID du produit à mettre à jour
        return $this->dao->update('t_d_categorie', $data,$where,$params);
      }

    public function deleteCategorie()
    {
        $where = 'Id_Categorie = ?';
        $params = [$this->getId_Categorie()]; // ID du produit à mettre à jour
       
        return $this->dao->delete('t_d_categorie',$where,$params);
    }
}
