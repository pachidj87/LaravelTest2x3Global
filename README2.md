# Observaciones


## Paquetes usados
Se instalaron para la implementación algunos paquetes adicionales como guzzle, infyom-laravel-generator y otros para
ayudar en el desarrollo rápido de la prueba.

## Tests
Los Tests generados son parte del Scaffolding generado por el paquete infyom, no se terminaron de implementar pues ***(aún siendo de vital
importancia en todo sistema serio)*** estimé que a propósito de la prueba se habían desarrollado de manera básica los requerimientos 
iniciales y quedaba clara la intención expresa de implementarlos en un entorno real de desarrollo.

## Generales
Al ejecutar migraciones y Seeders el sistema devuelve el token generado para hacer las pruebas. Puede ser usado como párametro
*api_token={generated_token}* o como header *Authorization: Bearer {generated_token}*

### Un cordial Saludo :)
