<?php exit() ?>--by 16hex16 105.236.17.194
local qRange, eRange, rRange = 475, 475, 1300
local knockUpReady, dashing, damageAmp, qTick = false, false, 1, 0
local towers, KnockedUp = {}, {}

function OnLoad()
	createMenu()
	
	if _G.allowSpells then _G.allowSpells.Yasuo = {_W, _R} end
	
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, qRange, DAMAGE_PHYSICAL, true)
	es = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1300, DAMAGE_PHYSICAL, true)
	enemyMinions = minionManager(MINION_ENEMY, 1300, myHero)
	
	towersUpdate()
	focalPointCheck()
	
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
		if (YasuoConfig.Basic.champsOnly and ts.target and ts.target.type == myHero.type) or (not YasuoConfig.Basic.champsOnly and ts.target) then
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
	
	--> Ult Cast
	for i, knockedUp in pairs(KnockedUp) do
		if YasuoConfig.Basic.basicCombo and YasuoConfig.Basic.useR then
			if ts.target and knockedUp.champ == ts.target then
				CastSpell(_R)
			elseif es.target and knockedUp.champ == es.target then
				CastSpell(_R)
			elseif not ts.target or not es.target then
				CastSpell(_R)
			end
		elseif YasuoConfig.Auto.autoR and countKnockedUp(knockedUp.champ, 400) >= YasuoConfig.Auto.autoREnemies then
			CastSpell(_R)
		elseif YasuoConfig.Kill.rKS and knockedUp.champ.health < getDamage(knockedUp.champ) then
			CastSpell(_R)
		end
	end
	
	--> Harass
	if YasuoConfig.Basic.basicHarass and not knockUpReady then
		if ts.target and ts.target.type == myHero.type then
			if dashOut then
				eDashOut(ts.target)
			elseif GetDistance(ts.target) <= qRange then 
				castQ(ts.target)
			elseif GetDistance(ts.target) <= 1200 then
				eGapClose(ts.target)
			end
		end
	end

	--> Auto
	if not YasuoConfig.Basic.basicCombo and not YasuoConfig.Basic.basicHarass then
		if QREADY and YasuoConfig.Auto.autoQ then
			if ts.target and ts.target.type == myHero.type then castQ(ts.target) end
		end
		
		if QREADY and YasuoConfig.Auto.qStacker and not knockUpReady then
			if qTick - GetGameTimer() <= 1.5 then
				local closestPoint, closestUnit = findClosestPoint(myHero)
				if closestUnit and GetDistance(closestUnit) <= qRange then castQ(closestUnit) end
			end
		end
	end
	
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if enemy and not enemy.dead and enemy.visible then
			--> KillSteal
			if YasuoConfig.Kill.eKS then
				if not TargetHaveBuff("YasuoDashWrapper", enemy) and GetDistance(enemy) <= 900 then 
					eKS(enemy)
				end
			end
			--> Anti Q Waste
			if QREADY and knockUpReady and GetDistance(enemy) <= qRange then
				if qTick - GetGameTimer() <= 1.5 then castQ(enemy) end
			end
		end
	end
	
	--> Farm
	if YasuoConfig.Farm.eFarm and (not YasuoConfig.Basic.basicCombo and not YasuoConfig.Basic.basicHarass) then eFarm() end
end

function OnDraw()
	local qTimer = round(qTick - GetGameTimer(), 0)
	if qTimer > 0 then PrintFloatText(myHero, 0, tostring(qTimer)) end
end

function OnDash(unit, dash)
	if (unit.isMe and not knockUpReady) and (YasuoConfig.Basic.basicCombo or YasuoConfig.Basic.basicHarass) then
		if ts.target and GetDistance(ts.target, dash.endPos) < 325 then 
			CastSpell(_Q, ts.target.x, ts.target.z) 
			if YasuoConfig.Basic.basicHarass then dashOut = true end
		end
	end
end
 
function OnAnimation(unit, animationName)
	if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end

function OnGainBuff(unit, buff)
	if unit.isMe then
		if buff.name == "yasuoq" then qTick = GetGameTimer() + 10 end
		if buff.name == "yasuoq3w" then knockUpReady = true qTick = GetGameTimer() + 10 end
		if buff.name == "yasuodashscalar" then damageAmp = 1 + buff.stack*0.25 end
	end
	if unit.team ~= myHero.team and unit.type == myHero.type and (buff.type == 29 or buff.type == 30) then
		table.insert(KnockedUp, {champ = unit, endTick = buff.endT, duration = buff.duration+1})
	end
end

function OnLoseBuff(unit, buff)
	if unit.isMe then
		if buff.name == "yasuoq" then qTick = 0 end
		if buff.name == "yasuoq3w" then knockUpReady = false qTick = 0 end
	end
end

function OnUpdateBuff(unit, buff)
	if unit.isMe and buff.name == "yasuodashscalar" then damageAmp = 1 + buff.stack*0.25 end
end

function castQ(target)
	local qPred = (knockUpReady and qp2:GetPrediction(target)) or qp:GetPrediction(target)
	if qPred and GetDistance(qPred) <= qRange then 
		CastSpell(_Q, qPred.x, qPred.z) 
		if YasuoConfig.Basic.basicHarass then dashOut = true end
	end
end

function eKS(target)
	local eDmg = getDamageE(target)
	if target.health < eDmg then
		if GetDistance(target) <= eRange then 
			--local dashPos = myHero + (Vector(target) - myHero):normalized()*eRange
			CastSpell(_E, target)--if not inTurretRange(dashPos) then CastSpell(_E, target) end
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

