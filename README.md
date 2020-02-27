# Desafio 2x3 Global

El proposito de este desafío es demostrar y seguir los siguientes puntos:

- Generar código PHP decente y mantenible
- Utilizar el framework Laravel 5.x - 6.x
- Utilizar siempre que se pueda el ORM de Laravel (Eloquent)
- Respetar las definiciones en modelos tal como lo propone el Framework
- Uso de colas y events / listeners
- Escribir el código en **inglés**
- Seguir simples instrucciones


## Gestión básica de pagos (de clientes)

#### Instrucciones del proyecto
El proyecto de prueba busca gestionar de manera básica, una base de datos de clientes, que tienen asociados pagos, es decir un cliente puede tener muchos pagos, que a su vez generan determinados eventos en el sistema.

En términos técnicos, deberás generar 3 endpoints, los cuales deberan retornar las siguientes estructuras (ojo con los arreglos y objetos):

    GET /clients => [{                                        Listar clientes
		"id": 1,
		"email": "admin@example.com",
		"join_date": "Y-m-d",
	}]
	
	GET /payments?client=? =>                          Listar pagos de un cliente
	[
		{
			"uuid": "4dc2aa90-744e-46da-aeea-952e211b719d",
			"payment_date": null,
			"expires_at": "2019-01-01"
			"status": "pending",
			"user_id": ?,
			"clp_usd": 810,
		},
		{
			"uuid": "4638609f-0b81-4d5d-a82a-456533e2d509",
			"payment_date": "2019-12-01",
			"expires_at": "2020-01-01"
			"status": "paid",
			"user_id": ?
			"clp_usd": 820,
		}
	]
	 
	POST /payments => {                                 Crear un pago en la plataforma
		"uuid": "1a59549c-0111-4411-86c3-8c3c0f9f0a99",
		"payment_date": null,
		"expires_at": "2020-02-26"
		"status": "paid",
		"user_id": ?,
		"clp_usd": null,
	}
	

Como podrás imaginar, los modelos se componen de **Client** y **Payment**, la estructura de los modelos queda en tus manos, pero deben contener los valores señalados en los JSON de ejemplo y las relaciones correspondientes, deberás utilizar migraciones para generar la estructura de la base de datos.

Para la generación de clientes, no es necesario contar con un endpoint, pero queda en tus manos la carga de esos datos.

### Lógica de Negocio

- Al crear un nuevo pago, deberas lanzar un proceso en background utilizando **Jobs** de Laravel, en el cual consulte la siguiente API https://mindicador.cl/api/dolar y almacene el valor del día en el cual se genero en el pago que se creó, el driver de **queues** queda en tu decisión.

- Si al momento de ejecutarse el Job de consulta a la API del dolar, se detecta que ya hay un pago creado el mismo día, se debera utilizar el valor del registro que ya contiene ese dato, en vez de volver a consultar a la API.

- Al momento de crear un nuevo pago, deberás gatillar medianto el uso de **Events y Listeners** el envío de un correo de notificacion (no tiene que contener absolutamente nada importante, basta con que se envíe), se recomienda el uso de **Mailtrap** para testing, puedes aplicar el envío del Job dentro de un Listener si te acomoda.


### Como entregar
- Simplemente, genera un nuevo proyecto de Laravel, y envianos el link del repositorio, puedes utilizar cualquier servicio de Git en la nube.
