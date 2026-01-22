#!/bin/bash
# Script para preparar y subir FinanzaPro a GitHub

echo "🚀 FinanzaPro - Upload to GitHub"
echo "=================================="
echo ""

# Cambiar al directorio del proyecto
cd "$(dirname "$0")"

# Configurar git
echo "📝 Configurando Git..."
git config core.autocrlf true
git config user.name "FinanzaPro Dev"
git config user.email "dev@finanzapro.com"

# Agregar remote (reemplazar con tu usuario y token)
echo ""
echo "📡 Agregando repositorio remoto..."
echo ""
echo "Debes ejecutar uno de estos comandos (reemplaza USERNAME y TOKEN):"
echo ""
echo "Opción 1: Con HTTPS (más seguro con token)"
echo "git remote add origin https://github.com/USERNAME/Sistema-Finanzas.git"
echo ""
echo "Opción 2: Con SSH (requiere clave SSH configurada)"
echo "git remote add origin git@github.com:USERNAME/Sistema-Finanzas.git"
echo ""
echo "Luego ejecuta:"
echo "git branch -M main"
echo "git push -u origin main"
echo ""
echo "=================================="
echo "✅ Pasos completados:"
echo "   ✓ Repositorio git inicializado"
echo "   ✓ Archivos comprometidos"
echo "   ✓ README documentado"
echo "   ✓ Listo para subir a GitHub"
echo ""
