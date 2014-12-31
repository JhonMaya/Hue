<?php exit() ?>--by 16hex16 105.236.17.194
if GetUser() ~= "3rasus" and GetUser() ~= "xzz" and GetUser() ~= "16hex16" and GetUser() ~= "kriksi" and GetUser() ~= "rlntlss" then return end
mapPosition = MapPosition()

local lastAttack, lastWindUpTime, lastAttackCD = 0, 0, 0
local qRange, wRange, eRange, rRange = 900, 600, 290, 625
local shadowW, shadowR, markedTarget = nil, nil, nil
local shadowWBuff, shadowRBuff = false, false
local Prodiction = ProdictManager.GetInstance()
local qPred = Prodiction:AddProdictionObject(_Q, qRange, 1700, 0.25, 50)

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

function OnLoad()
	PrintChat(" >> Zed - Dancing with Shadows")
	Menu()
	Load()
end

local tm = TickManager(20)

function OnTick()
	Checks()
	local Target = myTarget()
	--> Combo/Harass
	if ZedConfig.Combo.comboCombo then
		if Target and Target.type == myHero.type then
			OrbWalk(Target)
			comboMangement(Target)
		else
			moveToCursor()
		end
	end
	if Target and Target.type == myHero.type then
		if ZedConfig.Harass.harassCombo then Harass(Target) end
		--if ZedConfig.Combo.comboCombo then comboMangement(Target) end
	end
	--> Farm Q
	if QREADY and ZedConfig.Farm.farmQ and (not ZedConfig.Combo.comboCombo or ZedConfig.Harass.harassCombo) then
		for _, minion in pairs(enemyMinions.objects) do
			if minion and minion.health < getDmg("Q", minion, myHero) then castQ(minion) end
		end
	end
	--> Auto Q
	if QREADY and ZedConfig.Auto.autoQ and (not ZedConfig.Combo.comboCombo or ZedConfig.Harass.harassCombo) then
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and enemy.visible then castQ(enemy) end
		end
	end
	--> Auto E
	if EREADY and ZedConfig.Auto.autoE and (not ZedConfig.Combo.comboCombo or ZedConfig.Harass.harassCombo) then
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and enemy.visible then castE(enemy) end
		end
	end
	--> Auto Combo
	if ZedConfig.Kill.killCombo and not ZedConfig.Combo.comboCombo then
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and enemy.visible and GetDistance(enemy) <= wRange+qRange then killManagement(enemy) end
		end
	end
end

