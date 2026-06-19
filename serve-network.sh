#!/usr/bin/env bash
# Démarre le serveur Laravel accessible sur le réseau local

PORT=${1:-8000}

echo "🔨 Compilation des assets Vite..."
npm run build 2>&1 || { echo "❌ Échec de la compilation"; exit 1; }

# Détecte l'IP locale
if command -v ip &>/dev/null; then
    IP=$(ip -4 addr show | grep -oP '(?<=inet\s)\d+(\.\d+){3}' | grep -v '127.0.0.1' | head -1)
elif command -v ifconfig &>/dev/null; then
    IP=$(ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1' | head -1)
else
    IP="<votre_ip_locale>"
fi

echo ""
echo "╔══════════════════════════════════════════════╗"
echo "║        FlashFood - Serveur Réseau        ║"
echo "╠══════════════════════════════════════════════╣"
echo "║  Local :  http://127.0.0.1:${PORT}/              ║"
echo "║  Réseau: http://${IP}:${PORT}/                 ║"
echo "╠══════════════════════════════════════════════╣"
echo "║  Autres appareils sur le même WiFi :        ║"
echo "║  → http://${IP}:${PORT}/                    ║"
echo "╚══════════════════════════════════════════════╝"
echo ""

php artisan serve --host=0.0.0.0 --port=$PORT
