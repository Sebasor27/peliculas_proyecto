<?php
include 'includes/db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_genero = $_POST["id_genero"];
    $nombre_genero = $_POST["nombre_genero"];
    $is_edit = isset($_POST["is_edit"]) && $_POST["is_edit"] === "true";
    
    if ($is_edit) {
        $sql = "UPDATE Generos SET nombre_genero='$nombre_genero' WHERE id_genero='$id_genero'";
    } else {
        $sql = "INSERT INTO Generos (nombre_genero) VALUES ('$nombre_genero')";
    }

    if ($conn->query($sql) === TRUE) {
        echo 'Registro ' . ($is_edit ? 'actualizado' : 'agregado') . ' exitosamente';
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }
}
if (isset($_GET["delete_id"])) {
    $id_genero = $_GET["delete_id"];
    $sql = "DELETE FROM Generos WHERE id_genero='$id_genero'";
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
    <title>Generos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function cargarDatos(id_genero, nombre_genero) {
            document.getElementById("id_genero").value = id_genero;
            document.getElementById("nombre_genero").value = nombre_genero;
            document.getElementById("is_edit").value = "true";
        }
    </script>
</head>
<body>
    <a href="index.php">Volver a la página principal</a>
    <h1>Generos</h1>
    <form action="generos.php" method="post">
        <input type="hidden" id="id_genero" name="id_genero">

        <label for="nombre_genero">Nombre genero:</label>
        <input type="text" id="nombre_genero" name="nombre_genero" required>
        
        <input type="hidden" id="is_edit" name="is_edit" value="false">
        <input type="submit" value="Guardar">
    </form>

    <h2>Lista de Generos</h2>
    <table>
        <thead>
            <tr>
                <th>Id genero</th>
                <th>Nombre genero</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM Generos";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id_genero"] . "</td>";
                    echo "<td>" . $row["nombre_genero"] . "</td>";
                    echo "<td>
                            <button class='btn btn-warning' onclick=\"cargarDatos('" . $row["id_genero"] . "', '" . $row["nombre_genero"] . "')\">Editar</button>
                            <a href='generos.php?delete_id=" . $row["id_genero"] . "' class='btn btn-danger' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este registro?')\">Eliminar</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No hay registros</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
