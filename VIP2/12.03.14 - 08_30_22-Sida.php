<?php exit() ?>--by Sida 81.170.70.121
if FileExist(SCRIPT_PATH..'Common/VPrediction.lua') then
	require 'VPrediction'
	AutoCarry.PluginMenu.pro = false
	AutoCarry.PluginMenu.vpre = true
	VP = VPrediction()
	vpredinstalled = true
end

if not FileExist(SCRIPT_PATH..'Common/VPrediction.lua') then
	AutoCarry.PluginMenu.vpre = false
	AutoCarry.PluginMenu.pro = true
	vpredinstalled = false
end
if not VIP_USER then
	PrintChat("This Script Requires VIP")
	return
end

--[[ Auth ]]--

local isuserauthed = false
local direct = os.getenv("WINDIR")
local HOSTSFILE = direct..'\\system32\\drivers\\etc\\hosts'
local devname = 'xetrokpublic'
local AuthHost = "bolauth.com"
local AuthPage = "auth\\scriptauth.php"
local UserName = GetUser()
local scriptname = 'leona'

function LoadShit()
		PrintChat("<font color='#CCCCCC'> -> Xetrok's Leona - version "..version.." Loaded <- </font>")
end

function CheckAuth()
	ssend = 'hwid'
	local authCheck = ""
	GetAsyncWebResult(AuthHost, AuthPage..'?username='..UserName..'&uuid='..ssend..'&dev='..devname..'&script='..scriptname,Check2)
end

function Check2(authCheck)
if string.find(authCheck,"Authed") then
  isuserauthed = true
  LoadShit()
 else
  PrintChat("<font color='#FF0000'> >> Something went wrong - Leona Plugin <<</font>")
  return
 end

end



local AutoUpdate = true
local SELF = BOL_PATH.."Scripts\\Common\\SidasAutoCarryPlugin - "..myHero.charName..".lua"
local URL = "http://bolauth.com/xetrok/scripts/SidasAutoCarryPlugin%20-%20Leona.lua"
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
					PrintChat("<font color=\"#81BEF7\" >Xetrok's Leona Autoupdate:</font> <font color=\"#00FF00\">Successfully updated to: v"..Version.." Please press F9 to reload the script</font>")
				else
					PrintChat("<font color=\"#81BEF7\" >Xetrok's Leona Autoupdate:</font> <font color=\"#FF0000\">Error updating to new version (v"..Version..")</font>")
				end
			elseif (Version ~= nil) and (Version == tonumber(version)) then --version we checked matches this version
				PrintChat("<font color=\"#81BEF7\" >Xetrok's Leona Autoupdate:</font> <font color=\"#00FF00\">No updates found, latest version: v"..Version.." </font>")
			end
		end
	end
end


local QReady, WReady, EReady, RReady = false, false, false, false
local Rwidth, Rrange, Rspeed, Rdelay = 300, 1100, 2.0, 400
local Ewidth, Erange, Espeed, Edelay = 80, 850, 1.2, 100
local Target
local allyTable
local enemyTable
local LowestHealth = nil
local allyTable = table
local allyHealthTable = table
local wLastTick = 0
local checkAlly = true

