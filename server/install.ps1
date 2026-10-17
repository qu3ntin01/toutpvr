#Requires -Version 5.1
<#
.SYNOPSIS
  ToutPVR — installeur Windows (Windows 10 / 11 / Server 2016+).

.DESCRIPTION
  - Installe Python 3.12 (winget ou installeur officiel python.org) s'il manque
  - Installe ffmpeg via winget (optionnel)
  - Crée un environnement virtuel dans "C:\Program Files\ToutPVR" et y installe le paquet
  - Écrit la configuration dans "C:\ProgramData\ToutPVR\config.yaml", crée le compte propriétaire
  - Crée une tâche planifiée « ToutPVR » (démarrage automatique, compte SYSTEM) ou un service NSSM si présent
  - Ouvre le port dans le pare-feu Windows
  - Ajoute le lanceur "toutpvr" au PATH

.EXAMPLE
  powershell -ExecutionPolicy Bypass -File install.ps1
  powershell -ExecutionPolicy Bypass -File install.ps1 -Yes -Port 8080 -AdminPassword 'MonMotDePasse!' -License TP1-XXXXX-XXXXX-XXXXX-XXXXX
  powershell -ExecutionPolicy Bypass -File install.ps1 -Upgrade
  powershell -ExecutionPolicy Bypass -File install.ps1 -Uninstall -Purge
#>
[CmdletBinding()]
param(
  [switch]$Yes,
  [int]$Port = 8080,
  [ValidateSet('fr','en','es','de','it','pt','nl','pl','ru','uk','tr','ar','hi','bn','id','vi','th','zh','ja','ko')]
  [string]$Lang = 'fr',
  [string]$AdminUser = 'admin',
  [string]$AdminPassword = '',
  [string]$License = '',
  [string]$PortalUrl = 'https://toutpvr.example',
  [ValidateSet('sqlite','mysql')]
  [string]$Db = 'sqlite',
  [string]$DbUrl = '',
  [string]$Source = '',
  [string]$Prefix = "$env:ProgramFiles\ToutPVR",
  [string]$DataDir = "$env:ProgramData\ToutPVR",
  [switch]$NoService,
  [switch]$NoFirewall,
  [switch]$NoFfmpeg,
  [switch]$Upgrade,
  [switch]$Uninstall,
  [switch]$Purge,
  [switch]$DryRun
)

$ErrorActionPreference = 'Stop'
$ProgressPreference = 'SilentlyContinue'
$Product = 'ToutPVR'
$ScriptVersion = '1.0.0'
$ConfigFile = Join-Path $DataDir 'config.yaml'
$VenvDir = Join-Path $Prefix 'venv'
$Launcher = Join-Path $Prefix 'toutpvr.cmd'
$TaskName = 'ToutPVR'
$PythonMinimum = [version]'3.10'
$PythonInstallerUrl = 'https://www.python.org/ftp/python/3.12.8/python-3.12.8-amd64.exe'
$script:GeneratedPassword = $false

function Write-Step([string]$Text) { Write-Host "`n$Text" -ForegroundColor White }
function Write-Ok([string]$Text) { Write-Host "[OK] $Text" -ForegroundColor Green }
function Write-Info([string]$Text) { Write-Host "  >  $Text" -ForegroundColor Cyan }
function Write-Warn([string]$Text) { Write-Host "[!]  $Text" -ForegroundColor Yellow }
function Fail([string]$Text) { Write-Host "[X]  $Text" -ForegroundColor Red; exit 1 }

function Invoke-Step {
  param([string]$Description, [scriptblock]$Action)
  if ($DryRun) { Write-Host "  (dry-run) $Description" -ForegroundColor DarkGray; return }
  & $Action
}

function Test-Admin {
  $id = [Security.Principal.WindowsIdentity]::GetCurrent()
  return (New-Object Security.Principal.WindowsPrincipal $id).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
}

# ---------------------------------------------------------------------------
#  Python
# ---------------------------------------------------------------------------
function Get-PythonVersion([string]$Exe) {
  try {
    $out = & $Exe -c 'import sys; print("%d.%d.%d" % sys.version_info[:3])' 2>$null
    if ($LASTEXITCODE -eq 0 -and $out) { return [version]$out.Trim() }
  } catch { }
  return $null
}

