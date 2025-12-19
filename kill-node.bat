@echo off
echo ========================================
echo  Killing All Node.js Processes
echo ========================================
echo.

REM Kill all node.exe processes
taskkill /F /IM node.exe /T 2>nul

if %errorlevel% equ 0 (
    echo [SUCCESS] All Node.js processes have been terminated.
) else (
    echo [INFO] No Node.js processes found running.
)

echo.
echo ========================================
echo  Done!
echo ========================================
pause