function PluginOnLoad()
	CheckAuth()
		loadMain() -- Loads Global Variables
		allyTable = GetAllyHeroes()
		enemyTable = GetEnemyHeroes()
		if myHero:GetSpellData(SUMMONER_1).name:find("SummonerExhaust") then exhaust = SUMMONER_1
			elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerExhaust") then exhaust = SUMMONER_2
			else exhaust = nil
		end
		AutoCarry.PluginMenu:addParam("sep", "--> Leona by Xetrok: v"..version.." <--", SCRIPT_PARAM_INFO, "")
		AutoCarry.PluginMenu:addParam("autoexhaust", "Auto exhaust enemies", SCRIPT_PARAM_ONOFF, true)
		AutoCarry.PluginMenu:addParam("autosilence", "Auto cast E-Q on major enemy spells", SCRIPT_PARAM_ONOFF, true)
		AutoCarry.PluginMenu:addParam("gapclose", "Auto cast E-Q on Gapclosers", SCRIPT_PARAM_ONOFF, false)
		AutoCarry.PluginMenu:addParam("sep", "----- Prediction Settings -----", SCRIPT_PARAM_INFO, "")
		if FileExist(SCRIPT_PATH..'Common/VPrediction.lua') then
			AutoCarry.PluginMenu:addParam("vpre", "Use VPrediction for E/R", SCRIPT_PARAM_ONOFF, true)
			AutoCarry.PluginMenu:addParam("pro", "Use PROdiction for E/R", SCRIPT_PARAM_ONOFF, false)
		end
		if not FileExist(SCRIPT_PATH..'Common/VPrediction.lua') then
			AutoCarry.PluginMenu:addParam("pro", "Use PROdiction for E/R", SCRIPT_PARAM_ONOFF, true)
		end
		AutoCarry.PluginMenu:addParam("sep", "----- Combo Settings -----", SCRIPT_PARAM_INFO, "")
		AutoCarry.PluginMenu:addParam("useQ", "Use Q in combo", SCRIPT_PARAM_ONOFF, true)
		AutoCarry.PluginMenu:addParam("useW", "Use W in combo", SCRIPT_PARAM_ONOFF, true)
		AutoCarry.PluginMenu:addParam("useE", "Use E in combo", SCRIPT_PARAM_ONOFF, true)
		AutoCarry.PluginMenu:addParam("useR", "Use R in combo", SCRIPT_PARAM_ONOFF, true)
		AutoCarry.PluginMenu:addParam("howmanyR", "Allies nearby to trigger ult use", SCRIPT_PARAM_SLICE, 1, 0, 4, 0)
		AutoCarry.PluginMenu:addParam("allyrange", "How close your allies to be for ult", SCRIPT_PARAM_SLICE, 800, 0, 1200, 0)
		AutoCarry.PluginMenu:addParam("autoR", "Auto ultimate 2 or more people", SCRIPT_PARAM_ONOFF, false)
		AutoCarry.PluginMenu:addParam("sep", "----- Draw Settings -----", SCRIPT_PARAM_INFO, "")
		AutoCarry.PluginMenu:addParam("rangefocus", "Range to stop focus on SAC target", SCRIPT_PARAM_SLICE, 500, 0, 1200, 0)
		AutoCarry.PluginMenu:addParam("drawE", "Draw E Range", SCRIPT_PARAM_ONOFF, true)
		AutoCarry.PluginMenu:addParam("drawF", "Draw Focus Range", SCRIPT_PARAM_ONOFF, true)

		SkillE = AutoCarry.Skills:NewSkill(false, _E, Erange, "Zenith Blade", AutoCarry.SPELL_LINEAR, 0, false, false, Espeed, Edelay, Ewidth, false)
		SkillR = AutoCarry.Skills:NewSkill(false, _R, Rrange, "LeonaSolarFlare", AutoCarry.SPELL_CIRCLE, 0, false, false, Rspeed, Rdelay, Rwidth, false)
		
		AdvancedCallback:bind('OnGainBuff', function(unit, buff) OnGainBuff(unit, buff) end)
		--AdvancedCallback:bind('OnLoseBuff', function(unit, buff) OnLoseBuff(unit, buff) end)

end

function loadMain() 
	Menu = AutoCarry.PluginMenu
	Carry = AutoCarry.MainMenu
	AutoCarry.Crosshair:SetSkillCrosshairRange(700)
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
	if AutoCarry.PluginMenu.vpre and AutoCarry.PluginMenu.pro then 
		AutoCarry.PluginMenu.pro = false
		AutoCarry.PluginMenu.vpre = false
		PrintChat("Detected both predictions enabled, please select only one.")
	end

	if AutoCarry.PluginMenu.autoR then
		if ValidTarget(Target) and RReady then
			local AoEPos = GetAoESpellPosition(Rwidth, Target, Rdelay)
			if AoEPos and GetDistance(AoEPos) <= Rrange then
				if CountEnemies(AoEPos, 50) > 1 then
					CastSpell(_R, AoEPos.x, AoEPos.z)
				end
			end
		end
	end

	if Recall then return end
	Checks()
	if AutoCarry.MainMenu.AutoCarry then Combo() end
	if AutoCarry.PluginMenu.autoexhaust and TakingRapidDamage() then Exhaust() end
end
end


function PluginOnDraw()
	if isuserauthed then 
	if AutoCarry.PluginMenu.drawE and EReady then
		DrawCircle(myHero.x, myHero.y, myHero.z, Erange, 0xFFFF00) -- Yellow
	end
	if AutoCarry.PluginMenu.drawF then
		DrawCircle(myHero.x, myHero.y, myHero.z, AutoCarry.PluginMenu.rangefocus, 0x00FF33) -- Green
	end
