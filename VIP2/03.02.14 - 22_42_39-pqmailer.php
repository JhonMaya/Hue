<?php exit() ?>--by pqmailer 217.82.27.210
if myHero.charName ~= "Jinx" and myHero.charName ~= "Lucian" and myHero.charName ~= "Sivir" and myHero.charName ~= "Draven" and myHero.charName ~= "Ashe" and myHero.charName ~= "Varus" then return end 

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

	return self.instance
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
	if self.instance == '' then
		self.instance = ProSeriesUpdate(Name)
	end

	return self.instance
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

ProSeriesEncryption.instance = ''

function ProSeriesEncryption:__init(Name)
	self.instance = Name
end

function ProSeriesEncryption.Instance(Name)
	if self.instance == '' then
		self.instance = ProSeriesEncryption(Name)
	end

	return self.instance
end

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

class 'ProSeriesHelper'

ProSeriesHelper.instance = ''
DIRECTION_AWAY = 0 
DIRECTION_TOWARDS = 1
DIRECTION_UNKNOWN = 2

function ProSeriesHelper:__init(Name) 
	self.wp = WayPointManager()
end

function ProSeriesHelper.Instance(Name)
	if self.instance == '' then
		self.instance = ProSeriesHelper(Name)
	end

	return self.instance
end

function ProSeriesHelper:GetDirection(unit)
	if unit and not unit.dead then 
		local points = self.wp:GetWayPoints(unit)
		if points[1] == nil or points[2] == nil then return DIRECTION_UNKNOWN end 
		local d1 = GetDistanceSqr(points[1]) 
		local d2 = GetDistanceSqr(points[2]) 
		if d1 < d2 then 
			return DIRECTION_AWAY
		elseif d1 > d2 then 
			return DIRECTION_TOWARDS
		end 
	end 
end 

class 'ProSeriesDefaultLoad'

ProSeriesDefaultLoad.instance = ''

function ProSeriesDefaultLoad:__init(Name)
	self.instance = Name
	self.config = {}

	self:LoadMenus()
	self:RangeIndicator()
	self:AAtoKill()
end

function ProSeriesDefaultLoad.Instance(Name)
	if self.instance == '' then
		self.instance = ProSeriesDefaultLoad(Name)
	end

	return self.instance
end

function ProSeriesDefaultLoad:LoadMenus()
	self.config.draw = scriptConfig("[PS] Lib: Draw", "pslibdraw")
	self.config.draw:addParam("lagfree", "Use lagfree circles", SCRIPT_PARAM_ONOFF, false)

	self.config.extra = scriptConfig("[PS] Lib: Extra", "pslibextra")
end

function ProSeriesDefaultLoad:RangeIndicator()
	local RangeDraws = {
		["Jinx"] = {
			["W"] = {name = "ZAP", range = 1450}
		},
		["Lucian"] = {
			["Q1"] = {name = "Piercing Light", range = 550},
			["Q2"] = {name = "Piercing Light (extended)", range = 1100}
		},
		["Sivir"] = {
			["Q"] = {name = "Boomerang Blade", range = 1075}
		}
	}
	local Menu

	if RangeDraws[myHero.charName] then
		self.config.draw:addSubMenu("Range Indicator", "rangeindicator")

		for i, skill in pairs(RangeDraws[myHero.charName]) do
			self.config.draw.rangeindicator:addParam("draw"..tostring(i), "Draw "..tostring(skill.name), SCRIPT_PARAM_ONOFF, true)
		end

		AddDrawCallback(function()
			for i, skill in pairs(RangeDraws[myHero.charName]) do
				if self.config.draw.rangeindicator["draw"..tostring(i)] and not myHero.dead then
					if self.config.draw.lagfree then
						DrawCircle3D(myHero.x, myHero.y, myHero.z, skill.range, 1, ARGB(255, 191, 247, 84), 10)
					else
						DrawCircle(myHero.x, myHero.y, myHero.z, skill.range, ARGB(255, 191, 247, 84))
					end
				end
			end
		end)
	end
end

