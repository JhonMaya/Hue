<?php exit() ?>--by bothappy 83.46.99.170
function OnLoad()
	Updater("Tests.lua", 1, "BotHappy")
end

class 'Updater'

function Updater:__init(ScriptName, Version, BitbucketName)
	self.CurrentVersion = Version
	self.NeedUpdate = false
	self.Do_Once = true
	self.ScriptName = ScriptName
	self.RepoLink = "https://bitbucket.org/"..BitbucketName"../bol-free/src/master/"
	self.NetFile = self.RepoLink..ScriptName
	self.LocalFile = BOL_PATH.."Scripts\\"..ScriptName
	AddTickCallback(function() self:UpdateScript() end)
end


function Updater:CheckVersion(data)
	local NetVersion = tonumber(data)
	if type(NetVersion) ~= "number" then 
		return 
	end
	if NetVersion and NetVersion > self.CurrentVersion then
		print("<font color='#FF4000'>-- "..self.ScriptName..": Update found ! Don't F9 till done...</font>") 
		self.NeedUpdate = true  
	else
		print("<font color='#00BFFF'>-- "..self.ScriptName..": You have the lastest version</font>") 
	end
end

function Updater:UpdateScript()
	if Do_Once then
		Do_Once = false
		if _G.UseUpdater == nil or _G.UseUpdater == true then 
			GetAsyncWebResult("https://bitbucket.org", self.RepoLink.."/VER/"..self.ScriptName.."-Ver.txt", self:CheckVersion) 
		end
	end

	if self.NeedUpdate then
		self.NeedUpdate = false
		DownloadFile(self.NetFile, self.LocalFile, function()
				if FileExist(LocalFile) then
					print("<font color='#00BFFF'>-- "..ScriptName..": Script updated! Please reload.</font>")
				end
			end
		)
	end
end