function OnDraw()
	if myHero.dead or Blacklisted then return end
	
	local Target = myTarget()
	--> Shadow Drawing
	if shadowW then
		if ZedConfig.Draw.drawShadowText and WREADY then DrawText3D("Shadow - W", shadowW.x, shadowW.y, shadowW.z, 20, ARGB(255,0,255,0)) end
		if ZedConfig.Draw.drawShadowRange and WREADY then DrawCircle(shadowW.x, shadowW.y, shadowW.z, 1225, 0x0000FF) end
	end
	if shadowR then
		if ZedConfig.Draw.drawShadowText and RREADY then DrawText3D("Shadow - R", shadowR.x, shadowR.y, shadowR.z, 20, ARGB(255,255,0,0)) end
		if ZedConfig.Draw.drawShadowRange and RREADY then DrawCircle(shadowR.x, shadowR.y, shadowR.z, 1225, 0xFF0000) end
	end
	--> Spell Range Drawing
	if ZedConfig.Draw.drawQ then 
		DrawCircle(myHero.x, myHero.y, myHero.z, qRange, ColorARGB.FromTable(ZedConfig.Draw.drawQColour)) 
		if shadowW then DrawCircle(shadowW.x, shadowW.y, shadowW.z, qRange, ColorARGB.FromTable(ZedConfig.Draw.drawQColour)) end
		if shadowR then DrawCircle(shadowR.x, shadowR.y, shadowR.z, qRange, ColorARGB.FromTable(ZedConfig.Draw.drawQColour)) end
	end
	if ZedConfig.Draw.drawW then 
		DrawCircle(myHero.x, myHero.y, myHero.z, wRange, ColorARGB.FromTable(ZedConfig.Draw.drawWColour)) 
	end
	if ZedConfig.Draw.drawE then 
		DrawCircle(myHero.x, myHero.y, myHero.z, eRange, ColorARGB.FromTable(ZedConfig.Draw.drawEColour)) 
		if shadowW then DrawCircle(shadowW.x, shadowW.y, shadowW.z, eRange, ColorARGB.FromTable(ZedConfig.Draw.drawEColour)) end
		if shadowR then DrawCircle(shadowR.x, shadowR.y, shadowR.z, eRange, ColorARGB.FromTable(ZedConfig.Draw.drawEColour)) end
	end
	if ZedConfig.Draw.drawR then 
		DrawCircle(myHero.x, myHero.y, myHero.z, rRange, ColorARGB.FromTable(ZedConfig.Draw.drawRColour)) 
		if shadowW then DrawCircle(shadowW.x, shadowW.y, shadowW.z, rRange, ColorARGB.FromTable(ZedConfig.Draw.drawRColour)) end
	end
	--> Killable Drawing
	if ZedConfig.Draw.drawText and Target and Target.type == myHero.type then
		local aDmg = getDmg("AD", Target, myHero)
		local pDmg = (Target.health <= Target.maxHealth*0.5 and getDmg("P", Target, myHero)) or 0
		local qDmg = (QREADY and getDmg("Q", Target, myHero)) or 0
		local eDmg = (EREADY and getDmg("E", Target, myHero)) or 0
		local rDmg = (RREADY and getDmg("R", Target, myHero)) or 0
		local iDmg = (IREADY and getDmg("IGNITE", Target, myHero)) or 0
		local attackDamage = aDmg*2 + pDmg
		local bladeDmg = (GetInventorySlotItem(3153) and getDmg("RUINEDKING", Target, myHero)) or 0
		local cutlassDmg = (GetInventorySlotItem(3144) and getDmg("BWC", Target, myHero)) or 0
		local damageAmp = (RREADY and 1+((5 + myHero:GetSpellData(_R).level*15)/100)) or 0
		local maxDamage = (qDmg*1.5 + eDmg*2 + rDmg + iDmg + bladeDmg + cutlassDmg + attackDamage)*damageAmp

		if QREADY and Target.health < qDmg then
			PrintFloatText(Target, 0, "Killable - Q")
		elseif EREADY and Target.health < eDmg then
			PrintFloatText(Target, 0, "Killable - E")
		elseif QREADY and EREADY and Target.health < qDmg+eDmg then
			PrintFloatText(Target, 0, "Killable - Q+E")
		elseif RREADY and Target.health < maxDamage and energyCheck(3) then
			PrintFloatText(Target, 0, "Killable - Everything")
		elseif (not RREADY or shadowR) and IREADY and Target.health < iDmg then
			PrintFloatText(Target, 0, "Killable - Ignite")
		--elseif not energyCheck(1) then
		--	PrintFloatText(Target, 0, "Very low Energy")
		else
			PrintFloatText(Target, 0, "Harass Him")
		end
	end
	--> Debug Drawing
	if ZedConfig.Debug.debugTSRange then DrawCircle(myHero.x, myHero.y, myHero.z, ts.range, 0x0000FF) end
	if ZedConfig.Debug.debugTSTarget and markedTarget then PrintFloatText(markedTarget, 0, "Marked Target") end
	if ZedConfig.Debug.debugTSTarget and Target then DrawCircle(Target.x, Target.y, Target.z, 50, 0xFF0000) end
end

function OnProcessSpell(unit, spell)
	if Blacklisted then return end
	if ZedConfig.Evade.evadeSkills then EvadeOnProcessSpell(unit, spell) end
	if unit.isMe then
		if spell.name:lower():find("attack") then
			lastAttack = GetTickCount() + GetLatency()/2
			lastWindUpTime = spell.windUpTime*1000
			lastAttackCD = spell.animationTime*1000
		end
	end
end

function Harass(target)
	if ZedConfig.Harass.harassUseW then
		if not WREADY or shadowW or myHero:GetSpellData(_W).level < 1 then castQ(target) end
		if WREADY and energyCheck(1) and (QREADY or EREADY) then castW(target) end
		if EREADY then castE(target) end
	else
		if QREADY then castQ(target) end
		if EREADY then castE(target) end
	end
end

function Combo(target)
	if RREADY then castR(target) end
	if not RREADY or shadowR then
		shadowSwap(target)
		UseItems(target)
		if (not WREADY or shadowW or shadowR or myHero:GetSpellData(_W).level < 1) and GetDistance(target) < 225 then castQ(target) end
		if WREADY and GetDistance(target) >= 225 and (energyCheck(1) or (not QREADY and not EREADY)) then castW(target) end
		if EREADY then castE(target) end
	end
	if GetDistance(target) <= trueRange() and timeToShoot() then myHero:Attack(target) end
end

function castQ(target) 
	if QREADY and GetDistance(target) <= qRange then
		qPred:GetPredictionCallBack(target, CastQ, myHero)
	elseif QREADY and shadowW and shadowW.valid and GetDistance(target, shadowW) <= qRange then
		qPred:GetPredictionCallBack(target, CastQ, shadowW)
	elseif QREADY and shadowR and shadowR.valid and GetDistance(target, shadowR) <= qRange then
		qPred:GetPredictionCallBack(target, CastQ, shadowR)
	end
