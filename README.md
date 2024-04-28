### Installation Guide

After cloning the project follow the steps below.
1. Run command:
   ```composer install```
2. Copy **.env.example** file and paste in the project folder with the name **".env"** , also set
   values in **.env**. 
3. Run command: ```./vendor/bin/sail up```  (To generate new key if needed)

#server {
#
#    listen 443 ssl default_server;
#    # listen [::]:443 ssl default_server;
#    
#    server_name svr2.scadware.com;
#	
#    ssl_certificate /etc/apache2/ssl.crt/scadware/scadware.crt;
#    ssl_certificate_key /etc/apache2/ssl.crt/scadware/scadware.key;
#    ssl_trusted_certificate /etc/apache2/ssl.crt/scadware/scadware.ca-bundle;
#    
#    # Other SSL configurations go here
#
#    root /var/www/nginx-web-test;
#    
#    location / {
#        # Your regular server block configuration for HTTPS
#	# First attempt to serve request as file, then
#	# as directory, then fall back to displaying a 404.
#	try_files $uri $uri/ =404;        
#    }
#}