<?php

PUBLICACION (id: int, fecha: date, activa: boolean, descripcion: string,  id_user: int, id_categoria: int) // id_user es el profesional asociado
CATEGORIA(id: int, nombre: string, destacada: boolean)
VISITA(id: int, fecha: date, id_publicacion: int, id_user: int)
USER(id: int, email: string, telefono: string, password: string, premium: boolean)


Como usuario quiero poder ingresar una nueva publicación al sistema. 
El usuario no puede tener más de 5 publicaciones activas a menos que sea premium.

/////////////  CONTROLADOR  //////////////

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

    function ingresarPublicacion() {
        $id_user = $_REQUEST['id'];
        if (!isset($id_user) || empty($id_user)) {
            $this->view->showMsj("Error en ingreso de datos.");
            die();
        }
        $user = $this->publicacionModel->getUser($id_user);
        if (empty($user)) {
            $this->view->showMsj("Usuario con id = $id_user no encontrado.");
        } else {
            $fecha = $_REQUEST['fecha'];
            $activa = $_REQUEST['activa'];
            $descripcion = $_REQUEST['descripcion'];
            $id_categoria = $_REQUEST['id_categoria'];

            if ((!isset($fecha) || empty($fecha)) || (!isset($activa) || empty($activa)) 
               || (!isset($descripcion) || empty($descripcion)) || (!isset($id_categoria) || empty($id_categoria))) {
                    $this->view->showMsj("Error en ingreso de datos.");
                    die();
            };

            $categoria = $this->categoriaModel->getCategoria($id_categoria);
            if (empty($categoria)) {
                $this->view->showMsj("Categoria con id = $id_categoria no encontrada.");
                die();
            }

            $cant_publicaciones = $this->publicacionModel->getCantidadPublicaciones($id_user);
            if ($cant_publicaciones > 5) {
                if ($user->premium == FALSE) {
                    $this->view->showMsj("No se puede ingresar publicacion porque no es usuario PREMIUM.");
                } else {
                    $id = $this->publicacionModel->addPublicacion($fecha,$activa,$descripcion,$id_user,$id_categoria);
                    $publicacion = $this->publicacionModel->getPublicacion($id);
                    if (empty($publicacion)){
                        $this->view->showMsj("Error, no pude insertarse la publicacionm.");
                    } else {
                        $this->view->showMsj("Publicacion insertada correctamente.");
                    }
                }
            } else {
                $id = $this->publicacionModel->addPublicacion($fecha,$activa,$descripcion,$id_user,$id_categoria);
                $publicacion = $this->publicacionModel->getPublicacion($id);
                if (empty($publicacion)){
                    $this->view->showMsj("Error, no pude insertarse la publicacionm.");
                } else {
                    $this->view->showMsj("Publicacion insertada correctamente.");
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

    function getCantidadPublicaciones($id_user) {
        $consulta = 'SELECT count(*) as cantidad FROM publicacion WHERE id_user=?';
        $query = $this->db->prepare($consulta);
        $query->execute([$id]);
        $cantidad_publicaciones = $query->fetchAll(PDO::FETCH_OBJ);
        return $cantidad_publicaciones['cantidad'];
    }

    function addPublicacion($fecha,$activa,$descripcion,$id_user,$id_categoria){
        $consulta = 'INSERT INTO publicacion(fecha, activa, descripcion, id_user, id_categoria) VALUES (?,?,?,?,?)';
        $query = $this->db->prepare($consulta);
        $query->execute([$fecha,$activa,$descripcion,$id_user,$id_categoria]);
        return $this->db->lastInsertId();
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
