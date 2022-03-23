<?php

PUBLICACION (id: int, fecha: date, activa: boolean, descripcion: string,  id_user: int, id_categoria: int) // id_user es el profesional asociado
CATEGORIA(id: int, nombre: string, destacada: boolean)
VISITA(id: int, fecha: date, id_publicacion: int, id_user: int)
USER(id: int, email: string, telefono: string, password: string, premium: boolean)

Como usuario quiero poder listar todas las publicaciones que coincidan con un criterio de búsqueda. 
La búsqueda puede realizarse por categoría y/o descripción.

/////////////// CONTROLADOR /////////////////

<?php

class PublicacionController {

    private $publicacionModel;
    private $userModel;
    private $view;

    public function __construct()
    {
        $this->publicacionModel = new PublicacionModel();
        $this->userModel = new UserModel();
        $this->view = new PublicacionView();
    }

    function listarPublicaciones() {

        $id_user = $_REQUEST['id_user'];
        if (!isset($id_user) || empty($id_user)) {
            $this->view->showMsj("Error en ingreso de datos.");
            die();
        }
        $user = $this->userModel->getUser($id_user);
        if (empty($user)) {
            $this->view->showMsj("Usuario con id = $id_user no encontrado.");
        } else {
           $descripcion = $_REQUEST['descripcion'];
           if (!isset($descripcion) || empty($descripcion)) {
             $this->view->showMsj("Error en ingreso de datos.");
             die();
           } else {
               $publicaciones = $this->publicacionModel->getPublicaciones($descripcion);
               if (empty($publicaciones)) {
                   $this->view->showMsj("No hay publicaciones que coincidan con esta busqueda.");
               } else {
                   $this->view->showPublicaciones($publicaciones); 
               }
           }
        }
   
    }
}


//////////  USER MODEL /////////////////


<?php

class UserModel {

    private $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=DB_NAME;charset=utf8', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }

    function getUser($id) {
        $consulta = 'SELECT * FROM user WHERE id=?';
        $query = $this->db->prepare($consulta);
        $query->execute([$id]);
        $user = $query->fetch(PDO::FETCH_OBJ);
        return $user;
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

    function getPublicaciones($descripcion) {
        $consulta = 'SELECT * FROM publicacion WHERE descripcion=?';
        $query = $this->db->prepare($consulta);
        $query->execute([$descripcion]);
        $publicaciones = $query->fetchAll(PDO::FETCH_OBJ);
        return $publicaciones;
    }
}