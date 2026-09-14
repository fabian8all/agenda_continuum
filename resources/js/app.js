import './bootstrap';

// Solo el componente Collapse (usado por el navbar en móvil); no se importa
// el bundle completo de Bootstrap JS porque nada más lo necesita (Popper,
// dropdowns, tooltips, etc. no se usan en este proyecto).
import 'bootstrap/js/dist/collapse';
