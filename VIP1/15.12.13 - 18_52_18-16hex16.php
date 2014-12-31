<?php exit() ?>--by 16hex16 105.236.17.194
local qRange, eRange, rRange = 475, 475, 1300
local knockUpReady, dashing, damageAmp = false, false, 1
local towers, KnockedUp = {}, {}

function OnLoad()
	createMenu()
	
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, qRange, DAMAGE_PHYSICAL, true)
	es = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1300, DAMAGE_PHYSICAL, true)
	enemyMinions = minionManager(MINION_ENEMY, 1300, myHero)
	
	towersUpdate()
	
	Prodiction = ProdictManager.GetInstance()
	qp = Prodiction:AddProdictionObject(_Q, 450, 1800, 0.25, 50)
	qp2 = Prodiction:AddProdictionObject(_Q, 900, 1200, 0.25, 50)
	
	PrintChat("<font color='#CCCCCC'> >> Yasuo - The Unforgiven loaded! <<</font>")
end

class 'TickManager'
 
function TickManager:__init(ticksPerSecond)
	self.TPS = ticksPerSecond
	self.lastClock = 0
	self.currentClock = 0
end
 
function TickManager:__type()
	return "TickManager"
end
 
function TickManager:setTPS(ticksPerSecond)
	self.TPS = ticksPerSecond
end
 
function TickManager:getTPS(ticksPerSecond)
	return self.TPS
end
 
function TickManager:isReady()
	self.currentClock = os.clock()
	if self.currentClock < self.lastClock + (1 / self.TPS) then return false end
	self.lastClock = self.currentClock
	return true
end

local tm = TickManager(20)

function OnTick()
	Checks()

	--> Combo
	if YasuoConfig.Basic.basicCombo then
		if ts.target and ts.target.type == myHero.type then
			if QREADY then castQ(ts.target) end
			if YasuoConfig.Basic.useE and EREADY then
				if GetDistance(ts.target) <= eRange and not TargetHaveBuff("YasuoDashWrapper", ts.target) then
					local dashPos = myHero + (Vector(ts.target) - myHero):normalized()*eRange
					local eDmg = getDamageE(ts.target)
					if (GetDistance(ts.target) > 250 and not inTurretRange(dashPos)) or ts.target.health < eDmg then 
						CastSpell(_E, ts.target)
					end
				elseif GetDistance(ts.target) <= 1200 then
					eGapClose(ts.target)
				end
			end
		elseif YasuoConfig.Basic.useE and EREADY and es.target and es.target.type == myHero.type and GetDistance(es.target) <= 1200 then
			eGapClose(es.target)
		end
	end
	
	--> Harass
	if YasuoConfig.Basic.basicHarass then
		--E Q E Combo
	end
	
	--> Auto
	if QREADY and YasuoConfig.Auto.autoQ then
		if ts.target and ts.target.type == myHero.type then castQ(ts.target) end
	end
	if YasuoConfig.Auto.qStacker then
		-- Q as buff is running low(10sec buff if hit)
	end
	
	--> KillSteal
	if YasuoConfig.Kill.eKS then
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and enemy.visible then
				if not TargetHaveBuff("YasuoDashWrapper", enemy) and GetDistance(enemy) <= 900 then 
					eKS(enemy)
				end
			end
		end
	end
	
	--> Farm
	if YasuoConfig.Farm.eFarm and (not YasuoConfig.Basic.basicCombo and not YasuoConfig.Basic.basicHarass) then eFarm() end
