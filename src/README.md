# Sistema de Gestión para Motel

## Descripción del proyecto

Este proyecto está desarrollado con **Laravel 13**, **PHP 8.4** y **MariaDB**.

El objetivo es crear un sistema integral para la administración de un motel, permitiendo gestionar habitaciones, reservas, clientes, tarifas, promociones, pagos, auditoría y estadísticas desde una interfaz moderna, intuitiva y rápida.

---

# Tecnologías utilizadas

- Laravel 13
- PHP 8.4
- MariaDB
- Bootstrap 5
- JavaScript
- Vite
- SweetAlert2
- DataTables
- Chart.js
- FontAwesome

---

# Objetivos del proyecto

El sistema debe permitir administrar:

- Habitaciones
- Estados de habitaciones
- Tarifas
- Promociones
- Clientes
- Registro de ocupantes
- Reservas
- Pagos
- Caja
- Auditoría
- Estadísticas
- Usuarios
- Roles y permisos

---

# Reglas de desarrollo

## Diseño Responsive (MUY IMPORTANTE)

**Todo el sistema debe ser completamente Responsive.**

Cada pantalla, formulario, tabla, modal, dashboard y componente debe visualizarse correctamente en:

- Computadores
- Notebook
- Tablet
- Teléfonos móviles

No se aceptarán diseños que funcionen únicamente en escritorio.

Siempre que se cree una nueva vista se debe considerar primero la experiencia móvil (Mobile First).

Los botones nunca deben quedar fuera de pantalla.

Las tablas deben adaptarse mediante:

- Scroll horizontal cuando sea necesario.
- Cards cuando sea conveniente.
- Columnas ocultables.
- Diseño adaptable.

Los formularios deben reorganizar automáticamente sus columnas dependiendo del tamaño de la pantalla.

Los modales deben verse correctamente tanto en escritorio como en dispositivos móviles.


---

# Estilo visual

Se busca una interfaz moderna y elegante.

Debe privilegiarse:

- Diseño limpio
- Colores suaves adaptads para modo claro y modo oscuro
- Espaciado adecuado
- Iconografía consistente
- Animaciones discretas
- Excelente experiencia de usuario (UX)

Evitar interfaces antiguas o sobrecargadas.

---

# Calidad del código

Siempre seguir las buenas prácticas de Laravel.

Priorizar:

- Código limpio
- Componentes reutilizables
- Validaciones tanto en frontend como backend
- Uso correcto de Eloquent
- Relaciones bien definidas
- Controladores livianos
- Lógica de negocio en Services cuando corresponda

---

# Base de datos

Todas las migraciones deben:

- utilizar claves foráneas
- índices adecuados
- nombres descriptivos
- timestamps
- softDeletes cuando corresponda

---

# Frontend

Preferencias:

- Bootstrap 5
- SweetAlert2
- DataTables
- Chart.js

No utilizar librerías innecesarias.

Mantener una interfaz rápida.

---

# Rendimiento

Priorizar:

- Consultas optimizadas
- Eager Loading
- Paginación
- Caché cuando corresponda
- Evitar consultas N+1

---

# Seguridad

Implementar siempre:

- CSRF
- Validaciones
- Autorizaciones
- Policies
- Gates
- Protección contra SQL Injection
- Escape de datos en vistas

---

# Convenciones

- Variables con nombres descriptivos.
- Métodos pequeños.
- Comentarios solo cuando sean realmente necesarios.
- Código fácil de mantener.

---

# Importante para asistentes de IA

Cuando se solicite generar código para este proyecto, siempre considerar:

- Laravel 13
- PHP 8.4
- Bootstrap 5
- MariaDB
- Código limpio
- Buenas prácticas de Laravel
- Componentes reutilizables
- Seguridad
- Optimización
- **Diseño 100% Responsive**
- Compatibilidad con dispositivos móviles
- Excelente experiencia de usuario (UX)

El asistente debe asumir que cualquier nueva funcionalidad debe integrarse siguiendo estas reglas sin necesidad de volver a indicarlas.

---

# Estado del proyecto

Proyecto en desarrollo activo.

El objetivo es construir un sistema moderno, mantenible, escalable y preparado para futuras funcionalidades.