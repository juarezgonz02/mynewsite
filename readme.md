# Proyecto CSS

Un proyecto de Laravel

## Instalación

Antes de empezar, prepare su entorno local siguiendo los pasos de la seccion "Instalación" del [Manual técnico](https://docs.google.com/document/d/1aGGLLClkp3PS_JLMKiS_XNteM25jJFu5/edit?usp=sharing&ouid=117573464062116534209&rtpof=true&sd=true).

En la raíz del proyecto...

Descargue el [.env](https://drive.google.com/file/d/1V10saHaWt1lYmSVnU9ZITT_ZGGQxakZA/view?usp=sharing).

Acto seguido, ejecute los siguientes comandos:

```bash
# Instalación de composer
composer install

# Instalación de las dependencias de npm
npm install

# Migraciones
php artisan migrate
```

## Desarrollo

Antes de empezar sus sesiones de codificación debe ejecutar el siguiente comando, el cual es necesario para que se vean reflejados los cambios de fornt-end en el sitio:

```bash
npm run watch
```
En caso de que los cambios no se vean reflejados, presionar Ctrl+F5, esto recarga el sitio web sin cache.

En otra consola hacer: 
```bash
php artisan serve --port 80 
```

Finalmente el sitio será accesible en el siguiente link [http://localhost](http://localhost).

## Producción

Al momento de hacer el último commit antes de implementar los nuevos cambios en el servidor, ejecute el siguiente comando para activar el modo producción:

```bash
npm run production
```
