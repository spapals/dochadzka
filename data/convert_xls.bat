@echo off
FOR %%X in (".\*.xls") DO IF NOT %%~xX == .xlsx echo Converting "%%~dpnxX"  & "c:\Program Files (x86)\Microsoft Office\root\Office16\excelcnv.exe"  -nme -oice "%%~dpnxX" "%%~dpnX.xlsx" 

rename "V?etky osoby_*.xlsx" "/////////////*.xlsx"

FTP -v -i -s:ftpscript.txt

del /s /q /f *.xlsx
del /s /q /f *.xls
