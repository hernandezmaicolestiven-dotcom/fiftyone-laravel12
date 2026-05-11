// Script para limpiar toda la caché del navegador
// Ejecutar en la consola del navegador (F12)

console.log('🧹 Limpiando caché...');

// 1. Limpiar Service Workers
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.getRegistrations().then(function(registrations) {
    for(let registration of registrations) {
      registration.unregister();
      console.log('✅ Service Worker desregistrado');
    }
  });
}

// 2. Limpiar Cache Storage
if ('caches' in window) {
  caches.keys().then(function(names) {
    for (let name of names) {
      caches.delete(name);
      console.log('✅ Cache eliminado:', name);
    }
  });
}

// 3. Limpiar Local Storage
localStorage.clear();
console.log('✅ LocalStorage limpiado');

// 4. Limpiar Session Storage
sessionStorage.clear();
console.log('✅ SessionStorage limpiado');

// 5. Recargar la página sin caché
console.log('🔄 Recargando página...');
setTimeout(() => {
  location.reload(true);
}, 1000);
