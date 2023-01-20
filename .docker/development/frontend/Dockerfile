FROM node:18.10-alpine

WORKDIR /usr/src/frontend

RUN apk add --update nodejs npm

COPY frontend/ .

RUN npm install
RUN npm run build

CMD npm run dev