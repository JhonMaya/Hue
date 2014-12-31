<?php exit() ?>--by kevinkev 27.253.95.57
--[[
$Name: AIOSupport
$Version: $inf
$Author: Pain
$Credits: pqmailer
$Information: This script will always get the latest version of AIOSupport
--]]
--[[ENCRYPT ALL BELOW]]--

class 'UpdateLib'
UpdateLib.instance = ''
function UpdateLib:__init(Name)
	self.LocalVersion = 0
	self.SCRIPT_NAME = ""
	self.SCRIPT_URL = ""
	self.PATH = ""
	self.HOST = ""
	self.URL_PATH = ""
	self.NeedUpdate = false
	self.NeedRun = true
	self.instance = Name
end
function UpdateLib.Instance(Name)
	if self.instance == "" then
		self.instance = UpdateLib(Name)
	end

	return self.instance
end
function UpdateLib:Run()
	if self.LocalVersion ~= 0 and self.SCRIPT_NAME ~= "" and self.SCRIPT_URL ~= "" and self.PATH ~= "" and self.HOST ~= "" and self.URL_PATH ~= "" then
		AddTickCallback(function() self:OnTick() end)
	else
		PrintChat("You missed variables. Won't start the update class.")
	end
end
function UpdateLib:OnTick()
	if self.NeedRun then
		self.NeedRun = false
		GetAsyncWebResult(self.HOST, self.URL_PATH, function(Data)
			local OnlineVersion = tonumber(Data)

			if type(OnlineVersion) ~= "number" then return end
			if OnlineVersion and self.LocalVersion and OnlineVersion > self.LocalVersion then
				print("Updater: There is a new version of "..self.SCRIPT_NAME..". Don't F9 till done...") 
				self.NeedUpdate = true
			end
		end)
	end
	if self.NeedUpdate then
		self.NeedUpdate = false
		DownloadFile(self.SCRIPT_URL, self.PATH, function()
			if FileExist(self.PATH) then
				print("Updater: "..self.SCRIPT_NAME.." updated! Double F9 to use new version!")
			end
		end)
	end
end
function OnLoad()
	versionGOE = 0.01
	Update = UpdateLib("AIOSupport")
	Update.LocalVersion = versionGOE
	Update.SCRIPT_NAME = "AIOSupport"
	Update.SCRIPT_URL = "http://bolpain.cuccfree.com/Scripts/AIOSupport/AIOSupport.lua"
	Update.PATH = BOL_PATH.."Scripts\\".."AIOSupport.lua"
	Update.HOST = "bolpain.cuccfree.com"
	Update.URL_PATH = "/Scripts/AIOSupport/Revision.lua"
	Update:Run()
end