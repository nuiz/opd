@echo off
title opd-hide-45min

:loop1

timeout /t 5 /nobreak

php fetch_que.php

goto loop1

pause
exit