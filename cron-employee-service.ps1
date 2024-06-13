$timeout = new-timespan -Minutes 1
$sw = [diagnostics.stopwatch]::StartNew()

cd  C:\Apache24\htdocs\attendance_terminal_zkteco_speedface

while ($sw.elapsed -lt $timeout){

#################### Obselete. Not in use #########################
        # php artisan app:employee-service
 
    start-sleep -seconds 1
}
 
write-host "Timed out"