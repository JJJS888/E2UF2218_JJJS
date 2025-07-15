<?php
function limpiar($dato) {
    return htmlspecialchars(trim($dato));
}

$xmlFile = "coches.xml";
$xml = simplexml_load_file($xmlFile);

// 🎯 Detectar mensajes
$alerta = null;
if (isset($_GET["insertado"])) {
    $mat = limpiar($_GET["insertado"]);
    $alerta = ["success", "✔️ Coche <strong>$mat</strong> insertado correctamente."];
} elseif (isset($_GET["actualizado"])) {
    $mat = limpiar($_GET["actualizado"]);
    $alerta = ["primary", "🔧 Coche <strong>$mat</strong> modificado correctamente."];
} elseif (isset($_GET["eliminado"])) {
    $mat = limpiar($_GET["eliminado"]);
    $alerta = ["danger", "🗑️ Coche <strong>$mat</strong> eliminado correctamente."];
} elseif (isset($_GET["error"])) {
    switch ($_GET["error"]) {
        case "duplicada":  $alerta = ["warning", "⚠️ Matrícula duplicada."]; break;
        case "incompleto": $alerta = ["warning", "⚠️ Faltan campos obligatorios."]; break;
        case "noexiste":   $alerta = ["danger",  "❌ Matrícula no encontrada."]; break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Coches (XML)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<a href="https://www.google.com/?hl=es" class="btn btn-light" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
  🔚 SALIR
</a>

<body class="bg-light">
<center><H1 class="text-secondary"><bi><i>CONCESIONARIOS-LANTIGUA</i></bi></H1></center>
<div class="container py-5">
    <h2 class="mb-4 text-secondary">Insertar Nuevo Coche</h2>

    <?php if ($alerta): ?>
        <div class="alert alert-<?= $alerta[0] ?> alert-dismissible fade show text-center" role="alert">
            <?= $alerta[1] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <form action="insertar_coche.php" method="post" class="bg-white p-4 shadow rounded mb-5">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Matrícula</label>
                <input type="text" name="matricula" class="form-control" required pattern="\d{4}[A-Z]{3}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Marca</label>
                <input type="text" name="marca" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Modelo</label>
                <input type="text" name="modelo" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Puertas</label>
                <input type="number" name="puertas" class="form-control" required min="2" max="5">
            </div>
            <div class="col-md-3">
                <label class="form-label">Color</label>
                <input type="text" name="color" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Precio</label>
                <input type="number" step="0.01" name="precio" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo de venta</label>
                <select name="venta" class="form-select" required>
                    <option value="">Selecciona</option>
                    <option value="nuevo">Nuevo</option>
                    <option value="ocasión">Ocasión</option>
                    <option value="segunda mano">Segunda Mano</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-outline-primary mt-4">Insertar</button>
    </form>

    <h2 class="mb-3 text-secondary">Listado de Coches</h2>
    <?php if ($xml && count($xml->coche) > 0): ?>
    <table id="tabla-coches" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-secondary">Matrícula</th>
                <th class="text-secondary">Marca</th>
                <th class="text-secondary">Modelo</th>
                <th class="text-secondary">Puertas</th>
                <th class="text-secondary">Color</th>
                <th class="text-secondary">Precio</th>
                <th class="text-secondary">Tipo de Venta</th>
                <th class="text-secondary">Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($xml->coche as $coche): ?>
            <tr>
                <td><?= $coche["matricula"] ?></td>
                <td><?= ucfirst($coche->marca) ?></td>
                <td><?= ucfirst($coche->modelo) ?></td>
                <td><?= $coche->puertas ?></td>
                <td><?= ucfirst($coche->color) ?></td>
                <td><?= number_format((float)$coche->precio, 2) ?> €</td>
                <td><?= ucfirst($coche->precio["venta"]) ?></td>
                <td class="d-flex gap-1 justify-content-center">
                    <form action="modificar_coche.php" method="get">
                        <input type="hidden" name="matricula" value="<?= $coche["matricula"] ?>">
                        <button type="submit" class="btn btn-outline-success btn-sm">Editar</button>
                    </form>
                    <form action="eliminar_coche.php" method="post" onsubmit="return confirm('¿Eliminar <?= $coche["matricula"] ?>?');">
                        <input type="hidden" name="matricula" value="<?= $coche["matricula"] ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="alert alert-info">No hay coches registrados en el XML.</div>
    <?php endif; ?>
</div>

<!-- Bootstrap & jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tabla-coches').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            pageLength: 10
        });
    });
</script>
</body>
</html>

