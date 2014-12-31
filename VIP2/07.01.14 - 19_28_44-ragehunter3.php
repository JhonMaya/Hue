<?php exit() ?>--by ragehunter3 46.117.73.179
--[[ENCRYPT ALL BELOW]]--
--[[This Version of the script will be uploaded to a host as the main version to download]]--
if debug.getinfo(GetUser).linedefined > -1 then print("<font color='#FF0000'>AIOSupport: Denied</font>") return end
_G.UseUpdater = true
local versionGOE = 0.1
local SCRIPT_NAME_GOE = "AIOSupport"
local needUpdate_GOE = false
local needRun_GOE = true
local URL_GOE = "http://bolpain.cuccfree.com/Scripts/AIOSupport/AIOSupport.lua"
local PATH_GOE = BOL_PATH.."Scripts\\"..debug.getinfo(2).source
function CheckVersionGOE(data)
	local onlineVerGOE = tonumber(data)
	if type(onlineVerGOE) ~= "number" then return end
	if onlineVerGOE and onlineVerGOE > versionGOE then
		print("<font color='#00FFFF'>Downloading new version of: "..SCRIPT_NAME_GOE..". Please do not press f9.</font>")
		needUpdate_GOE = true
	end
end
function UpdateScriptGOE()
	if needRun_GOE then
		needRun_GOE = false
		if _G.UseUpdater == nil or _G.UseUpdater == true then GetAsyncWebResult("bolpain.cuccfree.com", "Scripts/AIOSupport/Revision.lua", CheckVersionGOE) end
	end
	
	if needUpdate_GOE then
		needUpdate_GOE = false
		DownloadFile(URL_GOE, PATH_GOE, function()
			if FileExist(PATH_GOE) then
				print("<font color='#00FFFF'>Script updated. You can now press f9-f9 to reload</font>")
				end
			end)
	end
end
AddTickCallback(UpdateScriptGOE)
print(SCRIPT_NAME_GOE.." handler has loaded. This version will be updated with the script.")