<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <fieldset>
        <legend>Librería</legend>
        <form method="POST" enctype="multipart/form-data">
        <?php
            $libroISBN = isset($_REQUEST["libroISBN"]) ? $_REQUEST["libroISBN"] : null;
            $user = "root";
            $pwd = "";
            try {
                $conexion = new PDO('mysql:host=localhost;dbname=libreria',$user,$pwd);
                $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                echo "<h1>Conectado correctamente</h1>";

                $stmt = $conexion->prepare("SELECT * FROM libro WHERE ISBN like '$libroISBN'");
                $stmt->execute();
                $libro = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($libro as $libro) {
                    echo "
                        <label for=txtISBN>ISBN:</label>
                        <input name=libroNuevoISBN type=text maxlength=12 required id=txtISBN value=$libro[ISBN] disabled=true>
                        <label for=txtNombre>Nombre:</label>
                        <input name=libroNuevoNombre type=text maxlength=50 required id=txtNombre value='$libro[Nombre]' placeholder='$libro[Nombre]'>
                        <label for=txtPuntuacion>Puntuación:</label>
                        <input name=libroNuevoPuntuacion type=number min=0 max=10 maxlength=2 id=txtNumber value=$libro[Puntuacion] placeholder=$libro[Puntuacion]>
                        <input type=file name=libroImg>
                        <p class=aviso>SOLO se modificará la portada en caso de subir un fichero compatible (JPG y/o menor de 2MB).</p>
                        <br>
                ";
                }
            }
            catch(PDOException $e) {
                echo "<h1>ERROR: " . $e->getMessage() . "</h1>";
            }
        ?>
            <input type="submit" value="Guardar"> <input type="reset" value="Resetear">
        </form>
    </fieldset>

    <?php
    //Portada
    if ($_SERVER["REQUEST_METHOD"]=="POST") {
        if (isset($_FILES["libroImg"]) && $_FILES["libroImg"]["error"] == 0) {
            $nombre_img = $_FILES["libroImg"]["name"];
            $libro_tipo = $_FILES["libroImg"]["type"];
            $libro_peso = $_FILES["libroImg"]["size"];
            $tmpNombre_libro = $_FILES["libroImg"]["tmp_name"];
            $error_libro = $_FILES["libroImg"]["error"];
            
            if ($libro_peso < 2000000 && $libro_tipo == "image/jpeg") {
                unlink("./portada/$libroISBN.jpg");
                $libro_ruta = "./portada/".$libroISBN.".jpg";
                move_uploaded_file($tmpNombre_libro, $libro_ruta);
            }
        }
    }
    ?>


   <?php
    //Modificar
        if ($_SERVER["REQUEST_METHOD"]=="POST") {
            try {
                // $conexion = new PDO('mysql:host=localhost;dbname=libreria',$user,$pwd);
                // $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                $stmt = $conexion->prepare("UPDATE libro SET Nombre=:nombre, Puntuacion=:puntuacion, Portada=:portada WHERE ISBN like :isbn");
                $stmt->bindParam(':nombre',$_POST['libroNuevoNombre']);
                $stmt->bindParam(':puntuacion',$_POST['libroNuevoPuntuacion']);
                $stmt->bindParam(':isbn',$libroISBN);
                $stmt->bindParam(':portada',$libro["Portada"]);   
                $stmt->execute();
                header("Location:./");
            } catch (PDOException  $e) {
                echo "<p>ERROR: " . $e->getMessage() . "</p>";
            }
        }
    ?>
    <a href="./" class="btnAcciones">Volver</a>
</body>
</html>