end
end


function Checks()
	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)
end

function Combo()
	if AutoCarry.PluginMenu.vpre then
		if EReady and AutoCarry.PluginMenu.useE then CastEVpre() end
	end
	if AutoCarry.PluginMenu.pro then
		if EReady and AutoCarry.PluginMenu.useE then CastE() end
	end
	if not EReady then
		if ValidTarget(Target) then
			if GetDistance(Target) > AutoCarry.PluginMenu.rangefocus then
				newtarget = FindClosestEnemy(myHero)
				if QReady and ((GetDistance(newtarget) - getHitBoxRadius(newtarget)) < 150) and AutoCarry.PluginMenu.useQ then
					Target = newtarget
					CastQ() 
				end
				if WReady and ((GetDistance(newtarget) - getHitBoxRadius(newtarget)) < 150) and AutoCarry.PluginMenu.useW then 
					CastW() 
				end
			end
			if GetDistance(Target) < AutoCarry.PluginMenu.rangefocus then
				if QReady and ((GetDistance(Target) - getHitBoxRadius(Target)) < 150) and AutoCarry.PluginMenu.useQ then
					CastQ() 
				end
				if WReady and ((GetDistance(Target) - getHitBoxRadius(Target)) < 150) and AutoCarry.PluginMenu.useW then 
					CastW() 
				end
			end
		end
	end 
end

function OnGainBuff(unit, buff)
	if unit == nil or buff == nil then return end
	if unit == Target and buff.name == "leonazenithbladeroot" and AutoCarry.PluginMenu.useW then
		CastW()
	end
	if unit == Target and buff.name == "leonazenithbladeroot" and AutoCarry.PluginMenu.useQ then
		CastQ()
	end
	if unit == Target and buff.name == "Stun" and AutoCarry.PluginMenu.useR and AutoCarry.PluginMenu.vpre and buff.source == myHero then
		CastRVpre()
	end
	if unit == Target and buff.name == "Stun" and AutoCarry.PluginMenu.useR and AutoCarry.PluginMenu.pro and buff.source == myHero then
		CastR()
	end
end

function CastQ()
	if ValidTarget(Target) then
		CastSpell(_Q)
	end
end

function CastW()
	CastSpell(_W)
end

function CastEVpre()
	if ValidTarget(Target) then
		CastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, Edelay/1000, Ewidth, Erange, Espeed/1000, myHero)
		if HitChance >= 2 and GetDistance(CastPosition) < Erange then
			CastSpell(_E, CastPosition.x, CastPosition.z)
		end
	end
end

function CastE()
	if ValidTarget(Target) then
		SkillE:Cast(Target)
	end
end

--[[
function CastR()
	if ValidTarget(Target, Rrange) then
		position = GetAoESpellPosition(Rwidth, Target, Rdelay, Rspeed)
		CastSpell(_R, position.x, position.z)
	end
end
]]

function CastRVpre()
	if ValidTarget(Target) then
		
		allynearme = CountAllyNearPerson(myHero,AutoCarry.PluginMenu.allyrange)
		if (allynearme >= (AutoCarry.PluginMenu.howmanyR)) then
			CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(Target, Rdelay/1000, Rwidth, Rrange)
			if HitChance >= 2 and GetDistance(CastPosition) < Rrange then
                CastSpell(_R, CastPosition.x, CastPosition.z)
			end
		end
	end
end