end
--[[
function OnDraw()
	if ts.target then
		DrawCircle(ts.target.x, ts.target.y, ts.target.z, 50, 0x0000FF)
		DrawCircle(ts.target.x, ts.target.y, ts.target.z, 55, 0x0000FF)
		DrawCircle(ts.target.x, ts.target.y, ts.target.z, 60, 0x0000FF)
		DrawCircle(ts.target.x, ts.target.y, ts.target.z, 65, 0x0000FF)
		DrawCircle(ts.target.x, ts.target.y, ts.target.z, 70, 0x0000FF)
		local qPred = (knockUpReady and qp2:GetPrediction(ts.target)) or qp:GetPrediction(ts.target)
		local wPred, closestMinion = findClosestPoint(ts.target)
		if qPred then
			DrawCircle(qPred.x, qPred.y, qPred.z, 50, 0xFF0000)
			DrawCircle(qPred.x, qPred.y, qPred.z, 55, 0xFF0000)
			DrawCircle(qPred.x, qPred.y, qPred.z, 60, 0xFF0000)
			DrawCircle(qPred.x, qPred.y, qPred.z, 65, 0xFF0000)
			DrawCircle(qPred.x, qPred.y, qPred.z, 70, 0xFF0000)
		end
		if wPred then
			DrawCircle(wPred.x, wPred.y, wPred.z, 50, 0x00FF00)
			DrawCircle(wPred.x, wPred.y, wPred.z, 55, 0x00FF00)
			DrawCircle(wPred.x, wPred.y, wPred.z, 60, 0x00FF00)
			DrawCircle(wPred.x, wPred.y, wPred.z, 65, 0x00FF00)
			DrawCircle(wPred.x, wPred.y, wPred.z, 70, 0x00FF00)
		end
	end
end
]]
function eGapClose(target)
	local closestPoint, closestUnit = findClosestPoint(target)
	if closestPoint and not inTurretRange(closestPoint) and GetDistance(closestPoint, target) < GetDistance(target) and GetDistance(closestPoint, target) < 425 then 
		CastSpell(_E, closestUnit)
	end
end

function findClosestPoint(target)
	local closestPoint = nil
	local currentPoint = nil
	for i, minion in pairs(enemyMinions.objects) do
		if minion and minion.valid and not minion.dead and GetDistance(minion) <= eRange then
			currentPoint = myHero + (Vector(minion) - myHero):normalized()*eRange
			if closestPoint == nil then
				closestPoint = currentPoint
				closestUnit = minion
			elseif GetDistance(currentPoint, target) < GetDistance(closestPoint, target) then
				closestPoint = currentPoint
				closestUnit = minion
			end
		end
	end
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if enemy and not enemy.dead and enemy.visible and GetDistance(enemy) <= eRange then
			if (ts.target and enemy ~= ts.target) or not ts.target then
				currentPoint = myHero + (Vector(enemy) - myHero):normalized()*eRange
				if closestPoint == nil then
					closestPoint = currentPoint
					closestUnit = enemy
				elseif GetDistance(currentPoint, target) < GetDistance(closestPoint, target) then
					closestPoint = currentPoint
					closestUnit = enemy
				end
			end
		end
	end
	return closestPoint, closestUnit
end

function OnAnimation(unit, animationName)
	if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end

function OnGainBuff(unit, buff)
	if unit.isMe and buff.name == "yasuoq3w" then knockUpReady = true end
	if unit.isMe and buff.name == "yasuodashscalar" then damageAmp = 1 + buff.stack*0.25 end
	if RREADY and GetDistance(unit) < rRange then
		if unit.team ~= myHero.team and unit.type == myHero.type and buff.type == 29 then
			table.insert(KnockedUp, {champ = unit, endTick = buff.endT, duration = buff.duration})
			if YasuoConfig.Auto.autoUlt and #KnockedUp >= YasuoConfig.Auto.autoREnemies then
				CastSpell(_R, unit)
			elseif YasuoConfig.Auto.rKS and unit.health < getDmg("R", unit, myHero) then
				CastSpell(_R, unit)
			elseif YasuoConfig.Basic.basicCombo and YasuoConfig.Basic.useR and ((ts.target and unit == ts.target) or (es.target and unit == es.target)) then
				CastSpell(_R, unit)
			end
		end
	end
end

function OnLoseBuff(unit, buff)
	if unit.isMe and buff.name == "yasuoq3w" then knockUpReady = false end
end

function OnUpdateBuff(unit, buff)
	if unit.isMe and buff.name == "yasuodashscalar" then damageAmp = 1 + buff.stack*0.25 end
end

function cleanKnockedUp()
	local i = 1
	while i <= #KnockedUp do
		knockedUp = KnockedUp[i]
		if GetGameTimer() - knockedUp.endTick > knockedUp.duration then
			table.remove(KnockedUp, i)
		else
			i = i + 1
		end
	end
end