function ProSeriesDefaultLoad:AAtoKill()
	self.config.extra:addSubMenu("AA to Kill", "aatokill")
	self.config.extra.aatokill:addParam("enableAA", "Show auto attacks to kill", SCRIPT_PARAM_ONOFF, true)
	self.config.extra.aatokill:addParam("enableTime", "Show time to kill", SCRIPT_PARAM_ONOFF, true)
	self.config.extra.aatokill:addParam("enableThird", "Try to calculate crits/champion abilites", SCRIPT_PARAM_ONOFF, true)
	self.config.extra.aatokill:addSubMenu("Masteries", "masteries")
		self.config.extra.aatokill.masteries:addParam("sword", "Double Edged Sword", SCRIPT_PARAM_SLICE, 0, 0, 1, 0)
		self.config.extra.aatokill.masteries:addParam("exec", "Executioner", SCRIPT_PARAM_SLICE, 3, 0, 3, 0)
		self.config.extra.aatokill.masteries:addParam("havoc", "Havoc", SCRIPT_PARAM_SLICE, 1, 0, 1, 0)

	AddDrawCallback(function()
		local function _getMasteryDmg(unit)
			local masteryDmg = {sword = 0.02 * self.config.extra.aatokill.masteries.sword, exec = 0, havoc = self.config.extra.aatokill.masteries.havoc * 0.03}

			if (self.config.extra.aatokill.masteries.exec == 3 and unit.health < unit.maxHealth * 0.5) or (self.config.extra.aatokill.masteries.exec == 2 and unit.health < unit.maxHealth * 0.35) then
				masteryDmg.exec = 0.05
			elseif self.config.extra.aatokill.masteries.exec < 2 and unit.health < unit.maxHealth * 0.2 then
				masteryDmg.exec = 0.05 * self.config.extra.aatokill.masteries.exec
			end

			return masteryDmg.sword * masteryDmg.exec * masteryDmg.havoc
		end

		local function _getHeroDmg(unit)
			if myHero.charName == "Vayne" and myHero:CanUseSpell(_W) == READY then
				return (((3 + myHero:GetSpellData(_W).level) * 0.01) * unit.maxHealth) / 3
			elseif myHero.charName == "Varus" then
				return myHero:CalcMagicDamage(unit, 10 + 4 * myHero:GetSpellData(_W).level + 0.25 * myHero.ap)
			elseif myHero.charName == "Twitch" then
				local twitchDmg = {2, 2, 2, 2, 2, 4, 4, 4, 4, 4, 6, 6, 6 ,6 ,6, 8, 8, 8}
				return twitchDmg[myHero.level] * 3 * 6
			else
				return 0
			end
		end

		local function _timeToKill(unit)
			local haveIE = (GetInventorySlotItem(3031) and 1.5 or 1)
			local haveBOTRK = (GetInventorySlotItem(3153) and myHero:CalcDamage(unit, unit.health * 0.05) or 0)
			local masteryDmg = _getMasteryDmg(unit)
			local critDmg = myHero.totalDamage*myHero.critChance*haveIE
			local heroDmg = _getHeroDmg(unit)
			local aaDmg = (myHero:CalcDamage(unit, myHero.totalDamage) + (myHero:CalcDamage(unit, critDmg)) + haveBOTRK + heroDmg)*(1 + masteryDmg)
			local dps = aaDmg * myHero.attackSpeed


			if self.config.extra.aatokill.enableThird then
				return (unit.health / dps), math.floor(unit.health / aaDmg) + 1
			else
				return (unit.health / (myHero:CalcDamage(unit, myHero.totalDamage) * myHero.attackSpeed)), math.floor(unit.health / myHero:CalcDamage(unit, myHero.totalDamage)) + 1
			end
		end

		if (self.config.extra.aatokill.enableTime or self.config.extra.aatokill.enableAA) and not myHero.dead then
			for _, enemy in ipairs(GetEnemyHeroes()) do
				if ValidTarget(enemy, 2000) then
					local time, aaCount = _timeToKill(enemy)

					if self.config.extra.aatokill.enableTime then
						DrawText3D(tostring(string.format("%4.1s", time .. "s")), enemy.x, enemy.y, enemy.z, 20, RGB(255, 255, 255), true)
					end

					if self.config.extra.aatokill.enableAA then
						DrawText3D(tostring(aaCount), enemy.x+10, enemy.y, enemy.z+65, 20, RGB(255, 255, 255), true)
					end
				end
			end
		end
	end)