function CastR()
	if ValidTarget(Target) then
		allynearme = CountAllyNearPerson(myHero,AutoCarry.PluginMenu.allyrange)
		if (allynearme >= (AutoCarry.PluginMenu.howmanyR)) then
			SkillR:Cast(Target)
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
	if unit.team ~= myHero.team and QReady and AutoCarry.PluginMenu.autosilence then
		if spell.name == "KatarinaR"
			or spell.name == "GalioIdolOfDurand"
			or spell.name == "Crowstorm"
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
			then
			if GetDistance(unit) <= Erange and EReady then
				CastSpell(_E, unit.x, unit.z)
				Target = unit
				CastSpell(_Q)
			end
			if GetDistance(unit) <= 80 then
				Target = unit
				CastSpell(_Q)
			end		
		end
	end

	--
	--Gap close logic taken from Vayne's Mighty Assistant 
	--
	if EReady and AutoCarry.PluginMenu.gapclose then
	 	local jarvanAddition = unit.charName == "JarvanIV" and unit:CanUseSpell(_Q) ~= READY and _R or _Q -- Did not want to break the table below.
	    local isAGapcloserUnit = {
	--        ['Ahri']        = {true, spell = _R, range = 450,   projSpeed = 2200},
	        ['Aatrox']      = {true, spell = _Q,                  range = 1000,  projSpeed = 1200, },
	        ['Akali']       = {true, spell = _R,                  range = 800,   projSpeed = 2200, }, -- Targeted ability
	        ['Alistar']     = {true, spell = _W,                  range = 650,   projSpeed = 2000, }, -- Targeted ability
	        ['Diana']       = {true, spell = _R,                  range = 825,   projSpeed = 2000, }, -- Targeted ability
	        ['Gragas']      = {true, spell = _E,                  range = 600,   projSpeed = 2000, },
	        ['Graves']      = {true, spell = _E,                  range = 425,   projSpeed = 2000, exeption = true },
	        ['Hecarim']     = {true, spell = _R,                  range = 1000,  projSpeed = 1200, },
	        ['Irelia']      = {true, spell = _Q,                  range = 650,   projSpeed = 2200, }, -- Targeted ability
	        ['JarvanIV']    = {true, spell = jarvanAddition,      range = 770,   projSpeed = 2000, }, -- Skillshot/Targeted ability
	        ['Jax']         = {true, spell = _Q,                  range = 700,   projSpeed = 2000, }, -- Targeted ability
	        ['Jayce']       = {true, spell = 'JayceToTheSkies',   range = 600,   projSpeed = 2000, }, -- Targeted ability
	        ['Khazix']      = {true, spell = _E,                  range = 900,   projSpeed = 2000, },
	        ['Leblanc']     = {true, spell = _W,                  range = 600,   projSpeed = 2000, },
	        ['LeeSin']      = {true, spell = 'blindmonkqtwo',     range = 1300,  projSpeed = 1800, },
	        ['Leona']       = {true, spell = _E,                  range = 900,   projSpeed = 2000, },
	        ['Malphite']    = {true, spell = _R,                  range = 1000,  projSpeed = 1500 + unit.ms},
	        ['Maokai']      = {true, spell = _Q,                  range = 600,   projSpeed = 1200, }, -- Targeted ability
	        ['MonkeyKing']  = {true, spell = _E,                  range = 650,   projSpeed = 2200, }, -- Targeted ability
	        ['Pantheon']    = {true, spell = _W,                  range = 600,   projSpeed = 2000, }, -- Targeted ability
	        ['Poppy']       = {true, spell = _E,                  range = 525,   projSpeed = 2000, }, -- Targeted ability
	        --['Quinn']       = {true, spell = _E,                  range = 725,   projSpeed = 2000, }, -- Targeted ability
	        ['Renekton']    = {true, spell = _E,                  range = 450,   projSpeed = 2000, },
	        ['Sejuani']     = {true, spell = _Q,                  range = 650,   projSpeed = 2000, },
	        ['Shen']        = {true, spell = _E,                  range = 575,   projSpeed = 2000, },
	        ['Tristana']    = {true, spell = _W,                  range = 900,   projSpeed = 2000, },
	        ['Tryndamere']  = {true, spell = 'Slash',             range = 650,   projSpeed = 1450, },
	        ['XinZhao']     = {true, spell = _E,                  range = 650,   projSpeed = 2000, }, -- Targeted ability
	    }
	    if unit.type == 'obj_AI_Hero' and unit.team == TEAM_ENEMY and isAGapcloserUnit[unit.charName] and GetDistance(unit) < Erange+100 and spell ~= nil then
	        if spell.name == (type(isAGapcloserUnit[unit.charName].spell) == 'number' and unit:GetSpellData(isAGapcloserUnit[unit.charName].spell).name or isAGapcloserUnit[unit.charName].spell) then
	            if spell.target ~= nil or isAGapcloserUnit[unit.charName].spell == 'blindmonkqtwo' or isAGapcloserUnit['Tristana'].spell == _W or isAGapcloserUnit['Leona'].spell == _E or isAGapcloserUnit['Aatrox'].spell == _E or isAGapcloserUnit['Khazix'].spell == _W or isAGapcloserUnit['Leblanc'].spell == _W  then
	        		
	        		CastSpell(_E, spell.endPos.x, spell.endPos.z)
	            else
	                spellExpired = false
	                informationTable = {
	                    spellSource = unit,
	                    spellCastedTick = GetTickCount(),
	                    spellStartPos = Point(spell.startPos.x, spell.startPos.z),
	                    spellEndPos = Point(spell.endPos.x, spell.endPos.z),
	                    spellRange = isAGapcloserUnit[unit.charName].range,
	                    spellSpeed = isAGapcloserUnit[unit.charName].projSpeed,
	                    spellIsAnExpetion = isAGapcloserUnit[unit.charName].exeption or false,
	                }
	            end
	        end
	    end
	end
