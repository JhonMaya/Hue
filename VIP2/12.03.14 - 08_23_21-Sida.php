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


local AutoUpdate = true
local SELF = BOL_PATH.."Scripts\\Common\\SidasAutoCarryPlugin - "..myHero.charName..".lua"
local URL = "http://bolauth.com/xetrok/scripts/SidasAutoCarryPlugin%20-%20Nami.lua"
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
					PrintChat("<font color=\"#81BEF7\" >Xetrok's Nami Autoupdate:</font> <font color=\"#00FF00\">Successfully updated to: v"..Version.." Please press F9 to reload the script</font>")
				else
					PrintChat("<font color=\"#81BEF7\" >Xetrok's Nami Autoupdate:</font> <font color=\"#FF0000\">Error updating to new version (v"..Version..")</font>")
				end
			elseif (Version ~= nil) and (Version == tonumber(version)) then --version we checked matches this version
				PrintChat("<font color=\"#81BEF7\" >Xetrok's Nami Autoupdate:</font> <font color=\"#00FF00\">No updates found, latest version: v"..Version.." </font>")
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
local scriptname = 'nami'

function LoadShit()
PrintChat("<font color='#CCCCCC'> -> Xetrok's Nami - version "..version.." Loaded <- </font>")
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
  PrintChat("<font color='#FF0000'> >> Something went wrong - Nami Plugin <<</font>")
return
 end

end

local QReady, WReady, EReady, RReady = false, false, false, false
local RangeQ, RangeW, RangeE, RangeR = 850, 700, 800, 2750
local QDelay, QWidth = 700, 300
local Target
local allyTable
local enemyTable
local LowestHealth = nil
local HealAmount = nil
local allyTable = table
local allyHealthTable = table
local Vwidth, Vrange, Vspeed, Vdelay = 300, 850, math.huge, 0.6
local wLastTick = 0
local checkAlly = true
local Prodict, ProdictQ
local RPredic = TargetPredictionVIP(RangeR, 1750, 0.5)
AutoCarry.PluginMenu.vpre = nil
AutoCarry.PluginMenu.vpre = nil

