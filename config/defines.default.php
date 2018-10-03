<?php

define("API_SSL", getenv('API_SSL')=='true');	// Forcer la Vérification SSL lors des requêtes APÏ
define("API_URL", getenv('API_URL')); // Url de l'API
define("API_URL_FRONT", getenv('API_URL_FRONT')); // Url de l'API