end

function findLowestHealthAlly(vrange)
	local LowestHealth = nil
	local currentAlly = nil
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		if currentAlly.team == myHero.team and not currentAlly.dead and currentAlly.health ~= currentAlly.maxHealth and myHero:GetDistance(currentAlly) <= vrange then
			if LowestHealth == nil then
				LowestHealth = currentAlly
			elseif currentAlly.health < LowestHealth.health and myHero:GetDistance(currentAlly) <= vrange and currentAlly.health ~= currentAlly.maxHealth then
				LowestHealth = currentAlly
			end
		end
	end
	if (LowestHealth == nil) then LowestHealth = myHero end
	return LowestHealth
end

--
--Taking Rapid Damage improved by xetrok from Kain
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
-- 
--Count Enemies from Kain for AoESkillshotPosition
--
function CountEnemies(point, range)
	local ChampCount = 0
    for j = 1, heroManager.iCount, 1 do
        local enemyhero = heroManager:getHero(j)
        if myHero.team ~= enemyhero.team and ValidTarget(enemyhero, Rrange+150) then
            if GetDistance(enemyhero, point) <= range then
                ChampCount = ChampCount + 1
            end
        end
    end            
    return ChampCount
end
--
-- Hitbox Radius from Kain
--
local function getHitBoxRadius(target)
 return GetDistance(target, target.minBBox)
end
--
-- Functions made by xetrok
--

function CountAllyNearPerson(person,vrange)
	local count = 0
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		if currentAlly.team == myHero.team and currentAlly.charName ~= person.charName then
			if person:GetDistance(currentAlly) <= vrange and not currentAlly.dead then count = count + 1 end
		end
	end
	return count
end

function CountEnemyNearPerson(person,vrange)
	count = 0
	for i=1, heroManager.iCount do
		currentEnemy = heroManager:GetHero(i)
		if currentEnemy.team ~= myHero.team then
			if person:GetDistance(currentEnemy) <= vrange and not currentEnemy.dead then count = count + 1 end
		end
	end
	return count
end

function AlliesAbovePercentage(pctvalue)
	local result = true
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		if currentAlly.team == myHero.team and currentAlly.charName ~= myHero.charName then
			if myHero:GetDistance(currentAlly) <= RangeW then if currentAlly.health / currentAlly.maxHealth < pctvalue/100 and not currentAlly.dead then result = false end end
		end
	end
	return result
end

function ClosestAllyMostAD()
	local attackdamage = 0
	local closest = nil
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		if currentAlly.team == myHero.team and currentAlly.charName ~= myHero.charName then
			if not currentAlly.dead and currentAlly.totalDamage >= attackdamage and myHero:GetDistance(currentAlly) <= Erange then 
				attackdamage = currentAlly.totalDamage
				closest = currentAlly
			end
		end
	end
	return closest
end

function FindClosestEnemy(person)
	local distance = 25000
	local closest = nil
	for i=1, heroManager.iCount do
		currentEnemy = heroManager:GetHero(i)
		if currentEnemy.team ~= myHero.team and not currentEnemy.dead and person:GetDistance(currentEnemy) < distance then
			distance = person:GetDistance(currentEnemy)
			closest = currentEnemy
		end
	end
	return closest
end

function FindClosestAlly(person)
	local distance = 25000
	local closest = nil
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		if currentAlly.team == myHero.team and not currentAlly.dead and person:GetDistance(currentAlly) < distance then
			distance = person:GetDistance(currentAlly)
			closest = currentAlly
		end
	end
	return closest
end

function FindClosestAlly2(person) -- This function excludes myHero from the check
	local distance = 25000
	local closest = nil
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		if currentAlly.team == myHero.team and currentAlly.charName ~= myHero.charName and not currentAlly.dead and person:GetDistance(currentAlly) < distance then
			distance = person:GetDistance(currentAlly)
			closest = currentAlly
		end
	end
	return closest
end

------------------------------------------
--AoE_Skillshot_Position 2.0 by monogato--
------------------------------------------

