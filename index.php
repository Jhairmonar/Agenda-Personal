<?php
$filename = "contactos.txt";

function getContacts($filename) {
    if (!file_exists($filename)) {
        return [];
    }
    return file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

function saveContacts($filename, $contacts) {
    file_put_contents($filename, implode(PHP_EOL, $contacts));
}

$editIndex = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$nombre = $contacto = $imagenBase64 = "";

if ($editIndex !== null) {
    $contacts = getContacts($filename);
    if (isset($contacts[$editIndex])) {
        list($nombre, $contacto, $imagenBase64) = explode('|', $contacts[$editIndex]);
    }
}

$mensajeError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && ($_POST['accion'] === 'guardar' || $_POST['accion'] === 'editar')) {
    $nombre = trim($_POST['nombre']);
    $contacto = trim($_POST['contacto']);
    $imagenBase64 = '';

    if (!empty($_FILES['imagen']['tmp_name'])) {
        $imagenData = file_get_contents($_FILES['imagen']['tmp_name']);
        $imagenBase64 = base64_encode($imagenData);
    }

    if (!empty($nombre) && !empty($contacto)) {
        $contacts = getContacts($filename);
        $nombresExistentes = array_map(function ($line) {
            return strtolower(explode('|', $line)[0]); // Convertir el nombre a minúsculas
        }, $contacts);

        if (in_array(strtolower($nombre), $nombresExistentes)) {
            $mensajeError = "El usuario ya está ingresado.";
        } else {
            if ($_POST['accion'] === 'editar' && isset($_POST['index'])) {
                $contacts[(int)$_POST['index']] = $nombre . "|" . $contacto . "|" . $imagenBase64;
            } else {
                $contacts[] = $nombre . "|" . $contacto . "|" . $imagenBase64;
            }

            saveContacts($filename, $contacts);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    } else {
        $mensajeError = "Por favor, complete todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Personal</title>
    <link rel="stylesheet" href="style.css">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if (!empty($mensajeError)): ?>
                alert("<?= $mensajeError ?>");
            <?php endif; ?>
        });
    </script>
</head>
<body>
    <header>
        <h1 class="titulo"><?= $editIndex !== null ? 'Editar Contacto' : 'Agregar Contacto' ?></h1>
    </header>
    <main>
        <section class="main_section">
            <form class="main_form" method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="<?= $editIndex !== null ? 'editar' : 'guardar' ?>">
                <?php if ($editIndex !== null): ?>
                    <input type="hidden" name="index" value="<?= $editIndex ?>">
                <?php endif; ?>

                <label for="nombre" id="name">Nombre: </label>
                <br>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" placeholder="Ingrese el nombre" required>
                <br>
                <label for="contacto" id="contact">Contacto: </label>
                <br>
                <input type="text" id="contacto" name="contacto" value="<?= htmlspecialchars($contacto) ?>" placeholder="Ingrese el contacto" required>
                <br>
                <label class="caja_subida" id="drop-area">
                    <span class="plus">+</span>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                    <img id="preview" style="display: none; width: 100px; height: 100px; border-radius: 50%; margin-top: 10px;">
                </label>

                <?php if ($imagenBase64): ?>
                    <img src="data:image/png;base64,<?= $imagenBase64 ?>" alt="Imagen actual" style="width: 50px; height: 50px; border-radius: 50%;">
                    <br>
                <?php endif; ?>
                <button type="submit" class="boton"><?= $editIndex !== null ? 'Actualizar' : 'Guardar' ?></button>
            </form>

            <form class="enlace_contactos" action="base.php" method="get">
                <button type="submit" class="boton">Ver Contactos</button>
            </form>
        </section>
    </main>
</body>
</html>
