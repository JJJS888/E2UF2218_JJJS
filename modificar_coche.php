<?php
function limpiar($dato) {
    return htmlspecialchars(trim($dato));
}

$xmlFile = "coches.xml";
$mensaje = "";
$coche = null;

// 📤 Proceso de modificación
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matricula = limpiar($_POST["matricula"]);
    $marca     = limpiar($_POST["marca"]);
    $modelo    = limpiar($_POST["modelo"]);
    $puertas   = intval($_POST["puertas"]);
    $color     = limpiar($_POST["color"]);
    $precio    = floatval($_POST["precio"]);
    $venta     = limpiar($_POST["venta"]);

    // Cargar XML
    $xml = new DOMDocument();
    $xml->preserveWhiteSpace = false;
    $xml->formatOutput = true;
    $xml->load($xmlFile);

    $xpath = new DOMXPath($xml);
    $nodos = $xpath->query("//coche[@matricula='$matricula']");

    if ($nodos->length === 0) {
        $mensaje = "❌ Matrícula <strong>$matricula</strong> no encontrada.";
    } else {
        $cocheNode = $nodos->item(0);

        $cocheNode->getElementsByTagName("marca")->item(0)->nodeValue = $marca;
        $cocheNode->getElementsByTagName("modelo")->item(0)->nodeValue = $modelo;
        $cocheNode->getElementsByTagName("puertas")->item(0)->nodeValue = $puertas;
        $cocheNode->getElementsByTagName("color")->item(0)->nodeValue = $color;

        $precioNode = $cocheNode->getElementsByTagName("precio")->item(0);
        $precioNode->nodeValue = $precio;
        $precioNode->setAttribute("venta", $venta);

        if ($xml->save($xmlFile)) {
            header("Location: index.php?actualizado=" . urlencode($matricula));
            exit;
        } else {
            $mensaje = "❌ Error al guardar los cambios.";
        }
    }
}

// 📥 Mostrar datos en el formulario
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["matricula"])) {
    $matricula = limpiar($_GET["matricula"]);

    $xml = simplexml_load_file($xmlFile);
    foreach ($xml->coche as $item) {
        if ((string)$item['matricula'] === $matricula) {
            $coche = $item;
            break;
        }
    }

    if (!$coche) {
        $mensaje = "❌ Matrícula no encontrada.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Modificar Coche <?= htmlspecialchars($_GET["matricula"] ?? "") ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">

  <h2 class="mb-4 text-secondary">Modificar Coche: <?= htmlspecialchars($_GET["matricula"] ?? "") ?></h2>

  <?php if ($mensaje): ?>
    <div class="alert alert-warning"><?= $mensaje ?></div>
  <?php endif; ?>

  <?php if ($coche): ?>
  <form action="modificar_coche.php" method="post" class="bg-white p-4 shadow rounded">
    <input type="hidden" name="matricula" value="<?= htmlspecialchars($coche["matricula"]) ?>">

    <div class="mb-3">
      <label class="form-label">Marca</label>
      <input type="text" name="marca" class="form-control" value="<?= $coche->marca ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Modelo</label>
      <input type="text" name="modelo" class="form-control" value="<?= $coche->modelo ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Puertas</label>
      <input type="number" name="puertas" class="form-control" value="<?= $coche->puertas ?>" min="2" max="5" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Color</label>
      <input type="text" name="color" class="form-control" value="<?= $coche->color ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Precio</label>
      <input type="number" step="0.01" name="precio" class="form-control" value="<?= $coche->precio ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Tipo de Venta</label>
      <select name="venta" class="form-select" required>
        <option value="nuevo"        <?= $coche->precio["venta"] == "nuevo" ? "selected" : "" ?>>Nuevo</option>
        <option value="ocasión"      <?= $coche->precio["venta"] == "ocasión" ? "selected" : "" ?>>Ocasión</option>
        <option value="segunda mano" <?= $coche->precio["venta"] == "segunda mano" ? "selected" : "" ?>>Segunda Mano</option>
      </select>
    </div>

    <button type="submit" class="btn btn-outline-success">Guardar Cambios</button>
    <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
  </form>
  <?php endif; ?>

</div>
</body>
</html>




