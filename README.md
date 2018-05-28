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



	Copy the file etc/nginx/sites-enabled
		listen 80;
		listen [::]:80;

		server_name admin-sts.actu.com;
		root /data/vhosts/science_trotters/admin;


License

	This project is licensed under the GPLv2 License - see the GPLv2-LICENSE.md file for details
