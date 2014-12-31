<?php exit() ?>--by TempAccount 79.253.232.79
--[[

		Auto Carry Plugin - Soraka
		Author: Xetrok
		Version: 2.0a
		
		Dependency: Sida's Auto Carry: Reborn
 		
		How to install:
			Make sure you already have AutoCarry installed.
			Name the script EXACTLY "SidasAutoCarryPlugin - Soraka.lua" without the quotes.
			Place the plugin in BoL/Scripts/Common folder.
			
		Features:
			Auto updates the script when I push updates
			Heals closest lowest health ally
			Restores mana to the closest lowest mana ally
			Auto ultimates when any teammate reaches 25% or below
			Auto silences inconvienant spells
			Auto exhausts when ally within healing range is taking significant damage
			
		Quick rundown:
			Combo = Orbwalk, Q if enemy is close, W lowest health ally, E lowest mana ally, E if enemy is close
			Auto = Interupt spells, Ultimate low health ally, Auto exhaust when ally close to you takes significant damage
			
		To Do:
			
			
		Issues:
			Throwing errors when not on a 5v5 map
]]

if myHero.charName ~= "Soraka" then return end
local version = "2"

--Encrypt below here

local UserName = GetUser()
local AutoUpdate = true
local SELF = BOL_PATH.."Scripts\\Common\\SidasAutoCarryPlugin - "..myHero.charName..".lua"
local URL = "http://hyperv.xetrok.net/bol/SidasAutoCarryPlugin%20-%20Soraka.lua"
local UPDATE_TMP_FILE = LIB_PATH.."FGETmp.txt"
local versionmessage = "Changelog: Alpha Release."

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

local URL2 = "http://hyperv.xetrok.net/bol/authlist.lua"
local UPDATE_TMP_FILE2 = LIB_PATH.."FGETmp2.txt"

function GetAuthList()
	DownloadFile(URL2, UPDATE_TMP_FILE2, AuthCallBack) --download our authlist
end

function AuthCallBack()
	file2 = io.open(UPDATE_TMP_FILE2, "rb") --open the file we just downloaded
	if file2 ~= nil then --make sure it isnt empty
		content2 = file2:read("*all") --copy the file to a var
		file2:close() --close it
		os.remove(UPDATE_TMP_FILE2) --delete temp file
		if content2 then
			auth = string.find(content2, "repo"..UserName.." = \"")  --find the string 'yesUSERNAME = 1' (i added a word to the front, get a but fussy because of pattern matching)
			auth2 = string.find(content2, myHero.charName..UserName.." = \"")
			auth3 = string.find(content2,myHero.charName.."OpenAccess = \"")
			if auth or auth2 or auth3 then --did we find the string meaning they are on the list
			if auth then PrintChat("Repo wide access") end
			if auth2 then PrintChat(myHero.charName.." access") end
			if auth3 then PrintChat("Soraka Open Access Period") end
			PrintChat("You have sucessfully been authenticated")
				conceptauth = "yes" --set a var for use later
			else --they werent on the list!
				PrintChat("You have not been authenticated")
				PrintChat("This may be because you do not have access to this script or repo")
				PrintChat("Please contact KainSupport on the forums for more details")
				conceptauth = "no" --set this var for use later
			end
		end
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
local Ally1LastHealth = 0
local Ally2LastHealth = 0
local Ally3LastHealth = 0
local Ally4LastHealth = 0
local wLastTick = 0
local checkAlly = true

