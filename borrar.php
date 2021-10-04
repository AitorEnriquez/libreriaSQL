<?php
$libroISBN = isset($_REQUEST["libroISBN"]) ? $_REQUEST["libroISBN"] : null;
$user = "root";
$pwd = "";
    try {
        $conexion = new PDO('mysql:host=localhost;dbname=libreria', $user, $pwd);
        $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $stmt = $conexion -> prepare("DELETE FROM libro WHERE ISBN like '$libroISBN'");
        $stmt->execute();
        header("Location:./");
    }
    catch(PDOException $e) {
        echo "Ha ocurrido un error con la BD. " . $e->getMessage();
    }
?>