<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_actor = $_POST["nombre_actor"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $is_edit = isset($_POST["is_edit"]) && $_POST["is_edit"] === "true";
    
    if ($is_edit) {
        $id_actor = $_POST["id_actor"];
        $sql = "UPDATE Actores SET nombre_actor='$nombre_actor', fecha_nacimiento='$fecha_nacimiento' WHERE id_actor='$id_actor'";
    } else {
        // No incluir id_actor en la inserción
        $sql = "INSERT INTO Actores (nombre_actor, fecha_nacimiento) VALUES ('$nombre_actor', '$fecha_nacimiento')";
    }

    if ($conn->query($sql) === TRUE) {
        echo 'Registro ' . ($is_edit ? 'actualizado' : 'agregado') . ' exitosamente';
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }
}

if (isset($_GET["delete_id"])) {
    $id_actor = $_GET["delete_id"];
    $sql = "DELETE FROM Actores WHERE id_actor='$id_actor'";
    if ($conn->query($sql) === TRUE) {
        echo 'Registro eliminado exitosamente';
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actores</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script>
        function cargarDatos(id_actor, nombre_actor, fecha_nacimiento) {
            document.getElementById("id_actor").value = id_actor;
            document.getElementById("nombre_actor").value = nombre_actor;
            document.getElementById("fecha_nacimiento").value = fecha_nacimiento;
            document.getElementById("is_edit").value = "true";
        }
    </script>
</head>
<body>
    <a href="index.php">Volver a la página principal</a>
    <h1>Actores</h1>
    <form action="actores.php" method="post">
        <input type="hidden" id="id_actor" name="id_actor">
        
        <label for="nombre_actor">Nombre actor:</label>
        <input type="text" id="nombre_actor" name="nombre_actor" required>

        <label for="fecha_nacimiento">Fecha nacimiento:</label>
        <input type="text" id="fecha_nacimiento" name="fecha_nacimiento" required>
        
        <input type="hidden" id="is_edit" name="is_edit" value="false">
        <input type="submit" value="Guardar">
    </form>

    <h2>Lista de Actores</h2>
    <table>
        <tr>
            <th>Id actor</th>
            <th>Nombre actor</th>
            <th>Fecha nacimiento</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT * FROM Actores";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_actor"] . "</td>";
                echo "<td>" . $row["nombre_actor"] . "</td>";
                echo "<td>" . $row["fecha_nacimiento"] . "</td>";
                echo "<td>
                     <button class='btn btn-warning' onclick=\"cargarDatos('" . $row["id_actor"] . "', '" . $row["nombre_actor"] . "', '" . $row["fecha_nacimiento"] . "')\">Editar</button>
                     <a href='actores.php?delete_id=" . $row["id_actor"] . "' class='btn btn-danger' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este registro?')\">Eliminar</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay registros</td></tr>";
        }
        ?>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
