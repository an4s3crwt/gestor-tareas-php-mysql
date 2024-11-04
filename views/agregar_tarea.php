<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Tareas</title>
    <link rel="stylesheet" href="estilos/agregar.css">
</head>

<body>
    <div class="container">
        <h1>Agregar Tarea</h1>
        <form action="agregar.php" method="post" class="form">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre_tarea" id="nombre" required>


            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" required></textarea>

            <label for="prioridad">Prioridad:</label>
            <select name="prioridad" id="prioridad" required>
                <option value="baja">Baja</option>
                <option value="media">Media</option>
                <option value="alta">Alta</option>
            </select>

            <label for="fecha_limite">Fecha Límite:</label>
            <input type="date" name="fecha_limite" id="fecha_limite" required>

            <button type="submit" class="btn">Agregar Tarea</button>
        </form>

        <a href="index.php" class="link">Ir a la lista</a>

    </div>

</body>

</html>