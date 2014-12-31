<?php exit() ?>--by pqmailer 217.82.4.36
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
	local Result = GetWebResult(self.HOST, self.AUTH_PATH.."check.php?a=login&app_id="..self.APP_ID.."&hwid="..self.HWID)

	if Result ~= nil and Result:lower():find("authed") then
		self.Authed = true
	elseif string.len(GetClipboardText()) == 32 then

		local RegResult = GetWebResult(self.HOST, self.AUTH_PATH.."check.php?a=register&app_id="..self.APP_ID.."&serial="..GetClipboardText().."&hwid="..self.HWID)

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
				print("<font color='#CCCCCC'>[PS] Updater: There is a new version of "..self.SCRIPT_NAME..". Don't F9 till done...</font>") 
				self.NeedUpdate = true
			end
		end)
	end

	if self.NeedUpdate then
		self.NeedUpdate = false
		DownloadFile(self.SCRIPT_URL, self.PATH, function()
			if FileExist(self.PATH) then
				print("<font color='#CCCCCC'>[PS] Updater: "..self.SCRIPT_NAME.." updated! Double F9 to use new version!</font>")
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