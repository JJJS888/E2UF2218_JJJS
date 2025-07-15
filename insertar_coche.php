<?php
function limpiar($cadena) {
    return htmlspecialchars(trim($cadena));
}

// Cargar XML existente
$xmlFile = "coches.xml";
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;

if (!file_exists($xmlFile)) {
    die("❌ Archivo XML no encontrado.");
}

$xml->load($xmlFile);

// Capturar datos POST
$matricula = limpiar($_POST["matricula"]);
$marca     = limpiar($_POST["marca"]);
$modelo    = limpiar($_POST["modelo"]);
$puertas   = intval($_POST["puertas"]);
$color     = limpiar($_POST["color"]);
$precio    = floatval($_POST["precio"]);
$venta     = limpiar($_POST["venta"]);

// Validaciones básicas
if (!$matricula || !$marca || !$modelo || !$puertas || !$color || !$precio || !$venta) {
    header("Location: index.php?error=incompleto");
    exit;
}

// Verificar duplicado
$xpath = new DOMXPath($xml);
$existe = $xpath->query("//coche[@matricula='$matricula']");
if ($existe->length > 0) {
    header("Location: index.php?error=duplicada");
    exit;
}

// Crear nodo nuevo
$cochesNode = $xml->getElementsByTagName("coches")->item(0);
$nuevoCoche = $xml->createElement("coche");
$nuevoCoche->setAttribute("matricula", $matricula);

$nuevoCoche->appendChild($xml->createElement("marca", $marca));
$nuevoCoche->appendChild($xml->createElement("modelo", $modelo));
$nuevoCoche->appendChild($xml->createElement("puertas", $puertas));
$nuevoCoche->appendChild($xml->createElement("color", $color));

$precioNode = $xml->createElement("precio", $precio);
$precioNode->setAttribute("venta", $venta);
$nuevoCoche->appendChild($precioNode);

$cochesNode->appendChild($nuevoCoche);

// Guardar cambios
if ($xml->save($xmlFile)) {
    header("Location: index.php?insertado=" . urlencode($matricula));
    exit;
} else {
    echo "❌ Error al guardar los cambios en el XML.";
}
?>







