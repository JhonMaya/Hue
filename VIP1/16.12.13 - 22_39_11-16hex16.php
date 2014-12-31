<?php exit() ?>--by 16hex16 105.236.17.194
--> Start Check
local function abs(k) if k < 0 then return -k else return k end end
if tonumber == nil or tonumber("223") ~= 223 or -9 ~= "-10" + 1 then return end
if tostring == nil or tostring(220) ~= "220" then return end
if string.sub == nil or string.sub("imahacker", 4) ~= "hacker" then return end
last1 = tonumber(string.sub(tostring(GetUser), 11), 16)
last2 = tonumber(string.sub(tostring(GetAsyncWebResult), 11), 16)
last3 = tonumber(string.sub(tostring(CastSpell), 11), 16)
local function rawset3(table, value, id) end
local function protect(table) return setmetatable({}, { __index = table, __newindex = function(table, key, value) end, __metatable = false }) end
--overload check (addresses should be almost equal)
if _G.GetAsyncWebResult == nil or _G.GetUser == nil or _G.CastSpell == nil then print("Zed - Dancing with Shadows: Unauthorized User") return end
local a1 = tonumber(string.sub(tostring(_G.GetAsyncWebResult), 11), 16)
local a2 = tonumber(string.sub(tostring(_G.GetUser), 11), 16)
local a3 = tonumber(string.sub(tostring(_G.CastSpell), 11), 16)
if abs(a2-a1) > 500000 and abs(a3-a2) > 500000 then print("Zed - Dancing with Shadows: Unauthorized User") return end
if abs(a2-a1) < 500 and abs(a3-a2) < 500 then print("Zed - Dancing with Shadows: Unauthorized User") return end
_G.rawset = rawset3
namez = protect {
	["16hex16"] = true, ["Fueledbyrainbows"] = true,
}
_G.rawset = rawset3
if namez[_G.GetUser():lower()] == nil or namez[_G.GetUser():lower()] == false then print("Zed - Dancing with Shadows: Unauthorized User") return end

-- Todo:
--  Bug Fixes 
--   Current Bugs: ...
--  Defensive Swapping System
--  Finish Evade System

local lastAttack, lastWindUpTime, lastAttackCD = 0, 0, 0
local qRange, wRange, eRange, rRange = 900, 600, 290, 625
local shadowW, shadowR, markedTarget = nil, nil, nil
local shadowWBuff, shadowRBuff = false, false
local Prodiction = ProdictManager.GetInstance()
local qp = Prodiction:AddProdictionObject(_Q, qRange, 1700, 0.25, 50)

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

function OnLoad()
	PrintChat("<font color='#CCCCCC'> >> Zed - Dancing with Shadows loaded! <<</font>")
	Menu()
	Load()
end

function OnTick()
	Checks()
	local Target = myTarget()

	--> Combo/Harass
	if ZedConfig.Combo.comboCombo then
		if Target and Target.type == myHero.type then
			if ZedConfig.Orbwalk.orbwalkActive then OrbWalk(Target) end
			comboMangement(Target)
		else
			if ZedConfig.Orbwalk.orbwalkActive then moveToCursor() end
		end
	end
	if Target and Target.type == myHero.type then
		if ZedConfig.Harass.harassCombo then Harass(Target) end
	end
	--> Farm Q
	if QREADY and ZedConfig.Farm.farmQ and (not ZedConfig.Combo.comboCombo or ZedConfig.Harass.harassCombo) then
		for _, minion in pairs(enemyMinions.objects) do
			if minion and minion.health < getDmg("Q", minion, myHero) then castQ(minion) end
		end
	end
	--> Auto Casts
	if not ZedConfig.Combo.comboCombo and not ZedConfig.Harass.harassCombo then
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and enemy.visible then
				--> Auto Combo
				if ZedConfig.Kill.killCombo and GetDistance(enemy) <= wRange+qRange then
					killManagement(enemy) 
				end
				--> Auto Q
				if QREADY and ZedConfig.Auto.autoQ then castQ(enemy) end
				--> Auto E
				if EREADY and ZedConfig.Auto.autoE then castE(enemy) end
			end
		end
	end
