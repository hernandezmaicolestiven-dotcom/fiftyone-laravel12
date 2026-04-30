# 🎨 Docker - Guía Visual

Diagramas y visualizaciones para entender Docker en FiftyOne.

## 🏗️ Arquitectura Simplificada

```
┌─────────────────────────────────────────────────┐
│              TU COMPUTADORA                     │
│                                                 │
│  ┌───────────────────────────────────────────┐ │
│  │         DOCKER                            │ │
│  │                                           │ │
│  │  ┌─────────────┐      ┌─────────────┐    │ │
│  │  │     APP     │      │     DB      │    │ │
│  │  │             │      │             │    │ │
│  │  │  Laravel    │─────▶│   MySQL     │    │ │
│  │  │  PHP 8.2    │      │   8.0       │    │ │
│  │  │  Nginx      │      │             │    │ │
│  │  │  Node.js    │      │             │    │ │
│  │  └─────────────┘      └─────────────┘    │ │
│  │       ↑                     ↑             │ │
│  └───────┼─────────────────────┼─────────────┘ │
│          │                     │               │
│      Puerto 8000           Puerto 3307         │
└──────────┼─────────────────────┼───────────────┘
           │                     │
           ▼                     ▼
    http://localhost:8000   MySQL Client
```

## 🔄 Flujo de Trabajo

### 1. Inicio del Sistema

```
Usuario ejecuta:
docker-start.bat

         ↓

Docker construye imágenes
(solo la primera vez)

         ↓

Docker inicia contenedores
- MySQL (db)
- Laravel (app)

         ↓

Instala dependencias
- Composer
- NPM

         ↓

Configura Laravel
- APP_KEY
- Storage link
- Permisos

         ↓

Ejecuta migraciones
(opcional)

         ↓

✅ Sistema listo
http://localhost:8000
```

### 2. Desarrollo Diario

```
Abrir editor de código
         ↓
Editar archivos PHP/Blade
         ↓
Guardar
         ↓
✅ Cambios visibles inmediatamente
(no necesitas reiniciar)

─────────────────────────

Editar CSS/JS
         ↓
Guardar
         ↓
Compilar assets:
docker compose exec app npm run build
         ↓
✅ Cambios visibles
```

### 3. Petición HTTP

```
Usuario → http://localhost:8000/productos
                    ↓
         Host (Puerto 8000)
                    ↓
         Docker mapea a app:80
                    ↓
              Nginx recibe
                    ↓
         ¿Es archivo estático?
         ├─ Sí → Sirve directamente
         └─ No → Pasa a PHP-FPM
                    ↓
              Laravel procesa
                    ↓
         ¿Necesita base de datos?
         ├─ Sí → Conecta a db:3306
         │         ↓
         │      MySQL responde
         │         ↓
         └─ No → Continúa
                    ↓
         Laravel genera HTML
                    ↓
         Nginx envía respuesta
                    ↓
         Usuario ve la página
```

## 📦 Estructura de Archivos

```
proyecto/
│
├── 🐳 DOCKER
│   ├── Dockerfile                    ← Define imagen app
│   ├── docker-compose.yml            ← Configuración desarrollo
│   ├── docker-compose.prod.yml       ← Configuración producción
│   └── .dockerignore                 ← Archivos a ignorar
│
├── ⚙️ CONFIGURACIONES
│   └── docker/
│       ├── nginx/
│       │   └── default.conf          ← Config Nginx
│       ├── supervisor/
│       │   └── supervisord.conf      ← Gestión de procesos
│       └── mysql/
│           └── my.cnf                ← Config MySQL
│
├── 🌍 VARIABLES DE ENTORNO
│   ├── .env                          ← Tu configuración
│   ├── .env.docker                   ← Ejemplo desarrollo
│   └── .env.docker.production        ← Ejemplo producción
│
├── 🚀 SCRIPTS
│   ├── docker-start.sh / .bat        ← Inicio automático
│   ├── docker-check.sh / .bat        ← Verificación
│   └── docker-dev.sh / .bat          ← Comandos útiles
│
├── 📚 DOCUMENTACIÓN
│   ├── DOCKER.md                     ← Guía completa
│   ├── DOCKER-QUICKSTART.md          ← Inicio rápido
│   ├── DOCKER-FAQ.md                 ← Preguntas frecuentes
│   ├── DOCKER-ARCHITECTURE.md        ← Arquitectura técnica
│   ├── DOCKER-FILES.md               ← Índice de archivos
│   ├── DOCKER-CHECKLIST.md           ← Checklist
│   ├── DOCKER-SUMMARY.md             ← Resumen ejecutivo
│   ├── DOCKER-INDEX.md               ← Índice de docs
│   └── DOCKER-VISUAL.md              ← Este archivo
│
└── 💻 TU CÓDIGO
    ├── app/                          ← No modificado
    ├── resources/                    ← No modificado
    ├── routes/                       ← No modificado
    └── ...                           ← No modificado
```

