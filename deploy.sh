#!/bin/bash
git pull origin main
npm install
npm run build
# Copiar los archivos build a la ubicación correcta en el servidor
