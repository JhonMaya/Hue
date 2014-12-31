<?php exit() ?>--by 16hex16 105.236.181.197
if myHero.charName ~= "Zed" then return end
if GetUser ~= "3rasus" or myHero.name ~= "erasuss" then return end

require 'Prodiction'
require 'MapPosition'
mapPosition = MapPosition()

local qRange, wRange, eRange, rRange = 900, 600, 290, 625
local shadowW, shadowR = nil, nil
local shadowWBuff, shadowRBuff = false, false

ts = TargetSelector(TARGET_LOW_HP, qRange, DAMAGE_PHYSICAL, true)
enemyMinions = minionManager(MINION_ENEMY, 2000, myHero)

prodiction = Prodict(_Q, qRange, 1700, 0.25, 50, myHero, function(unit, pos, castSpell) 
	if GetDistanceSqr(unit, castSpell.Source) < castSpell.RangeSqr then
		CastSpell(castSpell.Name, pos.x, pos.z)
	end 
end)

function OnLoad()
	PrintChat(" >> Zed - Dancing with Shadows")  
	
	ZedConfig = scriptConfig("Zed - Dancing with Shadows", "ZedDancingwithShadows") 
	
	ZedConfig:addSubMenu("Basic Settings", "Basic")
	ZedConfig.Basic:addParam("basicCombo", "Basic - Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	ZedConfig.Basic:addParam("basicHarass", "Basic - Harass", SCRIPT_PARAM_ONKEYDOWN, false, 88)
	
	ZedConfig:addSubMenu("Auto Settings", "Auto")
	ZedConfig.Auto:addParam("autoE", "Auto - Shadow Slash", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Auto:addParam("autoQ", "Auto - Razor Shuriken", SCRIPT_PARAM_ONOFF, false)
	
	ZedConfig:addSubMenu("Kill Settings", "Kill")
	ZedConfig.Kill:addParam("killCombo", "Kill - Auto Combo", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("T"))
	ZedConfig.Kill:addParam("killUseR", "Kill - Use Ultimate", SCRIPT_PARAM_ONOFF, true)
	ZedConfig.Kill:addParam("killUseI", "Kill - Use Ignite", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Kill:addParam("killEnemies", "Kill - Enemy Danger Limit",SCRIPT_PARAM_SLICE, 3, 0, 5, 0)
	ZedConfig.Kill:addParam("killIgnore", "Kill - Ignore Danger",SCRIPT_PARAM_ONOFF, false)
	
	ZedConfig:addSubMenu("Farm Settings", "Farm")
	ZedConfig.Evade:addParam("farmQ", "Farm - Razor Shuriken", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("G"))
	
	ZedConfig:addSubMenu("Evade Settings", "Evade")
	ZedConfig.Evade:addParam("evadeSkills", "Evade - Danger", SCRIPT_PARAM_ONOFF, false)
	
	ZedConfig:addSubMenu("Draw Settings", "Draw")
	ZedConfig.Draw:addParam("drawShadowText", "Draw - Shadow Text", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawShadowRange", "Draw - Shadow Range", SCRIPT_PARAM_ONOFF, false)
	ZedConfig.Draw:addParam("drawText", "Draw - Killable Text", SCRIPT_PARAM_ONOFF, false)

	ignite = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
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

	--> Combo/Harass
	if ts.target then
		if ZedConfig.Basic.basicHarass then Harass(ts.target) end
		if ZedConfig.Basic.basicCombo then comboMangement(ts.target) end
	end
	--> Auto Q
	if QREADY and ZedConfig.Auto.autoQ and (not ZedConfig.Basic.basicCombo or ZedConfig.Basic.basicHarass) then
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and enemy.visible then castQ(enemy) end
		end
	end
	--> Auto E
	if EREADY and ZedConfig.Auto.autoE and (not ZedConfig.Basic.basicCombo or ZedConfig.Basic.basicHarass) then
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and enemy.visible then castE(enemy) end
		end
	end
	--> Farm Q
	if QREADY and ZedConfig.Farm.farmQ and (not ZedConfig.Basic.basicCombo or ZedConfig.Basic.basicHarass) then
		for _, minion in pairs(enemyMinions.objects) do
			if minion and minion.health < getDmg("Q", minion, myHero) then castQ(minion) end
		end
	end
	--> Auto Combo
	if ZedConfig.Kill.killCombo and not ZedConfig.Basic.basicCombo then
		for i, enemy in ipairs(GetEnemyHeroes()) do
			if enemy and not enemy.dead and enemy.visible and GetDistance(enemy) <= wRange+qRange then killManagement(enemy) end
		end
	end
end

function OnDraw()
	if shadowW then
		if ZedConfig.Draw.drawShadowText then PrintFloatText(shadowW, 0, "W - Shadow") end
		if ZedConfig.Draw.drawShadowRange then DrawCircle(shadowW.x, shadowW.y, shadowW.z, 1225, 0x0000FF) end
	end
	if shadowR then
		if ZedConfig.Draw.drawShadowText then PrintFloatText(shadowR, 0, "R - Shadow") end
		if ZedConfig.Draw.drawShadowRange then DrawCircle(shadowR.x, shadowR.y, shadowR.z, 1225, 0xFF0000) end
	end
	
	if ZedConfig.Draw.drawText and ts.target and ts.target.type == myHero.type then
		local aDmg = getDmg("AD", ts.target, myHero)
		local pDmg = (ts.target.health <= ts.target.maxHealth*0.5 and getDmg("P", ts.target, myHero)) or 0
		local qDmg = (QREADY and getDmg("Q", ts.target, myHero)) or 0
		local eDmg = (EREADY and getDmg("E", ts.target, myHero)) or 0
		local rDmg = (RREADY and getDmg("R", ts.target, myHero)) or 0
		local iDmg = (IREADY and getDmg("IGNITE", ts.target, myHero)) or 0
		local attackDamage = aDmg*3 + pDmg
		local bladeDmg = (GetInventorySlotItem(3153) and getDmg("RUINEDKING", ts.target, myHero)) or 0
		local cutlassDmg = (GetInventorySlotItem(3144) and getDmg("BWC", ts.target, myHero)) or 0
		local damageAmp = (RREADY and 1+((5 + myHero:GetSpellData(_R).level*15)/100)) or 0
		local maxDamage = (qDmg*1.5 + eDmg*2 + rDmg + iDmg + bladeDmg + cutlassDmg + attackDamage)*damageAmp

		if QREADY and ts.target.health < qDmg then
			PrintFloatText(ts.target, 0, "Killable - Q")
		elseif EREADY and ts.target.health < eDmg then
			PrintFloatText(ts.target, 0, "Killable - E")
		elseif QREADY and EREADY and ts.target.health < qDmg+eDmg then
			PrintFloatText(ts.target, 0, "Killable - Q+E")
		elseif RREADY and ts.target.health < maxDamage and energyCheck(3) then
			PrintFloatText(ts.target, 0, "Killable - Everything")
		elseif (not RREADY or shadowR) and IREADY and ts.target.health < iDmg then
			PrintFloatText(ts.target, 0, "Killable - Ignite")
		elseif not energyCheck(1) then
			PrintFloatText(ts.target, 0, "Very low Energy")
		else
			PrintFloatText(ts.target, 0, "Harass Him")
		end
	end
end

function Harass(target)
	if not WREADY or shadowW then castQ(target) end
	if WREADY and energyCheck(1) then castW(target) end
	if EREADY then castE(target) end
end

function Combo(target)
	if RREADY then castR(target) end
	if not RREADY or shadowR then
		shadowSwap(target)
		UseItems(target)
		if not WREADY or shadowW or shadowR or GetDistance(target) < 225 then castQ(target) end
		if WREADY and energyCheck(1) and GetDistance(target) >= 225 then castW(target) end
		if EREADY then castE(target) end
	end
end

function castQ(target) 
	if QREADY and GetDistance(target) <= qRange then
		prodiction:EnableTarget(target, true, myHero)
	elseif QREADY and shadowW and shadowW.valid and GetDistance(target, shadowW) <= qRange then
		prodiction:EnableTarget(target, true, shadowW)
	elseif QREADY and shadowR and shadowR.valid and GetDistance(target, shadowR) <= qRange then
		prodiction:EnableTarget(target, true, shadowR)
	end
end

function castW(target)
	if GetDistance(target) <= qRange+wRange and myHero:GetSpellData(_W).name == "ZedShadowDash" then
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
	if shadowW and shadowW.valid and GetDistance(target, shadowW) < GetDistance(target)  then
		CastSpell(_W)
	elseif shadowR and shadowR.valid and GetDistance(target, shadowR) < GetDistance(target) then
		CastSpell(_W)
	end 
end

function dangerousArea(target)
	if ZedConfig.Kill.killIgnore then
		return false
	elseif CountEnemies(target, 600) >= ZedConfig.Kill.killEnemies or myHero.health < myHero.maxHealth*0.2 then
		return true
	end
	return false
end

function CountEnemies(point, range)
	local ChampCount = 0
	for j = 1, heroManager.iCount, 1 do
		local enemyhero = heroManager:getHero(j)
		if myHero.team ~= enemyhero.team and ValidTarget(enemyhero, range+800) then
			if enemyhero and GetDistance(enemyhero, point) <= range then
				ChampCount = ChampCount + 1
			end
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
	local attackDamage = aDmg*3 + pDmg
	local maxDamage = iDmg + (qDmg*1.5 + eDmg*2 + rDmg + bladeDmg + cutlassDmg + attackDamage)*damageAmp
	
	if not dangerousArea(target) then
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
		elseif ZedConfig.Kill.killUseR and RREADY and target.health < maxDamage and energyCheck(3) then
			if GetDistance(target) < rRange then
				Combo(target)
				CastSpell(ignite, target)
			elseif shadowW and shadowW.valid and GetDistance(pred, shadowW) <= rRange then
				shadowSwap(target)
			elseif GetDistance(pred) <= rRange+wRange then
				castW(target)
				shadowSwap(target)
			end
		elseif ZedConfig.Kill.killUseI and (not RREADY or shadowR) and IREADY and target.health < iDmg then
			if GetDistance(target) <= 600 then
				CastSpell(ignite, target)
			elseif shadowW and shadowW.valid and GetDistance(pred, shadowW) <= 600 then
				shadowSwap(target)
			elseif GetDistance(pred) <= wRange+600 then
				castW(target)
				shadowSwap(target)
			end
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
			CastSpell(ignite, target)
		elseif shadowW and shadowW.valid and GetDistance(pred, shadowW) <= rRange then
			shadowSwap(target)
		elseif GetDistance(pred) <= rRange+wRange then
			castW(target)
			shadowSwap(target)
		end
	elseif (not RREADY or shadowR) and IREADY and target.health < iDmg then
		if GetDistance(target) <= 600 then
			CastSpell(ignite, target)
		elseif shadowW and shadowW.valid and GetDistance(pred, shadowW) <= 600 then
			shadowSwap(target)
		elseif GetDistance(pred) <= wRange+600 then
			castW(target)
			shadowSwap(target)
		end
	else
		if not RREADY or shadowR then UseItems(target) end
		if WREADY and energyCheck(1) and GetDistance(target) >= 225 then castW(target) end
		shadowSwap(target)
		if not WREADY or shadowW or shadowR or GetDistance(target) < 225 then castQ(target) end
		if EREADY then castE(target) end
	end
end

function Checks()
	if not tm:isReady() then return end
	if shadowW or shadowR then 
		local extraRange = wRange
		if shadowW and shadowR then 
			if GetDistance(shadowW) > GetDistance(shadowR) then
				extraRange = extraRange + GetDistance(shadowW)
			else 
				extraRange = extraRange + GetDistance(shadowR)
			end  
		elseif shadowW then
			extraRange = extraRange + GetDistance(shadowW)
		elseif shadowR then 
			extraRange = extraRange + GetDistance(shadowR)
		end 
		ts.range = qRange + 100 + extraRange
	elseif ts.range ~= qRange + 100 then
		ts.range = qRange + 100
	end
	ts:update()
	enemyMinions:update()
	
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	WREADY = (myHero:CanUseSpell(_W) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)
	IREADY = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
end

function energyCheck(stage)
	local qMana = {75, 70, 65, 60, 55}
	local wMana = {40, 35, 30, 25, 20}
	local eMana = 50
	local qEnergy = qMana[myHero:GetSpellData(_Q).level]
	local wEnergy = wMana[myHero:GetSpellData(_W).level]
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

function UseItems(target)
	if GetInventorySlotItem(3153) ~= nil then 
		CastSpell(GetInventorySlotItem(3153), target) 
	end 	
	if GetInventorySlotItem(3144) ~= nil then 
		CastSpell(GetInventorySlotItem(3144), target) 
	end 
end

function intersectWall(pStart, distance)
	StartPos = Vector(pStart.x, pStart.y, pStart.z)
	MyPos = Vector(myHero.x, myHero.y, myHero.z)
	DashPos = StartPos + (StartPos - MyPos)*((distance/GetDistance(StartPos)))
  if DashPos and mapPosition:intersectsWall(Point(DashPos.x, DashPos.z)) then
		return true
	else
		return false
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
	end 
end 

local SkillList = {
	["Malphite"] = {charName = "Malphite", Spells = {
		["Unstoppable Force"] = {spellName = "UFSlash", radius = 300, type = CIRCLE, canShadow = true}
	}},
	["Gragas"] = {charName = "Gragas", Spells = {
		["Explosive Cask"] = {spellName = "GragasExplosiveCask", radius = 400, type = CIRCLE, canShadow = true}
	}},
	["Leona"] = {charName = "Leona", Spells = {
		["Solar Flare"] = {spellName = "LeonaSolarFlare", radius = 250, type = CIRCLE, canShadow = true}
	}},
	["Kennen"] = {charName = "Kennen", Spells = {
		["Slicing Maelstrom"] = {spellName = "KennenShurikenStorm", radius = 400, type = INSTANT, canShadow = true}
	}},
	["Fiddlesticks"] = {charName = "Fiddlesticks", Spells = {
		["Crowstorm"] = {spellName = "Crowstorm", radius = 400, type = INSTANT, canShadow = true}
	}},
	["Morgana"] = {charName = "Morgana", Spells = {
		["Soul Shackles"] = {spellName = "SoulShackles", radius = 400, type = INSTANT, canShadow = true}
	}},
	["Nunu"] = {charName = "Nunu", Spells = {
		["Absolute Zero"] = {spellName = "AbsoluteZero", radius = 600, type = INSTANT, canShadow = true}
	}},
	["Orianna"] = {charName = "Orianna", Spells = {
		["Command: Shockwave"] = {spellName = "OrianaDetonateCommand", radius = 400, type = INSTANT, canShadow = true},
		["Command: Shockwave2"] = {spellName = "OrianaDetonateCommand", radius = 400, type = CIRCLE, canShadow = true}
	}},
	["Pantheon"] = {charName = "Pantheon", Spells = {
		["Grand Skyfall"] = {spellName = "Pantheon_GrandSkyfall_Jump", radius = 900, type = CIRCLE, canShadow = true}
	}},
	["Zyra"] = {charName = "Zyra", Spells = {
		["Stranglethorns"] = {spellName = "ZyraBrambleZone", radius = 600, type = CIRCLE, canShadow = true}
	}},
	["Amumu"] = {charName = "Amumu", Spells = {
		["Curse of the Sad Mummy"] = {spellName = "CurseoftheSadMummy", radius = 550, type = INSTANT, canShadow = false}
	}},
	["Alistar"] = {charName = "Alistar", Spells = {
		["Pulverize"] = {spellName = "Pulverize", radius = 280, type = INSTANT, canShadow = false}
	}},
	["Annie"] = {charName = "Annie", Spells = {
		["Summon Tibbers"] = {spellName = "InfernalGuardian", radius = 250, type = CIRCLE, canShadow = false}
	}}
}
	
function OnProcessSpell(unit, spell)
	if ZedConfig.Evade.evadeSkills then
		for i, champ in pairs(SkillList) do
			if champ.charName == unit.charName then
				for i, Spell in pairs(champ.Spells) do
					if Spell.spellName == spell.name then
						if (Spell.type == "CIRCLE" and GetDistance(spell.endPos) <= Spell.radius) or (Spell.type == "INSTANT" and GetDistance(unit) <= Spell.radius) then 
							EvadeSkill(unit, Spell.canShadow)
						end
					end
				end
			end
		end
	end
end

function EvadeSkill(target, castable)
	if target then
		if shadowW then wDist = GetDistance(target, shadowW) end
		if shadowR then rDist = GetDistance(target, shadowR) end
	end
	if shadowW and shadowW.valid then
		CastSpell(_W)
		--PrintChat("W Evade")
	elseif shadowR and shadowR.valid then
		CastSpell(_R)
		--PrintChat("R Evade")
	elseif castable == true then
		local evadingPosition = target + (Vector(myHero) - target):normalized()*(GetDistance(target)+wRange)
		if not intersectWall(evadingPosition, wRange) then
			CastSpell(_W, evadingPosition.x, evadingPosition.y) 
			--PrintChat("Cast Evade")
		end
	end
end