<?php exit() ?>--by iuser99 98.119.108.50
if myHero.charName ~= "Annie" then return end

--libs
require "AoE_Skillshot_Position"

--main program
function OnLoad()
	PrintChat(">> Challenger Annie loaded")
	initiateVariables()
	GetIgnite()
	CreateMenu()
end

function OnTick()
	OnTickChecks()
	Combo()
	HarrassEnemy()
	AutoFarmQ()
end

function OnCreateObj(object)
	if object.name == "StunReady.troy" then
		stunReady = true
	end
	if object.name == "BearFire_foot.troy" then
		existTibbers = true
	end
end

function OnDeleteObj(object)
	if object.name == "StunReady.troy" then
		stunReady = false
	end
	if object.name == "BearFire_foot.troy" then
		existTibbers = false
	end
end

function OnDraw()
	if Menu.drawCircles then
		if target ~= nil and ValidTarget(target) then
			DrawCircle2(target.x, target.y, target.z, 100, ARGB(255,255,0,0))
		end
		DrawCircle2(myHero.x, myHero.y, myHero.z, qRange, ARGB(255, 125, 0, 225))
		DrawCircle2(myHero.x, myHero.y, myHero.z, qRange + fRange, ARGB(255, 125, 0, 225))
	end
	if Menu.drawComboKiller then
		DrawComboKiller()
	end
end

-- OnLoad() functions
function initiateVariables()
	--skill information
	qRange = 625
	rRange = 650 --evtl 600 wegent libary, dann aber alle rRange im script überprüfen
	rRadius = 230
	fRange = 400
	dfgRange = 750
	igniteRange = 600
	tibbersTargetRange = 2000
	--combos
	mainComboDfg = {"DFG", "R", "W", "Q", "IGNITE"}
	mainCombo = {"R", "W", "Q", "IGNITE"}
	mainComboWithoutIgniteDfg = {"DFG", "R", "W", "Q"}
	mainComboWithoutIgnite = {"R", "W", "Q"}
	normalComboDfg = {"DFG", "W", "Q"}
	normalCombo = {"W", "Q"}
	rCombo = {"R"}
	eCombo = {"E"}
	qCombo = {"Q"}
	igniteCombo = {"IGNITE"}
	dfgCombo = {"DFG"}
	rIgniteCombo = {"R", "IGNITE"}
	dfgRCombo = {"DFG", "R"}
	dfgRIgniteCombo = {"DFG", "R", "IGNITE"}
	--misc
	enemyMinions = minionManager(MINION_ENEMY, qRange, player, MINION_SORT_HEALTH_ASC)
end

function GetIgnite()
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignite = SUMMONER_2
	else 
		ignite = nil
	end
end

