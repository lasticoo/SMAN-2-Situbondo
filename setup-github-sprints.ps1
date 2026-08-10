# PowerShell Script: Setup GitHub Milestones, Labels, & Issues for SMAN 2 Situbondo Website

Write-Host "Creating Milestones..." -ForegroundColor Green
gh milestone create --title "Sprint 1 - Setup & Core Content (Minggu 1)" --description "US-01..04, AD-01..05, AD-13, AD-15"
gh milestone create --title "Sprint 2 - Modul Informasi & Media (Minggu 2)" --description "US-05..08, AD-06..10"
gh milestone create --title "Sprint 3 - Modul Spesifik & Integrasi (Minggu 3)" --description "US-09..10, AD-11..12, AD-14, Local Testing & Prep Hosting"
gh milestone create --title "Sprint 4 - Deployment & Finalisasi (Minggu 4)" --description "Deploy Hosting, Online Testing, & Final Release"

Write-Host "Creating Labels..." -ForegroundColor Green
gh label create "role:admin" --color 1D76DB --description "Role Admin Task" --force
gh label create "role:user" --color 0E8A16 --description "Role User Task" --force
gh label create "role:shared" --color C5DEF5 --description "Shared Architecture Task" --force
gh label create "priority:high" --color B60205 --description "High Priority" --force
gh label create "priority:medium" --color FBCA04 --description "Medium Priority" --force
gh label create "priority:low" --color 0E8A16 --description "Low Priority" --force
gh label create "status:in-review" --color FEF2C0 --description "In Code Review" --force
gh label create "status:blocked" --color D93F0B --description "Blocked Task" --force

Write-Host "GitHub Setup Complete!" -ForegroundColor Green
