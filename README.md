# multisitios

Plugins de WordPress + Elementor para una red de portales de noticias. Cada plugin
aporta el mismo conjunto funcional de widgets (header, footer, grillas, archivos,
nota individual, bloques de publicidad, instalador demo) con una **identidad visual
propia**, de modo que cada sitio se lea como un medio distinto desde el momento de
la instalación.

## Plugins

| Plugin | Estado | Identidad |
| ------ | ------ | --------- |
| `nsf-pulso` | **v2.0 — rediseñado** | Berlin Techno: brutalista sobre blanco, rojo señal `#e60023`, retículas visibles, índices numerados, tipografía monoespaciada en etiquetas |
| `nsf-crux` | terminado | Cerrado, no se toca |
| `nsf-arena` | base | — |
| `nsf-atlas` | base | — |
| `nsf-aurora` | base | — |
| `nsf-capital` | base | — |
| `nsf-cenit` | base | — |
| `nsf-eldestape-news` | base | — |
| `nsf-marea` | base | — |
| `nsf-meridiano` | base | — |

Los que figuran como *base* son clones del mismo esqueleto con distinto prefijo
(`nsfarena`, `nsfatlas`, …) y paletas emparentadas. Todavía no tienen identidad
diferenciada.

## Cómo se trabaja

- **La fuente es la carpeta** `nsf-<plugin>/`. Nunca se edita adentro del ZIP.
- **El ZIP es output de build** y está en `.gitignore`. Se regenera con:

  ```sh
  zip -q -r -X nsf-<plugin>.zip nsf-<plugin> -x '*.DS_Store' -x '__MACOSX/*'
  ```

- El ZIP resultante se sube a WordPress desde **Plugins → Añadir nuevo → Subir plugin**.

## Arquitectura de los rediseños

El rediseño de un plugin no reescribe el CSS existente: **agrega una capa al final**
de `assets/css/frontend.css`, acotada por una clase marcadora que se inyecta en el
`<div>` raíz de los widgets alcanzados (en `nsf-pulso` es `.nsfpulso-bln`). Los
widgets que no llevan la clase quedan fuera de alcance por construcción.

Dos cosas que hay que tener presentes al tocar cualquiera de estos plugins:

1. **Los `default` de los controles de Elementor le ganan a `frontend.css`.** Elementor
   emite CSS por página con selectores de hasta `(0,5,0)` y lo encola *después* del
   plugin, así que en empate de especificidad gana Elementor. Cambiar sólo la hoja de
   estilos no alcanza: hay que cambiar también los valores por defecto en el PHP del
   widget.
2. **Al pasar un contenedor de `flex` a `grid`, las propiedades flex que inyecta
   Elementor quedan inertes.** Es la forma limpia de neutralizar un control sin pelear
   especificidad ni recurrir a `!important`.

Cada plugin rediseñado documenta su propia capa en su `README.md`.
