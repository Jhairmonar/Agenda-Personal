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

if (isset($_POST['update'])) {
    $index = (int)$_POST['index'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    $contacts = getContacts($filename);
    if (isset($contacts[$index])) {
        $contacts[$index][$field] = $value;
        saveContacts($filename, $contacts);
    }
    exit;
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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
                            <td contenteditable="true" data-index="<?= $index ?>" data-field="name" class="editable"> <?= htmlspecialchars($contact['name']) ?> </td>
                            <td contenteditable="true" data-index="<?= $index ?>" data-field="number" class="editable"> <?= htmlspecialchars($contact['number']) ?> </td>
                            <td>
                                <?php if (!empty($contact['profile'])): ?>
                                    <img src="data:image/png;base64,<?= htmlspecialchars($contact['profile']) ?>" alt="Perfil" style="width: 50px; height: 50px; border-radius: 50%;">
                                <?php else: ?>
                                    Sin imagen
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?delete=<?= $index ?>" class="delete-btn">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('.editable').on('blur', function() {
                var index = $(this).data('index');
                var field = $(this).data('field');
                var value = $(this).text().trim();
                $.post('', { update: true, index: index, field: field, value: value });
            });
            
            $('#contactTable').DataTable({
                "language": {
                    "sProcessing": "Procesando...",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sSearch": "Buscar:",
                    "sLengthMenu": "Mostrar _MENU_ contactos",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de _MAX_ registros en total)",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                },
                "pageLength": 3,
                "lengthMenu": [[2,3, 5], [2,3, 5]],
                "order": [[0, "asc"]]
            });
        });
    </script>
</body>
</html>
