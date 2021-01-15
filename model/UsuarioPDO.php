<?php

class UsuarioPDO{
    public static function validarUsuario($codUsuario, $password){
        $oUsuario = null; //Inicializamos la variable oUsuario a null
        
        $consulta = "Select * from T01_Usuario where T01_CodUsuario=? and T01_Password=?";// Realizamos la consulta
        $passwordEncriptado=hash("sha256", ($codUsuario.$password));// Encriptamos la contraseña del usuario concatenandola con el codigo del usuario
        $resultado = DBPDO::ejecutaConsulta($consulta, [$codUsuario,$passwordEncriptado]);// Ejecutamos la consulta
        
        if($resultado->rowCount()>0){// Si la consulta devuelve algo, el usuario introducido es correcto
            $oUsuarioConsulta = $resultado->fetchObject();// Almacenamos el objeto devualto por la consulta en la variable ousuarioConsulta
            
            //Instanciamos un objeto Usuario con los campos del resultado de la consulta
            $oUsuario = new Usuario($oUsuarioConsulta->T01_CodUsuario, $oUsuarioConsulta->T01_Password, $oUsuarioConsulta->T01_DescUsuario, $oUsuarioConsulta->T01_NumConexiones, $oUsuarioConsulta->T01_FechaHoraUltimaConexion, $oUsuarioConsulta->T01_Perfil, $oUsuarioConsulta->T01_ImagenUsuario);
        
            $consultaActualizarUlimatConexion = "Update T01_Usuario set T01_NumConexiones = T01_NumConexiones+1, T01_FechaHoraUltimaConexion=? where T01_CodUsuario=?";
            $resultadoActualizarUlimatConexion = DBPDO::ejecutaConsulta($consultaActualizarUlimatConexion, [time(),$codUsuario]);//Actualizamos la fecha y hora de la ultima conexion
        }
        
        return $oUsuario;// Devolvemos el objeto oUsuario instanciado
    }
}