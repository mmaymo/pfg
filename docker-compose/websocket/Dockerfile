FROM node


ADD /docker-compose/websocket/start.sh /start.sh
COPY /docker-compose/websocket/server.js /var/www/

RUN chmod 700 /start.sh
RUN chmod 700 /var/www/server.js

CMD ["/start.sh"]

EXPOSE 8999