function Find-Python {
  $candidates = @()
  foreach ($name in @('py', 'python3', 'python')) {
    $cmd = Get-Command $name -ErrorAction SilentlyContinue
    if ($cmd) { $candidates += $cmd.Source }
  }
  foreach ($dir in @("$env:ProgramFiles\Python313", "$env:ProgramFiles\Python312", "$env:ProgramFiles\Python311", "$env:ProgramFiles\Python310",
                     "$env:LOCALAPPDATA\Programs\Python\Python313", "$env:LOCALAPPDATA\Programs\Python\Python312",
                     "$env:LOCALAPPDATA\Programs\Python\Python311", "$env:LOCALAPPDATA\Programs\Python\Python310")) {
    $exe = Join-Path $dir 'python.exe'
    if (Test-Path $exe) { $candidates += $exe }
  }
  foreach ($exe in $candidates) {
    $args = @()
    if ((Split-Path $exe -Leaf) -ieq 'py.exe') { $args = @('-3') }
    try {
      $out = & $exe @args -c 'import sys; print("%d.%d.%d" % sys.version_info[:3]); print(sys.executable)' 2>$null
      if ($LASTEXITCODE -eq 0 -and $out) {
        $v = [version]($out[0].Trim())
        if ($v -ge $PythonMinimum) { return $out[1].Trim() }
      }
    } catch { }
  }
  return $null
}

function Install-Python {
  Write-Step "Installation de Python 3.12"
  $winget = Get-Command winget -ErrorAction SilentlyContinue
  if ($winget) {
    Invoke-Step "winget install Python.Python.3.12" {
      & winget install --id Python.Python.3.12 --exact --silent --accept-package-agreements --accept-source-agreements --scope machine | Out-Null
    }
  } else {
    $tmp = Join-Path $env:TEMP 'python-installer.exe'
    Write-Info "Téléchargement de $PythonInstallerUrl"
    Invoke-Step "Télécharger et exécuter l'installeur python.org" {
      Invoke-WebRequest -Uri $PythonInstallerUrl -OutFile $tmp -UseBasicParsing
      $p = Start-Process -FilePath $tmp -ArgumentList '/quiet', 'InstallAllUsers=1', 'PrependPath=1', 'Include_test=0', 'Include_launcher=1' -Wait -PassThru
      if ($p.ExitCode -ne 0) { throw "L'installeur Python a renvoyé le code $($p.ExitCode)" }
      Remove-Item $tmp -Force -ErrorAction SilentlyContinue
    }
  }
  # Rafraîchir le PATH de la session courante
  $env:Path = [Environment]::GetEnvironmentVariable('Path', 'Machine') + ';' + [Environment]::GetEnvironmentVariable('Path', 'User')
}

function Install-Ffmpeg {
  if ($NoFfmpeg) { return }
  if (Get-Command ffmpeg -ErrorAction SilentlyContinue) { Write-Ok "ffmpeg déjà présent"; return }
  $winget = Get-Command winget -ErrorAction SilentlyContinue
  if (-not $winget) { Write-Warn "winget absent : ffmpeg non installé (enregistrement via OpenCV). Installez-le depuis https://www.gyan.dev/ffmpeg/builds/"; return }
  Write-Step "Installation de ffmpeg"
  Invoke-Step "winget install Gyan.FFmpeg" {
    try {
      & winget install --id Gyan.FFmpeg --exact --silent --accept-package-agreements --accept-source-agreements --scope machine | Out-Null
      $env:Path = [Environment]::GetEnvironmentVariable('Path', 'Machine') + ';' + [Environment]::GetEnvironmentVariable('Path', 'User')
      Write-Ok "ffmpeg installé"
    } catch { Write-Warn "ffmpeg non installé : $($_.Exception.Message)" }
  }
}

# ---------------------------------------------------------------------------
#  Source
# ---------------------------------------------------------------------------
# Deux dispositions : arbre source (pyproject.toml + toutpvr\) ou arbre de diffusion (wheels\toutpvr-*.whl compilées).
$script:WheelDir = ''

function Find-PackageRoot([string]$Base) {
  foreach ($c in @(Get-Item $Base) + @(Get-ChildItem -Path $Base -Recurse -Depth 2 -Directory -ErrorAction SilentlyContinue)) {
    if ((Test-Path (Join-Path $c.FullName 'wheels')) -and (Get-ChildItem (Join-Path $c.FullName 'wheels') -Filter 'toutpvr-*.whl' -ErrorAction SilentlyContinue)) { return $c.FullName }
    if ((Test-Path (Join-Path $c.FullName 'pyproject.toml')) -and (Test-Path (Join-Path $c.FullName 'toutpvr'))) { return $c.FullName }
  }
  return $null
}

