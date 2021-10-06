<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php
            $user= "root";
            $pwd = "";
                try {
                    $conexion = new PDO('mysql:host=localhost;dbname=mysql',$user,$pwd);
                    $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                    echo "<h1>Conectado correctamente</h1>";
                }
                catch(PDOException $e) {
                    echo "<h1>ERROR: " . $e->getMessage() . "</h1>";
                }
           ?>
    <fieldset>
        <legend>Librería</legend>
        <form method="POST" enctype="multipart/form-data">
            <label for="txtISBN">ISBN:</label>
            <input name="libroISBN"type="text" maxlength="12" required id="txtISBN" placeholder="123456789AAA" pattern="[0-9]{9}[A-Z]{3}">
            <label for="txtNombre">Nombre:</label>
            <input name="libroNombre" type="text" maxlength="50" required id="txtNombre" placeholder="El silencio de los corderos">
            <label for="txtPuntuacion">Puntuación:</label>
            <input name="libroPuntuacion" type="number" min="0" max="10" maxlength="2" value="0" id="txtNumber" placeholder="0">
            <input type="file" name="libroImg" required>
            <br>
            <input type="submit" value="Agregar"> <input type="reset" value="Resetear">
        </form>

       <table>
           <thead>
                <th>ISBN</th>
                <th>Nombre</th>
                <th>Puntuacion</th>
                <th>Portada</th>
                <th>Acciones</th>
           </thead>
            <?php
                try {
                    
                    //Buscar libro
                    $conexion = new PDO('mysql:host=localhost;dbname=libreria',$user,$pwd);
                    $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                    $stmt = $conexion->prepare("SELECT * FROM libro");
                    $stmt->execute();
                    $libro = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $posicion = 0;
                    foreach($libro as $libro) {
                        $posicion +=1;
                        echo "  
                        <tr>
                            <th name=libroISBN$posicion>$libro[ISBN]</th>
                            <th>$libro[Nombre]</th>
                            <th>$libro[Puntuacion]</th>
                            <th><img src=$libro[Portada] alt=$libro[ISBN]></th>
                            <th>
                               <!-- <label class=btnAcciones for=chkSeguro>Eliminar</label> -->
                                <a href=editar.php?libroISBN=$libro[ISBN] id=editar$posicion class=btnAcciones>Editar</a>
                                <a href=borrar.php?libroISBN=$libro[ISBN] id=borrar$posicion class=btnAcciones>Eliminar</a>
                            </th>
                        </tr> ";
                    }
                }
                catch(PDOException $e) {
                    echo "<td>Error con la base de datos</td>";
                }
            ?>
    
       </table>
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
                $libro_ruta = "./portada/".$_POST['libroISBN'].".jpg";
                move_uploaded_file($tmpNombre_libro, $libro_ruta);
            }
        }
    }
    ?>


    <?php
    //Agregar libro
        if ($_SERVER["REQUEST_METHOD"]=="POST") {
            try {
                $stmt = $conexion->prepare("INSERT INTO libro VALUES(:isbn,:nombre,:puntuacion,:portada)");
                $stmt->bindParam(':isbn',$_POST['libroISBN']);
                $stmt->bindParam(':nombre',$_POST['libroNombre']);
                $stmt->bindParam(':puntuacion',$_POST['libroPuntuacion']);
                $stmt->bindParam(':portada',$libro_ruta);
                $stmt->execute();
                header("Location:./");
            } catch (PDOException  $e) {
                switch ($e->getCode()) {
                    case '23000':
                        echo "<p class=error>El archivo solo puede ser JPG de menos de 2MB.</p>";
                        break;
                    
                    default:
                    echo "<p class=error>ERROR: " . $e->getMessage() . "</p>";
                    echo "<p class=error>ERROR: ". $e->getCode() . "</p>";
                        break;
                }
            }
        }
    ?>

    
    <input type="checkbox" name="" id="chkSeguro">
    
    <div id="seguro">
        <h1>¿Seguro que quieres eliminar el libro ''?</h1>
        <a class="btnAcciones" href="">Sí</a>
        <label class="btnAcciones" for="chkSeguro">No</label>
    </div>

</body>
</html>