end

class 'ProSeriesOrbwalker'

ProSeriesOrbwalker.instance = ''

function ProSeriesOrbwalker:__init(Name)
	self.instance = Name
	self.enabled = true
	self.nextAttack = 0
	self.windUp = 0
	self.Destination = 0
	self.AutoAttackAlias = {
		["frostarrow"] = true,
		["CaitlynHeadshotMissile"] = true,
		["QuinnWEnhanced"] = true,
		["TrundleQ"] = true,
		["XenZhaoThrust"] = true,
		["XenZhaoThrust2"] = true,
		["XenZhaoThrust3"] = true,
		["GarenSlash2"] = true,
		["RenektonExecute"] = true,
		["RenektonSuperExecute"] = true,
		["KennenMegaProc"] = true
	}
	self.NonAttack = {
		 ["shyvanadoubleattackdragon"] = true,
		 ["ShyvanaDoubleAttack"] = true,
		 ["MonkeyKingDoubleAttack"] = true
	}
	self.ResetSpell = {
		["Powerfist"] = true,
		["DariusNoxianTacticsONH"] = true,
		["Takedown"] = true,
		["Ricochet"] = true,
		["BlindingDart"] = true,
		["VayneTumble"] = true,
		["JaxEmpowerTwo"] = true,
		["MordekaiserMaceOfSpades"] = true,
		["SiphoningStrikeNew"] = true,
		["RengarQ"] = true,
		["MonkeyKingDoubleAttack"] = true,
		["YorickSpectral"] = true,
		["ViE"] = true,
		["GarenSlash3"] = true,
		["HecarimRamp"] = true,
		["XenZhaoComboTarget"] = true,
		["LeonaShieldOfDaybreak"] = true,
		["ShyvanaDoubleAttack"] = true,
		["shyvanadoubleattackdragon"] = true,
		["TalonNoxianDiplomacy"] = true,
		["TrundleTrollSmash"] = true,
		["VolibearQ"] = true,
		["PoppyDevastatingBlow"] = true,
		["SivirW"] = true
	}
	self.BaseAttackSpeed = { 
		["Aatrox"] = 0.651,
		["Ahri"] = 0.668,
		["Akali"] = 0.694,
		["Alistar"] = 0.625,
		["Amumu"] = 0.638,
		["Anivia"] = 0.625,
		["Annie"] = 0.579,
		["Ashe"] = 0.658,
		["Blitzcrank"] = 0.625,
		["Brand"] = 0.625,
		["Caitlyn"] = 0.625,
		["Cassiopeia"] = 0.647,
		["Chogath"] = 0.625,
		["Corki"] = 0.625,
		["Darius"] = 0.6679,
		["Diana"] = 0.625,
		["DrMundo"] = 0.625,
		["Draven"] = 0.679,
		["Elise"] = 0.625,
		["Evelynn"] = 0.625,
		["Ezreal"] = 0.625,
		["Fiddlestick"] = 0.625,
		["Fiora"] = 0.672,
		["Fizz"] = 0.658,
		["Galio"] = 0.638,
		["Gangplank"] = 0.651,
		["Garen"] = 0.625,
		["Gragas"] = 0.651,
		["Graves"] = 0.625,
		["Hecarim"] = 0.67,
		["Heimerdinger"] = 0.625,
		["Irelia"] = 0.665,
		["Janna"] = 0.625,
		["JarvanIV"] = 0.658,
		["Jax"] = 0.638,
		["Jayce"] = 0.658,
		["Jinx"] = 0.625,
		["Karma"] = 0.625,
		["Karthus"] = 0.625,
		["Kassadin"] = 0.64,
		["Katarina"] = 0.658,
		["Kayle"] = 0.638,
		["Kennen"] = 0.69,
		["Khazix"] = 0.668,
		["Kogmaw"] = 0.665,
		["LeBlanc"] = 0.625,
		["LeeSin"] = 0.651,
		["Leona"] = 0.625,
		["Lissandra"] = 0.625,
		["Lucian"] = 0.638,
		["Lulu"] = 0.625,
		["Lux"] = 0.625,
		["Malphite"] = 0.638,
		["Malzahar"] = 0.625,
		["Maokai"] = 0.694,
		["MasterYi"] = 0.679,
		["MissFortune"] = 0.656,
		["Mordekaiser"] = 0.694,
		["Morgana"] = 0.625,
		["Nami"] = 0.644,
		["Nasus"] = 0.638,
		["Nautilus"] = 0.613,
		["Nidalee"] = 0.67,
		["Nocturne"] = 0.668,
		["Nunu"] = 0.625,
		["Olaf"] = 0.694,
		["Orianna"] = 0.658,
		["Pantheon"] = 0.679,
		["Poppy"] = 0.638,
		["Quinn"] = 0.668,
		["Rammus"] = 0.625,
		["Renekton"] = 0.665,
		["Rengar"] = 0.679,
		["Riven"] = 0.625,
		["Rumble"] = 0.644,
		["Ryze"] = 0.625,
		["Sejuani"] = 0.67,
		["Shaco"] = 0.694,
		["Shen"] = 0.651,
		["Shyvana"] = 0.658,
		["Singed"] = 0.613,
		["Sion"] = 0.625,
		["Sivir"] = 0.658,
		["Skarner"] = 0.625,
		["Sona"] = 0.644,
		["Soraka"] = 0.625,
		["Swain"] = 0.625,
		["Syndra"] = 0.625,
		["Talon"] = 0.668,
		["Taric"] = 0.625,
		["Teemo"] = 0.69,
		["Thresh"] = 0.625,
		["Tristana"] = 0.656,
		["Trundle"] = 0.67,
		["Tryndamere"] = 0.67,
		["TwistedFate"] = 0.651,
		["Twitch"] = 0.679,
		["Udyr"] = 0.658,
		["Urgot"] = 0.644,
		["Varus"] = 0.658,
		["Vayne"] = 0.658,
		["Veigar"] = 0.625,
		["Vi"] = 0.644,
		["Viktor"] = 0.625,
		["Vladimir"] = 0.658,
		["Volibear"] = 0.658,
		["Warwick"] = 0.679,
		["MonkeyKing"] = 0.658,
		["Xerath"] = 0.625,
		["XinZhao"] = 0.672,
		["Yasuo"] = 0.658,
		["Yorick"] = 0.625,
		["Zac"] = 0.638,
		["Zed"] = 0.638,
		["Ziggs"] = 0.656,
		["Zilean"] = 0.625,
		["Zyra"] = 0.625
	}
	self.ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, myHero.range+50, DAMAGE_PHYSICAL)
	self.combatInstance = ProSeriesCombatHandler(self.ts)

	self.config = scriptConfig("[PS] Lib: Orbwalking", "psorbwalking")   
	self.config:addParam("orbwalk", "Orbwalk", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("A"))
	self.config:addParam("enabled", "Enable orbwalking", SCRIPT_PARAM_ONOFF, true)
	self.config:addParam("followMouse", "Follow the mouse", SCRIPT_PARAM_ONOFF, true)
	self.config:addParam("drawRange", "Draw auto attack range", SCRIPT_PARAM_ONOFF, true)
	self.config:addParam("drawLagfree", "Use lagfree circles", SCRIPT_PARAM_ONOFF, true)

	AddTickCallback(function() self:OnTick() end)
	AddProcessSpellCallback(function(unit, spell) self:OnProcessSpell(unit, spell) end)
	AddDrawCallback(function() self:OnDraw() end)
	AddSendPacketCallback(function(obj) self:OnSendPacket(packet) end)
