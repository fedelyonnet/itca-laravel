# 🎯 REMINDER: Solución para Animaciones Deshabilitadas en Windows 11

## **Problema Identificado**
- **Síntoma**: Las animaciones CSS/JS (flip de tarjetas, transiciones de carrusel) no funcionan en Windows 11
- **Causa**: Configuración de accesibilidad del SO deshabilitada
- **Ubicación**: Windows 11 → Configuración → Accesibilidad → Efectos visuales
- **Configuraciones afectadas**:
  - "Efectos de animación" DESHABILITADO
  - "Mostrar animaciones en Windows" DESHABILITADO  
  - "Mostrar transiciones en Windows" DESHABILITADO

## **Solución Implementar: Banner de Opción (Solución #3)**

### **1. CSS a agregar en `resources/css/public.css`**

```css
/* Detectar preferencias del SO */
@media (prefers-reduced-motion: reduce) {
    .flip-card {
        transform: none !important;
        transition: none !important;
    }
    
    .slick-slide {
        transition: none !important;
    }
}

/* Override cuando usuario elige animaciones */
body.user-wants-animations .flip-card {
    transition: transform 0.6s ease !important;
}

body.user-wants-animations .slick-slide {
    transition: all 0.3s ease !important;
}

/* Estilos para el banner */
#animation-toggle-banner {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: #333;
    color: white;
    padding: 10px;
    text-align: center;
    z-index: 9999;
    display: none;
}

#animation-toggle-banner button {
    margin-left: 10px;
    padding: 5px 10px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
```

### **2. JavaScript a agregar en `resources/js/app.js`**

```javascript
// Al final del archivo, antes del cierre
document.addEventListener('DOMContentLoaded', function() {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const userWantsAnimations = localStorage.getItem('userWantsAnimations') === 'true';
    
    if (prefersReduced && !userWantsAnimations) {
        showAnimationOption();
    } else if (userWantsAnimations) {
        forceAnimations();
    }
});

function showAnimationOption() {
    const banner = document.createElement('div');
    banner.id = 'animation-toggle-banner';
    banner.innerHTML = `
        <div>
            Las animaciones están deshabilitadas en tu sistema. 
            <button onclick="enableAnimations()">Habilitar animaciones</button>
        </div>
    `;
    document.body.appendChild(banner);
    banner.style.display = 'block';
}

function enableAnimations() {
    localStorage.setItem('userWantsAnimations', 'true');
    document.body.classList.add('user-wants-animations');
    document.getElementById('animation-toggle-banner').remove();
}

function forceAnimations() {
    document.body.classList.add('user-wants-animations');
}
```

### **3. HTML a agregar en `resources/views/welcome.blade.php`**

```html
<!-- Agregar al final del body, antes del cierre </body> -->
<script>
// Función global para el botón
window.enableAnimations = function() {
    localStorage.setItem('userWantsAnimations', 'true');
    document.body.classList.add('user-wants-animations');
    const banner = document.getElementById('animation-toggle-banner');
    if (banner) banner.remove();
};
</script>
```

## **Cómo Funciona**

1. **Detección**: JavaScript detecta si `prefers-reduced-motion: reduce` está activo
2. **Banner**: Si está activo, muestra banner con opción de habilitar
3. **Persistencia**: Guarda la preferencia del usuario en `localStorage`
4. **Override**: Aplica clases CSS que fuerzan las animaciones
5. **Respeto**: Respeta la decisión del usuario para futuras visitas

## **Beneficios**

- ✅ Respeta las preferencias de accesibilidad del usuario
- ✅ Da opción de habilitar animaciones si lo desea
- ✅ Mantiene funcionalidad sin animaciones por defecto
- ✅ Persistente entre sesiones
- ✅ Estándar de la industria (usado por sitios grandes)

## **Archivos a Modificar**

1. `resources/css/public.css` - Agregar CSS de detección y override
2. `resources/js/app.js` - Agregar lógica de detección y banner
3. `resources/views/welcome.blade.php` - Agregar función global del botón

## **Testing**

- Probar con configuración de accesibilidad habilitada/deshabilitada
- Verificar que el banner aparece solo cuando corresponde
- Confirmar que las animaciones se fuerzan cuando el usuario lo elige
- Verificar persistencia en localStorage

---
**Fecha**: $(date)
**Estado**: Pendiente de implementación
**Prioridad**: Media (mejora UX para usuarios con configuraciones específicas)
