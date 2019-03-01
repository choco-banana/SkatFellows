FROM php:7.2.8-apache
COPY ./webservice /var/www/html
COPY ./apache/my-httpd.conf /etc/apache2/apache2.conf
COPY ./apache/my-httpd-ssl.conf /etc/apache2/extra/httpd-ssl.conf
COPY ./apache/server.crt /etc/apache2/extra/server.crt
COPY ./apache/server.key /etc/apache2/extra/server.key
COPY ./apache/.htpasswd /etc/apache2/.htpasswd
RUN mkdir /etc/apache2/logs
RUN docker-php-ext-install -j$(nproc) mysqli
RUN sed -ri \
		-e 's!^(\s*CustomLog)\s+\S+!\1 /proc/self/fd/1!g' \
		-e 's!^(\s*ErrorLog)\s+\S+!\1 /proc/self/fd/2!g' \
		"/etc/apache2/apache2.conf"
RUN apache2ctl configtest
RUN apache2ctl -V
RUN apache2ctl restart