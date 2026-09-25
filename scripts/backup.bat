@echo off
REM =============================================================================
REM  MMCS Database & Uploads Backup Script
REM =============================================================================
REM Schedule this via Windows Task Scheduler to run daily/weekly.
REM
REM Example Task Scheduler command:
REM   schtasks /create /tn "MMCS Backup" /tr "D:\xampp\htdocs\MMCF\scripts\backup.bat" /sc daily /st 02:00
REM =============================================================================

SET BACKUP_DIR=D:\MMCS_Backups
SET DB_NAME=mmcs_db
SET DB_USER=root
SET DB_PASS=
SET MYSQL_DUMP="D:\xampp\mysql\bin\mysqldump.exe"
SET PROJECT_DIR=D:\xampp\htdocs\MMCF
SET TIMESTAMP=%DATE:~10,4%%DATE:~4,2%%DATE:~7,2%_%TIME:~0,2%%TIME:~3,2%%TIME:~6,2%
SET TIMESTAMP=%TIMESTAMP: =0%

REM Create backup directory if not exists
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

REM Dump database
%MYSQL_DUMP% -u%DB_USER% %DB_NAME% > "%BACKUP_DIR%\mmcs_db_%TIMESTAMP%.sql"
echo [%DATE% %TIME%] Database backup saved: mmcs_db_%TIMESTAMP%.sql

REM Copy uploads folder
xcopy /E /I /Y "%PROJECT_DIR%\uploads" "%BACKUP_DIR%\uploads_%TIMESTAMP%\"

REM Keep only last 30 backups
forfiles /p "%BACKUP_DIR%" /m *.sql /d -30 /c "cmd /c del @path" 2>nul
forfiles /p "%BACKUP_DIR%" /d -30 /c "cmd /c if @isdir==TRUE rmdir /S /Q @path" 2>nul

echo [%DATE% %TIME%] Backup complete. Old backups (>30 days) cleaned up.
