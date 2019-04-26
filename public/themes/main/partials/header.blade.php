server {
listen 443 ssl;
server_name www.cungcap.com.vn;
ssl_certificate /etc/letsencrypt/live/cungcap.com.vn/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/cungcap.com.vn/privkey.pem;
include /usr/local/nginx/snippets/ssl.conf;
rewrite ^(.*) https://cungcap.com.vn$1 permanent;
}
server {
listen 443 ssl;
server_name cungcap.com.vn;
ssl_certificate /etc/letsencrypt/live/cungcap.com.vn/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/cungcap.com.vn/privkey.pem;
include /usr/local/nginx/snippets/ssl.conf;
rewrite ^(.*) https://cungcap.com.vn$1 permanent;
}
server {
listen 80;
server_name *.cungcap.com.vn cungcap.com.vn;
include /usr/local/nginx/snippets/letsencrypt.conf;
rewrite ^(.*) https://cungcap.com.vn$1 permanent;
root /home/cc_site/public;
include /usr/local/nginx/conf/503include-main.conf;
location / {
include /usr/local/nginx/conf/503include-only.conf;
try_files $uri $uri/ /index.php?q=$uri&$args;
}
include /usr/local/nginx/conf/pre-staticfiles-global.conf;
include /usr/local/nginx/conf/staticfiles.conf;
include /usr/local/nginx/conf/php.conf;
include /usr/local/nginx/conf/drop.conf;
include /usr/local/nginx/conf/vts_server.conf;
}
server {
listen 443 ssl;
server_name *.cungcap.com.vn cungcap.com.vn;
ssl_certificate /etc/letsencrypt/live/cungcap.com.vn/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/cungcap.com.vn/privkey.pem;
include /usr/local/nginx/snippets/ssl.conf;
root /home/cc_site/public;
include /usr/local/nginx/conf/503include-main.conf;
location / {
include /usr/local/nginx/conf/503include-only.conf;
try_files $uri $uri/ /index.php?q=$uri&$args;
}
include /usr/local/nginx/conf/pre-staticfiles-global.conf;
include /usr/local/nginx/conf/staticfiles.conf;
include /usr/local/nginx/conf/php.conf;
include /usr/local/nginx/conf/drop.conf;
include /usr/local/nginx/conf/vts_server.conf;
}