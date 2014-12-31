<?php exit() ?>--by pqmailer 217.82.39.151
class 'ProSeriesAuth'

ProSeriesAuth.instance = ''

function ProSeriesAuth:__init(Name)
	self.baseEnc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
	self.Authed = false
	self.RetryTime = 0
	self.attemptNumber = 0
	self.SCRIPT_NAME = 'ProSeries'
	self.AUTH_PATH = "auth/"
	self.HOST = 'pqbol.de'
	self.APP_ID = 1
	self.instance = Name
	self.HWID = self:URL_Encode(self:Enc(tostring(os.getenv("PROCESSOR_IDENTIFIER")..os.getenv("USERNAME")..os.getenv("COMPUTERNAME")..os.getenv("PROCESSOR_LEVEL")..os.getenv("PROCESSOR_REVISION"))))
	self.WELCOME_MESSAGE = GetWebResult(self.HOST, "/scripts/release/proseries/adc/welcome.txt")

	if self.WELCOME_MESSAGE then
		PrintChat("<font color='#CCCCCC'>[PS Announce]: "..tostring(self.WELCOME_MESSAGE).."</font>")
	end

	AddTickCallback(function() self:OnTick() end)
end

function ProSeriesAuth.Instance(Name)
	if self.instance == '' then
		self.instance = ProSeriesAuth(Name)
	end

	return ProSeriesAuth.instance
end

function ProSeriesAuth:IsAuthed()
	return self.Authed
end

function ProSeriesAuth:OnTick()
	if self.Authed == false then
		if os.clock() > self.RetryTime and self.attemptNumber < 3 then
			self:CheckAuth()

			self.RetryTime = os.clock() + 20
			self.attemptNumber = self.attemptNumber + 1
		end
	end
end

function ProSeriesAuth:CheckAuth()
	local Result = GetWebResult(self.HOST, self.AUTH_PATH.."check.php?a=login&app_id="..self.APP_ID.."&hwid="..self.HWID.."&bol_user="..GetUser())

	if Result ~= nil and Result:lower():find("authed") then
		self.Authed = true
	elseif string.len(GetClipboardText()) == 32 then

		local RegResult = GetWebResult(self.HOST, self.AUTH_PATH.."check.php?a=register&app_id="..self.APP_ID.."&serial="..GetClipboardText().."&hwid="..self.HWID.."&bol_user="..GetUser())

		if RegResult ~= nil and RegResult:lower():find("bound") then
			PrintChat(RegResult)
			self.Authed = true
		else
			PrintChat("Please copy a valid key in your clipboard. If you are registered the host server may have problems.")
		end
	else
		PrintChat("Please copy a valid key in your clipboard. If you are registered the host server may have problems.")
	end
end

function ProSeriesAuth:URL_Encode(String)
	if String then
		String = string.gsub(String, "\n", "\r\n")
		String = string.gsub(String, "([^%w %-%_%.%~])",
			function (c) return string.format("%%%02X", string.byte(c)) end)
		String = string.gsub(String, " ", "+")
	end

	return String
end

