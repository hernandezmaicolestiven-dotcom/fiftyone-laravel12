#!/bin/bash

# Script de verificación de Docker
# Ejecuta: bash docker-check.sh

echo "🔍 Verificando configuración de Docker para FiftyOne..."
echo ""

# Colores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para verificar
check() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓${NC} $1"
    else
        echo -e "${RED}✗${NC} $1"
    fi
}

# 1. Verificar Docker
echo "1️⃣  Verificando Docker..."
docker --version > /dev/null 2>&1
check "Docker instalado"

docker info > /dev/null 2>&1
check "Docker corriendo"

# 2. Verificar Docker Compose
echo ""
echo "2️⃣  Verificando Docker Compose..."
docker compose version > /dev/null 2>&1
check "Docker Compose instalado"

# 3. Verificar archivos necesarios
echo ""
echo "3️⃣  Verificando archivos de configuración..."

[ -f "docker-compose.yml" ]
check "docker-compose.yml existe"

[ -f "Dockerfile" ]
check "Dockerfile existe"

[ -f "docker/nginx/default.conf" ]
check "nginx/default.conf existe"

[ -f "docker/supervisor/supervisord.conf" ]
check "supervisor/supervisord.conf existe"

[ -f ".env" ] || [ -f ".env.docker" ]
check "Archivo .env o .env.docker existe"

# 4. Verificar estado de contenedores
echo ""
echo "4️⃣  Verificando contenedores..."

if docker compose ps | grep -q "fiftyone_app"; then
    if docker compose ps | grep "fiftyone_app" | grep -q "Up"; then
        echo -e "${GREEN}✓${NC} Contenedor app corriendo"
    else
        echo -e "${YELLOW}⚠${NC} Contenedor app existe pero no está corriendo"
    fi
else
    echo -e "${YELLOW}⚠${NC} Contenedor app no existe (ejecuta: docker compose up -d)"
fi

if docker compose ps | grep -q "fiftyone_db"; then
    if docker compose ps | grep "fiftyone_db" | grep -q "Up"; then
        echo -e "${GREEN}✓${NC} Contenedor db corriendo"
    else
        echo -e "${YELLOW}⚠${NC} Contenedor db existe pero no está corriendo"
    fi
else
    echo -e "${YELLOW}⚠${NC} Contenedor db no existe (ejecuta: docker compose up -d)"
fi

# 5. Verificar puertos
echo ""
echo "5️⃣  Verificando puertos..."

if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null 2>&1 || netstat -an 2>/dev/null | grep -q ":8000.*LISTEN"; then
    echo -e "${GREEN}✓${NC} Puerto 8000 en uso (app corriendo)"
else
    echo -e "${YELLOW}⚠${NC} Puerto 8000 libre (app no está corriendo)"
fi

if lsof -Pi :3307 -sTCP:LISTEN -t >/dev/null 2>&1 || netstat -an 2>/dev/null | grep -q ":3307.*LISTEN"; then
    echo -e "${GREEN}✓${NC} Puerto 3307 en uso (MySQL corriendo)"
else
    echo -e "${YELLOW}⚠${NC} Puerto 3307 libre (MySQL no está corriendo)"
fi

# 6. Verificar volúmenes
echo ""
echo "6️⃣  Verificando volúmenes..."

if docker volume ls | grep -q "mysql_data"; then
    echo -e "${GREEN}✓${NC} Volumen mysql_data existe"
else
    echo -e "${YELLOW}⚠${NC} Volumen mysql_data no existe"
fi

if docker volume ls | grep -q "vendor"; then
    echo -e "${GREEN}✓${NC} Volumen vendor existe"
else
    echo -e "${YELLOW}⚠${NC} Volumen vendor no existe"
fi

if docker volume ls | grep -q "node_modules"; then
    echo -e "${GREEN}✓${NC} Volumen node_modules existe"
else
    echo -e "${YELLOW}⚠${NC} Volumen node_modules no existe"
fi

# 7. Información de red
echo ""
echo "7️⃣  Información de red..."
echo ""
echo "   🌐 URL Local: http://localhost:8000"
echo ""

# Obtener IP local
if command -v hostname &> /dev/null; then
    LOCAL_IP=$(hostname -I 2>/dev/null | awk '{print $1}')
    if [ ! -z "$LOCAL_IP" ]; then
        echo "   📱 URL Red Local: http://$LOCAL_IP:8000"
    fi
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Resumen
if docker compose ps | grep -q "Up"; then
    echo -e "${GREEN}✅ Todo listo! Tu aplicación está corriendo.${NC}"
    echo ""
    echo "Comandos útiles:"
    echo "  • Ver logs: docker compose logs -f"
    echo "  • Detener: docker compose stop"
    echo "  • Reiniciar: docker compose restart"
else
    echo -e "${YELLOW}⚠️  Los contenedores no están corriendo.${NC}"
    echo ""
    echo "Para iniciar:"
    echo "  • Automático: ./docker-start.sh"
    echo "  • Manual: docker compose up -d"
fi

echo ""