function CreateMenu()
	Menu = scriptConfig("Challenger Annie", "Challenger Annie")
	Menu:addParam("combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Menu:addParam("move", "Move to Mouse", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("harass", "Harass Enemy", SCRIPT_PARAM_ONKEYDOWN, false, 86)
	Menu:addParam("autoFarmQ", "Auto farm Q", SCRIPT_PARAM_ONKEYTOGGLE, false, 88)
	Menu:addParam("stopFarmWhenStun", "Stop farm when stun", SCRIPT_PARAM_ONKEYTOGGLE, true, 74)
	Menu:addParam("stackWithE", "Stack with E", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("ultOnlyWithStun", "Ult only with stun", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("minTargetsUlt", "Min ult stun targets", SCRIPT_PARAM_LIST, 2, {">0 targets", ">1 targets", ">2 targets", ">3 targets", ">4 targets" })
	Menu:addParam("drawCircles", "Draw Circles", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("drawComboKiller", "Draw Combo Killer", SCRIPT_PARAM_ONOFF, true)
	Menu:permaShow("autoFarmQ")
	Menu:permaShow("stopFarmWhenStun")
	Menu:permaShow("stackWithE")
end

-- OnTick() functions
function OnTickChecks()
	qReady = (myHero:CanUseSpell(_Q) == READY) or (myHero:GetSpellData(_Q).currentCd < 1)
	wReady = (myHero:CanUseSpell(_W) == READY) or (myHero:GetSpellData(_W).currentCd < 1)
	eReady = (myHero:CanUseSpell(_E) == READY) or (myHero:GetSpellData(_E).currentCd < 1)
	rReady = (myHero:CanUseSpell(_R) == READY) or (myHero:GetSpellData(_R).currentCd < 1)
	dfgSlot = GetInventorySlotItem(3128)
	dfgReady = (dfgSlot ~= nil and myHero:CanUseSpell(dfgSlot) == READY)
	igniteReady = ignite and myHero:CanUseSpell(ignite) == READY or false
	target = GetBestTarget(rRange)
	tibbersTarget = GetBestTarget(tibbersTargetRange)
	enemyMinions:update()
end

function Combo()
	local spellPos = nil
	local targets = nil
	if Menu.combo and target then
		--if stun 3 or more
		if stunReady and rReady and GetDistance(target) <= rRange and TargetsInUlt(GetAoESpellPosition(rRadius, target), rRadius) >= Menu.minTargetsUlt then
			CastCombo(dfgRCombo, target)
		end
		--combo
		if wReady and qReady and GetComboDamage(normalCombo, target) >= target.health then
			CastCombo(normalCombo, target)
		elseif dfgReady and wReady and qReady and GetComboDamage(normalComboDfg, target, true) >= target.health then
			CastCombo(normalComboDfg, target)
		elseif (not Menu.ultOnlyWithStun or stunReady) and wReady and qReady and rReady and GetComboDamage(mainComboWithoutIgnite, target) >= target.health then
			CastCombo(mainComboWithoutIgnite, target)
		elseif (not Menu.ultOnlyWithStun or stunReady) and wReady and qReady and rReady and dfgReady and GetComboDamage(mainComboWithoutIgniteDfg, target, true) >= target.health then
			CastCombo(mainComboWithoutIgniteDfg, target)
		elseif (not Menu.ultOnlyWithStun or stunReady) and wReady and qReady and rReady and igniteReady and GetComboDamage(mainCombo, target) >= target.health then
			CastCombo(mainCombo, target)
		elseif (not Menu.ultOnlyWithStun or stunReady) and wReady and qReady and rReady and igniteReady and dfgReady and GetComboDamage(mainComboDfg, target, true) >= target.health then
			CastCombo(mainComboDfg, target)
		--go ham anyways if stun and full combo ready
		elseif stunReady and wReady and qReady and rReady and dfgReady then
			CastCombo(mainComboWithoutIgniteDfg, target)
		elseif stunReady and wReady and qReady and rReady then
			CastCombo(mainComboWithoutIgnite, target)
		--else spam W Q
		else
			CastCombo(normalCombo, target)
		end
		--secure kill if possible and if cant combo due to cd
		--dfg
		if dfgReady and GetComboDamage(dfgCombo, target) >= target.health then
			if dfgReady and GetDistance(target) <= dfgRange then
				CastSpell(dfgSlot, target)
			end
		end
		--tibbers
		if rReady and GetComboDamage(rCombo, target) >= target.health then
			CastCombo(rCombo, target)
		end
		--ignite
		if igniteReady and GetComboDamage(igniteCombo, target) >= target.health then
			CastCombo(igniteCombo, target)
		end
		--dfg tibbers
		if rReady and dfgReady and GetComboDamage(dfgRCombo, target, true) >= target.health then
			CastCombo(dfgRCombo, target)
		end
		--dfg ignite		
		if rReady and igniteReady and GetComboDamage(rIgniteCombo, target) >= target.health then
			CastCombo(rIgniteCombo, target)
		end
		--dfg ignite tibbers
		if rReady and igniteReady and dfgReady and GetComboDamage(dfgRIgniteCombo, target, true) >= target.health then
			CastCombo(dfgRIgniteCombo, target)
		end		
	end
	if Menu.combo then
		Menu.autoFarmQ = false
		StackWithE()
		attackWithTibbers()
	end
	if Menu.combo and Menu.move then
			myHero:MoveTo(mousePos.x, mousePos.z)
	end
end

function HarrassEnemy()
	if Menu.harass and target then
		CastCombo(normalCombo, target)
	end
end

function AutoFarmQ()
	if Menu.autoFarmQ and qReady and not (Menu.stopFarmWhenStun and stunReady) then
		for _, minion in pairs(enemyMinions.objects) do
			if minion ~= nil and minion.health <= GetComboDamage(qCombo, minion) then
				CastCombo(qCombo, minion)
			end
		end	
	end
end

--overall functions
function attackWithTibbers()
	if existTibbers and tibbersTarget ~= nil then
		CastSpell(_R, tibbersTarget)
	end
end

function StackWithE()
	if Menu.stackWithE and not stunReady then
		CastCombo(eCombo, target)
	end
end

function GetBestTarget(range)
	local lessToKill = 100
	local lessToKilli = 0
	local target = nil
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if ValidTarget(enemy, range) then
			damageToHero = myHero:CalcMagicDamage(enemy, 200)
			toKill = enemy.health / damageToHero
			if ((toKill < lessToKill) or (lessToKilli == 0)) then
				lessToKill = toKill
				lessToKilli = i
				target = enemy
			end
		end
	end
	if GetTarget() ~= nil and ValidTarget(GetTarget(), range) then
		target = GetTarget()
	end
	
	return target
end

function TargetsInUlt(spellPos, radius)
	local count = 0
        for _, enemy in pairs(GetEnemyHeroes()) do
            if enemy and ValidTarget(enemy) and GetDistance(spellPos, enemy) <= radius then
                count = count + 1
            end
 
        end
    return count
end

function GetComboDamage(combo, target, dfg)
	local totaldamage = 0
	for i, spell in ipairs(combo) do
		local multiplier = 1
		if dfg ~= nil and spell ~= "DFG" and spell ~= "IGNITE" then
			multiplier = 1.2
		end
		totaldamage = totaldamage + getDmg(spell, target, myHero) * multiplier
	end
	return totaldamage
end

function CastCombo(combo, target)
	for i, spell in ipairs(combo) do
		Cast(spell, target)
	end
end

function Cast(spell, target)
	if spell == "DFG" then
		if dfgReady and GetDistance(target) <= rRange then
			CastSpell(dfgSlot, target)
		end
	elseif spell == "Q" then
		if qReady and GetDistance(target) <= qRange then 
			CastSpell(_Q, target)
		end
	elseif spell == "W" then
		if wReady and GetDistance(target) < qRange then
			CastSpell(_W, target.x, target.z)
		end
	elseif spell == "E" then
		if eReady then
			CastSpell(_E)
		end
	elseif spell == "R" then
		if rReady and GetDistance(target) <= rRange then
			spellPos = GetAoESpellPosition(rRadius, target)
			CastSpell(_R, spellPos.x, spellPos.z)
		end
	elseif spell == "IGNITE" then
		if igniteReady and GetDistance(target) <= igniteRange then
			CastSpell(ignite, target)
		end
	end
end

-- OnDraw() functions
function DrawComboKiller()
	for _, enemy in pairs(GetEnemyHeroes()) do
		if enemy and ValidTarget(enemy) then
			local killerCombo = nil
			local position = GetHPBarPos(enemy)
			if GetComboDamage(normalCombo, enemy) >= enemy.health then
				killerCombo = "W + Q"
			elseif dfgReady and GetComboDamage(normalComboDfg, enemy, true) >= enemy.health then
				killerCombo = "DFG + W + Q"
			elseif GetComboDamage(mainComboWithoutIgnite, enemy) >= enemy.health and myHero:GetSpellData(_R).level > 0 then
				killerCombo = "R + W + Q"
			elseif dfgReady and GetComboDamage(mainComboWithoutIgniteDfg, enemy, true) >= enemy.health then
				killerCombo = "DFG + R + W + Q"
			elseif igniteReady and GetComboDamage(mainCombo, enemy) >= enemy.health and myHero:GetSpellData(_R).level > 0 then
				killerCombo = "R + W + Q + I"
			elseif igniteReady and dfgReady and GetComboDamage(mainComboDfg, enemy, true) >= enemy.health then
				killerCombo = "DFG + R + W + Q + I"
			elseif dfgSlot ~= nil then
				killerCombo = tostring(math.floor(100*GetComboDamage(mainComboWithoutIgniteDfg, enemy, true)/enemy.health + 0.5))
			else
				killerCombo = tostring(math.floor(100*GetComboDamage(mainComboWithoutIgnite, enemy)/enemy.health + 0.5))
			end
			DrawText(killerCombo, 15, position.x, position.y,  ARGB(255, 0, 255, 0))
		end
	end
end

--copied functions
-- barasia, vadash, viseversa
function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
	radius = radius or 300
	quality = math.max(8,math.floor(180/math.deg((math.asin((chordlength/(2*radius)))))))
	quality = 2 * math.pi / quality
	radius = radius*.92
	local points = {}
	for theta = 0, 2 * math.pi + quality, quality do
		local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
		points[#points + 1] = D3DXVECTOR2(c.x, c.y)
	end
	DrawLines2(points, width or 1, color or 4294967295)
end

function DrawCircle2(x, y, z, radius, color)
	local vPos1 = Vector(x, y, z)
	local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
	local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
	local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
	if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y })  then
		DrawCircleNextLvl(x, y, z, radius, 1, color, 75)	
	end
end

--zikkah
function GetHPBarPos(enemy)
	enemy.barData = GetEnemyBarData()
	local barPos = GetUnitHPBarPos(enemy)
	local barPosOffset = GetUnitHPBarOffset(enemy)
	local barOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
	local barPosPercentageOffset = { x = enemy.barData.PercentageOffset.x, y = enemy.barData.PercentageOffset.y }
	local BarPosOffsetX = 171
	local BarPosOffsetY = 46
	local CorrectionY =  0
	local StartHpPos = 31
	barPos.x = barPos.x + (barPosOffset.x - 0.5 + barPosPercentageOffset.x) * BarPosOffsetX + StartHpPos
	barPos.y = barPos.y + (barPosOffset.y - 0.5 + barPosPercentageOffset.y) * BarPosOffsetY + CorrectionY 					
	local StartPos = Vector(barPos.x , barPos.y, 0)
	local EndPos =  Vector(barPos.x + 108 , barPos.y , 0)
	return Vector(StartPos.x, StartPos.y, 0), Vector(EndPos.x, EndPos.y, 0)
end