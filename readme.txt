=== Pub-lica-me para Wordpress ===
Contributors: Stefan Aichholzer S.
Donate link: http://www.aichholzer.name/donate/2898174
Tags: blog, micro, micro-bloggeo, micro-press, press
Requires at least: 2.6
Tested up to: 3.0
Stable tag: trunk

Publica el título y un enlace de cada entrada que escribas en tu blog en la plataforma de micro-bloggeo http://pub.lica.me


== Description ==

Cada vez que escribas una nueva entrada en tu blog, este plug-in extraerá el título y lo publicará junto con un enlace
en http://pub.lica.me Para esto necesitas una cuenta de usuario en http://pub.lica.me (esto es básicamente para evitar spammers)
Una cuenta en http://pub.lica.me es gratuita y se abre en menos de 3 minutos.

Este plug-in necesita PHP 5 o superior

Si te gusta este plug-in, por favor has una donación: 
http://www.aichholzer.name/donate/2898174


== Installation ==

El método común:

1. Sube el directorio del plug-in a tu directorio de plug-ins `/wp-content/plugins`
2. Activa el plug-in a través de la página de plugins
3. Visita la página de opciones del plug-in `Opciones/Pub-lica-me` y completa los sencillos pasos para activar tu plug-in. (Es importate que registres una cuenta en `pub.lica.me` para poder utilizar el plug-in)
4. Listo y ¡a publicar se ha dicho!


== Contributors/Changelog ==

    0.1.3	2009/01/31	Primera versión pública.
    
    0.1.4	2009/02/01	Arreglo en el problema de las rutas del plug-in
    					Ahora va bien.
    					Gracias a Will Castillo por reportar esto.
    					
    0.1.7	2009/02/06	Código encapsulado (Pura POO) como debe ser
						Mejorado el rendimiento

    0.1.8	2009/02/07	Soporte para múltiples blogs en una misma
						base de datos
						Gracias a Roger Soles por comentarlo
						
    0.2.0	2009/02/07	Resolución de ciertos fallos

    0.2.2	2009/02/07	Simple pero efectivo método de plantillas

    0.2.3	2009/02/13	Logotipo y esquema visual
						Gracias a David Bugeja
	
	0.2.4	2009/02/19	Pequeña mejora general
	
	0.2.7	2009/04/07	Mejorando un poco la sobrecarga
	
	0.3.0	2009/05/27	Nuevo API de pub.lica.me y nuevo uso del plug-in.
						Ahora solo los usuarios registrados pueden utilizar el plug-in.
						(Básicamente para evitar a los spammers)
						
	0.3.1	2009/06/01	Fallo con el nombre de la tabla del plug-in.
						El plug-in no funcionaba si se tenía un nombre de tabla común.
						Ahora el prefijo de tabla puede ser el que uno quiera.
						
	0.3.2	2009/09/22	Error al enviar los datos de usuario y contraseña.
						Gracias a todos los que han reportado este error,
						en especial a Kiki de www.GamesInvaders.es
						
	0.3.3	2009/09/25	He eliminado el uso de la funcion json_decode()
						que daba problemas a los usuarios con una version de PHP
						inferior a 5.2
						
	0.3.4	2010/07/02	Arreglo minimo y seguridad de compatibilidad con WP 3.0



Para más información visita la página `Informacion / Ayuda` en las opciones del plug-in
Por favor envíame tus ideas y/o comentarios.
