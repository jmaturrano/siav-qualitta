<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);
/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);
/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
/*
|--------------------------------------------------------------------------
| Custom IOrganizacional
|--------------------------------------------------------------------------
|
| Variables gloabales
|
*/
/*GENERAL*/
defined('KEY_ENCRYPT')      	OR define('KEY_ENCRYPT', 'agroruralmonitor');
defined('TITULO')      			OR define('TITULO', 'SIAV - Qualitta');
defined('PRODUCTO')      		OR define('PRODUCTO', 'SIAV - Qualitta');
defined('MAILPRODUCTO')      	OR define('MAILPRODUCTO', 'no-reply@qualitta.com.pe');
defined('LOGOEMPRESA')			OR define('LOGOEMPRESA', 'public/assets/img/logo/logo_145x166.png');
defined('ICON_EMPRESA')			OR define('ICON_EMPRESA', 'public/assets/img/logo/logo_icon.png');
defined('IMG_PATH')				OR define('IMG_PATH', 'public/assets/img/fotos/');

defined('ESTADO_PROYECTO_DEF')	OR define('ESTADO_PROYECTO_DEF', 1);
defined('CPAMB_UR')				OR define('CPAMB_UR', 'URBANO');
defined('CPAMB_RU')				OR define('CPAMB_RU', 'RURAL');
defined('DEFAULT_FRECID')		OR define('DEFAULT_FRECID', 1);
defined('LIMITSELECT')			OR define('LIMITSELECT', 90);
defined('CODIGOMETADEF')		OR define('CODIGOMETADEF', '0000000');
defined('SIMBOLO_MONEDA')		OR define('SIMBOLO_MONEDA', 'S/. ');
/*MESSAGE RESPONSE*/
defined('RTYPE_SUCCESS')		OR define('RTYPE_SUCCESS', 'alert-success');
defined('RTYPE_ERROR')			OR define('RTYPE_ERROR', 'alert-danger');
defined('RTITLE_SUCCESS')		OR define('RTITLE_SUCCESS', 'Éxito!. ');
defined('RTITLE_ERROR')			OR define('RTITLE_ERROR', 'Ha ocurrido un error!. ');
defined('RMESSAGE_DELETE')		OR define('RMESSAGE_DELETE', 'El registro se ha eliminado correctamente.');
defined('RMESSAGE_UPDATE')		OR define('RMESSAGE_UPDATE', 'El registro se ha actualizado correctamente.');
defined('RMESSAGE_INSERT')		OR define('RMESSAGE_INSERT', 'El registro se ha guardado correctamente.');
defined('RMESSAGE_ERROR')		OR define('RMESSAGE_ERROR', 'No se pudo procesar, por favor intente de nuevo.');
defined('RMESSAGE_ASSIGNED')	OR define('RMESSAGE_ASSIGNED', 'Elemento asignado con éxito!');
defined('RMESSAGE_ASSIGNEDUP')	OR define('RMESSAGE_ASSIGNEDUP', 'Elemento actualizado con éxito!');
defined('RMESSAGE_PROCESSED')	OR define('RMESSAGE_PROCESSED', 'Proceso realizado con éxito!');
/*GENERAL ICONS*/
defined('ICON_SETTINGS')		OR define('ICON_SETTINGS', 'icon-cog');
defined('ICON_TASKS')			OR define('ICON_TASKS', 'icon-tasks');
defined('ICON_VIEW')			OR define('ICON_VIEW', 'icon-eye-open');
defined('ICON_EDIT')			OR define('ICON_EDIT', 'icon-edit');
defined('ICON_DELETE')			OR define('ICON_DELETE', 'icon-trash');
defined('ICON_SAVE')			OR define('ICON_SAVE', 'icon-save');
defined('ICON_NEW')				OR define('ICON_NEW', 'icon-file');
defined('ICON_FORM')			OR define('ICON_FORM', 'icon-file');
defined('ICON_LIST')			OR define('ICON_LIST', 'icon-th-list');
defined('ICON_SEARCH')			OR define('ICON_SEARCH', 'icon-search');
defined('ICON_SIGNOUT')			OR define('ICON_SIGNOUT', 'icon-signout');
defined('ICON_HOME')			OR define('ICON_HOME', 'icon-home');
defined('ICON_BACK')			OR define('ICON_BACK', 'icon-arrow-left');
defined('ICON_SAVED')			OR define('ICON_SAVED', 'glyphicon glyphicon-floppy-disk');
defined('ICON_CHECK')			OR define('ICON_CHECK', 'glyphicon glyphicon-check');
defined('ICON_DOWNLOAD')		OR define('ICON_DOWNLOAD', 'icon-download');
defined('ICON_FLAG')			OR define('ICON_FLAG', 'icon-flag');
defined('ICON_DONE')			OR define('ICON_DONE', 'icon-check');
defined('ICON_CALENDAR')		OR define('ICON_CALENDAR', 'icon-calendar');
defined('ICON_GROUP')			OR define('ICON_GROUP', 'icon-group');
defined('ICON_THUMBS_UP')		OR define('ICON_THUMBS_UP', 'icon-thumbs-up');
defined('ICON_ADD')				OR define('ICON_ADD', 'glyphicon glyphicon-plus-sign');
defined('ICON_PHONE')			OR define('ICON_PHONE', 'glyphicon glyphicon-earphone');
defined('ICON_USER')			OR define('ICON_USER', 'glyphicon glyphicon-user');