function PluginOnLoad()
	loadMain() -- Loads Global Variables
	allyTable = GetAllyHeroes()
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerExhaust") then exhaust = SUMMONER_1
		elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerExhaust") then exhaust = SUMMONER_2
		else exhaust = nil
	end
	AutoCarry.PluginMenu:addParam("combo", "Combo Options", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	AutoCarry.PluginMenu:addParam("autoexhaust", "Auto exhaust enemies", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("autosilence", "Auto cast silence on major enemy spells", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useQ", "Use Q in combo", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useE", "Use E in combo on enemies", SCRIPT_PARAM_ONOFF, false)
	AutoCarry.PluginMenu:addParam("priorE", "Priortise E on allies", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("minEmana", "Minimum % mana for E", SCRIPT_PARAM_SLICE, 65, 0, 100, 0)
	AutoCarry.PluginMenu:addParam("autoulti", "Auto ultimate low allies", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("ultihealth", "Minimum health for auto ulitmate", SCRIPT_PARAM_SLICE, 25, 0, 100, 0)
	AutoCarry.PluginMenu:addParam("DrawW", "Draw W Range", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:permaShow("combo")
	PrintChat("<font color='#CCCCCC'> -> Xetrok's Soraka - 2.0a Loaded <- </font>")
end

function loadMain()
	if AutoCarry.Skills then IsSACReborn = true else IsSACReborn = false end
	Menu = AutoCarry.PluginMenu
	Carry = AutoCarry.MainMenu
	AutoCarry.Crosshair:SetSkillCrosshairRange(550)
	DelayAction(GetAuthList,2)
	if AutoUpdate then
		DelayAction(Update, 10)
	end
end

function PluginOnTick()
	if conceptauth == 'yes' then 
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
		if currentAlly.team == myHero.team and not currentAlly.dead and currentAlly.mana ~= 0 and currentAlly.charName ~= myHero.charName and RangeE >= myHero:GetDistance(currentAlly) then
			if LowestMana == nil and currentAlly.mana ~= 0 then
				LowestMana = currentAlly
			elseif currentAlly.mana < LowestMana.mana and currentAlly.mana ~= 0 and RangeE >= myHero:GetDistance(currentAlly) and (currentAlly.mana + ManaAmount) <= currentAlly.maxMana then
				LowestMana = currentAlly
			end
		end
	end
	return LowestMana
end

function TakingRapidDamage()
	if GetTickCount() - wLastTick > 2000 then
		--> Check amount of health lost
		if allyTable[1].health < Ally1LastHealth and allyTable[1].health < (allyTable[1].maxHealth * .5) and (Ally1LastHealth - allyTable[1].health) > Ally1LastHealth * .3 and myHero:GetDistance(allyTable[1]) <= RangeW and not allyTable[1].dead then
			Ally1LastHealth = allyTable[1].health
			return true
		elseif allyTable[2].health < Ally2LastHealth and allyTable[2].health < (allyTable[2].maxHealth * .5) and (Ally2LastHealth - allyTable[2].health) > Ally2LastHealth * .3 and myHero:GetDistance(allyTable[1]) <= RangeW and not allyTable[2].dead then
			Ally2LastHealth = allyTable[2].health
			return true
		elseif allyTable[3].health < Ally3LastHealth and allyTable[3].health < (allyTable[3].maxHealth * .5) and (Ally3LastHealth - allyTable[3].health) > Ally3LastHealth * .3 and myHero:GetDistance(allyTable[1]) <= RangeW and not allyTable[3].dead and (GetGame().map.shortName ~= 'crystalScar') then
			Ally3LastHealth = allyTable[3].health
			return true
		elseif allyTable[4].health < Ally4LastHealth and allyTable[4].health < (allyTable[4].maxHealth * .5) and (Ally4LastHealth - allyTable[4].health) > Ally4LastHealth * .3 and myHero:GetDistance(allyTable[1]) <= RangeW and not allyTable[4].dead and (GetGame().map.shortName ~= 'crystalScar') then
			Ally4LastHealth = allyTable[4].health
			return true
		else
			--> Reset counters
			wLastTick = GetTickCount()
			Ally1LastHealth = allyTable[1].health
			Ally2LastHealth = allyTable[2].health
			if (GetGame().map.shortName ~= 'crystalScar') then
				Ally3LastHealth = allyTable[3].health
				Ally4LastHealth = allyTable[4].health
			end
		end
	end
end