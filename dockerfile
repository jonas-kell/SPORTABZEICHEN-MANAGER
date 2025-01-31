# develop stage
FROM node:18-alpine as develop-quasar
WORKDIR /app
RUN npm i --location=global @quasar/cli