end

function round(num, idp)
  local mult = 10^(idp or 0)
  return math.floor(num * mult + 0.5) / mult
end

function OnDraw()
	if myHero.dead then return end
	local Target = myTarget()
	
	--> Shadow Drawing
	if shadowW then
		if ZedConfig.Draw.drawShadowText and WREADY then 
			DrawText3D("Shadow - W", shadowW.x, shadowW.y, shadowW.z, 20, ARGB(255,0,255,0))
			local wTime = round((wTimer - GetTickCount())/1000)
			if wTime > 0 then DrawText3D(tostring(wTime), shadowW.x, shadowW.y-40, shadowW.z, 20, ARGB(255,0,255,0)) end
		end
		if ZedConfig.Draw.drawShadowRange and WREADY then DrawCircle(shadowW.x, shadowW.y, shadowW.z, 1225, 0x0000FF) end
	end
	if shadowR then
		if ZedConfig.Draw.drawShadowText and RREADY then
			DrawText3D("Shadow - R", shadowR.x, shadowR.y, shadowR.z, 20, ARGB(255,255,0,0))
			local rTime = round((rTimer - GetTickCount())/1000)
			if rTime > 0 then DrawText3D(tostring(rTime), shadowR.x, shadowR.y-40, shadowR.z, 20, ARGB(255,255,0,0)) end
		end
		if ZedConfig.Draw.drawShadowRange and RREADY then DrawCircle(shadowR.x, shadowR.y, shadowR.z, 1225, 0xFF0000) end
	end
	
	--> Spell Range Drawing
	if ZedConfig.Draw.drawQ then 
		DrawCircle(myHero.x, myHero.y, myHero.z, qRange, ARGBFromTable(ZedConfig.Draw.drawQColour)) 
		if shadowW then DrawCircle(shadowW.x, shadowW.y, shadowW.z, qRange, ARGBFromTable(ZedConfig.Draw.drawQColour)) end
		if shadowR then DrawCircle(shadowR.x, shadowR.y, shadowR.z, qRange, ARGBFromTable(ZedConfig.Draw.drawQColour)) end
	end
	if ZedConfig.Draw.drawW then 
		DrawCircle(myHero.x, myHero.y, myHero.z, wRange, ARGBFromTable(ZedConfig.Draw.drawWColour)) 
	end
	if ZedConfig.Draw.drawE then 
		DrawCircle(myHero.x, myHero.y, myHero.z, eRange, ARGBFromTable(ZedConfig.Draw.drawEColour)) 
		if shadowW then DrawCircle(shadowW.x, shadowW.y, shadowW.z, eRange, ARGBFromTable(ZedConfig.Draw.drawEColour)) end
		if shadowR then DrawCircle(shadowR.x, shadowR.y, shadowR.z, eRange, ARGBFromTable(ZedConfig.Draw.drawEColour)) end
	end
	if ZedConfig.Draw.drawR then 
		DrawCircle(myHero.x, myHero.y, myHero.z, rRange, ARGBFromTable(ZedConfig.Draw.drawRColour)) 
		if shadowW then DrawCircle(shadowW.x, shadowW.y, shadowW.z, rRange, ARGBFromTable(ZedConfig.Draw.drawRColour)) end
	end
	
	--> Killable Drawing
	if ZedConfig.Draw.drawText and Target and Target.type == myHero.type then
		if not Target.dead and Target.visible then drawDamageText(Target) end
	end
end

