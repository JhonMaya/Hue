<?php exit() ?>--by 16hex16 105.236.17.194
local qRange, eRange, rRange = 475, 475, 1300
local knockUpReady, dashing, damageAmp = false, false, 1

local Prodiction = ProdictManager.GetInstance()

function OnLoad()
	YasuoConfig = scriptConfig("Yasuo - The Unforgiven", "Yasuo_The_Unforgiven")
	YasuoConfig:addParam("scriptActive", "Basic Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	YasuoConfig:addParam("useE", "Use Sweeping Blade", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig:addParam("useR", "Use Last Breath", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig:addParam("eKS", "Kill - Sweeping Blade", SCRIPT_PARAM_ONOFF, true)
	YasuoConfig:addParam("eKSLongRange", "Kill - Long Range(Buggy)", SCRIPT_PARAM_ONOFF, false)
	YasuoConfig:addParam("eFarm", "Farming with Sweeping Blade", SCRIPT_PARAM_ONOFF, false)
	
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, qRange, DAMAGE_PHYSICAL, true)
	es = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1300, DAMAGE_PHYSICAL, true)
	enemyMinions = minionManager(MINION_ENEMY, 1300, myHero)
	
	PrintChat("<font color='#CCCCCC'> >> Yasuo - The Unforgiven loaded! <<</font>")
end

function OnTick()
	Checks()
	if YasuoConfig.scriptActive then
		if ts.target and ts.target.type == myHero.type then
			if QREADY then castQ(ts.target) end
			if YasuoConfig.useE and EREADY and GetDistance(ts.target) <= eRange then
				local eDmg = getDamageE(ts.target)
				if GetDistance(ts.target) > 250 or ts.target.health < eDmg then 
					CastSpell(_E, ts.target)
				end
			end
		elseif YasuoConfig.useE and EREADY and es.target and es.target.type == myHero.type and GetDistance(es.target) <= 1200 then
			for i, minion in pairs(enemyMinions.objects) do
				if minion and minion.valid and not minion.dead and GetDistance(minion) <= eRange then
					local dashPos = myHero + (Vector(minion) - myHero):normalized()*eRange
					if GetDistance(dashPos, es.target) < GetDistance(es.target) then 
						CastSpell(_E, minion) 
					end
				end
			end
		end
	end
	if YasuoConfig.eKS then
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and enemy.visible and GetDistance(enemy) <= 900 then 
				eKS(enemy) 
			end
		end
	end
	if YasuoConfig.eFarm then eFarm() end
end

function OnAnimation(unit, animationName)
	if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end

function OnGainBuff(unit, buff)
	if unit.isMe and buff.name == "yasuoq3w" then knockUpReady = true end
	if unit.isMe and buff.name == "yasuodashscalar" then damageAmp = 1 + buff.stack*0.25 end
	if YasuoConfig.scriptActive and YasuoConfig.useR and RREADY and unit.type == myHero.type and buff.type == 29 then
		if GetDistance(unit) <= rRange then CastSpell(_R) end
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
		local qPred = qp:GetPrediction(target)
		if qPred and GetDistance(qPred) <= qRange then CastSpell(_Q, qPred.x, qPred.z) end
	end
end

function eKS(target)
	local eDmg = getDamageE(target)
	if target.health < eDmg then
		if GetDistance(target) <= eRange then 
			CastSpell(_E, target)
		elseif YasuoConfig.eKSLongRange and GetDistance(target) <= 900 then
			for i, minion in pairs(enemyMinions.objects) do
				if minion and minion.valid and not minion.dead and GetDistance(minion) <= eRange then
					local dashPos = myHero + (Vector(minion) - myHero):normalized()*eRange
					if GetDistance(dashPos, target) < 350 then CastSpell(_E, minion) end
				end
			end
		end
	end
end

function eFarm()
	for i, minion in pairs(enemyMinions.objects) do
		if minion and minion.valid and not minion.dead and GetDistance(minion) <= eRange then
			if minion.health < getDamageE(minion) then CastSpell(_E, minion) end
		end
	end
end

function getDamageE(target)
	return myHero:CalcMagicDamage(target, 50+(20*myHero:GetSpellData(_E).level)*damageAmp + (myHero.ap*0.6))
end

function Checks()
	es:update()
	enemyMinions:update()
	
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)
	
	qRange = (knockUpReady and 900) or 450
	dashing = (lastAnimation == "Spell3" and true) or false
	
	ts.range = qRange + 200
	ts:update()
	
	if knockUpReady then
		qp = Prodiction:AddProdictionObject(_Q, qRange, 1200, 0.25, 50) 
		-- Possibly not correct values for Whirlwind Q
	else
		qp = Prodiction:AddProdictionObject(_Q, qRange, 1800, 0.25, 50) 
		-- Possibly not correct values for Normal Q
	end
end