# MarketHub Local Development con Docker

Esta guía te permite levantar el entorno completo de desarrollo de MarketHub (Base de datos, Backend y Frontend) de manera local, utilizando Docker y eliminando cualquier dependencia de servidores externos.

## Requisitos previos
- Docker Desktop (o Docker Engine + Docker Compose) instalado.
- Ningún otro servicio ocupando los puertos `3000` (Backend), `4200` (Frontend) o `27017` (MongoDB).

## Pasos para iniciar el proyecto desde cero

### 1. Configurar las variables de entorno
Antes de levantar el entorno, necesitas crear un archivo `.env` en la raíz de este directorio (`MarketHub/`) copiando el contenido del archivo de ejemplo:

```bash
# En PowerShell
cp .env.example .env
```

Asegúrate de no subir **NUNCA** el archivo `.env` a GitHub. Verifica que está listado en tu `.gitignore` global o local haciendo `git status` antes de hacer commit.

### 2. Levantar los contenedores
Para construir y arrancar todos los servicios (MongoDB, Node.js Backend, Angular Frontend) en segundo plano, ejecuta el siguiente comando:

```bash
docker-compose up -d --build
```

### 3. Verificar los servicios

Una vez que Docker descargue las imágenes e instale las dependencias dentro de los contenedores (esto puede tardar unos minutos la primera vez), los servicios estarán disponibles en:

- **Frontend (Angular)**: [http://localhost:4200](http://localhost:4200)
- **Backend (API)**: [http://localhost:3000](http://localhost:3000)
- **Base de Datos (MongoDB)**: `localhost:27017`

### 4. Hot Reload
El archivo `docker-compose.yml` está configurado en modo desarrollo con volúmenes locales. Cualquier cambio que guardes en los archivos dentro de las carpetas `frontend/` o `backend/` se reflejará automáticamente (Nodemon para el backend, y el Angular dev server para el frontend).

### 5. Ver logs o reiniciar
Si necesitas ver qué está pasando (por ejemplo, ver los logs del backend para depurar errores):
```bash
docker-compose logs -f backend
```

Si instalas un nuevo paquete por consola (ej. `npm install module` desde la carpeta backend de tu máquina host), es recomendable reiniciar el servicio correspondiente o reconstruir:
```bash
docker-compose down
docker-compose up -d --build
```

---

## ⚠️ Riesgos de Producción

1. **Subir `.env` a GitHub:** El `.gitignore` global ya protege este archivo, pero siempre revisa tu `git status` antes de subir código.
2. **URLs Hardcodeadas:** La configuración actual apunta a `localhost`. Si necesitas llamar al backend desde el frontend, utiliza variables de entorno (como se hace en `environments/environment.ts` de Angular: `apiUrl: 'http://localhost:3000/api/v1'`) en lugar de escribir la URL directamente en los componentes.
