FROM node:18.10-alpine

WORKDIR /usr/src/frontend

RUN apk add --update nodejs npm

COPY frontend/ /usr/src/frontend

RUN npm ci
RUN npm run build

CMD npm run start