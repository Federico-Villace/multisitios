# Pulso · Widgets Elementor v2.0.0

Rediseño visual avanzado con layout propio, manteniendo los widgets, shortcodes, instalador demo y estructura funcional del plugin base.

## Identidad visual v2.0.0 — "Berlin Techno"

Estética brutalista tecno-berlinesa **sobre fondo blanco**: tinta negra `#0a0a0a` para el texto, **rojo señal `#e60023` como único acento**, cero radios de borde, retículas y filetes visibles, índices numerados (`01`, `02`, `03`), fotografía en escala de grises que se enciende al pasar el cursor, y tipografía **monoespaciada** para toda etiqueta, meta y numeración frente a grotesca pesada en mayúsculas para los titulares.

**El fondo es blanco en todas las superficies**, incluido header y footer. Un portal de noticias es superficie de lectura larga y, sobre todo, inventario publicitario: los creativos de los anunciantes vienen diseñados sobre blanco y en un contenedor oscuro quedan flotando con halos. Este plugin monta cuatro widgets de publicidad (`banner-ad`, `sidebar-ad`, `middle-ad`, `footer-ad`), así que el fondo claro no es preferencia estética, es requisito funcional.

El negro se usa **sólo como tinta y como relleno de píldoras/chips**, nunca como superficie.

### Componentes rediseñados

| Widget | Clase raíz | Tratamiento |
| ------ | ---------- | ----------- |
| Header Pulso | `nsfpulso-header-widget nsfpulso-bln` | Blanco con trama roja superior y filete rojo inferior, logo en tinta con cuadro rojo, botonera cuadrada de bordes colapsados, EN VIVO en píldora roja con parpadeo, canvas lateral con menú numerado |
| Footer Pulso | `nsfpulso-bln` | Contratapa de sello en blanco: filete rojo superior + trama diagonal, columnas indexadas, links monoespaciados con flecha en hover |
| Categoría con Notas en Grilla | `nsfpulso-category-grid-posts nsfpulso-bln` | Cabecera de bloque con cuadro rojo, tarjetas numeradas sobre filete negro, destacada a dos columnas |
| Bloque Noticias Dinámico | `nsfpulso-bln` | Superficie blanca con filete rojo, chip rojo de sección, columna lateral como lista de pistas numerada |
| Archivo de Notas por Categoría | `nsfpulso-archive-widget nsfpulso-bln` | Índice de catálogo: cabecera monumental, filas indexadas con hairlines, destacada marcada por filete rojo grueso y cuerpo tipográfico |
| Lo Último Box | `nsfpulso-bln` | Caja blanca con filete rojo superior, cabecera con chip de sección y listado numerado |

| Hero Noticias | `nsfpulso-hero-news nsfpulso-bln` | Banda panorámica 21:9 con el bloque de texto montado sobre la foto, y secundarias en tira horizontal numerada separada por filetes |

El cuadro rojo de 13–15 px se repite como motivo en el logo del header, el logo del footer, el logo del canvas y la cabecera de la grilla de categoría: es lo que hace que las piezas lean como un solo sistema.

## Layouts v2.1 — ritmo asimétrico

La v2.0 cambió la estética pero mantuvo las grillas simétricas de celdas iguales. La v2.1 rompe eso. Vive en el bloque `LAYOUTS v2.1` al final de `frontend.css`.

- **Header** → de una fila con logo centrado a **dos bandas**: tira de estado arriba (fecha izquierda, EN VIVO + CLUB derecha) y marca izquierda / botonera derecha abajo. En móvil la tira baja y queda como banda inferior.
- **Footer** → de marca en columna angosta a **banda de marca horizontal** a todo el ancho (logo · descripción · redes), con las columnas debajo ocupando todo el ancho y la barra inferior invertida.
- **Categoría en Grilla** → **mosaico**: una de cada seis celdas ocupa doble ancho y va en horizontal; otra de cada seis va sin foto, sólo tipografía sobre hormigón con barra roja. La destacada es panorámica a sangre con el texto montado.
- **Archivo por Categoría** → **zigzag**: la foto alterna de lado fila por fila, con la estructura espejada (pero la bajada siempre alineada a la izquierda por legibilidad).
- **Hero Noticias** → de "principal grande + columna al costado" a **banda panorámica + tira horizontal** de secundarias numeradas.
- **Lo Último Box** → de lista vertical a **destacada con foto arriba + grilla numerada** que se acomoda sola al ancho del contenedor (`auto-fill`): una columna en sidebar, dos o tres si la caja es ancha.

### Notas de implementación

Todo se resolvió **sin tocar el marcado PHP**: `grid-template-areas`, `order` y `nth-child` reordenan nodos que ya existían. El orden visual es CSS; el orden semántico es HTML. Mezclarlos rompe los enganches JS del drawer.

Dos trampas de especificidad que hay que tener presentes al seguir tocando esto:

1. Elementor emite selectores de hasta **(0,5,0)** y su CSS se encola **después** del plugin. En empate gana Elementor. Las reglas que pelean contra un control (proporciones de imagen, sobre todo) necesitan `.nsfpulso-scope` al frente para llegar a (0,6,0).
2. Al pasar un contenedor de `flex` a `grid`, las propiedades flex que inyecta Elementor (`flex-basis`) quedan **inertes**. Es la forma limpia de neutralizar un control sin `!important`.

Controles reconvertidos para que ninguno quede fantasma ni rompa las grillas nuevas: `header_left_width` → ancho máximo de la marca; `header_height` → sólo `min-height`; `brand_width` → ancho de la descripción; `image_width` del archivo → variable `--bln-archive-img`; y un control nuevo en el hero, `overlap`, que gobierna cuánto monta el texto sobre la foto.

### Arquitectura de estilos

La capa nueva vive al final de `assets/css/frontend.css` bajo el bloque `PULSO · CAPA "BERLIN TECHNO" v2.0` y **sólo aplica a los elementos marcados con `.nsfpulso-bln`**. Los otros dieciséis widgets del plugin quedan intactos.

> **Importante:** los `default` de los controles de Elementor generan CSS por página con mayor especificidad que `frontend.css`. Por eso los valores por defecto de color, radio y alineación de esos seis widgets se actualizaron en el PHP; si sólo se toca la hoja de estilos, la paleta vieja sigue ganando.

## Cambios de layout v1.1.0

- Maqueta completa reescrita en `templates/full-demo.php`.
- CSS específico en `assets/css/frontend.css`, scopeado bajo `nsfpulso-scope` para evitar conflictos.
- Drawer, búsqueda, switch de vistas, portada, nota, banners, grillas y footer conservados.
- Estética diferenciada según el medio de referencia, sin usar logos ni imágenes propietarias.

## Shortcode

`[nsfpulso_demo view="cat" switcher="yes"]`

## Instalación

Subir el ZIP desde **Plugins > Añadir nuevo > Subir plugin**. Luego ir a **Herramientas > Pulso Demo** para crear páginas de prueba.
