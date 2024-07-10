<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $año = $_POST["año"];
    $is_edit = isset($_POST["is_edit"]) && $_POST["is_edit"] === "true";
    
    if ($is_edit) {
        $id_pelicula = $_POST["id_pelicula"];
        $sql = "UPDATE Peliculas SET titulo='$titulo', año='$año' WHERE id_pelicula='$id_pelicula'";
    } else {
        // No incluir id_pelicula en la inserción
        $sql = "INSERT INTO Peliculas (titulo, año) VALUES ('$titulo', '$año')";
    }

    if ($conn->query($sql) === TRUE) {
        echo 'Registro ' . ($is_edit ? 'actualizado' : 'agregado') . ' exitosamente';
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }
}

if (isset($_GET["delete_id"])) {
    $id_pelicula = $_GET["delete_id"];
    $sql = "DELETE FROM Peliculas WHERE id_pelicula='$id_pelicula'";
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
    <title>Peliculas</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script>
        function cargarDatos(id_pelicula, titulo, año) {
            document.getElementById("id_pelicula").value = id_pelicula;
            document.getElementById("titulo").value = titulo;
            document.getElementById("año").value = año;
            document.getElementById("is_edit").value = "true";
        }
    </script>
</head>
<body>
    <a href="index.php">Volver a la página principal</a>
    <h1>Peliculas</h1>
    <form action="peliculas.php" method="post">
        <input type="hidden" id="id_pelicula" name="id_pelicula">
        
        <label for="titulo">Titulo:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="año">Año:</label>
        <input type="text" id="año" name="año" required>
        
        <input type="hidden" id="is_edit" name="is_edit" value="false">
        <input type="submit" value="Guardar">
    </form>

    <h2>Lista de Peliculas</h2>
    <table>
        <tr>
            <th>Id pelicula</th>
            <th>Titulo</th>
            <th>Año</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT * FROM Peliculas";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_pelicula"] . "</td>";
                echo "<td>" . $row["titulo"] . "</td>";
                echo "<td>" . $row["año"] . "</td>";
                echo "<td>
                     <button class='btn btn-warning' onclick=\"cargarDatos('" . $row["id_pelicula"] . "', '" . $row["titulo"] . "', '" . $row["año"] . "')\">Editar</button>
                     <a href='peliculas.php?delete_id=" . $row["id_pelicula"] . "' class='btn btn-danger' onclick=\"return confirm('¿Estás seguro de que deseas eliminar este registro?')\">Eliminar</a></td>";
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

