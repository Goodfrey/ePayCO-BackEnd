## Prueba ePayCo - Desarrollador BackEnd.

Servicio Soap y Servicio Rest que simula una billetera digital.

## Funcionalidades

El sistema se encuentra en la capacidad de realizar las siguientes funciones.

- ** Registro de Clientes.
- ** Consulta de Saldo en Billetera.
- ** Recarga de Saldo.
- ** Generación de Compra enviada.
- ** Confirmación de Compra enviada.

Cada una de estas funciones contiene su validación de datos.

Para la Generación de Compra enviada, este punto no estaba muy claro el como se genera una compra, por lo que se procedió a generar un salgo aleatorio comprendido entre 5 y 30 dólares, si dicho saldo generado es menor al saldo contenido en la billetera, el sistema retorna una advertencia de que no puede realizar dicha compra por insuficiencia de dinero, en caso contrario el sistema genera dicha factura y envía vía correo electrónico los datos correspondientes al token y a la sesión de la compra.

El Backend está desarrollado en Laravel y PHP 7.4 con la Base de datos en MySQL.

La comunicación se realiza basándose en el protocolo SOAP que se encarga de enviar los datos al backend y muestra el resultado de la operación.

- http://virtualhost/soap/service.php?wsdl

Este sistema fue probado mediante el WampServer y sus Virtual Host, por lo que se recomienda implementarlos para su correcto funcionamiento.

Adicional se anexa un video donde se puede visualizar el funcionamiento del mismo.

https://www.youtube.com/watch?v=XUwITgjmTGs

PD: Ejecutar los siguientes comandos

- composer install
- php artisan migrate

Corregir los datos del email y base de datos en el archivo .ENV
