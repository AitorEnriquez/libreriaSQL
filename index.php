<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería</title>
    <link rel="stylesheet" href="style.css">
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
        <form method="POST">
            <label for="txtISBN">ISBN:</label>
            <input name="libroISBN"type="text" maxlength="12" required id="txtISBN" placeholder="123456789AAA">
            <label for="txtNombre">Nombre:</label>
            <input name="libroNombre" type="text" maxlength="50" required id="txtNombre" placeholder="El silencio de los corderos">
            <label for="txtPuntuacion">Puntuación:</label>
            <input name="libroPuntuacion" type="number" maxlength="2" value="0" id="txtNumber" placeholder="0">
            <br>
            <input type="submit" value="Agregar"> <input type="reset" value="Resetear">
        </form>

       <table>
           <thead>
                <th>ISBN</th>
                <th>Nombre</th>
                <th>Puntuacion</th>
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
                            <th>
                                <label class=btnAcciones for=chkSeguro>Eliminar</label>
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
    //Agregar libro
        if ($_SERVER["REQUEST_METHOD"]=="POST") {
            try {
                // $conexion = new PDO('mysql:host=localhost;dbname=libreria',$user,$pwd);
                // $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $stmt = $conexion->prepare("INSERT INTO libro VALUES('$_POST[libroISBN]','$_POST[libroNombre]','$_POST[libroPuntuacion]')");
                $stmt->execute();
                header("Location:./");
            } catch (PDOException  $e) {
                echo "<p>ERROR: " . $e->getMessage() . "</p>";
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