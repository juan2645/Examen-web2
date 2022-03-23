
//// APIREST Obtener la lista de publicaciones del portal. 

<?php

class ApiController {

    private $publicacionModel;
    private $view;

    public function __construct(){
        $this->publicacionModel = new PublicacionModel();
        $this->view = new PublicacionView();
    }

    private function getBody(){
        $body = file_get_contents("php://input");
        return (json_decode($body));
    }

    public function obtenerPublicaciones($param = null) {
        $publicaciones = $this->publicacionModel->getPublicaciones();
        if($publicaciones) {
            $this->view->response($publicaciones, 200);
        } else {
            $this->view->response("No se encontraron publicaciones", 404);
        }
    }
}


//////////  PUBLICACION MODEL /////////////////

<?php

class PublicacionModel {

    private $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=DB_NAME;charset=utf8', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }

    function getPublicaciones() {
        $consulta = 'SELECT * FROM publicacion';
        $query = $this->db->prepare($consulta);
        $query->execute();
        $publicaciones = $query->fetchAll(PDO::FETCH_OBJ);
        return $publicaciones;
    }
}


