<?php exit() ?>--by Sida 81.170.70.121
local AutoUpdate = true
local SELF = BOL_PATH.."Scripts\\Common\\SidasAutoCarryPlugin - "..myHero.charName..".lua"
local URL = "http://bolauth.com/xetrok/scripts/SidasAutoCarryPlugin%20-%20Soraka.lua"
local UPDATE_TMP_FILE = LIB_PATH.."FGETmp.txt"
local versionmessage = "Changelog: Initial Release."

function Update()
	DownloadFile(URL, UPDATE_TMP_FILE, UpdateCallback) --download the most recent copy of the script
end

function UpdateCallback()
	file = io.open(UPDATE_TMP_FILE, "rb")
	if file ~= nil then
		content = file:read("*all") --save the whole file to a var
		file:close() --close it
		os.remove(UPDATE_TMP_FILE) --delete the temp file we just downloaded
		if content then
			tmp, sstart = string.find(content, "local version = \"") --find the string with our version number and store it as a var
			if sstart then --if the var exists then find our version string and copy the string from the " onwards
				send, tmp = string.find(content, "\"", sstart+1)
			end
			if send then
				Version = tonumber(string.sub(content, sstart+1, send-1)) --now look at the string we copied before and remove the closing "
			end
			if (Version ~= nil) and (Version > tonumber(version)) and content:find("--EOS--") then --if new script version > this one then do this stuff
				file = io.open(SELF, "w") --open this file
				if file then
					file:write(content) --write the new file to this file
					file:flush()
					file:close() --close the file
					PrintChat("<font color=\"#81BEF7\" >Xetrok's Soraka Autoupdate:</font> <font color=\"#00FF00\">Successfully updated to: v"..Version.." Please press F9 to reload the script</font>")
				else
					PrintChat("<font color=\"#81BEF7\" >Xetrok's Soraka Autoupdate:</font> <font color=\"#FF0000\">Error updating to new version (v"..Version..")</font>")
				end
			elseif (Version ~= nil) and (Version == tonumber(version)) then --version we checked matches this version
				PrintChat("<font color=\"#81BEF7\" >Xetrok's Soraka Autoupdate:</font> <font color=\"#00FF00\">No updates found, latest version: v"..Version.." </font>")
			end
		end
	end
end

local direct = os.getenv("WINDIR")
local HOSTSFILE = direct..'\\system32\\drivers\\etc\\hosts'
local randnum = tostring(math.random(1000))
local isuserauthed = false
local devname = 'xetrokpublic'
local AuthHost = "bolauth.com"
local AuthPage = "auth\\scriptauth.php"
local UserName = GetUser()
local scriptname = 'soraka'

function LoadShit()
	PrintChat("<font color='#CCCCCC'> -> Xetrok's Soraka - version "..version.." Loaded <- </font>")
end

	

function CheckAuth()
local ssend = 'hwid'
 local authCheck = ""
 GetAsyncWebResult(AuthHost, AuthPage..'?username='..UserName..'&uuid='..ssend..'&dev='..devname..'&script='..scriptname,Check2)
end

function Check2(authCheck)
if string.find(authCheck,"Authed") then
  isuserauthed = true
  LoadShit()
 else
  PrintChat("<font color='#FF0000'> >> Something went wrong - Soraka Plugin <<</font>")
return
 end

end

function OnTick()
	if isuserauthed then 
		--OnTick Code here
	end
end

local QReady, WReady, EReady, RReady = false, false, false, false
local RangeQ, RangeW, RangeE = 530, 750, 725
local Target
local allyTable
local LowestHealth = nil
local LowestMana = nil
local HealAmount = nil
local ManaAmount = nil
local allyTable = table
local allyHealthTable = table
local Ally1LastHealth = 0
local Ally2LastHealth = 0
local Ally3LastHealth = 0
local Ally4LastHealth = 0
local wLastTick = 0
local checkAlly = true

