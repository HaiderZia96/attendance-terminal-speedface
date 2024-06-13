$timeout = new-timespan -Minutes 1
$sw = [diagnostics.stopwatch]::StartNew()

cd  C:\Apache24\htdocs\attendance_terminal_zkteco_speedface

while ($sw.elapsed -lt $timeout){

        # write-host "Found a file!"
        # Add-Content -Path "C:\Apache24\htdocs\attendance_terminal_zkteco_speedface\persecondLog.txt" -Value "This is some more text."
        
        php artisan app:post-gre-attendance-sync
        php artisan app:employee-service
        php artisan app:attendance-service

 
    start-sleep -seconds 1
}
 
write-host "Timed out"