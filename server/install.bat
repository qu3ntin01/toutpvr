@echo off
rem ============================================================================
rem  ToutPVR — lanceur de l'installeur Windows
rem  Double-cliquez ce fichier : il demande l'élévation puis exécute install.ps1
rem  Arguments transmis à install.ps1, ex. : install.bat -Yes -Port 8080
rem ============================================================================
setlocal
set "SCRIPT_DIR=%~dp0"

net session >nul 2>&1
if %errorlevel% neq 0 (
  echo Elevation des privileges administrateur...
  powershell -NoProfile -ExecutionPolicy Bypass -Command "Start-Process -FilePath 'cmd.exe' -ArgumentList '/k \"\"%~f0\" %*\"' -Verb RunAs"
  exit /b
)

powershell -NoProfile -ExecutionPolicy Bypass -File "%SCRIPT_DIR%install.ps1" %*
echo.
pause
