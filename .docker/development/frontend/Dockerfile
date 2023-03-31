FROM node:18.10-alpine

WORKDIR /usr/src/frontend

RUN apk add --update nodejs npm

COPY frontend/ /usr/src/frontend

RUN npm ci

CMD npm run dev