PUBLICACION (id: int, fecha: date, activa: boolean, descripcion: string,  id_user: int, id_categoria: int) 
// id_user es el profesional asociado
CATEGORIA(id: int, nombre: string, destacada: boolean)
VISITA(id: int, fecha: date, id_publicacion: int, id_user: int)
USER(id: int, email: string, telefono: string, password: string, premium: boolean)

Como administrador quiero desactivar una publicación. La publicación solo se podrá desactivar si: 
no pertenece a una categoría destacada
el usuario no es premium

/////////////  CONTROLADOR  //////////////

<?php

class PublicacionController {

    private $publicacionModel;
    private $categoriaModel;
    private $userModel;
    private $view;

    public function __construct()
    {
        $this->publicacionModel = new PublicacionModel();
        $this->categoriaModel = new CategoriaModel();
        $this->userModel = new UserModel();
        $this->view = new PublicacionView();
    }

    function desactivarPublicacion() {
        $id_publicacion = $_REQUEST['id'];
        if (!isset($id_publicacion) || empty($id_publicacion)) {
            $this->view->showMsj("Error en ingreso de datos.");
            die();
        }
        $publicacion = $this->publicacionModel->getPublicacion($id_publicacion);
        if (empty($publicacion)) {
            $this->view->showMsj("Publicacion con id = $id_publicacion no encontrada.");
        } else {
            if ($publicacion->activa == FALSE){
                $this->view->showMsj("La publicacion ya esta DESACTIVADA."); 
            } else {
                $id_categoria = $publicacion->id_categoria;
                $categoria = $this->categoriaModel->getCategoria($id_categoria);
                if (empty($categoria)) {
                   $this->view->showMsj("Categoria con id = $id_categoria no encontrada.");
                   die();
                } 
                if ($categoria->destacada == TRUE) {
                    $this->view->showMsj("No se puede desactivar la publicacion porque pertenece a una categoria destacada.");
                    die();
                }
                $id_user = $categoria->id_user;
                $user = $this->userModel->getUser($id_user);
                if (empty($user)) {
                    $this->view->showMsj("Usuario con id = $id_user no encontrado.");
                    die();
                } 
                if ($user->premium == TRUE){
                    $this->view->showMsj("No se puede desactivar la publicacion porque el usuario es premium.");
                    die();
                } 
                $activa = FALSE;
                $this->publicacionModel->desactivaPublicacion($id_publicacion, $activa);  
                $this->view->showMsj("La publicacion fue desactivada exitosamente.");
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

    function desactivaPublicacion($id, $activa) {
        try {
           $query = $this->db->prepare('UPDATE publicacion SET activa=? WHERE id=?'); 
           $query->execute([$activa, $id]);
        }
        catch (PDOException $error) {
           $error->getMessage();
           echo $error;
        }
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