end

function castW(target)
	local bonusRange = 200
	if QREADY then 
		bonusRange = qRange
	elseif EREADY then
		bonusRange = eRange
	end
	if GetDistance(target) <= wRange+bonusRange and myHero:GetSpellData(_W).name == "ZedShadowDash" then
		if not intersectWall(target, wRange) then CastSpell(_W, target.x, target.z) end
	end
end

function castE(target) 
	if GetDistance(target) <= eRange then
		CastSpell(_E)
	elseif shadowW and shadowW.valid and GetDistance(target, shadowW) <= eRange  then
		CastSpell(_E)
	elseif shadowR and shadowR.valid and GetDistance(target, shadowR) <= eRange then
		CastSpell(_E)
	end
end

function castR(target)
	if GetDistance(target) <= rRange and myHero:GetSpellData(_R).name == "zedult" then 
		CastSpell(_R, target)
	end
end

function shadowSwap(target)
	if shadowWBuff and shadowW and shadowW.valid and GetDistance(target, shadowW) < GetDistance(target) and myHero:GetSpellData(_W).name == "zedw2" then
		CastSpell(_W)
	elseif shadowRBuff and shadowR and shadowR.valid and GetDistance(target, shadowR) < GetDistance(target) and myHero:GetSpellData(_R).name == "ZedR2" then
		CastSpell(_R)
	end 
end

function dangerousArea(target)
	if ZedConfig.Kill.killIgnore then
		return false
	elseif (CountEnemies(target, 900) >= ZedConfig.Kill.killEnemies) or (myHero.health < myHero.maxHealth*0.2 and target.health > myHero.health*2.25) then
		return true
	end
	return false
end

function CountEnemies(point, range)
	local ChampCount = 0
	for i, enemy in ipairs(GetEnemyHeroes()) do
		if enemy and not enemy.dead and enemy.visible and GetDistance(enemy, point) <= range then 
			ChampCount = ChampCount + 1 
		end
	end	
	return ChampCount
end

function killManagement(target)
	local pred = GetPredictionPos(target, 160) 
	local aDmg = getDmg("AD", target, myHero)
	local pDmg = (target.health <= target.maxHealth*0.5 and getDmg("P", target, myHero)) or 0
	local qDmg = (QREADY and getDmg("Q", target, myHero)) or 0
	local eDmg = (EREADY and getDmg("E", target, myHero)) or 0
	local rDmg = (RREADY and getDmg("R", target, myHero)) or 0
	local iDmg = (IREADY and getDmg("IGNITE", target, myHero)) or 0
	local bladeDmg = (GetInventorySlotItem(3153) and getDmg("RUINEDKING", target, myHero)) or 0
	local cutlassDmg = (GetInventorySlotItem(3144) and getDmg("BWC", target, myHero)) or 0
	local damageAmp = (RREADY and 1+((5 + myHero:GetSpellData(_R).level*15)/100)) or 0
	local attackDamage = aDmg*2 + pDmg
	local maxDamage = iDmg + (qDmg*1.5 + eDmg*2 + rDmg + bladeDmg + cutlassDmg + attackDamage)*damageAmp
	
	if QREADY and target.health < qDmg then
		if GetDistance(target) <= qRange then
			castQ(target)
		elseif WREADY and energyCheck(2) and GetDistance(target) <= qRange+wRange then
			if WREADY then castW(target) end
			if not WREADY or shadowW then castQ(target) end
		end
	elseif EREADY and target.health < eDmg then
		if GetDistance(target) <= eRange then
			castE(target)
		elseif WREADY and energyCheck(1) and GetDistance(target) <= eRange+wRange then
			if not WREADY or shadowW then castE(target) end
			if WREADY then castW(target) end
		end
	elseif QREADY and EREADY and target.health < qDmg+eDmg then
		if GetDistance(target) <= eRange then
			castE(target)
			castQ(target)
		elseif WREADY and energyCheck(3) and GetDistance(target) <= eRange+wRange then
			if not WREADY or shadowW then castQ(target) end
			if WREADY then castW(target) end
			if not WREADY or shadowW then castE(target) end
		end
	elseif ZedConfig.Kill.killUseR and RREADY and target.health < maxDamage and not dangerousArea(target) and energyCheck(3) then
		if GetDistance(target) < rRange then
			Combo(target)
			if IREADY then CastSpell(ignite, target) end
		elseif shadowW and shadowW.valid and GetDistance(pred, shadowW) <= rRange then
			shadowSwap(target)
		elseif GetDistance(pred) <= rRange+wRange then
			castW(target)
			shadowSwap(target)
		end
	elseif ZedConfig.Kill.killUseI and (not RREADY or shadowR) and IREADY and target.health < iDmg then
		if GetDistance(target) <= 600 then
			if IREADY then CastSpell(ignite, target) end
		elseif shadowW and shadowW.valid and GetDistance(pred, shadowW) <= 600 then
			shadowSwap(target)
		elseif GetDistance(pred) <= wRange+600 then
			castW(target)
			shadowSwap(target)
		end
	end