function Resolve-Source {
  Write-Step "Source de $Product"
  if (-not $Source) {
    $hasWheels = (Test-Path (Join-Path $PSScriptRoot 'wheels')) -and (Get-ChildItem (Join-Path $PSScriptRoot 'wheels') -Filter 'toutpvr-*.whl' -ErrorAction SilentlyContinue)
    if ($hasWheels -or ((Test-Path (Join-Path $PSScriptRoot 'pyproject.toml')) -and (Test-Path (Join-Path $PSScriptRoot 'toutpvr')))) {
      $script:Source = $PSScriptRoot
    } else {
      Fail "Aucune source trouvée : lancez le script depuis le dossier server\ (ou l'arbre de diffusion contenant wheels\) ou passez -Source <dossier|archive.tar.gz|URL>."
    }
  }
  if ($Source -match '^https?://') {
    $tmp = Join-Path $env:TEMP ("toutpvr-src-" + [guid]::NewGuid().ToString('N'))
    New-Item -ItemType Directory -Path $tmp -Force | Out-Null
    Write-Info "Téléchargement de $Source"
    Invoke-WebRequest -Uri $Source -OutFile (Join-Path $tmp 'src.tar.gz') -UseBasicParsing
    & tar -xzf (Join-Path $tmp 'src.tar.gz') -C $tmp
    $root = Find-PackageRoot $tmp
    if (-not $root) { Fail "L'archive ne contient ni pyproject.toml ni dossier wheels\." }
    $script:Source = $root
  } elseif ($Source -match '\.(tar\.gz|tgz)$') {
    $tmp = Join-Path $env:TEMP ("toutpvr-src-" + [guid]::NewGuid().ToString('N'))
    New-Item -ItemType Directory -Path $tmp -Force | Out-Null
    & tar -xzf $Source -C $tmp
    $root = Find-PackageRoot $tmp
    if (-not $root) { Fail "L'archive ne contient ni pyproject.toml ni dossier wheels\." }
    $script:Source = $root
  }
  $wd = Join-Path $Source 'wheels'
  if ((Test-Path $wd) -and (Get-ChildItem $wd -Filter 'toutpvr-*.whl' -ErrorAction SilentlyContinue)) {
    $script:WheelDir = $wd
    Write-Ok "Arbre de diffusion (roues compilées) : $WheelDir"
  } elseif (-not $DryRun -and -not (Test-Path (Join-Path $Source 'pyproject.toml'))) {
    Fail "Source invalide : $Source (ni pyproject.toml ni wheels\toutpvr-*.whl)."
  } else {
    Write-Ok "Source : $Source"
  }
}

# Installe / met à niveau le paquet (source : pip install <dossier> ; diffusion : roue compilée choisie selon l'interpréteur).
function Install-ToutPvrPackage([string]$Vpy) {
  if (-not $WheelDir) {
    & $Vpy -m pip install --quiet --upgrade $Source
    return
  }
  $tag = (& $Vpy -c 'import sys; print("cp%d%d" % sys.version_info[:2])').Trim()
  $wheel = Get-ChildItem $WheelDir -Filter "toutpvr-*-$tag-$tag-win_amd64.whl" -ErrorAction SilentlyContinue | Select-Object -First 1
  if (-not $wheel) {
    $avail = (Get-ChildItem $WheelDir -Filter 'toutpvr-*.whl' | ForEach-Object { $_.Name }) -join ', '
    Fail "Aucune roue ToutPVR pour Python $($tag.Substring(2)) sous Windows (amd64). Roues disponibles : $avail. Installez Python 3.11 ou 3.12."
  }
  Write-Info "Roue : $($wheel.Name)"
  # toutpvr vient uniquement du dossier wheels\ (jamais d'un index) ; ses dépendances viennent de PyPI.
  & $Vpy -m pip install --quiet --upgrade --force-reinstall --no-index --no-deps $wheel.FullName
  if ($LASTEXITCODE -ne 0) { throw "pip a échoué (code $LASTEXITCODE)" }
  $reqs = Join-Path $env:TEMP ("toutpvr-reqs-" + [guid]::NewGuid().ToString('N') + '.txt')
  & $Vpy -c 'import importlib.metadata as m; print("\n".join(r for r in (m.requires("toutpvr") or []) if "extra ==" not in r))' | Set-Content -Path $reqs -Encoding ASCII
  & $Vpy -m pip install --quiet --upgrade -r $reqs
  Remove-Item $reqs -Force -ErrorAction SilentlyContinue
}

