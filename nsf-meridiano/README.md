# Meridiano · Widgets Elementor v2.0.0

Identidad **broadsheet**: estética de diario clásico, pensada como la contracara
exacta de `nsf-pulso`. Donde aquél grita en mayúsculas con bloques de color plano,
éste susurra con remates, versalitas, filetes de 1px y capitulares.

## Reglas de la identidad

- **Papel, no blanco puro** (`#fbfaf7`). El blanco de pantalla no es el blanco del diario.
- **Azul marino `#0b2545` como tinta**, dorado apagado `#a8823a` como único acento.
- **Serif con remates para todo lo que se lee.** Nada de sans.
- **Versalitas** en secciones, autores y fechas.
- **Filetes de 1px y reglas dobles**; jamás una barra de color.
- Cero mayúsculas gritadas, cero radios de borde, cero fotos en escala de grises.

## Componentes rediseñados

Los ocho llevan la clase marcadora `.nsfmeridiano-brd` en su `<div>` raíz. Los otros
catorce widgets del plugin quedan fuera de alcance **por construcción**: sin esa clase,
ninguna regla de la capa los alcanza.

| Widget | Disposición |
| ------ | ----------- |
| Header | Línea de servicio arriba (iconos / fecha), y el nombre del diario **centrado y grande** debajo, entre reglas dobles |
| Footer | Invertido: primero las columnas, y la marca cierra abajo centrada, como el colofón de un diario |
| Categoría en Grilla | Retícula con filetes finos; destacada con foto a la izquierda y regla doble arriba |
| Bloque Dinámico | Principal con bajada; laterales como columna de breves con foto chica al costado |
| Archivo por Categoría | **Índice a dos columnas con corondel** (`columns` + `column-rule`); destacada cruzando ambas |
| Lo Último Box | Recuadro con regla doble arriba y cabecera centrada entre filetes |
| Hero Noticias | Portada: **titular arriba de la foto**, centrado — al revés que `nsf-pulso` |
| Página de Nota | Columna única centrada, medida de lectura de 66ch y **capitular** en el arranque |

## La jugada de firma

El índice del archivo usa `columns` + `column-rule`, no `grid`. El **corondel** —el
filete vertical entre columnas— es lo que hace que algo se lea como página de diario,
y no hay forma de conseguirlo con grid sin inventar elementos en el marcado.

```css
.nsfmeridiano-brd .cat-list.layout-list{
  columns: 2;
  column-rule: 1px solid var(--brd-rule);
}
.nsfmeridiano-brd .cat-item{ break-inside: avoid; }
.nsfmeridiano-brd .cat-item.is-featured-archive{ column-span: all; }
```

## Notas de implementación

La capa vive al final de `assets/css/frontend.css`, bajo el bloque
`MERIDIANO · CAPA "BROADSHEET" v2.0`. Todo se resolvió sin tocar el marcado:
`grid-template-areas`, `order` y `columns` reordenan nodos que ya existían.

Dos cosas para tener presentes al seguir tocando esto:

1. **Los `default` de los controles de Elementor le ganan a `frontend.css`.** Elementor
   emite selectores de hasta `(0,5,0)` y encola su CSS *después* del plugin, así que en
   empate gana Elementor. Las 21 opciones por defecto de estos widgets se migraron a la
   paleta broadsheet en el PHP; cambiar sólo la hoja de estilos no habría alcanzado.
2. **Antes de usar `order`, verificar el `display` del contenedor.** En `display:block`
   la propiedad no existe. Los layouts "Grilla" y "Compacto" del archivo ponen
   `.cat-item{display:block}`.

El control *Ancho imagen lista* se reconvirtió: en vez de escribir `grid-template-columns`
sobre `.cat-item` —lo que rompía la destacada— ahora emite la variable
`--brd-archive-img`, y la grilla se arma desde CSS.
