# develop stage
FROM node:16.15-alpine as develop-quasar
WORKDIR /app
RUN npm i --location=global @quasar/cli