function OnProcessSpell(unit, spell)
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
		if WREADY and energyCheck(1) and (QREADY or EREADY) then
			local shadowPos = myHero + (Vector(target) - myHero):normalized()*wRange
			if GetDistance(target, shadowPos) <= eRange then 
				if myHero:GetSpellData(_W).name == "ZedShadowDash" then 
					CastSpell(_W, shadowPos.x, shadowPos.z)
				end
				if not WREADY or shadowW or myHero:GetSpellData(_W).level < 1 then CastSpell(_E) end
			end
			if not WREADY or shadowW or myHero:GetSpellData(_W).level < 1 then castQ(target) end
		elseif not WREADY or shadowW or myHero:GetSpellData(_W).level < 1 then 
			castQ(target)
		elseif not WREADY or shadowW or myHero:GetSpellData(_W).level < 1 then 
			castE(target)
		end
		--if WREADY and energyCheck(1) and (QREADY or EREADY) then castW(target, 0) end
		--if not WREADY or shadowW or myHero:GetSpellData(_W).level < 1 then castQ(target) end
		--if not WREADY or shadowW or myHero:GetSpellData(_W).level < 1 then castE(target) end
	else
		if QREADY then castQ(target) end
		if EREADY then castE(target) end
	end
end

function Combo(target)
	if RREADY then castR(target) end
	if not RREADY or shadowR then
		shadowSwap(target)
		if shadowR or myHero:GetSpellData(_R).currentCd > 10 then UseItems(target) end
		if not WREADY or shadowW or shadowR or myHero:GetSpellData(_W).level < 1 then castQ(target) end
		if WREADY and GetDistance(target) >= 250 and (energyCheck(1) or (not QREADY and not EREADY)) then castW(target, 300) end
		if EREADY then castE(target) end
	end
	if not ZedConfig.Combo.comboCombo and GetDistance(target) <= trueRange() and timeToShoot() then myHero:Attack(target) end
end

function castQ(target) 
	if QREADY and GetDistance(target) <= qRange then
		qp:GetPredictionCallBack(target, CastQ, myHero)
	elseif QREADY and shadowW and shadowW.valid and GetDistance(target, shadowW) <= qRange then
		qp:GetPredictionCallBack(target, CastQ, shadowW)
	elseif QREADY and shadowR and shadowR.valid and GetDistance(target, shadowR) <= qRange then
		qp:GetPredictionCallBack(target, CastQ, shadowR)
	end
end

function castW(target, buffer)
	local bonusRange = 200
	if QREADY then 
		bonusRange = qRange
	elseif EREADY then
		bonusRange = eRange
	end
	if GetDistance(target) > buffer then
		local shadowPos = myHero + (Vector(target) - myHero):normalized()*wRange
		if GetDistance(target) <= wRange+bonusRange and myHero:GetSpellData(_W).name == "ZedShadowDash" then
			if shadowPos and wallCheck(shadowPos) then CastSpell(_W, shadowPos.x, shadowPos.z) end
		end
	end
end

function castE(target) 
	local pred = GetPredictionPos(target, 100) 
	if GetDistance(pred) <= eRange then
		CastSpell(_E)
	elseif shadowW and shadowW.valid and GetDistance(pred, shadowW) <= eRange  then
		CastSpell(_E)
	elseif shadowR and shadowR.valid and GetDistance(pred, shadowR) <= eRange then
		CastSpell(_E)
	end
end

function castR(target)
	if GetDistance(target) <= rRange and myHero:GetSpellData(_R).name == "zedult" then 
		CastSpell(_R, target)
	end
end

function shadowSwap(target)
	if shadowWBuff and shadowW and shadowW.valid and GetDistance(target, shadowW) < GetDistance(target) and GetDistance(target) > 125 and myHero:GetSpellData(_W).name == "zedw2" then
		CastSpell(_W)
	elseif shadowRBuff and shadowR and shadowR.valid and GetDistance(target, shadowR) < GetDistance(target) and GetDistance(target) > 125 and myHero:GetSpellData(_R).name == "ZedR2" then
		CastSpell(_R)
	end 
end