## 🔌 Conexiones

### Desde tu Computadora

```
┌─────────────────────────────────────┐
│      TU COMPUTADORA (Host)          │
│                                     │
│  Navegador                          │
│  http://localhost:8000 ────────┐    │
│                                │    │
│  MySQL Client                  │    │
│  localhost:3307 ───────────┐   │    │
│                            │   │    │
└────────────────────────────┼───┼────┘
                             │   │
                             ▼   ▼
┌─────────────────────────────────────┐
│            DOCKER                   │
│                                     │
│  ┌──────────┐      ┌──────────┐    │
│  │   APP    │      │    DB    │    │
│  │  :80     │◀─────│  :3306   │    │
│  └──────────┘      └──────────┘    │
│                                     │
└─────────────────────────────────────┘
```

### Desde Otro Equipo en la Red

```
┌─────────────────────────────────────┐
│      OTRO EQUIPO                    │
│                                     │
│  Navegador                          │
│  http://192.168.1.100:8000 ─────┐   │
│                                 │   │
└─────────────────────────────────┼───┘
                                  │
                    Red Local     │
                                  │
┌─────────────────────────────────┼───┐
│      TU COMPUTADORA             │   │
│      IP: 192.168.1.100          ▼   │
│                                     │
│  ┌─────────────────────────────┐   │
│  │         DOCKER              │   │
│  │                             │   │
│  │  ┌──────────┐  ┌──────────┐│   │
│  │  │   APP    │  │    DB    ││   │
│  │  │  :80     │  │  :3306   ││   │
│  │  └──────────┘  └──────────┘│   │
│  └─────────────────────────────┘   │
└─────────────────────────────────────┘
```

## 💾 Volúmenes

### Bind Mount (Código Fuente)

```
┌─────────────────────────────────────┐
│      TU COMPUTADORA                 │
│                                     │
│  📁 proyecto/                       │
│     ├── app/                        │
│     ├── resources/                  │
│     └── ...                         │
│          │                          │
│          │ Bind Mount               │
│          │ (sincronización)         │
│          ▼                          │
│  ┌─────────────────────────────┐   │
│  │      DOCKER                 │   │
│  │                             │   │
│  │  📁 /var/www/html/          │   │
│  │     ├── app/                │   │
│  │     ├── resources/          │   │
│  │     └── ...                 │   │
│  └─────────────────────────────┘   │
└─────────────────────────────────────┘

✅ Cambios en tu editor se reflejan
   inmediatamente en el contenedor
```

### Named Volumes (Datos Persistentes)

```
┌─────────────────────────────────────┐
│      DOCKER                         │
│                                     │
│  Contenedor DB                      │
│  ┌─────────────────┐                │
│  │  MySQL          │                │
│  │  /var/lib/mysql │                │
│  └────────┬────────┘                │
│           │                         │
│           │ Persiste en             │
│           ▼                         │
│  💾 Volumen: mysql_data             │
│     (gestionado por Docker)         │
│                                     │
└─────────────────────────────────────┘

✅ Los datos persisten incluso si
   eliminas el contenedor
```

## 🎯 Comandos Visualizados

### docker compose up -d

```
Antes:
┌─────────────────┐
│   Tu Código     │
└─────────────────┘

Después:
┌─────────────────┐
│   Tu Código     │
└─────────────────┘
        │
        ▼
┌─────────────────┐
│     DOCKER      │
│  ┌───┐   ┌───┐  │
│  │APP│   │DB │  │
│  └───┘   └───┘  │
└─────────────────┘
```

### docker compose exec app bash

```
┌─────────────────────────────────────┐
│      TU TERMINAL                    │
│                                     │
│  $ docker compose exec app bash    │
│                                     │
│         ↓                           │
│                                     │
│  ┌─────────────────────────────┐   │
│  │  DENTRO DEL CONTENEDOR      │   │
│  │                             │   │
│  │  root@app:/var/www/html#    │   │
│  │                             │   │
│  │  Puedes ejecutar:           │   │
│  │  - php artisan ...          │   │
│  │  - composer ...             │   │
│  │  - npm ...                  │   │
│  └─────────────────────────────┘   │
└─────────────────────────────────────┘
```

### docker compose logs -f