# ---------------------------------------------------------------------------
#  Installation du paquet
# ---------------------------------------------------------------------------
function Install-Package([string]$Python) {
  Write-Step "Installation dans $Prefix"
  Invoke-Step "Créer $Prefix, $DataDir et l'environnement virtuel" {
    New-Item -ItemType Directory -Path $Prefix, $DataDir -Force | Out-Null
    if (-not (Test-Path (Join-Path $VenvDir 'Scripts\python.exe'))) { & $Python -m venv $VenvDir }
    $vpy = Join-Path $VenvDir 'Scripts\python.exe'
    & $vpy -m pip install --quiet --upgrade pip wheel
    Install-ToutPvrPackage $vpy
    if ($LASTEXITCODE -ne 0) { throw "pip a échoué (code $LASTEXITCODE)" }
    if ($Db -eq 'mysql') { & $vpy -m pip install --quiet --upgrade pymysql }
    if ($LASTEXITCODE -ne 0) { throw "pip a échoué (code $LASTEXITCODE)" }
    # Lanceur global
    @"
@echo off
rem Lanceur $Product — généré par install.ps1
if "%TP_CONFIG%"=="" set "TP_CONFIG=$ConfigFile"
if "%TP_DATA_DIR%"=="" set "TP_DATA_DIR=$DataDir"
"$VenvDir\Scripts\toutpvr.exe" %*
"@ | Set-Content -Path $Launcher -Encoding ASCII
    $machinePath = [Environment]::GetEnvironmentVariable('Path', 'Machine')
    if ($machinePath -notlike "*$Prefix*") {
      [Environment]::SetEnvironmentVariable('Path', "$machinePath;$Prefix", 'Machine')
    }
    $env:Path = "$env:Path;$Prefix"
  }
  Write-Ok "Paquet installé"
}

function New-RandomPassword {
  $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!%+='.ToCharArray()
  $rng = [System.Security.Cryptography.RandomNumberGenerator]::Create()
  $bytes = New-Object byte[] 16
  $rng.GetBytes($bytes)
  return -join ($bytes | ForEach-Object { $chars[$_ % $chars.Length] })
}

function Invoke-Configure {
  Write-Step "Configuration"
  if ((Test-Path $ConfigFile) -and -not $Upgrade) {
    Write-Warn "Configuration existante conservée : $ConfigFile"
    return
  }
  if (-not $AdminPassword) {
    if (-not $Yes) {
      $sec = Read-Host -AsSecureString "Mot de passe du compte « $AdminUser » (vide = généré)"
      $AdminPassword = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($sec))
    }
    if (-not $AdminPassword) { $script:AdminPassword = New-RandomPassword; $script:GeneratedPassword = $true }
  }
  $args = @('install', '--yes', '--lang', $Lang, '--port', $Port, '--host', '0.0.0.0', '--data-dir', $DataDir,
            '--admin-user', $AdminUser, '--admin-password', $AdminPassword, '--portal-url', $PortalUrl, '--db', $Db)
  if ($DbUrl) { $args += @('--db-url', $DbUrl) }
  if ($License) { $args += @('--license', $License) }
  Invoke-Step "toutpvr install --yes ..." {
    $env:TP_CONFIG = $ConfigFile
    $env:TP_DATA_DIR = $DataDir
    & (Join-Path $VenvDir 'Scripts\toutpvr.exe') @args
    if ($LASTEXITCODE -ne 0) { throw "toutpvr install a échoué (code $LASTEXITCODE)" }
  }
}

