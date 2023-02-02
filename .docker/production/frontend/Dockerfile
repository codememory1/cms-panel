FROM node:lts as builder

WORKDIR /usr/src/frontend

COPY frontend/ .

RUN yarn install \
  --prefer-offline \
  --frozen-lockfile \
  --non-interactive \
  --production=false

RUN yarn build

RUN rm -rf node_modules && \
  NODE_ENV=production yarn install \
  --prefer-offline \
  --pure-lockfile \
  --non-interactive \
  --production=true

FROM node:lts

WORKDIR /usr/src/frontend

COPY --from=builder /usr/src/frontend  .

EXPOSE 3001

CMD [ "yarn", "start" ]