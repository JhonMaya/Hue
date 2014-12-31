<?php exit() ?>--by pqmailer 217.95.237.202
if myHero.charName ~= "Yasuo" then return end

local version = 0
local SCRIPT_NAME = "PQYasuo"
local needUpdate = false
local needRun = true
local PATH = BOL_PATH.."Scripts\\"..GetCurrentEnv().FILE_NAME
local URL = "http://pqmailer.cuccfree.org/Release/Yasuo/PQYasuo.lua"

function CheckVersion(data)
	local onlineVer = tonumber(data)
	if type(onlineVer) ~= "number" then return end
	if onlineVer and onlineVer > version then
		print("<font color='#CCCCCC'>Script Download: This script will download "..SCRIPT_NAME.." and be replaced automatically. Don't F9 till done...</font>") 
		needUpdate = true  
	end
end
function UpdateScriptGOE()
	if needRun then
		needRun = false
		GetAsyncWebResult("pqmailer.cuccfree.org", "Release/Yasuo/Revision.lua", CheckVersion)
	end

	if needUpdate then
		needUpdate = false
		DownloadFile(URL, PATH, function()
                if FileExist(PATH) then
                    print("<font color='#CCCCCC'>Script Download: Script downloaded! Reload scripts to use it! If you are not authed yet, redownload the downloader from time to time.</font>")
                end
            end)
	end
end

function OnTick()
	UpdateScriptGOE()
end