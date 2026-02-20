FROM node:22-alpine

WORKDIR /app

COPY package.json package-lock.json* ./

RUN npm install

EXPOSE 3000

CMD ["sh", "-c", "npx nuxt prepare && npm run dev -- --host 0.0.0.0"]
