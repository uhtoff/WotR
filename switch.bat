echo off
>nul find "Home" status.txt && (
  move /Y .\app\config\parameters.yml .\app\config\parameters-home.yml
  copy .\app\config\parameters-local.yml .\app\config\parameters.yml
  copy /Y localstat.txt status.txt
  echo "Switched to local database"
) || (
  move /Y .\app\config\parameters.yml .\app\config\parameters-local.yml
  copy .\app\config\parameters-home.yml .\app\config\parameters.yml
  copy /Y homestat.txt status.txt
  echo "Switched to home database"
)