```
┌─────────────────────────────────────┐
│      TU TERMINAL                    │
│                                     │
│  $ docker compose logs -f           │
│                                     │
│  ┌─────────────────────────────┐   │
│  │  LOGS EN TIEMPO REAL        │   │
│  │                             │   │
│  │  app  | [info] Request...  │   │
│  │  db   | [note] Ready...    │   │
│  │  app  | [info] Response... │   │
│  │  ...                        │   │
│  │                             │   │
│  │  (Ctrl+C para salir)        │   │
│  └─────────────────────────────┘   │
└─────────────────────────────────────┘
```

## 🔄 Ciclo de Vida de Contenedores

```
┌─────────────────────────────────────┐
│  docker compose up -d               │
│  ┌───────────────────────────────┐  │
│  │  Contenedores INICIADOS       │  │
│  │  Estado: Running              │  │
│  └───────────────────────────────┘  │
│              │                      │
│              ▼                      │
│  ┌───────────────────────────────┐  │
│  │  Trabajas normalmente         │  │
│  │  Editas código, ves logs, etc │  │
│  └───────────────────────────────┘  │
│              │                      │
│              ▼                      │
│  docker compose stop                │
│  ┌───────────────────────────────┐  │
│  │  Contenedores DETENIDOS       │  │
│  │  Estado: Stopped              │  │
│  │  Datos: Preservados           │  │
│  └───────────────────────────────┘  │
│              │                      │
│              ▼                      │
│  docker compose start               │
│  ┌───────────────────────────────┐  │
│  │  Contenedores REINICIADOS     │  │
│  │  Estado: Running              │  │
│  │  Datos: Intactos              │  │
│  └───────────────────────────────┘  │
│              │                      │
│              ▼                      │
│  docker compose down                │
│  ┌───────────────────────────────┐  │
│  │  Contenedores ELIMINADOS      │  │
│  │  Estado: No existen           │  │
│  │  Datos: Preservados (volumen)│  │
│  └───────────────────────────────┘  │
│              │                      │
│              ▼                      │
│  docker compose down -v             │
│  ┌───────────────────────────────┐  │
│  │  TODO ELIMINADO               │  │
│  │  Estado: No existe nada       │  │
│  │  Datos: ELIMINADOS ⚠️         │  │
│  └───────────────────────────────┘  │
└─────────────────────────────────────┘
```

## 🎨 Comparación: Con vs Sin Docker

### Sin Docker

```
┌─────────────────────────────────────┐
│      TU COMPUTADORA                 │
│                                     │
│  ❌ Instalar PHP 8.2                │
│  ❌ Instalar MySQL 8.0              │
│  ❌ Instalar Composer               │
│  ❌ Instalar Node.js                │
│  ❌ Configurar PATH                 │
│  ❌ Configurar MySQL                │
│  ❌ Resolver conflictos             │
│  ❌ Diferente en cada OS            │
│                                     │
│  ⏱️  Tiempo: 1-2 horas              │
│  😰 Dificultad: Media-Alta          │
└─────────────────────────────────────┘
```

### Con Docker

```
┌─────────────────────────────────────┐
│      TU COMPUTADORA                 │
│                                     │
│  ✅ Instalar Docker Desktop         │
│  ✅ Ejecutar docker-start.bat       │
│                                     │
│  ┌─────────────────────────────┐   │
│  │      DOCKER                 │   │
│  │  ✅ PHP 8.2                 │   │
│  │  ✅ MySQL 8.0               │   │
│  │  ✅ Composer                │   │
│  │  ✅ Node.js                 │   │
│  │  ✅ Todo configurado        │   │
│  └─────────────────────────────┘   │
│                                     │
│  ⏱️  Tiempo: 5-10 minutos           │
│  😊 Dificultad: Baja                │
└─────────────────────────────────────┘
```

## 🚀 Próximos Pasos

```
1. Instalar Docker Desktop
   └─▶ https://docker.com/products/docker-desktop

2. Ejecutar script de inicio
   └─▶ docker-start.bat (Windows)
   └─▶ ./docker-start.sh (Linux/Mac)

3. Abrir navegador
   └─▶ http://localhost:8000

4. ¡Empezar a desarrollar!
   └─▶ Edita código normalmente
   └─▶ Los cambios se reflejan automáticamente
```

## 📚 Más Información

- **Guía completa:** [DOCKER.md](DOCKER.md)
- **Inicio rápido:** [DOCKER-QUICKSTART.md](DOCKER-QUICKSTART.md)
- **Preguntas frecuentes:** [DOCKER-FAQ.md](DOCKER-FAQ.md)
- **Índice completo:** [DOCKER-INDEX.md](DOCKER-INDEX.md)

---

**Última actualización:** 30 de Abril de 2026

