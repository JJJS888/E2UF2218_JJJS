# Gestión de Coches con XML, PHP, Bootstrap y DataTables

Este sistema permite gestionar un catálogo de vehículos utilizando exclusivamente un archivo XML como fuente de datos. Toda la interfaz está desarrollada en PHP con componentes visuales de Bootstrap y funcionalidades interactivas mediante DataTables.

---

# Estructura del Proyecto


# Proyecto


> # coches.xml 
## Archivo principal con todos los coches registrados


> # coches.xsd           
## Esquema XSD para validar estructura del XML


> # coches.xsl            
## Transformación visual del XML a tabla HTML


> # index.php            
## Panel principal con formulario, listado y buscador


># insertar_coche.php
## Lógica para insertar nuevos coches en el XML


> # modificar_coche.php   
## Edición de coches existentes por matrícula


> # eliminar_coche.php    
## Eliminación de coches por matrícula

> # README.md            
## Documentación completa del sistema

---

## Funcionalidades

### Inserción de coches

- Desde `index.php`, el usuario puede añadir un coche completando los campos requeridos: matrícula, marca, modelo, puertas (2 a 5), color, precio y tipo de venta.
- El sistema verifica que:
  - La matrícula tenga formato válido (`1234ABC`)
  - Todos los campos estén completados
  - No exista un coche con la misma matrícula
- Si todo es correcto, `insertar_coche.php` añade el nuevo nodo al archivo `coches.xml`.

**Importante**: No se permite insertar matrículas duplicadas. En caso de coincidencia, se muestra una alerta informativa.

### Modificación de coches

- Desde `index.php`, al pulsar “Editar” se accede a `modificar_coche.php?matricula=...`.
- Se muestra un formulario precargado con los datos actuales.
- Al guardar:
  - Se actualizan los datos del nodo correspondiente en `coches.xml`
  - Si la matrícula no existe, se muestra una alerta indicando que el coche no se encontró.

### Eliminación de coches

- Al pulsar “Eliminar” desde la tabla, `eliminar_coche.php` recibe la matrícula.
- Si existe, elimina el nodo `<coche>` del XML.
- Si no existe, muestra una alerta de error indicando que no se puede eliminar un coche no registrado.

---

## Validaciones estructurales (`coches.xsd`)

El esquema XSD asegura que el archivo `coches.xml` cumple con:

- Matrícula: patrón `0000ABC` (4 dígitos seguidos por 3 letras mayúsculas)
- Puertas: entre 2 y 5
- Precio: entero positivo
- Atributo obligatorio `venta` dentro del nodo `<precio>` con los valores:
  - `nuevo`
  - `ocasión`
  - `segunda mano`

---

## Visualización directa con XSL

Si se abre `coches.xml` en un navegador con soporte XSL, se transforma automáticamente en una tabla HTML gracias a la hoja de estilo `coches.xsl`, con columnas para matrícula, marca, modelo, color, precio y tipo de venta.

---

## Buscador interactivo de coches

La tabla de `index.php` integra **DataTables**, lo que permite:

- Buscar coches por cualquier campo visible, incluyendo:
  - Matrícula (`1234ABC`)
  - Modelo (`Golf`, `Clio`, etc.)
  - Marca, color o tipo de venta
- Filtrado dinámico mientras se escribe
- Orden por columnas (precio, puertas...)
- Paginación automática si hay muchos registros

Ejemplos de uso:
- Escribir “Ford” mostrará todos los coches de esa marca
- Escribir “133” buscará por modelo
- Escribir “1234ABC” ubicará el coche exacto por matrícula

---

## Alertas visuales

El sistema utiliza Bootstrap para mostrar mensajes informativos según el resultado de cada operación. Estos incluyen un botón “X” para cerrar manualmente. Se activan mediante parámetros en la URL:

| Parámetro            | Mensaje mostrado                     |
|----------------------|---------------------------------------|
| `insertado=MAT1234`  | Coche insertado correctamente         |
| `actualizado=MAT1234`| Coche modificado correctamente        |
| `eliminado=MAT1234`  | Coche eliminado correctamente         |
| `error=duplicada`    | Matrícula duplicada                   |
| `error=incompleto`   | Faltan campos obligatorios            |
| `error=noexiste`     | Matrícula no encontrada en el sistema |

---

## Requisitos

- Servidor local con Apache + PHP (XAMPP, WAMP, Laragon...)
- PHP ≥ 7.4 con soporte DOM y SimpleXML
- Permisos de escritura activados sobre el archivo `coches.xml`
- Navegador moderno (Chrome, Firefox…) para ver transformaciones XSL

---
