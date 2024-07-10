<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_director = $_POST["nombre_director"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $is_edit = isset($_POST["is_edit"]) && $_POST["is_edit"] === "true";
    
    if ($is_edit) {
        $id_director = $_POST["id_director"];
        $sql = "UPDATE Directores SET nombre_director='$nombre_director', fecha_nacimiento='$fecha_nacimiento' WHERE id_director='$id_director'";
    } else {
        $sql = "INSERT INTO Directores (nombre_director, fecha_nacimiento) VALUES ('$nombre_director', '$fecha_nacimiento')";
    }

    if ($conn->query($sql) === TRUE) {
        echo 'Registro ' . ($is_edit ? 'actualizado' : 'agregado') . ' exitosamente';
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }
}

if (isset($_GET["delete_id"])) {
    $id_director = $_GET["delete_id"];
    $sql = "DELETE FROM Directores WHERE id_director='$id_director'";
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
    <title>Directores</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
</head>
<body>
    <a href="index.php">Volver a la página principal</a>
    <h1>Directores</h1>
    <form action="directores.php" method="post">
        <input type="hidden" id="id_director" name="id_director">

        <label for="nombre_director">Nombre director:</label>
        <input type="text" id="nombre_director" name="nombre_director" required>

        <label for="fecha_nacimiento">Fecha nacimiento:</label>
        <input type="text" id="fecha_nacimiento" name="fecha_nacimiento" required>
        
        <input type="hidden" id="is_edit" name="is_edit" value="false">
        <input type="submit" value="Guardar">
    </form>

    <h2>Lista de Directores</h2>
    <table>
        <tr>
            <th>Id director</th>
            <th>Nombre director</th>
            <th>Fecha nacimiento</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT * FROM Directores";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_director"] . "</td>";
                echo "<td>" . $row["nombre_director"] . "</td>";
                echo "<td>" . $row["fecha_nacimiento"] . "</td>";
                echo "<td>
                     <button class='btn btn-warning' onclick=\"cargarDatos('" . $row["id_director"] . "', '" . $row["nombre_director"] . "', '" . $row["fecha_nacimiento"] . "')\">Editar</button>
                     <a href='directores.php?delete_id=" . $row["id_director"] . "' class='btn btn-danger' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este registro?')\">Eliminar</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay registros</td></tr>";
        }
        ?>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function cargarDatos(id_director, nombre_director, fecha_nacimiento) {
            document.getElementById("id_director").value = id_director;
            document.getElementById("nombre_director").value = nombre_director;
            document.getElementById("fecha_nacimiento").value = fecha_nacimiento;
            document.getElementById("is_edit").value = "true";
        }
    </script>
</body>
</html>

