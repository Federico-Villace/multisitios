# Cenit · Widgets Elementor v2.3.0

Identidad **8 bits sobrios**: lenguaje de pixel art aplicado al envase de la
interfaz, no al contenido.

## La regla que ordena todo

Las piezas de interfaz —etiquetas, marcos, separadores, el logo— hablan en 8
bits. Los titulares y el cuerpo van en una grotesca normal y legible.

Una tipografía bitmap en texto corrido es ilegible y volvería el sitio un juego
en lugar de un portal de noticias. **Ese contraste es lo que lo mantiene serio.**

## Cuatro gestos, y nada más

1. **Esquinas biseladas** (`clip-path`), nunca redondeadas. Un octógono de
   esquinas cortadas: es lo que el ojo lee como "pixel" sin dibujar la escalera
   entera, y a diferencia de `border-radius` no suaviza nada, corta en recto.
2. **Retícula de 8px** en todos los espacios.
3. **Trama de damero** (`repeating-conic-gradient`) para separadores y para las
   fotos faltantes: sin imagen queda una textura en vez de un gris plano.
4. **Sombras duras sin desenfoque**, desplazadas, como una interfaz vieja.

Las transiciones usan `steps()` en lugar de curvas suaves: el movimiento salta,
no se desliza. El canvas desplegable baja en ocho pasos y el punto de EN VIVO
parpadea sin interpolar.

## Giro de la v2.1 — estructura moderna

La v2.0 llevaba el lenguaje de 8 bits a casi todas las piezas: biseles por todas
partes, damero en las tiras, bitmap en cada etiqueta y transiciones a saltos.
Leía como una interfaz retro antes que como un portal de noticias.

**El gesto retro funciona cuando es excepción, no cuando es norma.** En la v2.1
queda reducido a tres rastros:

1. El **logo** en bitmap, con una barra de acento al costado.
2. El **verdigrís** como único color de acento.
3. Una **sombra dura** en las fotos jerárquicas —tapa, destacadas, nota— que
   marca la jerarquía sin necesidad de agrandar la imagen.

Todo lo demás pasa a estructura contemporánea: más aire, tipografía más grande,
filetes de 1px en lugar de 2 y 3, y la jerarquía puesta en el **cuerpo de la
letra** en vez de en los marcos. Se retiran los biseles de las fotos —ocho
esquinas cortadas en cada miniatura de una retícula leen como defecto, no como
estilo—, el damero de las tiras, y las transiciones a pasos, que eran el gesto
más agresivo y el que más envejecía la interfaz.

### Header
Una sola banda con aire: logo con su barra de acento a la izquierda y la
navegación en la misma fila, con cuerpo más grande y un subrayado de acento que
crece desde la izquierda al pasar el cursor. Se fueron las pestañas y la tira de
damero.

### Footer
La marca sale de la fila horizontal apretada: logo en cuerpo grande arriba,
descripción debajo, y las columnas separadas por aire en lugar de filetes.

### Hero Noticias
Entra a la identidad. **El titular manda y la foto acompaña**, no al revés: la
imagen baja de media pantalla a poco más de un tercio, regulable con el control
*Ancho de la foto principal* (36% por defecto), y el peso lo lleva el titular,
que es lo que define una tapa. Las secundarias van en una fila limpia bajo el
filete, separadas por aire.

### Escala
| | |
| --- | --- |
| Titular de tapa y de nota | `clamp(32px, 4.6vw, 62px)` |
| Destacadas y sección | `clamp(24px, 2.9vw, 40px)` |
| Cabeceras de bloque | `clamp(19px, 2.1vw, 27px)` |
| Titulares de ficha | `18.5px` |
| Cuerpo | `18px` |

Si sólo creciera el hero la página se desarmaría: los titulares de las retículas
acompañan la escala nueva o la jerarquía se rompe.

## Tipografía — una sola familia

Hasta la v2.2 convivían dos: una bitmap para el logo y las etiquetas, y una
grotesca para titulares y cuerpo. No comparten nada —ni proporción, ni
terminaciones, ni eje— y el conjunto se leía como dos sitios pegados.

Pasa a una sola: **Chakra Petch**. Tiene las terminaciones cortadas en recto que
dan el aire digital del pixel, pero es una tipografía de texto y se lee sin
esfuerzo en párrafo. Más legible y menos dura, con el mismo formato en todas
partes.

Lo que distingue a una etiqueta de un titular deja de ser la familia y pasa a ser
lo que corresponde: **el cuerpo, el peso y el interletrado**.

