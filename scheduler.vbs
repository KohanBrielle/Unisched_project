Set WshShell = CreateObject("WScript.Shell")
WshShell.Run chr(34) & "C:\php-8.5.5\php.exe" & chr(34) & " " & chr(34) & "c:\Users\CRIZ JUDE\group_2\Unisched_project\artisan" & chr(34) & " schedule:run", 0
Set WshShell = Nothing