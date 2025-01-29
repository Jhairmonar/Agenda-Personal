# **Agenda Personal**  

Este es un proyecto sencillo desarrollado con **PHP, HTML y CSS**, diseñado para almacenar y gestionar contactos de forma básica.

## **Características**  
- Permite ingresar **nombre, número de celular y una foto**.  
- Los datos se almacenan en un archivo **contactos.txt**.  
- Las imágenes se convierten y almacenan en **formato Base64**.  
- Se pueden **visualizar, editar y eliminar** contactos.  
- Diseño simple utilizando **CSS** para mejorar la presentación.  

## **Estructura del Proyecto**  
- **`index.php`**: Página principal con el formulario para ingresar los datos del contacto.  
- **`base.php`**: Maneja la lógica de almacenamiento y visualización de los datos.  
- **`contactos.txt`**: Archivo donde se guardan los datos ingresados.  
- **`base.css` y `style.css`**: Archivos de estilos para mejorar la apariencia de la agenda.  
- **`fotos/fondo.jpg`**: Imagen de fondo utilizada en el diseño.  

## **Funcionamiento**  
1. En **`index.php`**, se ingresa nombre, número de celular y se selecciona una foto.  
2. Al enviar los datos, estos se almacenan en **`contactos.txt`** y la imagen se guarda en formato Base64.  
3. En **`base.php`**, se listan los contactos almacenados, con opciones para **editar o eliminar** cada uno.  

Este proyecto es simple, sin embargo, puede expandirse con bases de datos u otras mejoras con mayores funcionalidades en un futuro. 🚀
