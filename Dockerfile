FROM nginx:alpine
ADD nginx/default.conf /etx/nginx/conf.d

COPY