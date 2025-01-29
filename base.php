<?php
$filename = "contactos.txt";

function getContacts($filename) {
    if (!file_exists($filename)) {
        return [];
    }
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $contacts = [];
    foreach ($lines as $line) {
        $data = explode('|', $line, 3);
        $data = array_pad($data, 3, '');
        list($name, $number, $profile) = $data;
        $contacts[] = ['name' => $name, 'number' => $number, 'profile' => $profile];
    }
    return $contacts;
}

function saveContacts($filename, $contacts) {
    $lines = [];
    foreach ($contacts as $contact) {
        $lines[] = $contact['name'] . "|" . $contact['number'] . "|" . $contact['profile'];
    }
    file_put_contents($filename, implode(PHP_EOL, $lines));
}

if (isset($_GET['delete'])) {
    $index = (int)$_GET['delete'];
    $contacts = getContacts($filename);
    if (isset($contacts[$index])) {
        unset($contacts[$index]);
        $contacts = array_values($contacts);
        saveContacts($filename, $contacts);
    }
    header("Location: base.php");
    exit;
}

$contacts = getContacts($filename);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Contactos</title>
    <link rel="stylesheet" href="base.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Contactos</h1>
        <table id="contactTable" class="display">
            <thead>
                <tr>
                    <th>Nombre del Contacto</th>
                    <th>Número de Contacto</th>
                    <th>Perfil</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contacts)): ?>
                    <tr>
                        <td colspan="4">No hay contactos disponibles.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contacts as $index => $contact): ?>
                        <tr>
                            <td><?= htmlspecialchars($contact['name']) ?></td>
                            <td><?= htmlspecialchars($contact['number']) ?></td>
                            <td>
                                <?php if (!empty($contact['profile'])): ?>
                                    <img src="data:image/png;base64,<?= htmlspecialchars($contact['profile']) ?>" alt="Perfil" style="width: 50px; height: 50px; border-radius: 50%;">
                                <?php else: ?>
                                    Sin imagen
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?delete=<?= $index ?>" class="delete-btn">Eliminar</a>
                                <a href="index.php?edit=<?= $index ?>" class="edit-btn">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="footer-row">
                        <button class="agregar_contactos" onclick="location.href='index.php'">Agregar Nuevo Contacto</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contactTable').DataTable({
                "language": {
                    "sProcessing": "Procesando...",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de MAX registros)",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                },
                "pageLength": 5,
                "lengthMenu": [[3, 5,10, -1], [3, 5,10, "Todos"]],
                "order": [[0, "asc"]]
            });
        });
    </script>
</body>
</html>