function eGapClose(target)
	local closestPoint, closestUnit = findClosestPoint(target)
	if closestPoint and not inTurretRange(closestPoint) and GetDistance(closestPoint, target) < GetDistance(target) and GetDistance(closestPoint, target) < 425 then 
		CastSpell(_E, closestUnit)
	end
end

function eDashOut(target)
	local closestPoint, closestUnit = findClosestPoint(focalPoint)
	if closestPoint and not inTurretRange(closestPoint) then 
		CastSpell(_E, closestUnit)
		dashOut = false
	end
end

function Checks()
	if not tm:isReady() then return end
	cleanKnockedUp()
	if not YasuoConfig.Basic.basicHarass then dashOut = false end
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

function createMenu()
	YasuoConfig = scriptConfig("Yasuo - The Unforgiven", "Yasuo_The_Unforgiven")
	--> Basic
	YasuoConfig:addSubMenu("Basic Settings", "Basic")
	YasuoConfig.Basic:addParam("basicCombo", "Basic - Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	YasuoConfig.Basic:addParam("basicHarass", "Basic - Harass", SCRIPT_PARAM_ONKEYDOWN, false, 88)
	YasuoConfig.Basic:addParam("useE", "Use - Sweeping Blade", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig.Basic:addParam("useR", "Use - Last Breath", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig.Basic:addParam("ignoreTowers", "Ignore - Turrets", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Basic:addParam("champsOnly", "Combo - Champs Only", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig.Basic:addParam("sep", "Ignores turrets in Combo Basic/Kill Combo", SCRIPT_PARAM_INFO, "")
	
	--> Auto
	YasuoConfig:addSubMenu("Auto Settings", "Auto")
	YasuoConfig.Auto:addParam("autoQ", "Steel Tempest - Auto Poke[T]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("T"))
	YasuoConfig.Auto:addParam("qStacker", "Steel Tempest - Auto Stack[8]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("8"))
	YasuoConfig.Auto:addParam("autoR", "Last Breath - Auto Dash[9]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("9"))
	YasuoConfig.Auto:addParam("autoREnemies", "Last Breath - Min Knocked Up",SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
	YasuoConfig.Auto:permaShow("autoQ")
	YasuoConfig.Auto:permaShow("qStacker")
	
	--> Kill
	YasuoConfig:addSubMenu("Kill Settings", "Kill")
	YasuoConfig.Kill:addParam("eKS", "Kill - Sweeping Blade", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig.Kill:addParam("killGapClose", "Kill - Use Minions for Range", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig.Kill:addParam("rKS", "Kill - Last Breath[N]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("N"))
	YasuoConfig.Kill:permaShow("rKS")
	
	--> Farm
	YasuoConfig:addSubMenu("Farm Settings", "Farm")
	YasuoConfig.Farm:addParam("eFarm", "Farm - Sweeping Blade[G]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("G"))
	YasuoConfig.Farm:permaShow("eFarm")
	YasuoConfig.Farm.eFarm = false
end

function cleanKnockedUp()
	local i = 1
	while i <= #KnockedUp do
		knockedUp = KnockedUp[i]
		if GetGameTimer() > knockedUp.endTick then
			table.remove(KnockedUp, i)
		else
			i = i + 1
		end
	end
end

function findClosestPoint(target)
	local closestPoint = nil
	local currentPoint = nil
	for i, minion in pairs(enemyMinions.objects) do
		if minion and minion.valid and not minion.dead and GetDistance(minion) <= eRange and not TargetHaveBuff("YasuoDashWrapper", minion) then
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
		if enemy and not enemy.dead and enemy.visible and GetDistance(enemy) <= eRange and not TargetHaveBuff("YasuoDashWrapper", enemy) then
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

function getDamage(target)
	local aDmg = getDmg("AD", target, myHero)*2
	local qDmg = (QREADY and getDmg("Q", target, myHero)) or 0
	local eDmg = (EREADY and getDamageE(target)) or 0
	local rDmg = (RREADY and getDmg("R", target, myHero)) or 0
	local bladeDmg = (GetInventorySlotItem(3153) and getDmg("RUINEDKING", target, myHero)) or 0
	local cutlassDmg = (GetInventorySlotItem(3144) and getDmg("BWC", target, myHero)) or 0
	local tiamatDmg = (GetInventorySlotItem(3077) and getDmg("TIAMAT", target, myHero)) or 0
	local hydraDmg = (GetInventorySlotItem(3074) and getDmg("HYDRA", target, myHero)) or 0
	local damageAmp = (RREADY and 1+((5 + myHero:GetSpellData(_R).level*15)/100)) or 1
	return aDmg + (qDmg+ eDmg + rDmg) + (bladeDmg + cutlassDmg + tiamatDmg + hydraDmg)
end

function getDamageE(target)
	return myHero:CalcMagicDamage(target, 50+(20*myHero:GetSpellData(_E).level)*damageAmp + (myHero.ap*0.6))
end

function focalPointCheck()
	if player.team == TEAM_RED then
		focalPoint = Point(13936.64, 14174.86) -- y = 184.97, RED BASE
	else
		focalPoint = Point(28.58, 267.16) -- y = 184.62, BLUE BASE
	end
end

function countKnockedUp(point, range)
	local ChampCount = 0
	for i, knockedUp in ipairs(KnockedUp) do
		local champ = knockedUp.champ
		if champ and not champ.dead and GetDistance(champ, point) <= range then
			ChampCount = ChampCount + 1
		end
	end		
	return ChampCount
end

function round(num, idp)
  local mult = 10^(idp or 0)
  return math.floor(num * mult + 0.5) / mult
end

--> Tower Code
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
	if YasuoConfig.Basic.basicCombo then 
		if ts.target and ts.target.type == myHero.type then
			if ts.target.health < getDamage(ts.target) then return false end
		end
	end

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