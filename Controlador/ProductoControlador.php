<?php
require_once __DIR__ . "/../Modelo/ProductoDAO.php";
require_once __DIR__ . "/../Modelo/Producto.php";

class ProductoControlador
{
    private $productoDAO;

    public function __construct()
    {
        $this->productoDAO = new ProductoDAO();
    }

    /**
     * Devuelve un array con todos los productos (cada elemento es una instancia de Producto).
     * Usado por la vista para poblar selects, tablas, etc.
     *
     * @return array
     */
    public function obtenerProductos()
    {
        $productos = $this->productoDAO->consultarTodos();
        // consultarTodos() ya retorna un array de objetos Producto según tu DAO
        // simplemente lo devolvemos (o un array vacío si es null)
        if (is_array($productos)) {
            return $productos;
        }
        return [];
    }

    /**
     * Devuelve un producto por su id (instancia Producto) o null si no existe.
     *
     * @param int $id
     * @return Producto|null
     */
    public function obtenerProductoPorId($id)
    {
        // Como tu DAO actual no tiene un método consultarPorId, lo consultamos por toda la lista.
        // Si prefieres, podemos añadir en ProductoDAO un método consultarPorId($id) más eficiente.
        $productos = $this->productoDAO->consultarTodos();
        if (is_array($productos)) {
            foreach ($productos as $p) {
                if (method_exists($p, 'getIdProducto') && $p->getIdProducto() == $id) {
                    return $p;
                }
            }
        }
        return null;
    }
    
    /**
     * Cierra la conexión en el DAO (si hace falta liberar recursos).
     */
    public function cerrar()
    {
        $this->productoDAO->cerrarConexion();
    }
}
?>