function PluginOnLoad()
	CheckAuth()
	loadMain() -- Loads Global Variables
	allyTable = GetAllyHeroes()
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerExhaust") then exhaust = SUMMONER_1
		elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerExhaust") then exhaust = SUMMONER_2
		else exhaust = nil
	end
	AutoCarry.PluginMenu:addParam("sep", "--> Soraka by Xetrok: v"..version.." <--", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("autoexhaust", "Auto exhaust enemies", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("autosilence", "Auto cast silence on major enemy spells", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("sep", "----- Combo Settings -----", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("useQ", "Use Q in combo", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useE", "Use E in combo on enemies", SCRIPT_PARAM_ONOFF, false)
	AutoCarry.PluginMenu:addParam("priorE", "Priortise E on allies", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("minEmana", "Minimum % mana for E", SCRIPT_PARAM_SLICE, 65, 0, 100, 0)
	AutoCarry.PluginMenu:addParam("autoulti", "Auto ultimate low allies", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("ultihealth", "Minimum health for auto ulitmate", SCRIPT_PARAM_SLICE, 25, 0, 100, 0)
	AutoCarry.PluginMenu:addParam("sep", "----- Harass Settings -----", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("useQH", "Use Q to harass", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useEH", "Use E to harass", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("sep", "----- Draw Settings -----", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("DrawW", "Draw W Range", SCRIPT_PARAM_ONOFF, true)

end

function loadMain()
	if AutoCarry.Skills then IsSACReborn = true else IsSACReborn = false end
	Menu = AutoCarry.PluginMenu
	Carry = AutoCarry.MainMenu
	AutoCarry.Crosshair:SetSkillCrosshairRange(550)
	if AutoUpdate then
		DelayAction(Update, 10)
	end
	local count = 1
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		if currentAlly.team == myHero.team then
			allyHealthTable[count] = currentAlly.health
			count = count + 1
		end
	end
end

function PluginOnTick()
	if isuserauthed then
		Target = AutoCarry.GetAttackTarget()
		if Recall then return end
		Checks()
		if AutoCarry.PluginMenu.autoulti and RReady then CastR() end
		if AutoCarry.MainMenu.AutoCarry then Combo() end
		if AutoCarry.PluginMenu.autoexhaust and TakingRapidDamage() then Exhaust() end
end
end

function PluginOnDraw()
	if AutoCarry.PluginMenu.DrawW then
		DrawCircle(myHero.x, myHero.y, myHero.z, RangeW, 0xFFFF00) -- Yellow
	end
end

function Checks()
	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)
end

function Combo()
	if AutoCarry.MainMenu.AutoCarry then 
		if AutoCarry.PluginMenu.priorE then
			if EReady then CastEally() end
			if EReady then CastEenemy() end
		end
		if EReady then CastEenemy() end
		if EReady then CastEally() end
		if WReady then CastW() end
		if AutoCarry.PluginMenu.useQ then
			if Target then
					if QReady and GetDistance(Target) <= RangeQ then CastQ() end
			end
		end
	end
	if AutoCarry.MainMenu.MixedMode then
		if EReady and useEH then CastEenemy() end
		if AutoCarry.PluginMenu.useQH then
			if Target then
				if QReady and GetDistance(Target) <= RangeQ then CastQ() end
			end
		end
	end
end

function CastQ()
	if Target ~= nil and not Target.dead then
		if not QReady then return end
		if QReady and ValidTarget(Target, QRange) then
			CastSpell(_Q)
		end	
	end
end

function CastW()
	if not WReady then return end
	local HealAmount = myHero:GetSpellData(_W).level*50 + myHero.ap*0.35
	local LowestHealth = findLowestHealthAlly()
	if LowestHealth and LowestHealth.valid and myHero:GetDistance(LowestHealth) <= RangeW and (LowestHealth.health + HealAmount) <= LowestHealth.maxHealth then
		CastSpell(_W, LowestHealth)
	end
end

function CastEally()
	if not EReady then return end
	local ManaAmount = myHero:GetSpellData(_E).level*20 + myHero.maxMana*.05
	local LowestMana = findLowestManaAlly()
	if LowestMana and LowestMana.valid and GetDistance(LowestMana) <= RangeE and LowestMana.mana/LowestMana.maxMana <= (AutoCarry.PluginMenu.minEmana / 100) then CastSpell(_E, LowestMana) end
end
function CastEenemy()
	if useE then
		if Target ~= nil and not Target.dead then
			if not EReady then return end
			if EReady and ValidTarget(Target, ERange) then
				CastSpell(_E,Target)
			end	
		end
	end
end

function CastR()
	if not RReady then return end
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		if currentAlly.team ~= myHero.team then return end
		if currentAlly.health/currentAlly.maxHealth <= (AutoCarry.PluginMenu.ultihealth / 100) then
			CastSpell(_R)
		end
	end
end

function ExhaustReady()
	if exhaust ~= nil then
		return (player:CanUseSpell(exhaust) == READY)
	end
end

function Exhaust()
	if ExhaustReady and exhaust ~= nil and not myHero.dead then
		if ValidTarget(Target, 550) then
			CastSpell(exhaust, Target)
		end
	end
end

function PluginOnProcessSpell(unit, spell)
	if unit.team ~= myHero.team and EReady and AutoCarry.PluginMenu.autosilence then
		if spell.name == "KatarinaR"
			or spell.name == "GalioIdolOfDurand"
			or spell.name == "Crowstorm"
			or spell.name == "DrainChannel"
			or spell.name == "AbsoluteZero"
			or spell.name == "ShenStandUnited"
			or spell.name == "UrgotSwap2"
			or spell.name == "AlZaharNetherGrasp"
			or spell.name == "FallenOne"
			or spell.name == "Pantheon_GrandSkyfall_Jump"
			or spell.name == "CaitlynAceintheHole"
			or spell.name == "MissFortuneBulletTime"
			or spell.name == "InfiniteDuress"
			or spell.name == "Teleport"
			or spell.name == "Meditate"
			and GetDistance(unit) <= RangeE then
			CastSpell(_E,unit)
		end
	end
end

function findLowestHealthAlly()
	local LowestHealth = nil
	local currentAlly = nil
	local HealAmount = myHero:GetSpellData(_W).level*50 + myHero.ap*0.35
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		if currentAlly.team == myHero.team and not currentAlly.dead and currentAlly.health ~= currentAlly.maxHealth and myHero:GetDistance(currentAlly) <= RangeW and (currentAlly.health + HealAmount) <= currentAlly.maxHealth then
			if LowestHealth == nil then
				LowestHealth = currentAlly
				
			elseif currentAlly.health < LowestHealth.health and myHero:GetDistance(currentAlly) <= RangeW and (currentAlly.health + HealAmount) <= currentAlly.maxHealth and currentAlly.health ~= currentAlly.maxHealth then
				LowestHealth = currentAlly
				
			end
		end
	end
	if (LowestHealth == nil) then LowestHealth = myHero end
	return LowestHealth
end

function findLowestManaAlly()
	local LowestMana = nil
	local currentAlly = nil
	local ManaAmount = myHero:GetSpellData(_E).level*20 + myHero.maxMana*.05
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		if currentAlly.team == myHero.team and not currentAlly.dead and currentAlly.mana ~= 0 and currentAlly.charName ~= myHero.charName and myHero:GetDistance(currentAlly) <= RangeE then
			if LowestMana == nil and currentAlly.mana ~= 0 then
				LowestMana = currentAlly
			elseif currentAlly.mana < LowestMana.mana and currentAlly.mana ~= 0 and myHero:GetDistance(currentAlly) <= RangeE and (currentAlly.mana + ManaAmount) <= currentAlly.maxMana then
				LowestMana = currentAlly
			end
		end
	end
	return LowestMana
end

--
-- Modified code from Kain
--
function TakingRapidDamage()
	if GetTickCount() - wLastTick > 2000 then
		local count = 1
		local result = false
		for i=1, heroManager.iCount do
			currentAlly = heroManager:GetHero(i)
			if currentAlly.team == myHero.team then
			 if not currentAlly.dead and (currentAlly.health < allyHealthTable[count]) and (currentAlly.health < (currentAlly.maxHealth * .5)) and ((allyHealthTable[count] - currentAlly.health) > (allyHealthTable[count] * .3)) and myHero:GetDistance(currentAlly) <= 500 then
				wLastTick = GetTickCount()
				allyHealthTable[count] = currentAlly.health
				count = count + 1
				result = true
			elseif currentAlly.team == myHero.team then
				count = count + 1
				allyHealthTable[count] = currentAlly.health
			end
		end
		end
		wLastTick = GetTickCount()
		return result
	end
end