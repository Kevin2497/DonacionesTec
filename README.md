# Sistema de Gestión de Donaciones - TECNM

Este proyecto web permite la gestión de donaciones monetarias, en especie y propuestas dentro del Instituto Tecnológico de Orizaba (ITO). El sistema facilita el registro, consulta, seguimiento y control de donativos por parte de usuarios y administradores.

## Funcionalidades principales

- Registro de donantes
- Captura y visualización de donaciones:
  - Monetarias
  - En especie
  - Propuestas de proyectos
- Gestión de estados y seguimiento de donativos.
- Generación de reportes y estadísticas.
- Vista personalizada para usuarios y administradores.

## Estructura del proyecto

### Vista
- aside.php
- encabezado.html
- pie-html


### Controlador
- busqueda.js
- calendar.js
- generarComprobante.php
- generarDonacion.php
- graficaStats.js
- imgPop.js
- modalPerfil.js
- obtenerDetallesEspecie.php
- obtenerDetallesMonetaria.php
- obtenerDetallesPropuesta.php
- popDetalles.js
- popDonar.js
- popDonarEspecie.js
- popDonarPropuesta.js
- popup.js
- procesarRegistro.php

### Estilos

- aside.css
- encabezado.css
- landing.css
- menu.css
- pie.css
- menu.css
- popup.css
- section.css
- tablas.css

### IMG

- Imagenes usadas en el proyecto

### uploads/fotos

- imagenes cargadas por el usuario

- index.php: Página principal de inicio de sesión.
- inicio.php: Panel de inicio tras autenticación.
- donaciones.php: Módulo de gestión de donaciones para administradores.
- donacionesUsuario.php: Vista limitada para donantes registrados.
- donantes.php: Gestión del padrón de donantes.
- generarDonacionEspecie.php: Registro de donaciones en especie.
- generarDonacionPropuesta.php: Registro de propuestas.
- historialDonaciones.php: Historial completo de donaciones.
- estadisticas.php: Visualización de estadísticas generales.
- actualizarEstado.php: Control del estado de las donaciones.

## Requisitos

- Servidor web con soporte PHP (ej. XAMPP, WAMP)
- Base de datos MySQL
- Navegador web moderno

## Instalación

1. Copia o descomprime el repositorio en el servidor local.
2. Configura la base de datos en MySQL con las tablas necesarias.
3. Ajusta las credenciales de conexión en los archivos correspondientes.
4. Inicia el servidor y accede mediante 'http://localhost/DonacionesTec'.

## Créditos
Hernandez Cardoso Emmanuel
Ramirez Covarrubias Jose Manuel
Garcia Landa Diana
Gonzalez Morgado Kevin Jose

## Licencia

Uso académico y sin fines de lucro.
