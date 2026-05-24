# develop stage
FROM node:20-alpine as develop-quasar
WORKDIR /app
RUN npm i --location=global @quasar/cli