function PluginOnLoad()
	CheckAuth()
	loadMain() 
	allyTable = GetAllyHeroes()
	enemyTable = GetEnemyHeroes()
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerExhaust") then exhaust = SUMMONER_1
		elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerExhaust") then exhaust = SUMMONER_2
		else exhaust = nil
	end

	AutoCarry.PluginMenu:addParam("sep", "--> Nami by Xetrok: v"..version.." <--", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("autoexhaust", "Auto exhaust enemies", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("autosilence", "Auto cast Q on major enemy spells", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("gapclose", "Auto cast Q on Gapclosers", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("sep", "----- Combo Settings -----", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("useQ", "Use Q in combo", SCRIPT_PARAM_ONOFF, true)
	if FileExist(SCRIPT_PATH..'Common/VPrediction.lua') then
		AutoCarry.PluginMenu:addParam("vpre", "Use VPrediction for Q", SCRIPT_PARAM_ONOFF, true)
		AutoCarry.PluginMenu:addParam("pro", "Use PROdiction for Q", SCRIPT_PARAM_ONOFF, false)
	end
	if not FileExist(SCRIPT_PATH..'Common/VPrediction.lua') then
		AutoCarry.PluginMenu:addParam("pro", "Use PROdiction for Q", SCRIPT_PARAM_ONOFF, true)
	end
	AutoCarry.PluginMenu:addParam("useW", "Use W in combo", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useE", "Use E in combo", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useR", "Use R in combo", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("minhealth", "Minimum health to priortise heals", SCRIPT_PARAM_SLICE, 70, 0, 100, 0)
	AutoCarry.PluginMenu:addParam("harassW", "Harass with W in combo", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("sep", "----- Harass Settings -----", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("useQH", "Use Q to harass", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useWH", "Use W to harass", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("sep", "----- Draw Settings -----", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("DrawW", "Draw W Range", SCRIPT_PARAM_ONOFF, true)
	
	SkillQ = AutoCarry.Skills:NewSkill(false, _Q, RangeQ, "NamiQ", AutoCarry.SPELL_CIRCLE, 0, false, false, math.huge, QDelay, QWidth, false)
	
	local count = 1
	for i=1, heroManager.iCount do
		currentAlly = heroManager:GetHero(i)
		
		if currentAlly.team == myHero.team then
			allyHealthTable[count] = currentAlly.health
			count = count + 1
		end
	end
	
end

function loadMain()
	if AutoCarry.Skills then IsSACReborn = true else IsSACReborn = false end
	Menu = AutoCarry.PluginMenu
	Carry = AutoCarry.MainMenu
	AutoCarry.Crosshair:SetSkillCrosshairRange(1200)
	if AutoUpdate then
		DelayAction(Update, 10)
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

	if Target ~= nil then
		rPred = RPredic:GetPrediction(Target)
	end
	if Recall then return end
	Checks()
	if AutoCarry.MainMenu.AutoCarry then Combo() end
	if AutoCarry.PluginMenu.autoexhaust and TakingRapidDamage() then Exhaust() end
	end
end

function PluginOnDraw()
	if AutoCarry.PluginMenu.DrawW then
		DrawCircle(myHero.x, myHero.y, myHero.z, RangeW, 0xFFFF00)
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
		if AutoCarry.PluginMenu.vpre then
			if QReady and AutoCarry.PluginMenu.useQ then CastQVpre() end
		end
		if AutoCarry.PluginMenu.pro then
			if QReady and AutoCarry.PluginMenu.useQ then CastQPRO() end
		end
		if WReady and AutoCarry.PluginMenu.useW then CastW() end
		if RReady and AutoCarry.PluginMenu.useR then CastR() end
	end
	if AutoCarry.MainMenu.MixedMode then
		if AutoCarry.PluginMenu.vpre then
			if QReady and AutoCarry.PluginMenu.useQH then CastQVpre() end
		end
		if AutoCarry.PluginMenu.pro then
			if QReady and AutoCarry.PluginMenu.useQH then CastQPRO() end
		end
		if WReady and AutoCarry.PluginMenu.useWH then CastWHarass() end
	end
end

function CastQVpre()
	if Target ~= nil and not Target.dead then
		if not QReady then return end
		local AoEPos = GetVAoESpellPosition(QWidth, Target, QDelay/1000, math.huge)
		if AoEPos and GetDistance(Target) <= RangeQ then
			if CountEnemies(AoEPos, QWidth) > 1 then
				CastSpell(_Q, AoEPos.x, AoEPos.z)
			elseif CountEnemies(AoEPos, QWidth) < 2 then
				CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(Target, QDelay/1000, QWidth, math.huge)
				if HitChance >= 2 and GetDistance(CastPosition) < (RangeQ-70) then
           			CastSpell(_Q, CastPosition.x, CastPosition.z)
				end
			end
		end
	end	
end


function CastQPRO()
	if Target ~= nil and not Target.dead then
		if not QReady then return end
		AoEPos = GetAoESpellPosition(QWidth, Target, QDelay)
		if AoEPos and GetDistance(Target) <= RangeQ then
			if CountEnemies(AoEPos, RangeQ) > 1 then
				CastSpell(_Q, AoEPos.x, AoEPos.z)
			elseif CountEnemies(AoEPos, RangeQ) < 2 then
				SkillQ:Cast(Target)
			end
		end
		
	end	
end

function CastWHarass()
	if not WReady then return end
	if ValidTarget(Target) then
		CastSpell(_W, Target)
	end
end
function CastW()
	if not WReady then return end
	local HealAmount = myHero:GetSpellData(_W).level*30 + myHero.ap*0.30
	local LowestHealth = findLowestHealthAlly()
	-- HEAL
	-- If there is someone on the team with less than 70% health then heal them as a priority without caring for the best utilisation of bounces
	if LowestHealth and LowestHealth.valid and myHero:GetDistance(LowestHealth) <= RangeW and (LowestHealth.health + HealAmount) <= LowestHealth.maxHealth and (LowestHealth.health / LowestHealth.maxHealth) < (AutoCarry.PluginMenu.minhealth/100) then
		CastSpell(_W, LowestHealth)
	end
	if AlliesAbovePercentage(AutoCarry.PluginMenu.minhealth) and WReady then
		-- HEAL
		-- If there is more than 1 enemy near me then find the closest ally champion with the most enemies near them and heal them (2 heal bounces, 1 enemy bounce) 
		nearme = CountEnemyNearPerson(myHero,RangeW)
		if nearme >= 1 and WReady then
			local besttarget = FindClosestAlly2(myHero)
			if besttarget ~= nil then
				local count = 0
				for i=1, heroManager.iCount do
					currentAlly = heroManager:GetHero(i)
					if currentAlly.team == myHero.team and currentAlly.charName ~= myHero.charName and not currentAlly.dead and GetDistance(currentAlly) <= RangeW then
						loopcount = CountEnemyNearPerson(currentAlly, RangeW)
						if loopcount > 0 then
							count = CountEnemyNearPerson(currentAlly, RangeW)
							besttarget = currentAlly
						end
					end
				end
				if count ~= 0 and GetDistance(besttarget) <= RangeW then CastSpell(_W, besttarget) end
			end
		end	
		-- HARASS/HEAL
		-- If there is 1 or more allies near me in range of an enemy, find the ally with the most enemies near them and heal them (1-2 heal heal bounces (depending if other allies are within range of the enemy, 1 enemy bounce)
		if AutoCarry.PluginMenu.harassW then
			countallynearme = CountAllyNearPerson(myHero,RangeW)
			if countallynearme >= 1 then
				local besttarget = FindClosestAlly2(myHero)
				if besttarget ~= nil then
					local count = 0
					for i=1, heroManager.iCount do
						currentAlly = heroManager:GetHero(i)
						if currentAlly.team == myHero.team and currentAlly.charName ~= myHero.charName and not currentAlly.dead and GetDistance(currentAlly) <= RangeW then
							loopcount = CountEnemyNearPerson(currentAlly, RangeW)
							if loopcount > 0 then
								count = CountEnemyNearPerson(currentAlly, RangeW)
								besttarget = currentAlly
							end
						end
					end
					if count ~= 0 and GetDistance(besttarget) <= RangeW then CastSpell(_W, besttarget) end
				end
			end
			-- HARASS MAX DAMAGE
			-- If there are 2 or more enemies near me then W the one selected by SAC. (1 heal, 2 enemy bounces)
			nearme = CountEnemyNearPerson(myHero,RangeW)
			if nearme >= 2 then
				CastSpell(_W, Target)
			end
		end
	end
end

function CastR()
	if not RReady then return end
	if rPred == nil then return end
	closestenemy = FindClosestEnemy(myHero)
	if closestenemy ~= nil then
		totalenemy = CountEnemyNearPerson(closestenemy,500)
		allynearme = CountAllyNearPerson(myHero,(RangeR/2))
		if ValidTarget(Target, 1750) then
			if (GetDistance(closestenemy) < RangeR) and (totalenemy >= 3) and (allynearme >= 2) and (RPredic:GetHitChance(Target) > 0.65) then
					CastSpell(_R, rPred.x, rPred.z)
			end
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
			CastSpell(_Q, unit.x, unit.z)
		end
	end

	--
	-- Idea for auto E came from Klokje's power buffs, had to make my own function to find the closest ally with the most AD, also added my own checks to make sure E doesn't activate when hitting a turret or inhib.
	--
	if myHero:GetDistance(unit) <= RangeE and myHero.team == unit.team then 
		closestad = ClosestAllyMostAD()
		if closestad == nil then return end
 		if closestad == unit and spell.name:find("Attack") and not myHero.dead and not (unit.name:find("Minion_") or unit.name:find("Odin")) and myHero:GetDistance(closestad) <= RangeE and not (spell.target.charName:find("Minion_") or spell.target.charName:find("Odin") or spell.target.charName:find("TT_") or spell.target.charName:find("Turret") or spell.target.charName:find("Inhibitor")) then
   			CastSpell(_E, closestad)
		end
	end

	--
	--Gap close logic taken from Vayne's Mighty Assistant 
	--
	if QReady and AutoCarry.PluginMenu.gapclose then
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
	    if unit.type == 'obj_AI_Hero' and unit.team == TEAM_ENEMY and isAGapcloserUnit[unit.charName] and GetDistance(unit) < 2500 and spell ~= nil then
	        if spell.name == (type(isAGapcloserUnit[unit.charName].spell) == 'number' and unit:GetSpellData(isAGapcloserUnit[unit.charName].spell).name or isAGapcloserUnit[unit.charName].spell) then
	            if spell.target ~= nil or isAGapcloserUnit[unit.charName].spell == 'blindmonkqtwo' or isAGapcloserUnit['Tristana'].spell == _W or isAGapcloserUnit['Leona'].spell == _E or isAGapcloserUnit['Aatrox'].spell == _E or isAGapcloserUnit['Khazix'].spell == _W or isAGapcloserUnit['Leblanc'].spell == _W  then
	        		CastSpell(_Q, spell.endPos.x, spell.endPos.z)
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

function findLowestHealthAlly()
	local LowestHealth = nil
	local currentAlly = nil
	local HealAmount = myHero:GetSpellData(_W).level*30 + myHero.ap*0.30
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
        if myHero.team ~= enemyhero.team and ValidTarget(enemyhero, range) then
            if GetDistance(enemyhero, point) <= range then
                ChampCount = ChampCount + 1
            end
        end
    end            
    return ChampCount
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
			if not currentAlly.dead and currentAlly.totalDamage >= attackdamage and myHero:GetDistance(currentAlly) <= RangeE then 
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

--[[ 
	AoESkillshotPosition 2.0 by monogato // VPred AoESkillshotPosition modified by dienofail
	
	GetAoESpellPosition(radius, main_target, [delay]) returns best position in order to catch as many enemies as possible with your AoE skillshot, making sure you get the main target.
	Note: You can optionally add delay in ms for prediction (VIP if avaliable, normal else).
]]

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

-- I don´t need range since main_target is gonna be close enough. You can add it if you do.
function GetVAoESpellPosition(radius, main_target, delay, speed)
	local targets = delay and GetVPredictedInitialTargets(radius, main_target, delay, speed) or GetInitialTargets(radius, main_target)
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
   
    return position
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
 
-- I don´t need range since main_target is gonna be close enough. You can add it if you do.
function GetAoESpellPosition(radius, main_target, delay)
        local targets = delay and GetPredictedInitialTargets(radius, main_target, delay) or GetInitialTargets(radius, main_target)
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
       
        return position
end