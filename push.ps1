# PowerShell Deployment Script for Bibric Law Website

param (
    [string]$Message = "Update " + (Get-Date -Format "yyyy-MM-dd HH:mm:ss")
)

# Set the current directory to where the script is
$CurrentScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
Set-Location $CurrentScriptDir

Write-Host "----------------------------------------------------" -ForegroundColor Cyan
Write-Host "Starting Bibric Law Website Deployment Process" -ForegroundColor Cyan
Write-Host "----------------------------------------------------" -ForegroundColor Cyan

# 1. Check if Git is initialized
if (!(Test-Path ".git")) {
    Write-Host "[1/4] Git repository not found. Initializing..." -ForegroundColor Yellow
    git init
    # Add remote if provided in the prompt
    git remote add origin https://github.com/ffcu-pinellas/codecanyonlawwebsite26.git
} else {
    Write-Host "[1/4] Git repository verified." -ForegroundColor Green
}

# Ensure origin points to the correct repo if it changed or needs setup
$remoteUrl = git remote get-url origin 2>$null
if ($remoteUrl -ne "https://github.com/ffcu-pinellas/codecanyonlawwebsite26.git") {
    Write-Host "Updating remote origin URL..." -ForegroundColor Yellow
    git remote set-url origin https://github.com/ffcu-pinellas/codecanyonlawwebsite26.git
}

# 2. Stage Changes
Write-Host "[2/4] Staging changes..." -ForegroundColor White
git add .

# 3. Commit
Write-Host "[3/4] Committing changes..." -ForegroundColor White
git commit -m "$Message"

# 4. Push
Write-Host "[4/4] Ensuring branch is named 'main' and pushing..." -ForegroundColor Green
git branch -M main
git push origin main

Write-Host "----------------------------------------------------" -ForegroundColor Cyan
Write-Host "Deployment Complete!" -ForegroundColor Green
Write-Host "Hostinger should now pull the changes if Git Auto-Deploy is enabled." -ForegroundColor White
Write-Host "Visit: https://yourcpaexpert.com" -ForegroundColor Cyan
Write-Host "----------------------------------------------------" -ForegroundColor Cyan