# ---------------------------------------------------------------------------
#  Service (tâche planifiée au démarrage, ou service NSSM si disponible)
# ---------------------------------------------------------------------------
function Install-Service {
  if ($NoService) { Write-Warn "Service non créé (-NoService). Démarrez avec : toutpvr start"; return }
  Write-Step "Démarrage automatique"
  $exe = Join-Path $VenvDir 'Scripts\toutpvr.exe'
  $nssm = Get-Command nssm -ErrorAction SilentlyContinue
  Invoke-Step "Créer le service/tâche $TaskName" {
    if ($nssm) {
      & nssm stop $TaskName 2>$null | Out-Null
      & nssm remove $TaskName confirm 2>$null | Out-Null
      & nssm install $TaskName $exe start | Out-Null
      & nssm set $TaskName AppEnvironmentExtra "TP_CONFIG=$ConfigFile" "TP_DATA_DIR=$DataDir" | Out-Null
      & nssm set $TaskName AppStdout (Join-Path $DataDir 'logs\service.log') | Out-Null
      & nssm set $TaskName AppStderr (Join-Path $DataDir 'logs\service.log') | Out-Null
      & nssm set $TaskName Start SERVICE_AUTO_START | Out-Null
      & nssm start $TaskName | Out-Null
      Write-Ok "Service Windows « $TaskName » (NSSM) démarré"
    } else {
      # Tâche planifiée : s'exécute en SYSTEM au démarrage, sans session ouverte, redémarre en cas d'échec.
      $action = New-ScheduledTaskAction -Execute $exe -Argument 'start' -WorkingDirectory $DataDir
      $trigger = New-ScheduledTaskTrigger -AtStartup
      $settings = New-ScheduledTaskSettingsSet -ExecutionTimeLimit ([TimeSpan]::Zero) -RestartCount 999 -RestartInterval (New-TimeSpan -Minutes 1) `
                  -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable -MultipleInstances IgnoreNew
      $principal = New-ScheduledTaskPrincipal -UserId 'SYSTEM' -LogonType ServiceAccount -RunLevel Highest
      Unregister-ScheduledTask -TaskName $TaskName -Confirm:$false -ErrorAction SilentlyContinue
      Register-ScheduledTask -TaskName $TaskName -Action $action -Trigger $trigger -Settings $settings -Principal $principal -Description "$Product — serveur de vidéosurveillance" | Out-Null
      # Variables d'environnement machine lues par la tâche SYSTEM
      [Environment]::SetEnvironmentVariable('TP_CONFIG', $ConfigFile, 'Machine')
      [Environment]::SetEnvironmentVariable('TP_DATA_DIR', $DataDir, 'Machine')
      Start-ScheduledTask -TaskName $TaskName
      Write-Ok "Tâche planifiée « $TaskName » (démarrage automatique) lancée"
    }
  }
}

function Open-Firewall {
  if ($NoFirewall) { return }
  Invoke-Step "Règle de pare-feu ToutPVR ($Port/tcp)" {
    if (Get-Command New-NetFirewallRule -ErrorAction SilentlyContinue) {
      Remove-NetFirewallRule -DisplayName 'ToutPVR' -ErrorAction SilentlyContinue
      New-NetFirewallRule -DisplayName 'ToutPVR' -Direction Inbound -Protocol TCP -LocalPort $Port -Action Allow -Profile Any | Out-Null
      Write-Ok "Pare-feu : port $Port/tcp ouvert"
    } else {
      & netsh advfirewall firewall add rule name="ToutPVR" dir=in action=allow protocol=TCP localport=$Port | Out-Null
    }
  }
}

function Test-Health {
  if ($DryRun -or $NoService) { return }
  for ($i = 0; $i -lt 30; $i++) {
    try {
      $r = Invoke-WebRequest -Uri "http://127.0.0.1:$Port/health" -UseBasicParsing -TimeoutSec 2
      if ($r.StatusCode -eq 200) { Write-Ok "Le serveur répond sur le port $Port"; return }
    } catch { Start-Sleep -Seconds 1 }
  }
  Write-Warn "Le serveur ne répond pas encore. Journal : $DataDir\logs"
}

function Show-Summary {
  $ip = (Get-NetIPAddress -AddressFamily IPv4 -ErrorAction SilentlyContinue | Where-Object { $_.IPAddress -notlike '127.*' -and $_.IPAddress -notlike '169.254.*' } | Select-Object -First 1).IPAddress
  if (-not $ip) { $ip = '127.0.0.1' }
  Write-Host ""
  Write-Host "$Product installé." -ForegroundColor White
  Write-Host "  Panneau web    : http://${ip}:$Port/"
  Write-Host "  Compte         : $AdminUser"
  if ($script:GeneratedPassword) { Write-Host "  Mot de passe   : $AdminPassword  (généré — notez-le maintenant)" -ForegroundColor Yellow }
  Write-Host "  Configuration  : $ConfigFile"
  Write-Host "  Données        : $DataDir"
  Write-Host "  Commande       : toutpvr --help   (ouvrir un nouveau terminal pour le PATH)"
  if (-not $NoService) { Write-Host "  Service        : Planificateur de tâches > $TaskName  (ou nssm status $TaskName)" }
  if (-not $License) { Write-Host "  Licence        : toutpvr license activate TP1-XXXXX-XXXXX-XXXXX-XXXXX" }
  Write-Host ""
}

# ---------------------------------------------------------------------------
#  Désinstallation / mise à niveau
# ---------------------------------------------------------------------------
function Invoke-Uninstall {
  Write-Step "Désinstallation de $Product"
  if (-not $Yes) {
    $ans = Read-Host "Confirmer la désinstallation$(if ($Purge) { ' AVEC suppression des données' }) ? [o/N]"
    if ($ans -notmatch '^[oOyY]$') { Write-Host 'Annulé.'; exit 0 }
  }
  $exe = Join-Path $VenvDir 'Scripts\toutpvr.exe'
  if (Test-Path $exe) { try { $env:TP_CONFIG = $ConfigFile; $env:TP_DATA_DIR = $DataDir; & $exe stop 2>$null | Out-Null } catch { } }
  $nssm = Get-Command nssm -ErrorAction SilentlyContinue
  if ($nssm) { & nssm stop $TaskName 2>$null | Out-Null; & nssm remove $TaskName confirm 2>$null | Out-Null }
  Unregister-ScheduledTask -TaskName $TaskName -Confirm:$false -ErrorAction SilentlyContinue
  Remove-NetFirewallRule -DisplayName 'ToutPVR' -ErrorAction SilentlyContinue
  Get-Process -Name toutpvr -ErrorAction SilentlyContinue | Stop-Process -Force -ErrorAction SilentlyContinue
  if (Test-Path $Prefix) { Remove-Item -Recurse -Force $Prefix }
  $machinePath = [Environment]::GetEnvironmentVariable('Path', 'Machine')
  [Environment]::SetEnvironmentVariable('Path', (($machinePath -split ';' | Where-Object { $_ -and $_ -ne $Prefix }) -join ';'), 'Machine')
  [Environment]::SetEnvironmentVariable('TP_CONFIG', $null, 'Machine')
  [Environment]::SetEnvironmentVariable('TP_DATA_DIR', $null, 'Machine')
  if ($Purge) {
    if (Test-Path $DataDir) { Remove-Item -Recurse -Force $DataDir }
    Write-Ok "Programme, configuration et données supprimés."
  } else {
    Write-Ok "Programme supprimé. Données et configuration conservées dans $DataDir (-Purge pour les supprimer)."
  }
}

function Invoke-Upgrade {
  $exe = Join-Path $VenvDir 'Scripts\toutpvr.exe'
  if (-not (Test-Path $exe)) { Fail "Aucune installation trouvée dans $Prefix." }
  Resolve-Source
  $env:TP_CONFIG = $ConfigFile; $env:TP_DATA_DIR = $DataDir
  Write-Step "Sauvegarde avant mise à niveau"
  try { & $exe backup } catch { Write-Warn "Sauvegarde impossible (poursuite)." }
  $running = (Get-ScheduledTask -TaskName $TaskName -ErrorAction SilentlyContinue)
  if ($running) { Stop-ScheduledTask -TaskName $TaskName -ErrorAction SilentlyContinue }
  try { & $exe stop 2>$null | Out-Null } catch { }
  Install-ToutPvrPackage (Join-Path $VenvDir 'Scripts\python.exe')
  if ($running) { Start-ScheduledTask -TaskName $TaskName; Test-Health }
  Write-Ok "Mise à niveau terminée."
}

# ---------------------------------------------------------------------------
#  Programme principal
# ---------------------------------------------------------------------------
Write-Host "$Product — installeur Windows v$ScriptVersion" -ForegroundColor White
if (-not $DryRun -and -not (Test-Admin)) {
  Fail "Lancez ce script dans un PowerShell « Exécuter en tant qu'administrateur » (ou double-cliquez install.bat)."
}
if ([Environment]::OSVersion.Version.Major -lt 10) { Fail "Windows 10 / Server 2016 ou plus récent requis." }

if ($Uninstall) { Invoke-Uninstall; exit 0 }
if ($Upgrade) { Invoke-Upgrade; exit 0 }

$python = Find-Python
if (-not $python) {
  Install-Python
  $python = Find-Python
  if (-not $python -and -not $DryRun) { Fail "Python >= 3.10 introuvable après installation. Installez-le depuis https://www.python.org/downloads/windows/ puis relancez." }
  if ($DryRun -and -not $python) { $python = 'python' }
}
Write-Ok "Python : $python"
Install-Ffmpeg
Resolve-Source
Install-Package -Python $python
Invoke-Configure
Install-Service
Open-Firewall
Test-Health
Show-Summary