end

function comboMangement(target)
	local pred = GetPredictionPos(target, 200) 
	local aDmg = getDmg("AD", target, myHero)
	local pDmg = (target.health <= target.maxHealth*0.5 and getDmg("P", target, myHero)) or 0
	local qDmg = (QREADY and getDmg("Q", target, myHero)) or 0
	local eDmg = (EREADY and getDmg("E", target, myHero)) or 0
	local rDmg = (RREADY and getDmg("R", target, myHero)) or 0
	local iDmg = (IREADY and getDmg("IGNITE", target, myHero)) or 0
	local bladeDmg = (GetInventorySlotItem(3153) and getDmg("RUINEDKING", target, myHero)) or 0
	local cutlassDmg = (GetInventorySlotItem(3144) and getDmg("BWC", target, myHero)) or 0
	local damageAmp = (RREADY and 1+((5 + myHero:GetSpellData(_R).level*15)/100)) or 0
	local attackDamage = aDmg*3 + pDmg
	local maxDamage = iDmg + (qDmg*1.5 + eDmg*2 + rDmg + bladeDmg + cutlassDmg + attackDamage)*damageAmp
	
	if QREADY and target.health < qDmg then
		if GetDistance(target) <= qRange then
			castQ(target)
		elseif WREADY and energyCheck(2) and GetDistance(target) <= qRange+wRange then
			if WREADY then castW(target) end
			if not WREADY or shadowW then castQ(target) end
		end
	elseif EREADY and target.health < eDmg then
		if GetDistance(target) <= eRange then
			castE(target)
		elseif WREADY and energyCheck(1) and GetDistance(target) <= eRange+wRange then
			if not WREADY or shadowW then castE(target) end
			if WREADY then castW(target) end
		end
	elseif QREADY and EREADY and target.health < qDmg+eDmg then
		if GetDistance(target) <= eRange then
			castE(target)
			castQ(target)
		elseif WREADY and energyCheck(3) and GetDistance(target) <= eRange+wRange then
			if not WREADY or shadowW then castQ(target) end
			if WREADY then castW(target) end
			if not WREADY or shadowW then castE(target) end
		end
	elseif RREADY and target.health < maxDamage and energyCheck(3) then
		if GetDistance(target) < rRange then
			Combo(target)
			if IREADY then CastSpell(ignite, target) end
		elseif shadowW and shadowW.valid and GetDistance(pred, shadowW) <= rRange then
			shadowSwap(target)
		elseif GetDistance(pred) <= rRange+wRange then
			castW(target)
			shadowSwap(target)
		end
	elseif (not RREADY or shadowR) and IREADY and target.health < iDmg then
		if GetDistance(target) <= 600 then
			if IREADY then CastSpell(ignite, target) end
		elseif shadowW and shadowW.valid and GetDistance(pred, shadowW) <= 600 then
			shadowSwap(target)
		elseif GetDistance(pred) <= wRange+600 then
			castW(target)
			shadowSwap(target)
		end
	else
		if not RREADY or shadowR then UseItems(target) end
		if WREADY and GetDistance(target) >= 225 and (energyCheck(1) or (not QREADY and not EREADY)) then castW(target) end
		shadowSwap(target)
		if not WREADY or shadowW or shadowR or GetDistance(target) < 225 then castQ(target) end
		if EREADY then castE(target) end
	end
end

function Checks()
	if not tm:isReady() or myHero.dead or Blacklisted then return end
	QREADY = ((myHero:CanUseSpell(_Q) == READY and energyCheckReady(Q)) or (myHero:GetSpellData(_Q).level > 0 and myHero:GetSpellData(_Q).currentCd <= 0.4 and energyCheckReady(Q)))
	WREADY = ((myHero:CanUseSpell(_W) == READY and energyCheckReady(W)) or (myHero:GetSpellData(_W).level > 0 and myHero:GetSpellData(_W).currentCd <= 0.4 and energyCheckReady(W)))
	EREADY = ((myHero:CanUseSpell(_E) == READY and energyCheckReady(E)) or (myHero:GetSpellData(_E).level > 0 and myHero:GetSpellData(_E).currentCd <= 0.4 and energyCheckReady(E)))
	RREADY = (myHero:CanUseSpell(_R) == READY or (myHero:GetSpellData(_R).level > 0 and myHero:GetSpellData(_R).currentCd <= 0.4))
	IREADY = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
	
	if QREADY then
		ts.range = qRange + rangeCheck()
	elseif RREADY then
		ts.range = rRange + rangeCheck()
	elseif WREADY then
		ts.range = wRange + rangeCheck()
	elseif EREADY then
		ts.range = eRange + rangeCheck()
	else
		ts.range = 200 + rangeCheck()
	end
	ts:update()
	enemyMinions:update()