function ProSeriesAuth:Enc(Data)
	local BE = self.baseEnc

	return ((Data:gsub('.', function(x) 
		local r,BE='',x:byte()
		for i=8,1,-1 do r=r..(BE%2^i-BE%2^(i-1)>0 and '1' or '0') end
		return r;
	end)..'0000'):gsub('%d%d%d?%d?%d?%d?', function(x)
		if (#x < 6) then return '' end
		local c=0
		for i=1,6 do c=c+(x:sub(i,i)=='1' and 2^(6-i) or 0) end
		return BE:sub(c+1,c+1)
	end)..({ '', '==', '=' })[#Data%3+1])
end

class 'ProSeriesUpdate'

ProSeriesUpdate.instance = ''

function ProSeriesUpdate:__init(Name)
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

function ProSeriesUpdate.Instance(Name)
	if self.instance == "" then
		self.instance = ProSeriesUpdate(Name)
	end

	return ProSeriesUpdate.instance
end

function ProSeriesUpdate:Run()
	if self.LocalVersion ~= 0 and self.SCRIPT_NAME ~= "" and self.SCRIPT_URL ~= "" and self.PATH ~= "" and self.HOST ~= "" and self.URL_PATH ~= "" then
		AddTickCallback(function() self:OnTick() end)
	else
		PrintChat("You missed variables. Won't start the update class.")
	end
end

function ProSeriesUpdate:OnTick()
	if self.NeedRun then
		self.NeedRun = false
		GetAsyncWebResult(self.HOST, self.URL_PATH, function(Data)
			local OnlineVersion = tonumber(Data)

			if type(OnlineVersion) ~= "number" then return end
			if OnlineVersion and self.LocalVersion and OnlineVersion > self.LocalVersion then
				PrintChat("<font color='#CCCCCC'>[PS] Updater: There is a new version of "..self.SCRIPT_NAME..". Don't F9 till done...</font>") 
				self.NeedUpdate = true
			end
		end)
	end

	if self.NeedUpdate then
		self.NeedUpdate = false
		DownloadFile(self.SCRIPT_URL, self.PATH, function()
			if FileExist(self.PATH) then
				PrintChat("<font color='#CCCCCC'>[PS] Updater: "..self.SCRIPT_NAME.." updated! Double F9 to use new version!</font>")
			end
		end)
	end
end

class 'ProSeriesCombatHandler'

function ProSeriesCombatHandler:__init(TargetSelector)
	self.ts = TargetSelector
end

function ProSeriesCombatHandler:GetTrueRange(Unit)
	if ValidTarget(Unit) then
		return GetDistance(myHero.minBBox) + myHero.range + GetDistance(Unit.minBBox, Unit.maxBBox)/2
	else
		return nil
	end
end

function ProSeriesCombatHandler:GetTarget()
	if _G.MMA_Target ~= nil and _G.MMA_Target.type:lower() == "obj_ai_hero" then
		return _G.MMA_Target
	end

	if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Crosshair.Attack_Crosshair.target ~= nil and _G.AutoCarry.Crosshair.Attack_Crosshair.target.type:lower() == "obj_ai_hero" then
		return _G.AutoCarry.Crosshair.Attack_Crosshair.target
	end

	self.ts:update()

	return self.ts.target
end

class 'ProSeriesEncryption'

function ProSeriesEncryption:Encrypt(string)
	local function __str2hex(str2,spacer)
		return (
			string.gsub(str2,"(.)",
				function (c)
					return string.format("%02X%s",string.byte(c), spacer or "")
				end)
		)
	end
	
	local b='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
	local function __base64Encode(data)
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

	local toReplace = {'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '='}
	local replaceWith = {'ASHbOi', 'jEJcUn', '9fuGvH', 'ku4GLa', 'coy8eY', 'TYPp6O', 'Ug4hLz', 'dFvV3W', 'C2y3wS', 'emO0td', '2EIO9s', '5Hjqw7', 'e2vGz1', 'AFeDtC', 'goG8jm', 'dzACsx', '4gF3yk', 'kBo0g1', 'J0Dig8', 'XwGjco', 'sj9QEx', 'Dji9Sz', 'WoIP8k', '5uIjs0', 'cbFsVp', 'OK0wv7', 'xV3Bg1', 'r2Y3sp', 'KnadT4', 'fMQCs8', 'NITdpP', 'zSaPIQ', 'vlEnW7', 'v6YUrS', 'ZaLYyI', 'rxipD3', 'Hk7uQV', 'e8f5Oz', 'Ge4Dzn', 'ry8ko0', '8eXWvy', 'EPj60i', 'uiCbde', 'SrQjTP', 'FRjtQC', 'GfnomT', 'WaBDcl', 'MzukKZ', 'a1mnhf', '9Tvp0f', '3dBw2I', 'ofMhEJ', 'RiDkrb', 'ZDvFC4', 'dya5Wu', 'wCzYU7', 'ZQkEV3', '8yQtHA', 'cNWjEC', '1BrUmG', '4aucdM', 'VTsMlZ', '73aUIr'}
	local encodedString = string.reverse(__base64Encode(__str2hex(string.reverse(string))))
	local finalString = ''

	for i=1, #encodedString do
		for j=1, #toReplace do
			if encodedString:sub(i, i) == toReplace[j] then
				finalString = finalString..replaceWith[j]
			end
		end
	end

	return finalString
end

function ProSeriesEncryption:Decrypt(cipher)
	local function __hex2str(string)
		local temp_str = ""
		for ch in string:gmatch(string.rep("[A-Za-z0-9]",2)) do
			temp_str = temp_str..string.char("0x"..ch)
		end
		return temp_str
	end

	local b='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
		function __base64Decode(data)
			data = string.gsub(data, '[^'..b..'=]', '')
			return (data:gsub('.', function(x)
				if (x == '=') then return '' end
				local r,f='',(b:find(x)-1)
				for i=6,1,-1 do r=r..(f%2^i-f%2^(i-1)>0 and '1' or '0') end
				return r;
			end):gsub('%d%d%d?%d?%d?%d?%d?%d?', function(x)
				if (#x ~= 8) then return '' end
				local c=0
				for i=1,8 do c=c+(x:sub(i,i)=='1' and 2^(8-i) or 0) end
				return string.char(c)
			end))
		end

	local toReplace = {'ASHbOi', 'jEJcUn', '9fuGvH', 'ku4GLa', 'coy8eY', 'TYPp6O', 'Ug4hLz', 'dFvV3W', 'C2y3wS', 'emO0td', '2EIO9s', '5Hjqw7', 'e2vGz1', 'AFeDtC', 'goG8jm', 'dzACsx', '4gF3yk', 'kBo0g1', 'J0Dig8', 'XwGjco', 'sj9QEx', 'Dji9Sz', 'WoIP8k', '5uIjs0', 'cbFsVp', 'OK0wv7', 'xV3Bg1', 'r2Y3sp', 'KnadT4', 'fMQCs8', 'NITdpP', 'zSaPIQ', 'vlEnW7', 'v6YUrS', 'ZaLYyI', 'rxipD3', 'Hk7uQV', 'e8f5Oz', 'Ge4Dzn', 'ry8ko0', '8eXWvy', 'EPj60i', 'uiCbde', 'SrQjTP', 'FRjtQC', 'GfnomT', 'WaBDcl', 'MzukKZ', 'a1mnhf', '9Tvp0f', '3dBw2I', 'ofMhEJ', 'RiDkrb', 'ZDvFC4', 'dya5Wu', 'wCzYU7', 'ZQkEV3', '8yQtHA', 'cNWjEC', '1BrUmG', '4aucdM', 'VTsMlZ', '73aUIr'}
	local replaceWith = {'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '='}

	for i=1, #cipher/6 do
		for j=1, #toReplace do
			cipher = string.gsub(cipher, toReplace[j], replaceWith[j])
		end
	end

    return string.reverse(__hex2str(__base64Decode(string.reverse(cipher))))
end

--- Lib End

local PSLibUpdate = ProSeriesUpdate("LibUpdate")
local LibVersion = 0.12

PSLibUpdate.LocalVersion = LibVersion
PSLibUpdate.SCRIPT_NAME = "ProSeriesLib"
PSLibUpdate.SCRIPT_URL = "http://pqbol.de/scripts/release/proseries/ProSeriesLib.lua"
PSLibUpdate.PATH = BOL_PATH.."Scripts\\Common\\".."ProSeriesLib.lua"
PSLibUpdate.HOST = "pqbol.de"
PSLibUpdate.URL_PATH = "/scripts/release/proseries/librevision.lua"
PSLibUpdate:Run()