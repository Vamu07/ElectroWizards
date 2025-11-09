<?php
include_once "../config/conexion.php";

if($conexion){
    echo "✅ Conexión exitosa a la BD";
} else {
    echo "❌ Error de conexión a la BD";
}