end

function ProSeriesOrbwalker.Instance(Name)
	if ProSeriesOrbwalker.instance == '' then
		ProSeriesOrbwalker.instance = ProSeriesDefaultLoad(Name)
	end

	return ProSeriesOrbwalker.instance
end

function ProSeriesOrbwalker:OnTick()
	if not self.enabled or not self.config.enabled or not self.config.orbwalk then return end

	if self:CheckThirdParty() then
		PrintChat("<font color='#CCCCCC'>[PS Orbwalking]: MMA or Reborn were found as active scripts. The build-in orbwalker will be disabled now.</font>")
		self.enabled = false
	end

	local target = self.combatInstance:GetTarget()
	local destination = myHero + (Vector(mousePos) - myHero):normalized()*300
	local distance = GetDistance(myHero, target)
	local trueRange = self.combatInstance:GetTrueRange(target)

	if ValidTarget(target) and GetGameTimer() > self.windUp then
		if distance <= 400 and GetGameTimer() < self.nextAttack then
			if GetInventoryItemIsCastable(3077) then
				CastItem(3077)
				self.nextAttack = 0
			elseif GetInventoryItemIsCastable(3074) then
				CastItem(3074)
				self.nextAttack = 0
			end
		end

		if distance <= trueRange and GetGameTimer() > self.nextAttack then
			myHero:Attack(target)
		elseif self.config.followMouse then
			Packet('S_MOVE', {x = destination.x, y = destination.z}):send(true)
		end
	elseif self.config.followMouse and (not ValidTarget(target) or distance > trueRange) then
		Packet('S_MOVE', {x = destination.x, y = destination.z}):send(true)
	end