function castQ(target)
	if dashing and GetDistance(target) <= 375 then 
		CastSpell(_Q)
	else
		local qPred = (knockUpReady and qp2:GetPrediction(target)) or qp:GetPrediction(target)
		if qPred and GetDistance(qPred) <= qRange then CastSpell(_Q, qPred.x, qPred.z) end
	end
end

function eKS(target)
	local eDmg = getDamageE(target)
	if target.health < eDmg then
		if GetDistance(target) <= eRange then 
			local dashPos = myHero + (Vector(target) - myHero):normalized()*eRange
			if not inTurretRange(dashPos) then CastSpell(_E, target) end
		elseif YasuoConfig.Kill.killGapClose and GetDistance(target) <= 900 then
			eGapClose(target)
		end
	end
end

function eFarm()
	for i, minion in pairs(enemyMinions.objects) do
		if minion and minion.valid and not minion.dead and GetDistance(minion) <= eRange then
			local dashPos = myHero + (Vector(minion) - myHero):normalized()*eRange
			if minion.health < getDamageE(minion) and not inTurretRange(dashPos) then 
				CastSpell(_E, minion)
				break	
			end
		end
	end
end

function getDamageE(target)
	return myHero:CalcMagicDamage(target, 50+(20*myHero:GetSpellData(_E).level)*damageAmp + (myHero.ap*0.6))
end

function Checks()
	if not tm:isReady() then return end
	cleanKnockedUp()
	
	es:update()
	enemyMinions:update()
	
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)
	
	qRange = (knockUpReady and 900) or 450
	dashing = (lastAnimation == "Spell3" and true) or false
	
	ts.range = qRange + 450
	ts:update()
end

function towersUpdate()
	for i = 1, objManager.iCount, 1 do
		local obj = objManager:getObject(i)
		if obj and obj.type == "obj_AI_Turret" and obj.health > 0 then
			if not string.find(obj.name, "TurretShrine") and obj.team ~= player.team then
				table.insert(towers, obj)
			end
		end
	end
end

function inTurretRange(pos)
	if YasuoConfig.Basic.ignoreTowers then return false end
	local check = false
	for i, tower in ipairs(towers) do
		if tower and (tower.health > 0 or not tower.dead) then
			if GetDistance(tower, pos) <= 890 then check = true end
		else
			table.remove(towers, i)
		end
	end
	return check
end

function createMenu()
	YasuoConfig = scriptConfig("Yasuo - The Unforgiven", "Yasuo_The_Unforgiven")
	--> Basic
	YasuoConfig:addSubMenu("Basic Settings", "Basic")
	YasuoConfig.Basic:addParam("basicCombo", "Basic - Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	--YasuoConfig.Basic:addParam("basicHarass", "Basic - Harass", SCRIPT_PARAM_ONKEYDOWN, false, 88)
	YasuoConfig.Basic:addParam("useE", "Use - Sweeping Blade", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig.Basic:addParam("useR", "Use - Last Breath", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig.Basic:addParam("ignoreTowers", "Ignore - Turrets", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Basic:addParam("sep", "Ignores turrets in Combo Basic/Kill Combo", SCRIPT_PARAM_INFO, "")
	--> Auto
	YasuoConfig:addSubMenu("Auto Settings", "Auto")
	YasuoConfig.Auto:addParam("autoQ", "Steel Tempest - Auto Poke[T]",SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("T"))
	--YasuoConfig.Auto:addParam("qStacker", "Steel Tempest - Auto Stack", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Auto:addParam("autoR", "Last Breath - Auto Dash", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Auto:addParam("autoREnemies", "Last Breath - Min Knocked Up",SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
	--> Kill
	YasuoConfig:addSubMenu("Kill Settings", "Kill")
	YasuoConfig.Kill:addParam("eKS", "Kill - Sweeping Blade", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig.Kill:addParam("killGapClose", "Kill - Use Minions for Range", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Kill:addParam("rKS", "Kill - Last Breath", SCRIPT_PARAM_ONOFF, true)
	--> Farm
	YasuoConfig:addSubMenu("Farm Settings", "Farm")
	YasuoConfig.Farm:addParam("eFarm", "Farm - Sweeping Blade", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("G"))
	YasuoConfig.Farm:permaShow("eFarm")
end