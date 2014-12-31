<?php exit() ?>--by Kain 97.90.203.108
_G.AuthRequest = {
	dev = "Kain",
	sid = "1", 
	sver = "4.10",
	key = "590ef45081b467c801fda1dd617bae9044be814c66ee092c90580683ff28de7d",
	download_type = "stream",
	debug_mode = "false",
	debug_raw_data = "true",
	authfailmode = "kill"
}

function DownloadScript(url, savePath)
	RunCmdCommand("%WINDIR%/System32/bitsadmin.exe /transfer 'bol' "..url.." "..string.gsub(savePath, "/", "\\"))
end

function LoadScript(url)
	local file = "BoLScripts.lua"
	local filePath = SCRIPT_PATH.."\\Common\\"..file

	if not FileExist(filePath) then
		DownloadScript(url, filePath)
	end

	if FileExist(filePath) then
		require "BoLScripts"
	else
		ShowAuthMessage("There was an error with your script. Please download again.", true)
	end
end

function ShowAuthMessage(message, statusError)
	local prefix = "<u><b><font color='#2E9AFE'>BoL</font></b><font color='#00BFFF'><b><i>Scripts</i></b>.com</font></u><font color='#2E9AFE'>:</font> "

	if statusError then
		PrintChat(prefix.."<font color='#c22e13'>"..message.."</font>")
	else
		PrintChat(prefix.."<font color='#00FF40'>"..message.."</font>")
	end
end

function GetScriptFilePath()
	local debugInfo = debug.getinfo(BootLoader)
	if debugInfo and debugInfo["source"] then
		return string.gsub(debugInfo["source"], "@", "")
	end

	return nil
end

-- Encoding
function enc(data)
	local b='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'

    return ((data:gsub('.', function(x) 
        local r,b='',x:byte()
        for i=8,1,-1 do r=r..(b%2^i-b%2^(i-1)>0 and '1' or '0') end
        return r;
    end)..'0000'):gsub('%d%d%d?%d?%d?%d?', function(x)
        if (#x < 6) then return '' end
        local c=0
        for i=1,6 do c=c+(x:sub(i,i)=='1' and 2^(6-i) or 0) end
        return b:sub(c+1,c+1)
    end)..({ '', '==', '=' })[#data%3+1])
end

function BootLoader()
	AuthRequest["script_path"] = GetScriptFilePath()
	encodedQ = enc("dev="..AuthRequest["dev"].."&key="..AuthRequest["key"])
	local scripturl = "http://bolscripts.com/api/getclient.php?q="..encodedQ
	LoadScript(scripturl)
end

BootLoader()