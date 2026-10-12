# Cenit · Widgets Elementor v2.0.0

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