function dangerousArea(target)
	if ZedConfig.Kill.killIgnore then
		return false
	elseif (CountEnemies(target, 750) >= ZedConfig.Kill.killEnemies) or (myHero.health < myHero.maxHealth*0.2 and target.health > myHero.health*2.25) then
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
	local pred = GetPredictionPos(target, 150)
	local aDmg = getDmg("AD", target, myHero)
	local pDmg = (target.health <= target.maxHealth*0.5 and getDmg("P", target, myHero)) or 0
	local qDmg = (QREADY and getDmg("Q", target, myHero)) or 0
	local eDmg = (EREADY and getDmg("E", target, myHero)) or 0
	local rDmg = (RREADY and getDmg("R", target, myHero)) or 0
	local iDmg = (ZedConfig.Kill.killUseI and IREADY and getDmg("IGNITE", target, myHero)) or 0
	local bladeDmg = (GetInventorySlotItem(3153) and getDmg("RUINEDKING", target, myHero)) or 0
	local cutlassDmg = (GetInventorySlotItem(3144) and getDmg("BWC", target, myHero)) or 0
	local tiamatDmg = (GetInventorySlotItem(3077) and getDmg("TIAMAT", target, myHero)) or 0
	local hydraDmg = (GetInventorySlotItem(3074) and getDmg("HYDRA", target, myHero)) or 0
	local damageAmp = (RREADY and 1+((5 + myHero:GetSpellData(_R).level*15)/100)) or 1
	local attackDamage = aDmg*2 + pDmg
	local maxDamage = iDmg + (qDmg*1.5 + eDmg*2 + rDmg + bladeDmg + cutlassDmg + tiamatDmg + hydraDmg + attackDamage)*damageAmp
	
	if QREADY and target.health < qDmg then
		if GetDistance(target) <= qRange then
			castQ(target)
		elseif WREADY and energyCheck(2) and GetDistance(target) <= qRange+wRange then
			if WREADY then castW(target, 0) end
			if not WREADY or shadowW then castQ(target) end
		end
	elseif EREADY and target.health < eDmg then
		if GetDistance(target) <= eRange then
			castE(target)
		elseif WREADY and energyCheck(1) and GetDistance(pred) <= eRange+wRange then
			if not WREADY or shadowW then castE(target) end
			if WREADY then castW(target, 0) end
		end
	elseif QREADY and EREADY and target.health < qDmg+eDmg then
		if GetDistance(target) <= eRange then
			castE(target)
			castQ(target)
		elseif WREADY and energyCheck(3) and GetDistance(pred) <= eRange+wRange then
			if not WREADY or shadowW then castQ(target) end
			if WREADY then castW(target, 0) end
			if not WREADY or shadowW then castE(target) end
		end
	elseif ZedConfig.Kill.killUseR and ZedConfig.Kill.KillTargeting[target.charName.."killTarget"] and RREADY and target.health < maxDamage and not dangerousArea(target) and energyCheck(3) then
		if GetDistance(target) < rRange then
			Combo(target)
			if ZedConfig.Kill.killUseI and IREADY then CastSpell(ignite, target) end
		elseif shadowW and shadowW.valid and GetDistance(pred, shadowW) <= rRange then
			shadowSwap(target)
		elseif GetDistance(pred) <= rRange+wRange then
			castW(target, 0)
			shadowSwap(target)
		end
	elseif ZedConfig.Kill.killUseI and ZedConfig.Kill.KillTargeting[target.charName.."killTarget"] and (not RREADY or shadowR) and IREADY and target.health < iDmg then
		if GetDistance(target) <= 600 then
			if IREADY then CastSpell(ignite, target) end
		elseif shadowW and shadowW.valid and GetDistance(pred, shadowW) <= 600 then
			shadowSwap(target)
		elseif GetDistance(pred) <= wRange+600 then
			castW(target, 0)
			shadowSwap(target)
		end
	end
end

