

PUBLICACION (id: int, fecha: date, activa: boolean, descripcion: string,  id_user: int, id_categoria: int) 
// id_user es el profesional asociado
CATEGORIA(id: int, nombre: string, destacada: boolean)
VISITA(id: int, fecha: date, id_publicacion: int, id_user: int)
USER(id: int, email: string, telefono: string, password: string, premium: boolean)


Como usuario quiero poder ver una publicación determinada. La publicación deberá mostrar, además de sus datos, 
los detalles de la categoría y los datos del profesional asociado. Se debe registrar la visita a la publicación.


/////////////  CONTROLADOR  //////////////

<?php

class PublicacionController {

    private $publicacionModel;
    private $categoriaModel;
    private $userModel;
    private $visitaModel;
    private $view;

    public function __construct()
    {
        $this->publicacionModel = new PublicacionModel();
        $this->categoriaModel = new CategoriaModel();
        $this->userModel = new UserModel();
        $this->visitaModel = new VisitaModel();
        $this->view = new PublicacionView();
    }

    function mostrarPublicacion() {
        $id_publicacion = $_REQUEST['id'];
        if (!isset($id_publicacion) || empty($id_publicacion)) {
            $this->view->showMsj("Error en ingreso de datos.");
            die();
        }
        $publicacion = $this->publicacionModel->getPublicacion($id_publicacion);
        if (empty($publicacion)) {
            $this->view->showMsj("Publicacion con id = $id_publicacion no encontrada.");
        } else {
            $id_categoria = $publicacion->id_categoria;
            $categoria = $this->categoriaModel->getCategoria($id_categoria);
            if (empty($categoria)) {
                $this->view->showMsj("Categoria con id = $id_categoria no encontrada.");
                die();
            } else {
                $id_user = $publicacion->id_user;
                $user = $this->userModel->getUser($id_user);
                if (empty($user)) {
                    $this->view->showMsj("Usuario con id = $id_user no encontrado.");
                    die();
                } else {
                    $this->view->showPublicacion($publicacion, $categoria, $user);
                    $fecha = $_REQUEST['fecha'];
                    if (!isset($fecha) || empty($fecha)) {
                        $this->view->showMsj("Error en ingreso de datos.");
                        die();
                    }
                    $id = $this->visitaModel->addVisita($fecha,$id_publicacion,$id_user);
                    $visita = $this->visitaModel->getVisita($id);
                    if (empty($visita)){
                        $this->view->showMsj("Error, no pude insertarse la visita.");
                    } else {
                        $this->view->showMsj("Visita insertada correctamente.");
                    }
                }
            } 
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

    function getPublicacion($id) {
        $consulta = 'SELECT * FROM publicacion WHERE id=?';
        $query = $this->db->prepare($consulta);
        $query->execute([$id]);
        $publicacion = $query->fetch(PDO::FETCH_OBJ);
        return $publicacion;
    }

}

//////////  CATEGORIA MODEL /////////////////


<?php

class CategoriaModel {

    private $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=DB_NAME;charset=utf8', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }

    function getCategoria($id) {
        $consulta = 'SELECT * FROM categoria WHERE id=?';
        $query = $this->db->prepare($consulta);
        $query->execute([$id]);
        $categoria = $query->fetch(PDO::FETCH_OBJ);
        return $categoria;
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


//////////  VISITA MODEL /////////////////


<?php

class UserModel {

    private $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;'.'dbname=DB_NAME;charset=utf8', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }

    function addVisita($fecha,$id_publicacion,$id_user){
        $consulta = 'INSERT INTO visita(fecha, id_publicacion, id_user) VALUES (?,?,?)';
        $query = $this->db->prepare($consulta);
        $query->execute([$fecha,$id_publicacion,$id_user]);
        return $this->db->lastInsertId();
    }

    function getVisita($id) {
        $consulta = 'SELECT * FROM visita WHERE id=?';
        $query = $this->db->prepare($consulta);
        $query->execute([$id]);
        $visita = $query->fetch(PDO::FETCH_OBJ);
        return $visita;
    }
}