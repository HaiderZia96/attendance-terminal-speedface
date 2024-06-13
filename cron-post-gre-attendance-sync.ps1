$timeout = new-timespan -Minutes 1
$sw = [diagnostics.stopwatch]::StartNew()

cd  C:\Apache24\htdocs\attendance_terminal_zkteco_speedface

while ($sw.elapsed -lt $timeout){
        
        php artisan app:post-gre-attendance-sync
 
    start-sleep -seconds 1
}
 
write-host "Timed out"