function comboMangement(target)
	local aDmg = getDmg("AD", target, myHero)
	local pDmg = (target.health <= target.maxHealth*0.5 and getDmg("P", target, myHero)) or 0
	local qDmg = (QREADY and getDmg("Q", target, myHero)) or 0
	local eDmg = (EREADY and getDmg("E", target, myHero)) or 0
	local rDmg = (RREADY and getDmg("R", target, myHero)) or 0
	local iDmg = (IREADY and getDmg("IGNITE", target, myHero)) or 0
	local bladeDmg = (GetInventorySlotItem(3153) and getDmg("RUINEDKING", target, myHero)) or 0
	local cutlassDmg = (GetInventorySlotItem(3144) and getDmg("BWC", target, myHero)) or 0
	local tiamatDmg = (GetInventorySlotItem(3077) and getDmg("TIAMAT", target, myHero)) or 0
	local hydraDmg = (GetInventorySlotItem(3074) and getDmg("HYDRA", target, myHero)) or 0
	local damageAmp = (RREADY and 1+((5 + myHero:GetSpellData(_R).level*15)/100)) or 1
	local attackDamage = aDmg*3 + pDmg
	local maxDamage = iDmg + (qDmg*1.5 + eDmg*2 + rDmg + bladeDmg + cutlassDmg + tiamatDmg + hydraDmg + attackDamage)*damageAmp
	
	if QREADY and target.health < qDmg then
		if GetDistance(target) <= qRange then
			castQ(target)
		elseif WREADY and energyCheck(2) and GetDistance(target) <= qRange+wRange then
			if WREADY then castW(target, 0) end
			if not WREADY or shadowW then castQ(target) end
		end
	elseif EREADY and target.health < eDmg then
		if GetDistance(target) <= eRange then
			castE(target)
		elseif WREADY and energyCheck(1) and GetDistance(target) <= eRange+wRange then
			if not WREADY or shadowW then castE(target) end
			if WREADY then castW(target, 0) end
		end
	elseif QREADY and EREADY and target.health < qDmg+eDmg then
		if GetDistance(target) <= eRange then
			castE(target)
			castQ(target)
		elseif WREADY and energyCheck(3) and GetDistance(target) <= eRange+wRange then
			if not WREADY or shadowW then castQ(target) end
			if WREADY then castW(target, 0) end
			if not WREADY or shadowW then castE(target) end
		end
	elseif RREADY and target.health < maxDamage and energyCheck(3) then
		if GetDistance(target) < rRange then
			Combo(target)
			if IREADY then CastSpell(ignite, target) end
		elseif shadowW and shadowW.valid and GetDistance(target, shadowW) <= rRange then
			shadowSwap(target)
		elseif GetDistance(target) <= rRange+wRange then
			castW(target, 0)
			shadowSwap(target)
		end
	elseif (not RREADY or shadowR) and IREADY and target.health < iDmg then
		if GetDistance(target) <= 600 then
			if IREADY then CastSpell(ignite, target) end
		elseif shadowW and shadowW.valid and GetDistance(target, shadowW) <= 600 then
			shadowSwap(target)
		elseif GetDistance(target) <= wRange+600 then
			castW(target, 0)
			shadowSwap(target)
		end
	else
		if shadowR or myHero:GetSpellData(_R).currentCd > 10 then UseItems(target) end
		if WREADY and GetDistance(target) >= 225 and (energyCheck(1) or (not QREADY and not EREADY)) then castW(target, 0) end
		shadowSwap(target)
		if not WREADY or shadowW or shadowR or GetDistance(target) < 225 then castQ(target) end
		if EREADY then castE(target) end
	end
end

