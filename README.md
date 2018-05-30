Science Trotters Backoffice

	Build With
	    SMARTY 
	    PHP >=7.2
	    APACHE OR NGINX

Getting Started
	
	Configuration
		Update Configuration Values in /config/defines.php
		API_URL: URL for contacting API. (Ex: http://api-sts.actu.com)
		API_SSL: Set to True to force SSL verification while contacting API. (False for debug purpose)



	Copy the file vhost.conf
		Replace {$sslPath} with the SSL Certificates path
		Replace {$sitePath} with the Website path
	
	Paste the file in /etc/nginx/sites-enabled
	Restart Ngnix
		


License

	This project is licensed under the GPLv2 License - see the GPLv2-LICENSE.md file for details