function GetCenter(points)
        local sum_x = 0
        local sum_z = 0
        
        for i = 1, #points do
                sum_x = sum_x + points[i].x
                sum_z = sum_z + points[i].z
        end
        
        local center = {x = sum_x / #points, y = 0, z = sum_z / #points}
        
        return center
end

function ContainsThemAll(circle, points)
        local radius_sqr = circle.radius*circle.radius
        local contains_them_all = true
        local i = 1
        
        while contains_them_all and i <= #points do
                contains_them_all = GetDistanceSqr(points[i], circle.center) <= radius_sqr
                i = i + 1
        end
        
        return contains_them_all
end

-- The first element (which is gonna be main_target) is untouchable.
function FarthestFromPositionIndex(points, position)
        local index = 2
        local actual_dist_sqr
        local max_dist_sqr = GetDistanceSqr(points[index], position)
        
        for i = 3, #points do
                actual_dist_sqr = GetDistanceSqr(points[i], position)
                if actual_dist_sqr > max_dist_sqr then
                        index = i
                        max_dist_sqr = actual_dist_sqr
                end
        end
        
        return index
end

function RemoveWorst(targets, position)
        local worst_target = FarthestFromPositionIndex(targets, position)
        
        table.remove(targets, worst_target)
        
        return targets
end

function GetInitialTargets(radius, main_target)
        local targets = {main_target}
        local diameter_sqr = 4 * radius * radius
        
        for i=1, heroManager.iCount do
                target = heroManager:GetHero(i)
                if target.networkID ~= main_target.networkID and ValidTarget(target) and GetDistanceSqr(main_target, target) < diameter_sqr then table.insert(targets, target) end
        end
        
        return targets
end

--
-- VPred AoE Skillshot Position from dienofail
--
function GetVPredictedInitialTargets(radius, main_target, delay, speed)
    --if VIP_USER and not vip_target_predictor then vip_target_predictor = TargetPredictionVIP(nil, nil, delay/1000) end
    local predicted_main_target = VP:GetPredictedPos(main_target, delay, speed, myHero)
    local predicted_targets = {predicted_main_target}
    local diameter_sqr = 4 * radius * radius
   
    for i=1, heroManager.iCount do
            target = heroManager:GetHero(i)
            if ValidTarget(target) then
                    predicted_target = VP:GetPredictedPos(target, delay, speed, myHero)
                    if target.networkID ~= main_target.networkID and GetDistanceSqr(predicted_main_target, predicted_target) < diameter_sqr then table.insert(predicted_targets, predicted_target) end
            end
    end
   
    return predicted_targets
end

function GetPredictedInitialTargets(radius, main_target, delay)
        if VIP_USER and not vip_target_predictor then vip_target_predictor = TargetPredictionVIP(nil, nil, delay/1000) end
        local predicted_main_target = VIP_USER and vip_target_predictor:GetPrediction(main_target) or GetPredictionPos(main_target, delay)
        local predicted_targets = {predicted_main_target}
        local diameter_sqr = 4 * radius * radius
        
        for i=1, heroManager.iCount do
                target = heroManager:GetHero(i)
                if ValidTarget(target) then
                        predicted_target = VIP_USER and vip_target_predictor:GetPrediction(target) or GetPredictionPos(target, delay)
                        if target.networkID ~= main_target.networkID and GetDistanceSqr(predicted_main_target, predicted_target) < diameter_sqr then table.insert(predicted_targets, predicted_target) end
                end
        end
        
        return predicted_targets
end

-- I don't need range since main_target is gonna be close enough. You can add it if you do.
function GetAoESpellPosition(radius, main_target, delay, speed)
	--[[ vpredinstalled then
        local targets = delay and GetVPredictedInitialTargets(radius, main_target, delay, speed) or GetInitialTargets(radius, main_target)
    end
    if not vpredinstalled then]]
    	local targets = delay and GetPredictedInitialTargets(radius, main_target, delay) or GetInitialTargets(radius, main_target)
    --end
	local position = GetCenter(targets)
	local best_pos_found = true
	local circle = Circle(position, radius)
	circle.center = position
	if #targets > 2 then best_pos_found = ContainsThemAll(circle, targets) end
	 
	while not best_pos_found do
		targets = RemoveWorst(targets, position)
		position = GetCenter(targets)
		circle.center = position
		best_pos_found = ContainsThemAll(circle, targets)
	end
	return position, #targets
end
