<?php exit() ?>--by 16hex16 105.236.17.194
local qRange, eRange, rRange = 475, 475, 1300
local knockUpReady, dashing, damageAmp = false, false, 1
local towers = {}

local Prodiction = ProdictManager.GetInstance()
qp = Prodiction:AddProdictionObject(_Q, 450, 1800, 0.25, 50)
qp2 = Prodiction:AddProdictionObject(_Q, 900, 1200, 0.25, 50)

function OnLoad()
	YasuoConfig = scriptConfig("Yasuo - The Unforgiven", "Yasuo_The_Unforgiven")
	YasuoConfig:addParam("scriptActive", "Basic Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	YasuoConfig:addParam("useE", "Use Sweeping Blade", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig:addParam("useR", "Use Last Breath", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig:addParam("eKS", "Kill - Sweeping Blade", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig:addParam("eKSLongRange", "Kill - Long Range(Buggy)", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig:addParam("eFarm", "Farm with Sweeping Blade[G]", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("G"))
	YasuoConfig:addParam("ignoreTowers", "Ignore Turret Checks", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig:permaShow("eFarm")
	
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, qRange, DAMAGE_PHYSICAL, true)
	es = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1300, DAMAGE_PHYSICAL, true)
	enemyMinions = minionManager(MINION_ENEMY, 1300, myHero)
	
	towersUpdate()
	
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
	if YasuoConfig.scriptActive then
		if ts.target and ts.target.type == myHero.type then
			if QREADY then castQ(ts.target) end
			if YasuoConfig.useE and EREADY and GetDistance(ts.target) <= eRange then
				local dashPos = myHero + (Vector(ts.target) - myHero):normalized()*eRange
				local eDmg = getDamageE(ts.target)
				if (GetDistance(ts.target) > 250 and not inTurretRange(dashPos)) or ts.target.health < eDmg then 
					CastSpell(_E, ts.target)
				end
			end
		elseif YasuoConfig.useE and EREADY then
			if es.target and es.target.type == myHero.type and GetDistance(es.target) <= 1200 then
				eGapClose(es.target)
			elseif ts.target and ts.target.type == myHero.type and GetDistance(ts.target) > eRange and GetDistance(ts.target) <= 1200 then
				eGapClose(ts.target)
			end
		end
	end
	if YasuoConfig.eKS then
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and enemy.visible then
				if not TargetHaveBuff("YasuoDashWrapper", enemy) and GetDistance(enemy) <= 900 then 
					eKS(enemy)
				end
			end
		end
	end
	if YasuoConfig.eFarm and not YasuoConfig.scriptActive then eFarm() end
end

function eGapClose(target)
	for i, minion in pairs(enemyMinions.objects) do
		if minion and minion.valid and not minion.dead and GetDistance(minion) <= eRange then
			local dashPos = myHero + (Vector(minion) - myHero):normalized()*eRange
			if not inTurretRange(dashPos) and GetDistance(dashPos, target) < GetDistance(target) then 
				CastSpell(_E, minion) 
			end
		end
	end
end

function OnAnimation(unit, animationName)
	if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end

function OnGainBuff(unit, buff)
	if unit.isMe and buff.name == "yasuoq3w" then knockUpReady = true end
	if unit.isMe and buff.name == "yasuodashscalar" then damageAmp = 1 + buff.stack*0.25 end
	if YasuoConfig.scriptActive and YasuoConfig.useR and RREADY then
		if unit.type == myHero.type and buff.type == 29 then
			if (es.target and unit == es.target) or es.target == nil then
				if GetDistance(unit) <= rRange then CastSpell(_R) end
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
		elseif YasuoConfig.eKSLongRange and GetDistance(target) <= 900 then
			eGapClose(target)
		end
	end
end

function eFarm()
	for i, minion in pairs(enemyMinions.objects) do
		if minion and minion.valid and not minion.dead and GetDistance(minion) <= eRange then
			local dashPos = myHero + (Vector(minion) - myHero):normalized()*eRange
			if minion.health < getDamageE(minion) and not inTurretRange(dashPos) then CastSpell(_E, minion) end
		end
	end
end

function getDamageE(target)
	return myHero:CalcMagicDamage(target, 50+(20*myHero:GetSpellData(_E).level)*damageAmp + (myHero.ap*0.6))
end

function Checks()
	if not tm:isReady() then return end
	es:update()
	enemyMinions:update()
	
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)
	
	qRange = (knockUpReady and 900) or 450
	dashing = (lastAnimation == "Spell3" and true) or false
	
	ts.range = qRange + 200
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
	if YasuoConfig.ignoreTowers then return false end
	local check = false
	for i, tower in ipairs(towers) do
		if tower and (tower.health > 0 or not tower.dead) then
			if math.sqrt((tower.x - pos.x) ^ 2 + (tower.z - pos.z) ^ 2) < 900 then check = true end
		else
			table.remove(towers, i)
		end
	end
	return check
end