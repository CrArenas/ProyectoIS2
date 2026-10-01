# Detección de fraude con microservicios

Sistema de transacciones con arquitectura de microservicios: un **gateway en Laravel** autentica con JWT, los **servicios internos** se protegen con API key, un **modelo de machine learning** decide si cada transacción es fraudulenta y, si lo es, se envía una **alerta por SMS**. Todo se levanta con Docker Compose y se prueba con un pipeline de Jenkins.

![Arquitectura del sistema](docs/arquitectura.svg)

## Cómo funciona

1. El usuario envía una transacción a `POST /api/predict` con su JWT.
2. El **gateway** valida los datos (28 características numéricas y el monto) y la reenvía al servicio de predicción junto con el id del usuario.
3. El **modelo** (Flask + scikit-learn) la clasifica:
   - **Legítima**: se envía al servicio de **transacciones**, que la guarda.
   - **Fraudulenta**: se llama al servicio de **notificaciones**, que envía un SMS con Twilio.

Nadie habla directamente con los servicios internos: todo pasa por el gateway, y cada servicio rechaza las peticiones que no traen la cabecera `X-API-Key` correcta.

## Servicios

| Servicio | Tecnología | Puerto | Responsabilidad |
|---|---|---|---|
| `gateway` | Laravel 10, JWT | 8000 | Registro, inicio de sesión, roles (`admin` y `user`) y reenvío a los servicios |
| `transacciones` | Laravel 10 | 8001 | Guarda las transacciones legítimas y genera los reportes |
| `notificaciones` | Laravel 10, Twilio SDK | 8002 | Envía la alerta por SMS |
| `api_flask` | Flask, scikit-learn | 5000 | Clasifica la transacción con el modelo entrenado |
| `db` | MySQL 8 | 3306 | Usuarios, roles y transacciones |

## Rutas del gateway

| Rol | Método y ruta | Qué hace |
|---|---|---|
| Público | `POST /api/register`, `POST /api/login` | Registro e inicio de sesión (devuelve el JWT) |
| `user` | `POST /api/predict` | Envía una transacción a evaluar |
| `user` | `GET /api/userstransactions`, `GET /api/userReport` | Sus transacciones y su reporte |
| `admin` | `GET /api/transactions`, `DELETE /api/transactions/{id}` | Todas las transacciones |
| `admin` | `GET /api/adminReport`, `GET /api/adminReport/{user}` | Reporte general y por usuario |

## Puesta en marcha

Requiere Docker y Docker Compose.

1. Crea el `.env` de cada servicio de Laravel a partir de su `.env.example` (`gateway/`, `transacciones/` y `notificaciones/`), apuntando la base de datos al contenedor `db`. Además:
   - `gateway`: `JWT_SECRET`, `API_KEY` y las URL internas `MICROSERVICE_FLASK`, `MICROSERVICE_TRANSACTIONS` y `MICROSERVICE_NOTIFICATIONS`.
   - `transacciones` y `notificaciones`: `API_KEY`, la misma del gateway.
   - `notificaciones`: `TWILIO_SID`, `TWILIO_AUTH_TOKEN` y `TWILIO_PHONE_NUMBER`.
2. Levanta todo:

   ```bash
   docker-compose up -d --build
   ```

   El gateway ejecuta las migraciones y los seeders al arrancar.
3. Corre las pruebas del gateway:

   ```bash
   docker exec gateway php artisan test
   ```

Las credenciales nunca van en el repositorio: el pipeline de Jenkins (`Jenkinsfile`) copia el `.env` de cada servicio desde las credenciales guardadas en Jenkins, construye los contenedores y ejecuta las pruebas.

## Pruebas

Pruebas de feature del gateway (`gateway/tests/Feature`): inicio de sesión, verificación de roles, registro de transacciones, consulta de transacciones del usuario, reportes, alertas y que la contraseña nunca se guarde en texto plano.

## Equipo

- **Cristian Andrés Arenas Vargas**: gateway y autenticación, microservicios de transacciones y notificaciones, orquestación con Docker y parte de las pruebas. [Portafolio](https://portfolio-3d-ca.vercel.app)
- Alex Orlando Muñoz Ramos
- Santiago Blandón Forero