function Checks()
	if not tm:isReady() or myHero.dead then return end

	QREADY = ((myHero:CanUseSpell(_Q) == READY and energyCheckReady("Q")) or (myHero:GetSpellData(_Q).level > 0 and myHero:GetSpellData(_Q).currentCd <= 0.6 and energyCheckReady("Q")))
	WREADY = ((myHero:CanUseSpell(_W) == READY and energyCheckReady("W")) or (myHero:GetSpellData(_W).level > 0 and myHero:GetSpellData(_W).currentCd <= 0.6 and energyCheckReady("W")))
	EREADY = ((myHero:CanUseSpell(_E) == READY and energyCheckReady("E")) or (myHero:GetSpellData(_E).level > 0 and myHero:GetSpellData(_E).currentCd <= 0.6 and energyCheckReady("E")))
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
	local eMana = 50
	local qEnergy = (myHero:GetSpellData(_Q).level > 0 and qMana[myHero:GetSpellData(_Q).level]) or 0
	local wEnergy = (myHero:GetSpellData(_W).level > 0 and not shadowW and wMana[myHero:GetSpellData(_W).level]) or 0
	local myEnergy = myHero.mana
	local qCooldown = myHero:GetSpellData(_Q).currentCd
	local wCooldown = myHero:GetSpellData(_W).currentCd
	local eCooldown = myHero:GetSpellData(_E).currentCd
	
	if skill == "Q" then
		if myEnergy >= qEnergy then return true end
	elseif skill == "W" then
		if myEnergy >= wEnergy then return true end
	elseif skill == "E" then
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
	if GetInventorySlotItem(3077) ~= nil and GetDistance(target) < 275 then 
		CastSpell(GetInventorySlotItem(3077)) 
	end
	if GetInventorySlotItem(3074) ~= nil and GetDistance(target) < 275 then 
		CastSpell(GetInventorySlotItem(3074))
	end
end

function ARGBFromTable(table)
	return ARGB(table[1], table[2], table[3], table[4])
end

function wallCheck(pos)
	local checkPos = D3DXVECTOR3(pos.x, pos.y, pos.z)
	if checkPos and not IsWall(checkPos) then return true end
	return false
end

function drawDamageText(target)
	local aDmg = getDmg("AD", target, myHero)
	local pDmg = (target.health <= target.maxHealth*0.5 and getDmg("P", target, myHero)) or 0
	local qDmg = (QREADY and getDmg("Q", target, myHero)) or 0
	local eDmg = (EREADY and getDmg("E", target, myHero)) or 0
	local rDmg = (RREADY and getDmg("R", target, myHero)) or 0
	local iDmg = (IREADY and getDmg("IGNITE", target, myHero)) or 0
	local attackDamage = aDmg*2 + pDmg
	local bladeDmg = (GetInventorySlotItem(3153) and getDmg("RUINEDKING", target, myHero)) or 0
	local cutlassDmg = (GetInventorySlotItem(3144) and getDmg("BWC", target, myHero)) or 0
	local tiamatDmg = (GetInventorySlotItem(3077) and getDmg("TIAMAT", target, myHero)) or 0
	local hydraDmg = (GetInventorySlotItem(3074) and getDmg("HYDRA", target, myHero)) or 0
	local damageAmp = (RREADY and 1+((5 + myHero:GetSpellData(_R).level*15)/100)) or 1
	local maxDamage = (qDmg*1.5 + eDmg*2 + rDmg + iDmg + bladeDmg + cutlassDmg + tiamatDmg + hydraDmg + attackDamage)*damageAmp

	if QREADY and target.health < qDmg then
		PrintFloatText(target, 0, "Killable - Q")
	elseif EREADY and target.health < eDmg then
		PrintFloatText(target, 0, "Killable - E")
	elseif QREADY and EREADY and target.health < qDmg+eDmg then
		PrintFloatText(target, 0, "Killable - Q+E")
	elseif RREADY and target.health < maxDamage and energyCheck(3) then
		PrintFloatText(target, 0, "Killable - Everything")
	elseif (not RREADY or shadowR) and IREADY and target.health < iDmg then
		PrintFloatText(target, 0, "Killable - Ignite")
	else
		PrintFloatText(target, 0, "Harass Him")
	end
end