/*CONSTANTES DE ESTADO*/
defined('DB_ACTIVO')			OR define('DB_ACTIVO', 'AC');
defined('DB_INACTIVO')			OR define('DB_INACTIVO', 'IN');
defined('DB_BLOQUEADO')			OR define('DB_BLOQUEADO', 'BL');
defined('DB_REGISTRADO')		OR define('DB_REGISTRADO', 'RE');
defined('DB_PENDIENTE')			OR define('DB_PENDIENTE', 'PE');
defined('DB_APROBADO')			OR define('DB_APROBADO', 'AP');
defined('DB_DESAPROBADO')		OR define('DB_DESAPROBADO', 'DE');


/*BOTONES FOOTER*/
define('TEXTO_REGISTRAR', 'Nuevo');
define('TEXTO_EDITAR', 'Editar');
define('TEXTO_GRABAR', 'Guardar');
define('TEXTO_CANCELAR', 'Cancelar');
define('TEXTO_REGRESAR', 'Regresar');
define('TEXTO_EXPORTAR', 'Exportar');
define('TEXTO_IMPRIMIR', 'Imprimir');

define('EXT_EXCEL', 'XLSX');

define('FPDF_FONTPATH', APPPATH.'third_party/fpdf181/font/');
define( 'PCLZIP_TEMPORARY_DIR', BASEPATH . 'cache/' );

// CARLOS: modos de accion
define('MODO_REGISTRAR', 'R');
define('MODO_EDITAR', 'E');
define('TEXTO_ELEGIR', 'Elegir');
define('TEXTO_PERIODO_VERSION_FORMULARIO', 'Formulario de versiones de periodos');

// CARLOS: iconos
define('ICONO_HOME', 'icon-home');
define('ICONO_REGISTRAR', 'icon-plus');
define('ICONO_CALENDARIO', 'icon-calendar');
define('ICONO_GRABAR', 'icon-save');
define('ICONO_CANCELAR', 'icon-remove');
define('ICONO_PERIODOS', 'icon-th');
define('ICONO_VERSIONES', 'icon-tags');
define('ICONO_OBJETIVOS', 'icon-tags');
define('ICONO_UNIDADES_MEDIDA', 'icon-th');
define('ICONO_ESTRUCTURAS', 'icon-th');
define('ICONO_ESTRUCTURA_LINEAS', 'icon-sitemap');
define('ICONO_INDICADORES', 'icon-th');

// CARLOS: mensajes
define('MENSAJE_TIPO_EXITO', 'EXITO');
define('MENSAJE_TIPO_ERROR', 'ERROR');
define('MENSAJE_FECHA_NO_VALIDA', 'El formato de la fecha no es v&aacute;lido');
define('MENSAJE_REGISTRO_SATISFACTORIO', 'Registro ejecutado exitosamente !');
define('MENSAJE_REGISTRO_NO_VALIDO', 'No es posible ejecutar este registro !');
define('MENSAJE_MODIFICACION_SATISFACTORIA', 'Registro modificado exitosamente !');
define('MENSAJE_MODIFICACION_NO_VALIDA', 'No es posible modificar este registro !');
define('MENSAJE_ELIMINACION_SATISFACTORIA', 'Registro eliminado exitosamente !');
define('MENSAJE_ELIMINACION_NO_VALIDA', 'No es posible eliminar este registro !');
define('MENSAJE_DATA_DUPLICADA', 'Ya existe un registro con el dato ingresado !');
define('MENSAJE_IDENTIFICADOR_NO_VALIDO', 'El identificador no es v&aacute;lido !');
define('MENSAJE_VERSIONES_MULTIPLES', 'No es posible eliminar un periodo con m&aacute;s de una versi&oacute;n !');

// CARLOS: estados
define('ESTADO_REGISTRADO', 'RE');
define('ESTADO_MODIFICADO', 'MO');
define('ESTADO_ACTIVO', 'AC');
define('ESTADO_INACTIVO', 'IN');

// CARLOS: otros
define('KEY', '$%#&&(¨aa_["RE9111478');
define('FECHA_SEPARADOR', '/');
define('INDICADOR_SI', 'S');
define('INDICADOR_NO', 'N');
define('CANTIDAD_FILAS_PAGINACION', '10');
define('CANTIDAD_FILAS_BLANCO', '5');

//Version del Sistema

define('VERSION','1.0.0');

define('ID_DEFAULT', '1');
define('INSERT_ACTION', 'INSERT');
define('UPDATE_ACTION', 'UPDATE');
define('DELETE_ACTION', 'DELETE');

define('ESTADO_CIERRE_ABIERTO', 'AB');
define('ESTADO_CIERRE_CERRADO', 'CE');

define('ETIQUETA_CIERRE_ABIERTO', 'Abierto');
define('ETIQUETA_CIERRE_CERRADO', 'Cerrado');




































