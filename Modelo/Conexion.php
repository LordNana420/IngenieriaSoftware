<?php

class Conexion{
    private $conexion;
    private $resultado;
    
    public function abrir(){
       
            $this -> conexion = new mysqli("localhost", "root", "", "bd_pmirador");
        
    }
    
    public function cerrar(){
        $this -> conexion -> close();
    }
    
    public function ejecutar($sentencia){
        $this -> resultado = $this -> conexion -> query($sentencia);
    }

    public function getConexion() {
    return $this->conexion;
}

    
    public function registro(){
        return $this -> resultado -> fetch_row();
    }
    
    public function filas(){
        return $this -> resultado -> num_rows;
    }
    
}


?>