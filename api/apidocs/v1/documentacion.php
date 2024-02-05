<?php
header("Access-Control-Allow-Origin: ".(isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:"*"));
header("Access-Control-Allow-Methods: GET, OPTIONS");

header("Content-Type: application/json;charset=UTF-8;");

//---> Archivo local de constantes;
if(file_exists("../../config.php")){require_once("../../config.php");}
else{die(json_encode(["error"=>"No existe el archivo de configuraciones."]));};

//---> Cargo las funciones y otras cosas;
require(DIRCORE."load.php");
?>
{
	"swagger": "2.0",
	"info": {
		"description": "Documentación para realizar retiros y reversiones en cajeros 5B.",
		"version": "1.0.0",
		"title": "Documentación API",
		"termsOfService": "https://coincaex.com/Terminos-y-condiciones/",
		"contact": {
			"name": "Coincaex Devs",
			"email": "biancogaray@coincaex.com"
		}
	},
	"host": "<?php echo trim(trim(@$_SERVER['HTTP_HOST'],"/")."/".PATH,"/"); ?>",
	"basePath": "/API/v1",
	"schemes": ["http<?php echo (!empty($_SERVER['HTTPS'])&&mb_strtolower($_SERVER['HTTPS'])!="off"?'s':''); ?>"],
	"paths": {
		"/SolicitudRetiro": {
			"post": {
				"tags": ["SolicitudRetiro"],
				"summary": "Obtiene el retiro previamente creado en Coincaex y lo marca como pagado.",
				"description": "",
				"operationId": "SolicitudRetiro",
				"consumes": ["application/json"],
				"produces": ["application/json"],
				"parameters": [{
					"in": "body",
					"name": "body",
					"description": "Modelo de la solicitud.",
					"schema": { "$ref": "#/definitions/SolicitudRetiro" }
				}],
				"responses": {
					"200": { "description": "Solicitud Existosa." },
					"201": { "description": "Solicitud creada Existosamente." },
					"403": { "description": "Acceso Denegado." },
					"404": { "description": "El documento no fue localizado." },
					"405": { "description": "Campos inválidos." },
					"409": { "description": "Hubo un conflicto con los datos." },
					"500": { "description": "Error de conexión con el servidor." }
				},
				"security": [{ "Llave": ["write", "read"] }]
			}
		},
		"/ReversionRetiro": {
			"post": {
				"tags": ["ReversionRetiro"],
				"summary": "Revierte un retiro previamente marcado como pagado.",
				"description": "",
				"operationId": "ReversionRetiro",
				"consumes": ["application/json"],
				"produces": ["application/json"],
				"parameters": [{
					"in": "body",
					"name": "body",
					"description": "Modelo de la solicitud.",
					"schema": {
						"$ref": "#/definitions/ReversionRetiro"
					}
				}],
				"responses": {
					"200": { "description": "Solicitud Existosa." },
					"201": { "description": "Solicitud creada Existosamente." },
					"403": { "description": "Acceso Denegado." },
					"404": { "description": "El documento no fue localizado." },
					"405": { "description": "Campos inválidos." },
					"409": { "description": "Hubo un conflicto con los datos." },
					"500": { "description": "Error de conexión con el servidor." }
				},
				"security": [{
					"Llave": ["write", "read"]
				}]
			}
		}
	},
	"securityDefinitions": {
		"Llave": {
			"type": "apiKey",
			"name": "X-API-KEY",
			"in": "header",
			"scopes": {
				"read": "Obtener los datos",
				"write": "Escribir los datos"
			}
		}
	},
	"definitions": {
		"SolicitudRetiro": {
			"type": "object",
			"required": [
				"CodigoProceso",
				"Monto",
				"FechaHora",
				"NumeroAuditoria",
				"HoraTransaccion",
				"FechaTransaccion",
				"FechaLiquidacion",
				"Origen",
				"Moneda",
				"Trace",
				"Terminal",
				"Ubicacion",
				"Adquiriente",
				"Identificador",
				"Referencia"
			],
			"properties": {
				"CodigoProceso": {
					"type": "string",
					"length": 6,
					"example": "010000"
				},
				"Monto": {
					"type": "number",
					"format": 10.00,
					"required": true,
					"example": 100.00
				},
				"FechaHora": {
					"type": "string",
					"format": "yyyyMMddHHmmss",
					"length": 14,
					"example": "20210526154223"
				},
				"NumeroAuditoria": {
					"type": "integer",
					"format": "int64",
					"length": 6,
					"example": "523712"
				},
				"HoraTransaccion": {
					"type": "string",
					"format": "Hhmmss",
					"length": 6,
					"example": "154228"
				},
				"FechaTransaccion": {
					"type": "string",
					"format": "MMDD",
					"length": 6,
					"example": "0526"
				},
				"FechaLiquidacion": {
					"type": "string",
					"format": "MMDD",
					"length": 6,
					"example": "0726"
				},
				"Origen": {
					"type": "integer",
					"format": "int64",
					"length": 4,
					"example": 6011
				},
				"Moneda": {
					"type": "integer",
					"format": "int64",
					"length": 4,
					"example": 320
				},
				"Trace": {
					"type": "string",
					"length": 12,
					"example": "20210526154223"
				},
				"Terminal": {
					"type": "string",
					"length": 4,
					"example": "3052"
				},
				"Ubicacion": {
					"type": "string",
					"length": 40,
					"example": "TEST"
				},
				"Adquiriente": {
					"type": "string",
					"length": 11,
					"example": "491381"
				},
				"Identificador": {
					"type": "string",
					"length": 32,
					"example": "050199003565"
				},
				"Referencia": {
					"type": "string",
					"length": 32,
					"example": "EM123456"
				},
				"CampoUsuario1": {
					"type": "string",
					"length": 32,
					"example": null
				},
				"CampoUsuario2": {
					"type": "string",
					"length": 32,
					"example": null
				},
				"CampoUsuario3": {
					"type": "string",
					"length": 32,
					"example": null
				}
			}
		},
		"ReversionRetiro": {
			"type": "object",
			"required": [
				"CodigoProceso",
				"Monto",
				"FechaHora",
				"NumeroAuditoria",
				"HoraTransaccion",
				"FechaTransaccion",
				"FechaLiquidacion",
				"Origen",
				"Moneda",
				"Trace",
				"Terminal",
				"Ubicacion",
				"Adquiriente",
				"Identificador",
				"Referencia"
			],
			"properties": {
				"CodigoProceso": {
					"type": "string",
					"length": 6,
					"example": "010000"
				},
				"Monto": {
					"type": "number",
					"format": 10.00,
					"required": true,
					"example": 100.00
				},
				"FechaHora": {
					"type": "string",
					"format": "yyyyMMddHHmmss",
					"length": 14,
					"example": "20210526154223"
				},
				"NumeroAuditoria": {
					"type": "integer",
					"format": "int64",
					"length": 6,
					"example": "523712"
				},
				"HoraTransaccion": {
					"type": "string",
					"format": "Hhmmss",
					"length": 6,
					"example": "154228"
				},
				"FechaTransaccion": {
					"type": "string",
					"format": "MMDD",
					"length": 6,
					"example": "0526"
				},
				"FechaLiquidacion": {
					"type": "string",
					"format": "MMDD",
					"length": 6,
					"example": "0726"
				},
				"Origen": {
					"type": "integer",
					"format": "int64",
					"length": 4,
					"example": 6011
				},
				"Moneda": {
					"type": "integer",
					"format": "int64",
					"length": 4,
					"example": 320
				},
				"Trace": {
					"type": "string",
					"length": 12,
					"example": "20210526154223"
				},
				"Terminal": {
					"type": "string",
					"length": 4,
					"example": "3052"
				},
				"Ubicacion": {
					"type": "string",
					"length": 40,
					"example": "TEST"
				},
				"Adquiriente": {
					"type": "string",
					"length": 11,
					"example": "491381"
				},
				"Identificador": {
					"type": "string",
					"length": 32,
					"example": "050199003565"
				},
				"Referencia": {
					"type": "string",
					"length": 32,
					"example": "EM123456"
				},
				"CampoUsuario1": {
					"type": "string",
					"length": 32,
					"example": null
				},
				"CampoUsuario2": {
					"type": "string",
					"length": 32,
					"example": null
				},
				"CampoUsuario3": {
					"type": "string",
					"length": 32,
					"example": null
				}
			}
		},
		"Respuesta": {
			"type": "object",
			"properties":{
				"CodigoRespuesta": {
					"type": "string",
					"length": 6,
					"example": "010000"
				},
				"NumeroAuditoria": {
					"type": "integer",
					"format": "int64",
					"length": 6,
					"example": "523712"
				},
				"Trace": {
					"type": "string",
					"length": 12,
					"example": "20210526154223"
				},
				"Identificador": {
					"type": "string",
					"length": 32,
					"example": "050199003565"
				},
				"DescripcionError": {
					"type": "string",
					"length": 32,
					"example": null
				},
				"Campo60":{
					"type": "string",
					"length": 32,
					"example": "42156438"
				},
				"Autorizacion":{
					"type": "string",
					"length": 6,
					"example": "42156438"
				}
			}
		},
		"ErrorInesperado": {
			"type": "object",
			"properties":{
				"error": { "type":"boolean" },
				"titulo": { "type": "string" },
				"mensaje": { "type": "string" }
			}
		}
	},
	"externalDocs": {
		"description": "Questions or Suggestions",
		"url": "https://coincaex.com/Contacto/"
	}
}