function OrbWalk(target)
	if heroCanHit(target) then
		if timeToShoot() then
			myHero:Attack(target)
		elseif heroCanMove() and GetDistance(target) > 100 then
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
	local predPos = GetPredictionPos(target, 75)
	if predPos and GetDistance(predPos) <= trueRange() then return true end
	return false
end

function moveToCursor()
	if GetDistance(mousePos) > 1 or lastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
	end
end

function OnGainBuff(unit, buff)
	if buff.name == "zedwshadowbuff" and buff.source.isMe then 
		shadowW = unit
		wTimer = GetTickCount() + 5000
	elseif buff.name == "zedwhandler" and buff.source.isMe then 
		shadowWBuff = true
	elseif buff.name == "zedrshadowbuff" and buff.source.isMe then 
		shadowR = unit
		rTimer = GetTickCount() + 7000
	elseif buff.name == "ZedR2" and buff.source.isMe then 
		shadowRBuff = true
	elseif buff.name == "zedulttargetmark" and buff.source.isMe then
		markedTarget = unit
	end
end

function OnLoseBuff(unit, buff)
	if buff.name == "zedwshadowbuff" and unit.networkID == shadowW.networkID then 
		shadowW = nil
		wTimer = 0
	elseif buff.name == "zedwhandler" and unit.isMe then 
		shadowWBuff = false
	elseif buff.name == "zedrshadowbuff" and unit.networkID == shadowR.networkID then 
		shadowR = nil
		rTimer = 0
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
	ZedConfig:addSubMenu("Zed - Combo Settings", "Combo")
	ZedConfig.Combo:addParam("comboCombo", "Combo - Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	--> Harass
	ZedConfig:addSubMenu("Zed - Harass Settings", "Harass")
	ZedConfig.Harass:addParam("harassCombo", "Harass - Harass", SCRIPT_PARAM_ONKEYDOWN, false, 88)
	ZedConfig.Harass:addParam("harassUseW", "Harass - Use Shadow", SCRIPT_PARAM_ONOFF, true)
	--> Auto
	ZedConfig:addSubMenu("Zed - Auto Settings", "Auto")
	ZedConfig.Auto:addParam("autoE", "Auto - Shadow Slash", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Auto:addParam("autoQ", "Auto - Razor Shuriken", SCRIPT_PARAM_ONOFF, false)
	--> Farm
	ZedConfig:addSubMenu("Zed - Farm Settings", "Farm")
	ZedConfig.Farm:addParam("farmQ", "Farm - Razor Shuriken[G]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("G"))
	ZedConfig.Farm:permaShow("farmQ")
	--> Orbwalking
	ZedConfig:addSubMenu("Zed - Orbwalking Settings", "Orbwalk")
	ZedConfig.Orbwalk:addParam("orbwalkActive", "Orbwalk - Use Attacks", SCRIPT_PARAM_ONOFF, true)
	--> Evade
	--ZedConfig:addSubMenu("Zed - Evade Settings", "Evade")
	--ZedConfig.Evade:addParam("evadeSkills", "Evade - Danger", SCRIPT_PARAM_ONOFF, false)
	--ZedConfig.Evade:addParam("evadeDeathMark", "Evade - Using Death Mark", SCRIPT_PARAM_ONOFF, false)
	--> Kill
	ZedConfig:addSubMenu("Zed - Kill Settings", "Kill")
	ZedConfig.Kill:addParam("killCombo", "Kill - Auto Combo[T]", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("T"))
	ZedConfig.Kill:addParam("killUseR", "Kill - Use Death Mark", SCRIPT_PARAM_ONOFF, true)
	ZedConfig.Kill:addParam("killUseI", "Kill - Use Ignite", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Kill:addParam("sep", "-- Danger Settings --", SCRIPT_PARAM_INFO, "")
	ZedConfig.Kill:addParam("killEnemies", "Kill - Enemy Danger Limit",SCRIPT_PARAM_SLICE, 3, 0, 5, 0)
	ZedConfig.Kill:addParam("killIgnore", "Kill - Ignore Danger[H]",SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("H"))
	ZedConfig.Kill:permaShow("killCombo")
	ZedConfig.Kill:permaShow("killIgnore")
		--> Kill Targeting
		ZedConfig.Kill:addSubMenu("Zed - Kill Targeting Settings", "KillTargeting")
		for i, enemy in ipairs(GetEnemyHeroes()) do
			ZedConfig.Kill.KillTargeting:addParam(enemy.charName.."killTarget", "Kill Target - "..enemy.charName, SCRIPT_PARAM_ONOFF, true)
		end
	--> Draw
	ZedConfig:addSubMenu("Zed - Draw Settings", "Draw")
	ZedConfig.Draw:addParam("drawText", "Draw - Killable Text", SCRIPT_PARAM_ONOFF, false)
	
	ZedConfig.Draw:addParam("sep1", "-- Shadow Draw Settings --", SCRIPT_PARAM_INFO, "")
	ZedConfig.Draw:addParam("drawShadowText", "Draw - Shadow Text", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawShadowRange", "Draw - Shadow Range", SCRIPT_PARAM_ONOFF, false)
	
	ZedConfig.Draw:addParam("sep2", "-- Razor Shuriken Draw Settings --", SCRIPT_PARAM_INFO, "")
	ZedConfig.Draw:addParam("drawQ", "Draw - Razor Shuriken", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawQColour", "Draw - Q Colour", SCRIPT_PARAM_COLOR, {255, 255, 0, 0})
	ZedConfig.Draw:addParam("sep3", "-- Living Shadow Draw Settings --", SCRIPT_PARAM_INFO, "")
	ZedConfig.Draw:addParam("drawW", "Draw - Living Shadow", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawWColour", "Draw - W Colour", SCRIPT_PARAM_COLOR, {255, 0, 255, 0})
	ZedConfig.Draw:addParam("sep4", "-- Shadow Slash Draw Settings --", SCRIPT_PARAM_INFO, "")
	ZedConfig.Draw:addParam("drawE", "Draw - Shadow Slash", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawEColour", "Draw - E Colour", SCRIPT_PARAM_COLOR, {255, 0, 0, 255})
	ZedConfig.Draw:addParam("sep5", "-- Death Mark Draw Settings --", SCRIPT_PARAM_INFO, "")
	ZedConfig.Draw:addParam("drawR", "Draw - Death Mark", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawRColour", "Draw - R Colour", SCRIPT_PARAM_COLOR, {255, 0, 255, 255})
end

function Load()
	for i, hero in ipairs(GetEnemyHeroes()) do
		qp:GetPredictionOnDash(hero, OnDashFunc)
		qp:GetPredictionAfterDash(hero, AfterDashFunc)
		qp:GetPredictionAfterImmobile(hero, AfterImmobileFunc)
		qp:GetPredictionOnImmobile(hero, OnImmobileFunc)
	end
	
	ignite = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
	enemyMinions = minionManager(MINION_ENEMY, 2000, myHero)
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, qRange, DAMAGE_PHYSICAL, true)
	ts.name = "Zed"
	ZedConfig:addTS(ts)
	if #GetEnemyHeroes() > 1 then arrangePrioritys(#GetEnemyHeroes()) end
end

--> Prodiction Functions
function CastQ(unit, pos)
	CastSpell(_Q, pos.x, pos.z)
end

function OnDashFunc(unit, pos, spell)
	CastSpell(_Q, pos.x, pos.z)
end

function AfterDashFunc(unit, pos, spell)
	CastSpell(_Q, pos.x, pos.z)
end

function AfterImmobileFunc(unit, pos, spell)
	CastSpell(_Q, pos.x, pos.z)
end

function OnImmobileFunc(unit, pos, spell)
	CastSpell(_Q, pos.x, pos.z)
end

--> Priorty Setting - In for tests
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