end

function myTarget()
	if markedTarget and (markedTarget.dead or not markedTarget.valid) then markTarget = nil end
	if markedTarget and markedTarget.bTargetable and GetDistance(markedTarget) <= ts.range then 
		return markedTarget
	elseif ts.target and GetDistance(ts.target) <= ts.range then
		return ts.target
	end
end

function rangeCheck()
	local extraRange = (WREADY and wRange) or 0
	if (shadowW and WREADY) or (shadowR and RREADY) then 
		if (shadowW and WREADY) and (shadowR and RREADY) then 
			if GetDistance(shadowW) > GetDistance(shadowR) then
				extraRange = extraRange + GetDistance(shadowW)
			else 
				extraRange = extraRange + GetDistance(shadowR)
			end
		elseif shadowW and WREADY then
			extraRange = extraRange + GetDistance(shadowW)
		elseif shadowR and RREADY then
			extraRange = extraRange + GetDistance(shadowR)
		end
	end
	return extraRange
end

function energyCheck(stage)
	local qMana = {75, 70, 65, 60, 55}
	local wMana = {40, 35, 30, 25, 20}
	local eMana = (EREADY and 50) or 0
	local qEnergy = (QREADY and myHero:GetSpellData(_Q).level > 0 and qMana[myHero:GetSpellData(_Q).level]) or 0
	local wEnergy = (WREADY and myHero:GetSpellData(_W).level > 0 and not shadowW and wMana[myHero:GetSpellData(_W).level]) or 0
	local myEnergy = myHero.mana
	if stage == 1 then
		if myEnergy >= eMana + wEnergy then return true end
	elseif stage == 2 then
		if myEnergy >= qEnergy + wEnergy then return true end
	elseif stage == 3 then
		if myEnergy >= eMana + wEnergy + qEnergy then return true end
	end
	return false
end

function energyCheckReady(skill)
	local qMana = {75, 70, 65, 60, 55}
	local wMana = {40, 35, 30, 25, 20}
	local eMana = (EREADY and 50) or 0
	local qEnergy = (myHero:GetSpellData(_Q).level > 0 and qMana[myHero:GetSpellData(_Q).level]) or 0
	local wEnergy = (myHero:GetSpellData(_W).level > 0 and not shadowW and wMana[myHero:GetSpellData(_W).level]) or 0
	local myEnergy = myHero.mana
	if skill == Q then
		if myEnergy >= qEnergy then return true end
	elseif skill == W then
		if myEnergy >= wEnergy then return true end
	elseif skill == E then
		if myEnergy >= eMana then return true end
	end
	return false
end

function findBestTarget()
	local bestTarget = nil
	local currentTarget = nil
	for i, currentTarget in ipairs(GetEnemyHeroes()) do
		if GetDistance(currentTarget) <= rRange and not currentTarget.dead then
			if bestTarget == nil then
				bestTarget = currentTarget
			elseif TS_GetPriority(currentTarget) < TS_GetPriority(bestTarget) then
				bestTarget = currentTarget
			end
		end
	end
	return bestTarget
end

function UseItems(target)
	if GetInventorySlotItem(3153) ~= nil then CastSpell(GetInventorySlotItem(3153), target) end
	if GetInventorySlotItem(3144) ~= nil then CastSpell(GetInventorySlotItem(3144), target) end 
end

function intersectWall(pos, distance)
	local shadowPos = myHero + (Vector(pos) - myHero):normalized()*wRange
	if shadowPos and mapPosition:intersectsWall(Point(shadowPos.x, shadowPos.z)) then return true end
	return false
end

function OrbWalk(target)
	if heroCanHit(target) then
		if timeToShoot() then
			myHero:Attack(target)
		elseif heroCanMove() and GetDistance(target) > 150 then
			moveToCursor()
		end
	else
		myHero:Attack(target)
	end
end

function trueRange()
	return myHero.range + GetDistance(myHero.minBBox)
end

function heroCanMove()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastWindUpTime + 20)
end

function timeToShoot()
	return (GetTickCount() + GetLatency()/2 > lastAttack + lastAttackCD)
end

function heroCanHit(target)
	local predPos = GetPredictionPos(target, 100)
	if predPos and GetDistance(predPos) <= trueRange() then return true end
	return false
end

function moveToCursor()
	if GetDistance(mousePos) > 1 or lastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*500
		myHero:MoveTo(moveToPos.x, moveToPos.z)
	end
end

