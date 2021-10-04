<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <fieldset>
        <legend>Librería</legend>
        <form method="POST">
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
                        <input name=libroNuevoPuntuacion type=number maxlength=2 id=txtNumber value=$libro[Puntuacion] placeholder=$libro[Puntuacion]>
                        <br>
                ";
                }
            }
            catch(PDOException $e) {
                echo "<h1>ERROR: " . $e->getMessage() . "</h1>";
            }
        ?>
   <?php
    //Modificar
        if ($_SERVER["REQUEST_METHOD"]=="POST") {
            try {
                $conexion = new PDO('mysql:host=localhost;dbname=libreria',$user,$pwd);
                $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $smt = $conexion->prepare("UPDATE libro SET Nombre='$_POST[libroNuevoNombre]', Puntuacion=$_POST[libroNuevoPuntuacion] WHERE ISBN like '$libroISBN'");;
                $stmt->execute();
                echo "UPDATE libro SET Nombre='$_POST[libroNuevoNombre]', Puntuacion=$_POST[libroNuevoPuntuacion] WHERE ISBN like '$libroISBN'";
                // header("Location:./");
            } catch (PDOException  $e) {
                echo "<p>ERROR: " . $e->getMessage() . "</p>";
            }
        }
    ?>
            <input type="submit" value="Guardar"> <input type="reset" value="Resetear">
        </form>
    </fieldset>
    <a href="./" class="btnAcciones">Volver</a>
</body>
</html>