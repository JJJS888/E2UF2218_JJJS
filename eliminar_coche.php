<?php
function limpiar($dato) {
    return htmlspecialchars(trim($dato));
}

$xmlFile = "coches.xml";
$matricula = limpiar($_POST["matricula"]);

$xml = new DOMDocument();
$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;

if (!file_exists($xmlFile)) {
    die("❌ Archivo XML no encontrado.");
}

$xml->load($xmlFile);
$xpath = new DOMXPath($xml);

// Buscar coche por matrícula
$nodos = $xpath->query("//coche[@matricula='$matricula']");

if ($nodos->length === 0) {
    // Matrícula no existe
    header("Location: index.php?error=noexiste");
    exit;
}

// Eliminar el nodo del coche
$cocheNode = $nodos->item(0);
$cocheNode->parentNode->removeChild($cocheNode);

// Guardar cambios en XML
if ($xml->save($xmlFile)) {
    header("Location: index.php?eliminado=" . urlencode($matricula));
    exit;
} else {
    echo "❌ Error al guardar los cambios.";
}
?>
