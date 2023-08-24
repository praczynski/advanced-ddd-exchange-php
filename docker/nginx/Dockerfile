FROM nginx:1.17-alpine

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./conf.d/default.conf /etc/nginx/conf.d/default.conf

VOLUME /app

WORKDIR /app