function OnGainBuff(unit, buff)
	if buff.name == "zedwshadowbuff" and buff.source.isMe then 
		shadowW = unit
	elseif buff.name == "zedwhandler" and buff.source.isMe then 
		shadowWBuff = true
	elseif buff.name == "zedrshadowbuff" and buff.source.isMe then 
		shadowR = unit
	elseif buff.name == "ZedR2" and buff.source.isMe then 
		shadowRBuff = true
	elseif buff.name == "zedulttargetmark" and buff.source.isMe then
		markedTarget = unit
	end
end

function OnLoseBuff(unit, buff)
	if buff.name == "zedwshadowbuff" and unit.networkID == shadowW.networkID then 
		shadowW = nil
	elseif buff.name == "zedwhandler" and unit.isMe then 
		shadowWBuff = false
	elseif buff.name == "zedrshadowbuff" and unit.networkID == shadowR.networkID then 
		shadowR = nil
	elseif buff.name == "ZedR2" and unit.isMe then 
		shadowRBuff = false
	elseif buff.name == "zedulttargetmark" then
		markedTarget = nil
	end 
end 

function OnAnimation(unit, animationName)
	if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end

function Menu()
	ZedConfig = scriptConfig("Zed - Dancing with Shadows", "ZedDancingwithShadows") 
	--> Combo
	ZedConfig:addSubMenu("Combo Settings", "Combo")
	ZedConfig.Combo:addParam("comboCombo", "Combo - Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	--> Harass
	ZedConfig:addSubMenu("Harass Settings", "Harass")
	ZedConfig.Harass:addParam("harassCombo", "Harass - Harass", SCRIPT_PARAM_ONKEYDOWN, false, 88)
	ZedConfig.Harass:addParam("harassUseW", "Harass - Use Shadow", SCRIPT_PARAM_ONOFF, true)
	--> Auto
	ZedConfig:addSubMenu("Auto Settings", "Auto")
	ZedConfig.Auto:addParam("autoE", "Auto - Shadow Slash", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Auto:addParam("autoQ", "Auto - Razor Shuriken", SCRIPT_PARAM_ONOFF, false)
	--> Kill
	ZedConfig:addSubMenu("Kill Settings", "Kill")
	ZedConfig.Kill:addParam("killCombo", "Kill - Auto Combo[T]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("T"))
	ZedConfig.Kill:addParam("killUseR", "Kill - Use Death Mark", SCRIPT_PARAM_ONOFF, true)
	ZedConfig.Kill:addParam("killUseI", "Kill - Use Ignite", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Kill:addParam("killEnemies", "Kill - Enemy Danger Limit",SCRIPT_PARAM_SLICE, 3, 0, 5, 0)
	ZedConfig.Kill:addParam("killIgnore", "Kill - Ignore Danger[H]",SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("H"))
	ZedConfig.Kill:permaShow("killCombo")
	ZedConfig.Kill:permaShow("killIgnore")
	--> Farm
	ZedConfig:addSubMenu("Farm Settings", "Farm")
	ZedConfig.Farm:addParam("farmQ", "Farm - Razor Shuriken[G]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("G"))
	ZedConfig.Farm:permaShow("farmQ")
	--> Evade
	ZedConfig:addSubMenu("Evade Settings", "Evade")
	ZedConfig.Evade:addParam("evadeSkills", "Evade - Danger", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Evade:addParam("evadeDeathMark", "Evade - Using Death Mark", SCRIPT_PARAM_ONOFF, false)
	--> Draw
	ZedConfig:addSubMenu("Draw Settings", "Draw")
	ZedConfig.Draw:addParam("drawShadowText", "Draw - Shadow Text", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawShadowRange", "Draw - Shadow Range", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawText", "Draw - Killable Text", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawQ", "Draw - Razor Shuriken", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawQColour", "Draw - Q Colour", SCRIPT_PARAM_COLOR, {0, 255, 0, 255})
	ZedConfig.Draw:addParam("drawW", "Draw - Living Shadow", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawWColour", "Draw - W Colour", SCRIPT_PARAM_COLOR, {0, 255, 255, 255})
	ZedConfig.Draw:addParam("drawE", "Draw - Shadow Slash", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawEColour", "Draw - E Colour", SCRIPT_PARAM_COLOR, {255, 255, 0, 255})
	ZedConfig.Draw:addParam("drawR", "Draw - Death Mark", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawRColour", "Draw - R Colour", SCRIPT_PARAM_COLOR, {255, 0, 0, 255})
	--> Debug
	ZedConfig:addSubMenu("Debug Menu", "Debug")
	ZedConfig.Debug:addParam("debugTSRange", "Debug - TS Range", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Debug:addParam("debugTSTarget", "Debug - TS Target", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Debug:addParam("debugEvadeText", "Debug - Evade Text", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Debug:addParam("debugProdictionText", "Debug - Prodiction Text", SCRIPT_PARAM_ONOFF, false)
end

function Load()
	Blacklisted = false
	GetAsyncWebResult("boldevs.com","blacklist/blacklist.php", checkblacklist)
	
	for i, hero in ipairs(GetEnemyHeroes()) do
		qPred:GetPredictionOnDash(hero, OnDashFunc)
		qPred:GetPredictionAfterDash(hero, AfterDashFunc)
		qPred:GetPredictionAfterImmobile(hero, AfterImmobileFunc)
		qPred:GetPredictionOnImmobile(hero, OnImmobileFunc)
	end
	
	ignite = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
	enemyMinions = minionManager(MINION_ENEMY, 2000, myHero)
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, qRange, DAMAGE_PHYSICAL, true)
	ts.name = "Zed"
	ZedConfig:addTS(ts)
	if #GetEnemyHeroes() > 1 then arrangePrioritys(#GetEnemyHeroes()) end
end

function checkblacklist(response)
	if response:find(" " .. GetUser() .. " ") then 
		Blacklisted = true
	return end
	Blacklisted = false     
end

--> Prodiction Functions
function CastQ(unit, pos)
	CastSpell(_Q, pos.x, pos.z)
end

function OnDashFunc(unit, pos, spell)
	CastSpell(_Q, pos.x, pos.z)
	if ZedConfig.Debug.debugProdictionText then PrintChat("OnDashFunc") end
end

function AfterDashFunc(unit, pos, spell)
	CastSpell(_Q, pos.x, pos.z)
	if ZedConfig.Debug.debugProdictionText then PrintChat("AfterDashFunc") end
end

function AfterImmobileFunc(unit, pos, spell)
	CastSpell(_Q, pos.x, pos.z)
	if ZedConfig.Debug.debugProdictionText then PrintChat("AfterImmobileFunc") end
end

function OnImmobileFunc(unit, pos, spell)
	CastSpell(_Q, pos.x, pos.z)
	if ZedConfig.Debug.debugProdictionText then PrintChat("OnImmobileFunc") end
end

local SkillList = {
	["Malphite"] = {charName = "Malphite", Spells = {
		["Unstoppable Force"] = {spellName = "UFSlash", radius = 300, type = CIRCLE, canShadow = true, dangerous = true}
	}},
	["Gragas"] = {charName = "Gragas", Spells = {
		["Explosive Cask"] = {spellName = "GragasExplosiveCask", radius = 400, type = CIRCLE, canShadow = true, dangerous = true}
	}},
	["Leona"] = {charName = "Leona", Spells = {
		["Solar Flare"] = {spellName = "LeonaSolarFlare", radius = 250, type = CIRCLE, canShadow = true, dangerous = false}
	}},
	["Kennen"] = {charName = "Kennen", Spells = {
		["Slicing Maelstrom"] = {spellName = "KennenShurikenStorm", radius = 400, type = INSTANT, canShadow = true, dangerous = false}
	}},
	["Fiddlesticks"] = {charName = "Fiddlesticks", Spells = {
		["Crowstorm"] = {spellName = "Crowstorm", radius = 400, type = INSTANT, canShadow = true, dangerous = false}
	}},
	["Morgana"] = {charName = "Morgana", Spells = {
		["Soul Shackles"] = {spellName = "SoulShackles", radius = 400, type = INSTANT, canShadow = true, dangerous = false}
	}},
	["Nunu"] = {charName = "Nunu", Spells = {
		["Absolute Zero"] = {spellName = "AbsoluteZero", radius = 600, type = INSTANT, canShadow = true, dangerous = false}
	}},
	["Orianna"] = {charName = "Orianna", Spells = {
		["Command: Shockwave"] = {spellName = "OrianaDetonateCommand", radius = 400, type = INSTANT, canShadow = false, dangerous = true},
		["Command: Shockwave2"] = {spellName = "OrianaDetonateCommand", radius = 400, type = CIRCLE, canShadow = false, dangerous = true}
	}},
	["Pantheon"] = {charName = "Pantheon", Spells = {
		["Grand Skyfall"] = {spellName = "Pantheon_GrandSkyfall_Jump", radius = 900, type = CIRCLE, canShadow = true, dangerous = false}
	}},
	["Zyra"] = {charName = "Zyra", Spells = {
		["Stranglethorns"] = {spellName = "ZyraBrambleZone", radius = 600, type = CIRCLE, canShadow = true, dangerous = false}
	}},
	["Amumu"] = {charName = "Amumu", Spells = {
		["Curse of the Sad Mummy"] = {spellName = "CurseoftheSadMummy", radius = 550, type = INSTANT, canShadow = false, dangerous = true}
	}}
}

function EvadeOnProcessSpell(unit, spell)
	for i, champ in pairs(SkillList) do
		if champ.charName == unit.charName then
			for i, Spell in pairs(champ.Spells) do
				if Spell.spellName == spell.name then
					if Spell.type == CIRCLE and GetDistance(spell.endPos) <= Spell.radius then
						EvadeSkill(unit, Spell.canShadow, Spell.dangerous)
						if ZedConfig.Debug.debugEvadeText then PrintChat("CIRCLE") end
					elseif Spell.type == INSTANT and GetDistance(unit) <= Spell.radius then 
						EvadeSkill(unit, Spell.canShadow, Spell.dangerous)
						if ZedConfig.Debug.debugEvadeText then PrintChat("INSTANT") end
					end
				end
			end
		end
	end
end

function EvadeSkill(target, castable, danger)
	if target then
		if shadowW then wDist = GetDistance(target, shadowW) end
		if shadowR then rDist = GetDistance(target, shadowR) end
	end
	if shadowW and shadowW.valid and myHero:GetSpellData(_W).name == "zedw2" then
		CastSpell(_W)
		if ZedConfig.Debug.debugEvadeText then PrintChat("W Evade") end
	elseif shadowR and shadowR.valid and myHero:GetSpellData(_R).name == "ZedR2" then
		CastSpell(_R)
		if ZedConfig.Debug.debugEvadeText then PrintChat("R Evade") end
	elseif castable == true and myHero:GetSpellData(_W).name == "ZedShadowDash" then
		local evadingPosition = target + (Vector(myHero) - target):normalized()*wRange
		if not intersectWall(evadingPosition, wRange) then
			CastSpell(_W, evadingPosition.x, evadingPosition.y) 
			if ZedConfig.Debug.debugEvadeText then PrintChat("Cast Evade") end
		end
	elseif ZedConfig.Evade.evadeDeathMark and findBestTarget() and danger == true then
		if GetDistance(findBestTarget()) <= rRange and myHero:GetSpellData(_R).name == "zedult" then 
			CastSpell(_R, findBestTarget())
			if ZedConfig.Debug.debugEvadeText then PrintChat("R Evade") end
		end
	end
end

local priorityTable = {
	AP = {
		"Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
		"Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
		"Rumble", "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra", "MasterYi",
	},

	Support = {
		"Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Sona", "Soraka", "Thresh", "Zilean",
	},
 
	Tank = {
		"Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Shen", "Singed", "Skarner", "Volibear",
		"Warwick", "Yorick", "Zac", "Nunu", "Taric", "Alistar",
	},
 
	AD_Carry = {
		"Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "KogMaw", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
		"Talon", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Zed", "Jinx"
	},
 
	Bruiser = {
		"Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nautilus", "Nocturne", "Olaf", "Poppy",
		"Renekton", "Rengar", "Riven", "Shyvana", "Trundle", "Tryndamere", "Udyr", "Vi", "MonkeyKing", "XinZhao", "Aatrox"
	},
}
 
function SetPriority(table, hero, priority)
	for i=1, #table, 1 do
		if hero.charName:find(table[i]) ~= nil then TS_SetHeroPriority(priority, hero.charName) end
	end
end
 
function arrangePrioritys(enemies)
	local priorityOrder = {
		[2] = {1,1,2,2,2},
		[3] = {1,1,2,3,3},
		[4] = {1,2,3,4,4},
		[5] = {1,2,3,4,5},
	}
	for i, enemy in ipairs(GetEnemyHeroes()) do
		SetPriority(priorityTable.AD_Carry, enemy, priorityOrder[enemies][1])
		SetPriority(priorityTable.AP, enemy, priorityOrder[enemies][2])
		SetPriority(priorityTable.Support, enemy, priorityOrder[enemies][3])
		SetPriority(priorityTable.Bruiser, enemy, priorityOrder[enemies][4])
		SetPriority(priorityTable.Tank, enemy, priorityOrder[enemies][5])
	end
end

class 'ColorARGB'

function ColorARGB:__init(red, green, blue, alpha)
	self._R = red or 255
	self._G = green or 255
	self._B = blue or 255
	self._A = alpha or 255
end

function ColorARGB.FromArgb(red, green, blue, alpha)
	return ColorARGB(red,green,blue, alpha)
end

function ColorARGB:ToTable()
	return {self._A, self._R, self._G, self._B}
end 

function ColorARGB.FromTable(table)
	return ARGB(table[1], table[2], table[3], table[4])
end 

function ColorARGB:A(number)
	self._A = number or 255
	return self
end 

function ColorARGB:R(number)
	self._R = number or 255
	return self
end

function ColorARGB:B(number)
	self._B = number or 255
	return self
end

function ColorARGB:G(number)
	self._G = number or 255
	return self
end

function ColorARGB:ToARGB()
	return ARGB(self._A, self._R, self._G, self._B)
end