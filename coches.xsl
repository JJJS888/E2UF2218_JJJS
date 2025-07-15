<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="html" encoding="UTF-8" indent="yes"/>
<xsl:template match="/coches">
<html>
<head>
<meta charset="UTF-8"/>
<title>Listado de Coches</title>
<style> body { font-family: Arial, sans-serif; margin: 20px; } h1 { color: #333366; } table { border-collapse: collapse; width: 100%; margin-top: 20px; } th, td { border: 1px solid #ccc; padding: 8px; text-align: center; } th { background-color: #f2f2f2; } </style>
</head>
<body>
<h1>Listado de Coches</h1>
<table>
<tr>
<th>Matrícula</th>
<th>Marca</th>
<th>Modelo</th>
<th>Puertas</th>
<th>Color</th>
<th>Precio</th>
<th>Tipo de Venta</th>
</tr>
<xsl:for-each select="coche">
<tr>
<td>
<xsl:value-of select="@matricula"/>
</td>
<td>
<xsl:value-of select="marca"/>
</td>
<td>
<xsl:value-of select="modelo"/>
</td>
<td>
<xsl:value-of select="puertas"/>
</td>
<td>
<xsl:value-of select="color"/>
</td>
<td>
<xsl:value-of select="precio"/>
</td>
<td>
<xsl:value-of select="precio/@venta"/>
</td>
</tr>
</xsl:for-each>
</table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>