| Rol | Peso | Tratamiento |
| --- | --- | --- |
| Logo | 700 | Mayúsculas, interletrado `.16em` |
| Titulares | 700 | Caja normal, interletrado negativo |
| Etiquetas y controles | 600 | Mayúsculas, interletrado `.13em`, 11px |
| Cuerpo y bajadas | 400 | Caja normal |

Las dos variables `--f-px-bit` y `--f-px-sans` se conservan apuntando a la misma
familia: así los 54 usos repartidos por los trece componentes se actualizan de
una, sin tocar regla por regla.

Las etiquetas suben de 9,5 a 11px: una tipografía de texto en mayúsculas no se
lee al cuerpo que toleraba una bitmap. Y el cuerpo lleva un punto más de
interlínea, porque Chakra Petch tiene la caja de la x algo baja.

## Paleta

| | |
| --- | --- |
| Tinta | `#191d1c` |
| Papel | `#f3f2ee` |
| Panel | `#e9e8e2` |
| Verdigrís | `#2f6f5e` |
| Ámbar | `#b07d3a` |

Ningún otro plugin de la familia usa verde, y apagado no compite con la lectura.
El ámbar queda reservado para estados: en vivo, último momento.

## Componentes

Los once llevan la clase marcadora `.nsfcenit-px`. Los otros once widgets quedan
fuera de alcance **por construcción**: sin esa clase, ninguna regla los alcanza.

| Widget | Tratamiento |
| ------ | ----------- |
| Header | Tira de damero arriba, logo como bloque macizo biselado con sombra dura, secciones como **pestañas** |
| Footer | Panel en tinta plena con filete de damero y columnas indexadas |
| Categoría con Notas en Grilla | Fichas sin recuadro, foto biselada que salta al pasar el cursor, fecha anclada al pie |
| Grilla Noticias | Mismo tratamiento que la anterior, para que las dos retículas lean como un sistema |
| Archivo por Categoría | Índice con miniatura biselada; variante grilla con foto arriba |
| Categorías / Subcategorías | Fichas biseladas tipo menú de consola: la activa **se invierte** en vez de subrayarse |
| Bloque Noticias Dinámico | Principal con sombra en verdigrís y columna lateral que iguala su alto |
| Lo Último Box | Panel con borde grueso, sombra dura, cabecera invertida y numeración bitmap |
| Sidebar Más Leídas | El número es el protagonista, en bitmap y grande |
| Fila de notas editorial | Distintivo de opinión como cuadrado biselado, foto anclada al pie |
| Página de Nota | Columna medida, datos en placa biselada, separador de damero antes del cuerpo |

## Notas de implementación

La capa vive al final de `assets/css/frontend.css`. La tipografía bitmap la carga
el propio plugin como dependencia de su hoja: WordPress la pide sola donde hay un
widget y no en el resto del sitio.

Siete trampas de esta familia de plugins, desactivadas de entrada:

1. **El tema define su propio `.site-header`** y le impone un ancho máximo. Como
   el marcado del widget usa el mismo nombre de clase, se lo aplica creyendo que
   es el suyo.
2. **`.nsfcenit-dynamic-content` viene declarado como grilla de `1fr 280px`** en
   la capa base. En una columna angosta eso reserva 280px y parte los titulares
   en astillas de una palabra. Se resetea a bloque.
3. **`.nsfcenit-dynamic-grid` se declara dos veces** con `align-items:start` y
   después `stretch`. Gana el segundo, la ficha grande se estira y el texto
   superpuesto cae al fondo de una caja invisible.
4. **El control "Radio card" sumaba `overflow:hidden`**, que recorta el primer
   glifo de la fecha apoyada en la esquina. Se le quitó el recorte, y la fecha
   lleva además 2px de margen de seguridad.
5. **El control "Ancho imagen lista"** escribía `grid-template-columns` sobre
   `.cat-item` y fijaba el orden texto|foto. Ahora emite `--px-archive-img`.
6. **Los `default` de Elementor** emiten hasta `(0,5,0)` y se encolan después del
   plugin: en empate ganan. Los 22 valores de color se migraron en el PHP.
7. **Una propiedad heredada sobrevive hasta que alguien la contradice por su
   nombre.** Declarar una familia nueva no desactiva una itálica; declarar
   `display` no anula un `margin`.

Además: el botón de campana del header estaba muerto —sin handler en el JS— y se
eliminó, y se agregó la tira de secciones de escritorio, que el clon no traía.
