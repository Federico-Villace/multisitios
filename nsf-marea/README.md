# Noticias del Conurbano · Widgets Elementor v2.0.0

Identidad **papel de diario**. El logo es un sello entintado —letra ancha, muy
negra, con textura de impresión— y de ahí sale toda la dirección visual.

## Reglas de la identidad

- **Papel de diario real** (`#e7e3da`), no blanco ni papel caro. Con una trama
  de puntos muy tenue que imita el grano de la impresión, resuelta con un
  degradado repetido: no agrega ningún pedido extra.
- **Grotesca ancha y negrísima** en titulares (Archivo Black), deliberadamente
  lo opuesto a la condensada que usa el plugin hermano.
- **Cuerpo en serif**: es lo que se imprime en un diario.
- **Fotos con esquinas redondeadas** — el único plugin de la familia que las usa.
- **Retícula densa**: módulos chicos, fotos chicas, muchas notas por pantalla.
- Las fichas son **recortes pegados sobre el papel**: fondo más claro que el
  fondo, esquinas redondeadas y una sombra mínima.

## Paleta

| | |
| --- | --- |
| Papel | `#e7e3da` |
| Recorte | `#f4f1eb` |
| Tinta | `#1b1a17` |
| Rojo de sello | `#a82b1e` |
| Filete | `#b9b3a5` |

## Componentes rediseñados

Los ocho llevan la clase marcadora `.nsfmarea-np`. Los otros catorce widgets
quedan fuera de alcance **por construcción**: sin esa clase, ninguna regla de la
capa los alcanza.

| Widget | Disposición |
| ------ | ----------- |
| Header | Bandera a la izquierda en ele: el logo ocupa las dos filas, y a su derecha van la tira de servicio arriba y las secciones abajo |
| Footer | Bloque entintado que cierra el diario, con el logo grande arriba y las columnas debajo |
| Categoría en Grilla | Retícula densa de recortes, 4 columnas; destacada horizontal compacta, no panorámica |
| Bloque Dinámico | Principal compacta y laterales como fichas horizontales chicas |
| Archivo por Categoría | Índice denso: miniatura chica a la izquierda y texto a la derecha, muchas notas por pantalla |
| Lo Último Box | Lista compacta con miniatura cuadrada a la derecha |
| Hero Noticias | Texto a la izquierda y foto a la derecha, del tamaño de una foto de tapa; secundarias en fila densa |
| Página de Nota | Columna medida alineada a la izquierda, con ficha de datos enmarcada en lugar de capitular |
| Buscador | Recorte sobre el papel, con el campo en píldora y el envío en rojo de sello |

## Notas de implementación

La capa vive al final de `assets/css/frontend.css`. Todo se resolvió sin tocar el
marcado salvo para agregar la tira de secciones del header.

Tres cosas para tener presentes al seguir tocando esto:

1. **Los `default` de los controles de Elementor le ganan a `frontend.css`.**
   Elementor emite selectores de hasta `(0,5,0)` y encola su CSS *después* del
   plugin, así que en empate gana Elementor. Las 21 opciones por defecto de color
   se migraron en el PHP.
2. **El tema define su propio `.site-header`** y le impone un ancho máximo. Como
   el marcado del widget usa el mismo nombre de clase, el tema se lo aplica
   creyendo que es el suyo. Está neutralizado en la capa.
3. **Antes de usar `order`, verificar el `display` del contenedor.** En
   `display:block` la propiedad no existe.

El control *Ancho imagen lista* se reconvirtió: en vez de escribir
`grid-template-columns` sobre `.cat-item` —lo que fijaba el orden texto|foto e
impedía cualquier maqueta nueva— ahora emite la variable `--nc-archive-img`.