end

function ProSeriesOrbwalker:OnDraw()
	if self.config.drawRange and self.enabled and not myHero.dead then
		if self.config.drawLagfree then
			DrawCircle3D(myHero.x, myHero.y, myHero.z, myHero.range, 1, ARGB(255, 191, 247, 84), 10)
		else
			DrawCircle(myHero.x, myHero.y, myHero.z, myHero.range, ARGB(255, 191, 247, 84))
		end

	end
end

function ProSeriesOrbwalker:OnProcessSpell(unit, spell)
	if unit.isMe then
		if (spell.name:lower():find("attack") or self.AutoAttackAlias[spell.name] and not self.NonAttack[spell.name]) then
			self.target = spell.target
			self.windUp = GetGameTimer() + spell.windUpTime
			self.windUpTime = spell.windUpTime
			self.nextAttack =  GetGameTimer() + (spell.animationTime * ((myHero.attackSpeed * self.BaseAttackSpeed[myHero.charName])) / myHero.attackSpeed)
		elseif self.ResetSpell[spell.name] then
			self.nextAttack = 0
		elseif spell.name == "SummonerExhaust" and spell.target.networkID == myHero.networkID then
			self.nextAttack = self.nextAttack * 1.5
		elseif spell.name == "Wither" and spell.target.networkID == myHero.networkID then
			self.nextAttack = self.nextAttack * 1.35
		end
	end
end

function ProSeriesOrbwalker:OnSendPacket(packet)
	-- We could block attacks here
end

function ProSeriesOrbwalker:CheckThirdParty()
	return _G.MMA_Loaded or _G.AutoCarry
end

--- Lib End; Globals
_G.PSLib_UnloadScript = function(name)
	for scriptName, environment in pairs(_G.environment) do
		if scriptName == name then
			for Name, Data in pairs(environment) do 
				if Name ~= 'UnloadScript' then environment[Name] = nil end
			end
			break
		end
	end
end

-- Lib update stuff

local PSDefaultLoad = ProSeriesDefaultLoad("DefaultLoad")
local PSOrbwalk = ProSeriesOrbwalker("FallbackOrbwalker")
local PSLibUpdate = ProSeriesUpdate("LibUpdate")
local LibVersion = 0.16

PSLibUpdate.LocalVersion = LibVersion
PSLibUpdate.SCRIPT_NAME = "ProSeriesLib"
PSLibUpdate.SCRIPT_URL = "http://pqbol.de/scripts/release/proseries/ProSeriesLib.lua"
PSLibUpdate.PATH = BOL_PATH.."Scripts\\Common\\".."ProSeriesLib.lua"
PSLibUpdate.HOST = "pqbol.de"
PSLibUpdate.URL_PATH = "/scripts/release/proseries/librevision.lua"
PSLibUpdate:Run()