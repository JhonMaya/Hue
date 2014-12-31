<?php exit() ?>--by bothappy 83.38.21.248
--Akali by BotHappy and PQMailer


if myHero.charName ~= "Akali" then return end

require "SALib"

function GetHeroTable(mode)
	mode = mode or 1 -- 1 All; 2 Enemy; 3 Ally
	local tempTable = {}

	for i=1, heroManager.iCount do
		local hero = heroManager:GetHero(i)

		if mode == 1 or (mode == 2 and hero.team ~= myHero.team) or (mode == 3 and hero.team == myHero.team) then
			table.insert(tempTable, hero)
		end
	end

	return tempTable
end

local Config, rStacks, localVersion, heroTable, enemyTable, allyTable, towerFocusTable, lockedTarget, targetGlobal, targetExpected, IgniteSlot, lastAttack = nil, 0, 0.01, GetHeroTable(1), GetHeroTable(2), GetHeroTable(3), {}, nil, nil, nil, nil, os.clock()
local immuneEffects = {
	{'zhonyas_ring_activate.troy', 2.5},
	{'Aatrox_Passive_Death_Activate.troy', 3},
	{'LifeAura.troy', 4},
	{'nickoftime_tar.troy', 7},
	{'eyeforaneye_self.troy', 2},
	{'UndyingRage_buf.troy', 5},
	{'EggTimer.troy', 6},
}
local immuneTable = {}

SAUpdate = Updater("AkaliUpdate") 
SAUpdate.LocalVersion = localVersion 
SAUpdate.SCRIPT_NAME = "BHAkali" 
SAUpdate.SCRIPT_URL = "https://bitbucket.org/BotHappy/bhscripts/raw/master/BHAkali.lua" 
SAUpdate.PATH = BOL_PATH.."Scripts\\".."BHAkali.lua" 
SAUpdate.HOST = "bitbucket.org" 
SAUpdate.URL_PATH = "/BotHappy/bhscripts/raw/master/REV/AkaliRev.lua" 
SAUpdate:Run()
SAAuth = Auth("AkaliAuth")


function OnLoad()
	--scriptStart = os.clock()
	Menu()
	SpellData = {
		Q = {range = 600, ready = false, duration = 6, applied = 0},
		E = {range = 325, ready = false},
		R = {range = 800, ready = false},
		Ignite = {range = 600, ready = false}
	}

	ORB = Orbwalker("FallbackOrbwalker")

	IgniteSlot = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)

	print("<font color='#FFFFFF'> >> BH Akali v."..tostring(localVersion).." << </font>")
end

function Menu()
	Config = scriptConfig("BH Akali v."..tostring(localVersion), "BHAkali")
	Config:addParam("doCombo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)

	Config:addSubMenu("Combo", "combo")
	Config.combo:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useItems", "Use Items", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useIgnite", "Use Ignite", SCRIPT_PARAM_ONOFF, false)

	Config:addSubMenu("Harass", "harass")
	Config.harass:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, false)
	Config.harass:addParam("useRkeep", "Keep R stacks", SCRIPT_PARAM_SLICE, 2, 1, 3, 0)

	Config:addSubMenu("KS", "ks")
	Config.ks:addParam("smartKS", "Use smart KS", SCRIPT_PARAM_ONOFF, true)
	Config.ks:addParam("safeBalance", "Enemy balance to enable KS", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
	Config.ks:addParam("safeZone", "Hero range to the target", SCRIPT_PARAM_SLICE, 500, 200, 1000, 0)

	Config:addSubMenu("Draws", "draws")
	Config.draws:addParam("target", "Draw Target", SCRIPT_PARAM_ONOFF, true)
	Config.draws:addParam("kill", "Damage Draws", SCRIPT_PARAM_ONOFF, true)
	Config.draws:addParam("circles", "Draw Circles", SCRIPT_PARAM_ONOFF, true)

	Config:permaShow("doCombo")
	Config.ks:permaShow("smartKS")
end

function OnTick()
	if SAAuth:IsAuthed() == false then 
		return 
	end
	--if os.clock() > scriptStart + 20 and not SAAuth:IsAuthed() then UnloadScript(debug.getinfo(2).source) end
	__tickChecks()
	__clearLockedTarget()
	__clearTowerFocus()
	__clearImmuneTable()
	targetGlobal = __selectTarget(SpellData.R.range)
	targetExpected = __selectTarget(2000)

	if Config.doCombo and targetGlobal then
		if __killCombo(targetGlobal) then
			lockedTarget = targetGlobal
		else
			__harassCombo(targetGlobal)
		end
	end

	if Config.ks.smartKS then
		for _, enemy in ipairs(GetEnemyHeroes()) do
		local rKSDmg = ((SpellData.R.ready and getDmg("R", enemy, myHero)) or 0)
		local rEnergy = myHero:GetSpellData(_R).mana
			if __validTarget(enemy, SpellData.R.range) then
				if enemy.health < rKSDmg and myHero.mana > rEnergy then
					CastSpell(_R, enemy)
				elseif enemy.health < rKSDmg*2 and rStacks > 1 and myHero.mana > rEnergy*2 then
					CastSpell(_R, enemy)
				end
			end
		end
	end

	if Config.ks.smartKS then
		if lockedTarget then
			__AutoKS(lockedTarget)
		else
			for _, enemy in ipairs(GetEnemyHeroes()) do
				if __validTarget(enemy) and not UnderTurret(enemy, true) and __safeBalance(enemy, Config.ks.safeZone, Config.ks.safeBalance) then
					if __killCombo(enemy) then
						lockedTarget = enemy
					end
				end
			end
		end
	end
end

function OnDraw()
	if SAAuth:IsAuthed() == false then 
		return 
	end
	if Config.draws.target then __drawTarget() end
	if Config.draws.kill then __drawHPBar() end
	if Config.draws.circles then
		Drawer:DrawCircleHero(myHero, SpellData.Q.range, Drawer.Cyan, SpellData.Q.ready)
		Drawer:DrawCircleHero(myHero, SpellData.E.range, Drawer.Red, SpellData.E.ready)
		Drawer:DrawCircleHero(myHero, SpellData.R.range, Drawer.Green, SpellData.R.ready)
	end
end

function __drawTarget()
	if targetGlobal then
		DrawText3D("Current Target", targetGlobal.x, targetGlobal.y, targetGlobal.z, 20, RGB(255, 255, 255), true)
	elseif targetExpected then
		DrawText3D("Expected Target", targetExpected.x, targetExpected.y, targetExpected.z, 20, RGB(255, 255, 255), true)
	end
end

function __drawHPBar()
	for _, enemy in ipairs(enemyTable) do
		if __validTarget(enemy, 2000) then
			local comboDmg, comboArray, neededEnergy = __comboDmg(enemy)
			local barTable = {}
			local indicator_table = {}
			local height_modifier = 0
			local barPos = __getHPBarPosition(enemy)

			for i, dmg in pairs(comboArray) do
				if dmg ~= 0 then
					local health_left = math.round(enemy.health - dmg)
					local percent_left = math.round(health_left / enemy.maxHealth * 100)
					local percent_bar = percent_left / 103 * 100
					barTable[i] = {damage_percent = percent_left, IndicatorPos = percent_bar}
				end
			end

			for i, spell in pairs(barTable) do
				local Text, IndicatorPos = tostring(i), spell.IndicatorPos

				if IndicatorPos ~= nil then 
					indicator_table[i] = IndicatorPos
					for k,v in pairs(indicator_table) do 
						if v == IndicatorPos and k ~= i then 
							height_modifier = height_modifier + 10 
						end 
					end
					DrawText(tostring(Text),13,barPos.x+IndicatorPos - 10 ,barPos.y- 27,ARGB(255,0,255,0))	
					DrawText("|",13,barPos.x+IndicatorPos ,barPos.y ,ARGB(255,0,255,0))
					DrawText("|",13,barPos.x+IndicatorPos ,barPos.y-9 ,ARGB(255,0,255,0))
					DrawText("|",13,barPos.x+IndicatorPos ,barPos.y-18 ,ARGB(255,0,255,0))
				end 
			end
		end
	end
end

function __getHPBarPosition(unit)
	unit.barData = GetEnemyBarData()
	local barPos = GetUnitHPBarPos(unit)
	local barPosOffset = GetUnitHPBarOffset(unit)
	local barOffset = { x = unit.barData.PercentageOffset.x, y = unit.barData.PercentageOffset.y }
	local barPosPercentageOffset = { x = unit.barData.PercentageOffset.x, y = unit.barData.PercentageOffset.y }
	local BarPosOffsetX = 171
	local BarPosOffsetY = 46
	local CorrectionY =  14.5
	local StartHpPos = 31

	barPos.x = barPos.x + (barPosOffset.x - 0.5 + barPosPercentageOffset.x) * BarPosOffsetX + StartHpPos
	barPos.y = barPos.y + (barPosOffset.y - 0.5 + barPosPercentageOffset.y) * BarPosOffsetY + CorrectionY

	return barPos
end

function __tickChecks()
	SpellData.Q.ready = (myHero:CanUseSpell(_Q) == READY)
	SpellData.E.ready = (myHero:CanUseSpell(_E) == READY)
	SpellData.R.ready = (myHero:CanUseSpell(_R) == READY)
	SpellData.Ignite.ready = (IgniteSlot ~= nil and myHero:CanUseSpell(IgniteSlot) == READY)
end

function __selectTarget(range)
	range = range or myHero.range
	local bestT, bestO = nil, 0
	local useTable = (#towerFocusTable > 0 and towerFocusTable or enemyTable)

	if lockedTarget then
		return lockedTarget
	else
		for _, hero in pairs(useTable) do
			if __validTarget(hero, range) then
				if bestT ~= nil and bestT.valid then
					local maxDmg = __comboDmg(hero)

					if (hero.health - maxDmg) < bestO then
						bestT = hero
						bestO = hero.health - maxDmg
					end
				else
					bestT = hero
					bestO = hero.health - __comboDmg(hero)
				end
			end
		end
	end

	return bestT, bestO
end

function __clearLockedTarget()
	if lockedTarget then
		if not __validTarget(lockedTarget, SpellData.R.range) or (not SpellData.Q.ready and not SpellData.E.ready and not SpellData.R.ready) or os.clock() > lastAttack + 10 then
			lockedTarget = nil
		end
	end
end

function __comboDmg(unit)
	local onHitDmg = (GetInventoryHaveItem(3057) and getDmg("SHEEN", unit, myHero) or 0) + (GetInventoryHaveItem(3078) and getDmg("TRINITY", unit, myHero) or 0) + (GetInventoryHaveItem(3100) and getDmg("LICHBANE", unit, myHero) or 0) + (GetInventoryHaveItem(3025) and getDmg("ICEBORN", unit, myHero) or 0) + (GetInventoryHaveItem(3087) and getDmg("STATIKK", unit, myHero) or 0) + (GetInventoryHaveItem(3209) and getDmg("SPIRITLIZARD", unit, myHero) or 0)
	local onSpellDmg = (GetInventoryHaveItem(3151) and getDmg("LIANDRYS", unit, myHero) or 0) + (GetInventoryHaveItem(3188) and getDmg("BLACKFIRE", unit, myHero) or 0)
	local comboArray = {Q = 0, E = 0, R = 0, IGN = 0, I = onHitDmg + onSpellDmg}
	local neededEnergy = 0
	local maxDmg = onHitDmg + onSpellDmg

	if SpellData.Ignite.ready then
		local ignDamage = getDmg("IGNITE", unit, myHero)
		maxDmg = maxDmg + ignDamage
		comboArray.IGN = ignDamage
	end

	if GetInventoryItemIsCastable(3144) then
		local bwcDamage = getDmg("BWC", unit, myHero)
		maxDmg = maxDmg + bwcDamage
		comboArray.I = comboArray.I + bwcDamage
	end

	if GetInventoryItemIsCastable(3146) then
		local hxgDamage = getDmg("HXG", unit, myHero)
		maxDmg = maxDmg + hxgDamage
		comboArray.I = comboArray.I + hxgDamage
	end

	if GetInventoryItemIsCastable(3128) then
		local dfgDamage = getDmg("DFG", unit, myHero)
		maxDmg = maxDmg + dfgDamage
		comboArray.I = comboArray.I + dfgDamage
	end

	if GetInventoryItemIsCastable(3188) then
		local bftDamage = getDmg("BLACKFIRE", unit, myHero)
		maxDmg = maxDmg + bftDamage
		comboArray.I = comboArray.I + bftDamage
	end

	if SpellData.Q.ready then
		local qDamage = getDmg("Q", unit, myHero, 3)
		local qMana = myHero:GetSpellData(_Q).mana

		if myHero.mana >= neededEnergy + qMana and unit.health > maxDmg + qDamage then
			comboArray.Q = qDamage
			neededEnergy = neededEnergy + qMana
			maxDmg = maxDmg + qDamage
		else
			return maxDmg, comboArray, neededEnergy
		end
	end

	if SpellData.E.ready then
		local eDamage = getDmg("E", unit, myHero)
		local eMana = myHero:GetSpellData(_Q).mana

		if myHero.mana >= neededEnergy + eMana and unit.health > maxDmg + eDamage then
			comboArray.E = eDamage
			neededEnergy = neededEnergy + eMana
			maxDmg = maxDmg + eDamage
		else
			return maxDmg, comboArray, neededEnergy
		end
	end

	if SpellData.R.ready then
		local rDamage = getDmg("R", unit, myHero)
		local rMana = myHero:GetSpellData(_R).mana
		for i=1, rStacks do
			if myHero.mana >= neededEnergy + rMana and unit.health > maxDmg + rDamage then
				comboArray.R = comboArray.R + rDamage
				neededEnergy = neededEnergy + rMana
				maxDmg = maxDmg + rDamage
			else
				return maxDmg, comboArray, neededEnergy
			end
		end
	end

	return maxDmg, comboArray, neededEnergy
end

function __harassCombo(unit)
	if __validTarget(unit) then
		local Distance = GetDistance(myHero, unit)
		if Config.harass.useQ and SpellData.Q.ready and Distance <= SpellData.Q.range then
			CastSpell(_Q, unit)
		elseif Config.harass.useE and SpellData.E.ready and Distance <= SpellData.E.range then
			CastSpell(_E)
		elseif Config.harass.useR and SpellData.R.ready and Distance <= SpellData.R.range and Distance >= myHero.range and rStacks > Config.harass.useRkeep then
			CastSpell(_R, unit)
		end 
	end
end

function __killCombo(unit)

	local Health = unit.health + ((unit.hpRegen/5) * 1)

	--Energy
	local qEnergy = myHero:GetSpellData(_Q).mana
	local eEnergy = myHero:GetSpellData(_E).mana
	local rEnergy = myHero:GetSpellData(_R).mana

	--Abilities
	local passKSDmg = getDmg("P", unit, myHero)
	local ATTKSDmg = getDmg("AD", unit, myHero)
	local q1KSDmg = ((SpellData.Q.ready and getDmg("Q", unit, myHero, 1)) or 0)
	local q2KSDmg = ((SpellData.Q.ready and getDmg("Q", unit, myHero, 2)) or 0)
	local q3KSDmg = ((SpellData.Q.ready and getDmg("Q", unit, myHero, 3)) or 0)
	local eKSDmg = ((SpellData.E.ready and getDmg("E", unit, myHero)) or 0)
	local rKSDmg = ((SpellData.R.ready and getDmg("R", unit, myHero)) or 0)

	--Summoner
	local IgnKSDmg = ((SpellData.Ignite.ready and getDmg("IGNITE", unit, myHero)) or 0)

	--Castable Items
	local HXGKSDmg = ((GetInventoryItemIsCastable(3146) and getDmg("HXG", unit, myHero)) or 0)
	local DFGKSDmg = ((GetInventoryItemIsCastable(3128) and getDmg("DFG", unit, myHero)) or 0)
	local BFTKSDmg = ((GetInventoryHaveItem(3188) and getDmg("BLACKFIRE", unit, myHero)) or 0)
	local BWCKSDmg = ((GetInventoryHaveItem(3144) and getDmg("BWC", unit, myHero)) or 0)

	--OnHit Items
	local LianKSDmg = ((GetInventoryHaveItem(3151) and getDmg("LIANDRYS", unit, myHero)) or 0)
	local SheenKSDmg = ((GetInventoryHaveItem(3057) and getDmg("SHEEN", unit, myHero)) or 0)
	local TrinityKSDmg = ((GetInventoryHaveItem(3078) and getDmg("TRINITY", unit, myHero)) or 0)
	local LithKSDmg = ((GetInventoryHaveItem(3100) and getDmg("LICHBANE", unit, myHero)) or 0)
	local IGKSDmg = ((GetInventoryHaveItem(3025) and getDmg("ICEBORN", unit, myHero)) or 0)
	local SSKSDmg = ((GetInventoryHaveItem(3087) and getDmg("STATIKK", unit, myHero)) or 0)
	local LizKSDmg = ((GetInventoryHaveItem(3209) and getDmg("SPIRITLIZARD", unit, myHero)) or 0)

	--Total
	local OnHitDmg = ATTKSDmg + passKSDmg + LianKSDmg + SheenKSDmg + TrinityKSDmg + LithKSDmg + IGKSDmg + SSKSDmg + LizKSDmg
	local ItemCastDmg = ((Config.combo.useItems and HXGKSDmg + DFGKSDmg + BFTKSDmg + BWCKSDmg) or 0)
	local SummDmg = ((Config.combo.useIgnite and IgnKSDmg) or 0)
	local TotalCastDmg = ItemCastDmg + SummDmg

	if __validTarget(unit) then
		local Distance = GetDistance(unit)
		if __qMarkApplied() then --If the unit has the mark
			if Distance < SpellData.E.range and myHero.mana > eEnergy and SpellData.E.ready then -- If we are on ERange
				if Health < eKSDmg + TotalCastDmg then
					__castItems(unit)
					CastSpell(_E) --If we can kill him without the mark. It's a check, it's the same as the next one.
					return true
				elseif Health < eKSDmg + q2KSDmg + TotalCastDmg then
					__castItems(unit)
					CastSpell(_E) --Kill with E
					return true
				elseif SpellData.R.ready then --If this doesn't work well just write them all as if-elses, I tried to clear some code here
					if Health < eKSDmg + q2KSDmg + rKSDmg + OnHitDmg + TotalCastDmg and myHero.mana > eEnergy + rEnergy then
						__castItems(unit)
						CastSpell(_E) --It does the E and jumps to R function
						return true
					elseif Health < eKSDmg + q2KSDmg + (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > eEnergy + rEnergy*2 and rStacks > 1 then
						__castItems(unit)
						CastSpell(_E) --It does the E and jumps to R function
						return true
					elseif Health < eKSDmg + q2KSDmg + (rKSDmg + OnHitDmg)*3 + TotalCastDmg and myHero.mana > eEnergy + rEnergy*3 and rStacks > 2 then
						__castItems(unit)
						CastSpell(_E) --It does the E and jumps to R function
						return true
					end
				end
			end
			if Distance < SpellData.R.range and myHero.mana > rEnergy and SpellData.R.ready and rStacks > 0 then --If we are on RRange
				if Health < (rKSDmg + OnHitDmg) + TotalCastDmg then
					__castItems(unit)
					CastSpell(_R, unit) --If we can kill with a simple R, it does it.
					return true
				elseif Health < (rKSDmg + OnHitDmg) + eKSDmg + q2KSDmg + TotalCastDmg and myHero.mana > eEnergy + rEnergy and SpellData.E.ready then
					__castItems(unit)
					CastSpell(_R, unit) --If we can kill with 
					return true
				elseif Health < (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > rEnergy*2 and rStacks > 1 then
					__castItems(unit)
					CastSpell(_R, unit) --Using 2 Rs. It jumps back to the first function.
					return true
				elseif Health < (rKSDmg + OnHitDmg)*3 + TotalCastDmg and myHero.mana > rEnergy*3 and rStacks > 2 then
					__castItems(unit)
					CastSpell(_R, unit) --Using 3 Rs. It jumps back to the function before.
					return true
				end
			end
		else --If the unit doesn't have the mark
			if myHero.mana > eEnergy and Distance < SpellData.E.range and SpellData.E.ready then --If we are on Erange
				if Health < eKSDmg + TotalCastDmg then
					__castItems(unit)
					CastSpell(_E) --Simple E Kill.
					return true
				elseif SpellData.R.ready and rStacks > 0 then --If we have R and are on ERange
					if Health < eKSDmg + (rKSDmg + OnHitDmg) + TotalCastDmg and myHero.mana > eEnergy + rEnergy then
						__castItems(unit)
						CastSpell(_E) --E + R without mark. It jumps to R function.
						return true
					elseif Health < eKSDmg + (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > eEnergy + rEnergy*2 and rStacks > 1 then
						__castItems(unit)
						CastSpell(_E) --E + R*2 without mark. It jumps to R function.
						return true
					elseif Health < eKSDmg + (rKSDmg + OnHitDmg)*3 + TotalCastDmg and myHero.mana > eEnergy + rEnergy*3 and rStacks > 2 then
						__castItems(unit)
						CastSpell(_E) --E + R*3 without mark. It jumps to R function.
						return true
					end
				end
			end
			if myHero.mana > qEnergy and Distance < SpellData.Q.range and SpellData.Q.ready then --If we are on Qrange
				if Health < q1KSDmg + TotalCastDmg then
					__castItems(unit)
					CastSpell(_Q, unit) --Simple Q kill
					return true
				elseif Health < q3KSDmg + TotalCastDmg and Distance < __trueRange(unit) then
					__castItems(unit)
					CastSpell(_Q, unit) --Q + Attack
					return true
				elseif SpellData.R.ready and rStacks > 0 then --I check for R here to be sure I can get the enemy if I'm not near him
					if SpellData.E.ready then --If we have E ready
						if Health < q3KSDmg + eKSDmg + (rKSDmg + OnHitDmg) + TotalCastDmg and myHero.mana > qEnergy + eEnergy + rEnergy then
							__castItems(unit)
							CastSpell(_Q, unit) --Q+E+R, it jumps back to E function if we are near, and R if we are far.
							return true
						elseif Health < q3KSDmg + eKSDmg + (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > qEnergy + eEnergy + rEnergy*2 and rStacks > 1 then
							__castItems(unit)
							CastSpell(_Q, unit) --Q+E+R*2, as above.
							return true
						elseif Health < q3KSDmg + eKSDmg + (rKSDmg + OnHitDmg)*3 + TotalCastDmg and myHero.mana > qEnergy + eEnergy + rEnergy*3 and rStacks > 2 then
							__castItems(unit)
							CastSpell(_Q, unit) --Q+E+R*3, as above.
							return true
						end
					else --If we don't have E ready
						if Health < q3KSDmg + TotalCastDmg and myHero.mana > qEnergy + rEnergy then
							__castItems(unit)
							CastSpell(_Q, unit) --Q + R (To Autoattack) (Not sure if good coded)
							return true
						elseif Health < q3KSDmg + (rKSDmg + OnHitDmg) + TotalCastDmg and myHero.mana > qEnergy + rEnergy then
							__castItems(unit)
							CastSpell(_Q, unit) --Q + R(Needed) + Auto
							return true
						elseif Health < q3KSDmg + (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > qEnergy + rEnergy*2 and rStacks > 1 then
							__castItems(unit)
							CastSpell(_Q, unit) --Q+R*2 + Auto
							return true
						elseif Health < q3KSDmg + (rKSDmg + OnHitDmg)*3 + TotalCastDmg and myHero.mana > qEnergy + rEnergy*3 and rStacks > 2 then
							__castItems(unit)
							CastSpell(_Q, unit) --Q+R*3 + Auto
							return true
						end
					end
				end
			end
			if myHero.mana > rEnergy and Distance < SpellData.R.range and SpellData.R.ready and rStacks > 0 then --If in RRange
				if Health < (rKSDmg + OnHitDmg) + TotalCastDmg then
					__castItems(unit)
					CastSpell(_R, unit) --Simple R without mark
					return true
				elseif Health < (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > rEnergy*2 and rStacks > 1 then
					__castItems(unit)
					CastSpell(_R, unit) --R*2
					return true
				elseif Health < (rKSDmg + OnHitDmg)*3 + TotalCastDmg and myHero.mana > rEnergy*3 and rStacks > 2 then
					__castItems(unit)
					CastSpell(_R, unit) --R*3
					return true
				elseif SpellData.E.ready then --If we have E Ready
					if Health < (rKSDmg + OnHitDmg) + eKSDmg + TotalCastDmg and myHero.mana > rEnergy + eEnergy then
						__castItems(unit)
						CastSpell(_R) --R + E without mark
						return true
					elseif Health < (rKSDmg + OnHitDmg) + eKSDmg + q3KSDmg + TotalCastDmg and myHero.mana > rEnergy + eEnergy + qEnergy and SpellData.Q.ready then
						__castItems(unit)
						CastSpell(_R) --R + Q + E
						return true
					elseif Health < (rKSDmg + OnHitDmg)*2 + eKSDmg + q3KSDmg + TotalCastDmg and myHero.mana > rEnergy*2 + eEnergy + qEnergy and SpellData.Q.ready and rStacks > 1 then
						__castItems(unit)
						CastSpell(_R) --R*2 + Q + E 
						return true
					elseif Health < (rKSDmg + OnHitDmg)*3 + eKSDmg + q3KSDmg + TotalCastDmg and myHero.mana > rEnergy*3 + eEnergy + qEnergy and SpellData.Q.ready and rStacks > 2 then
						__castItems(unit)
						CastSpell(_R) --R*3 + Q + E
						return true
					end
				end
			end
		end
	end
	return false
end

--AutoKSFunction, without 3R, only with 2 to be less YOLO
function __AutoKS(unit)

	local Health = unit.health + ((unit.hpRegen/5) * 1)

	--Energy
	local qEnergy = myHero:GetSpellData(_Q).mana
	local eEnergy = myHero:GetSpellData(_E).mana
	local rEnergy = myHero:GetSpellData(_R).mana

	--Abilities
	local passKSDmg = getDmg("P", unit, myHero)
	local ATTKSDmg = getDmg("AD", unit, myHero)
	local q1KSDmg = ((SpellData.Q.ready and getDmg("Q", unit, myHero, 1)) or 0)
	local q2KSDmg = ((SpellData.Q.ready and getDmg("Q", unit, myHero, 2)) or 0)
	local q3KSDmg = ((SpellData.Q.ready and getDmg("Q", unit, myHero, 3)) or 0)
	local eKSDmg = ((SpellData.E.ready and getDmg("E", unit, myHero)) or 0)
	local rKSDmg = ((SpellData.R.ready and getDmg("R", unit, myHero)) or 0)

	--Summoner
	local IgnKSDmg = ((SpellData.Ignite.ready and getDmg("IGNITE", unit, myHero)) or 0)

	--Castable Items
	local HXGKSDmg = ((GetInventoryItemIsCastable(3146) and getDmg("HXG", unit, myHero)) or 0)
	local DFGKSDmg = ((GetInventoryItemIsCastable(3128) and getDmg("DFG", unit, myHero)) or 0)
	local BFTKSDmg = ((GetInventoryHaveItem(3188) and getDmg("BLACKFIRE", unit, myHero)) or 0)
	local BWCKSDmg = ((GetInventoryHaveItem(3144) and getDmg("BWC", unit, myHero)) or 0)

	--OnHit Items
	local LianKSDmg = ((GetInventoryHaveItem(3151) and getDmg("LIANDRYS", unit, myHero)) or 0)
	local SheenKSDmg = ((GetInventoryHaveItem(3057) and getDmg("SHEEN", unit, myHero)) or 0)
	local TrinityKSDmg = ((GetInventoryHaveItem(3078) and getDmg("TRINITY", unit, myHero)) or 0)
	local LithKSDmg = ((GetInventoryHaveItem(3100) and getDmg("LICHBANE", unit, myHero)) or 0)
	local IGKSDmg = ((GetInventoryHaveItem(3025) and getDmg("ICEBORN", unit, myHero)) or 0)
	local SSKSDmg = ((GetInventoryHaveItem(3087) and getDmg("STATIKK", unit, myHero)) or 0)
	local LizKSDmg = ((GetInventoryHaveItem(3209) and getDmg("SPIRITLIZARD", unit, myHero)) or 0)

	--Total
	local OnHitDmg = ATTKSDmg + passKSDmg + LianKSDmg + SheenKSDmg + TrinityKSDmg + LithKSDmg + IGKSDmg + SSKSDmg + LizKSDmg
	local ItemCastDmg = ((Config.combo.useItems and HXGKSDmg + DFGKSDmg + BFTKSDmg + BWCKSDmg) or 0)
	local SummDmg = ((Config.combo.useIgnite and IgnKSDmg) or 0)
	local TotalCastDmg = ItemCastDmg + SummDmg

	if __validTarget(unit) then
		local Distance = GetDistance(unit)
		if __qMarkApplied() then --If the unit has the mark
			if Distance < SpellData.E.range and myHero.mana > eEnergy and SpellData.E.ready then -- If we are on ERange
				if Health < eKSDmg + TotalCastDmg then
					__castItems(unit)
					CastSpell(_E) --If we can kill him without the mark. It's a check, it's the same as the next one.
					return true
				elseif Health < eKSDmg + q2KSDmg + TotalCastDmg then
					__castItems(unit)
					CastSpell(_E) --Kill with E
					return true
				elseif SpellData.R.ready then --If this doesn't work well just write them all as if-elses, I tried to clear some code here
					if Health < eKSDmg + q2KSDmg + rKSDmg + OnHitDmg + TotalCastDmg and myHero.mana > eEnergy + rEnergy then
						__castItems(unit)
						CastSpell(_E) --It does the E and jumps to R function
						return true
					elseif Health < eKSDmg + q2KSDmg + (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > eEnergy + rEnergy*2 and rStacks > 1 then
						__castItems(unit)
						CastSpell(_E) --It does the E and jumps to R function
						return true
					end
				end
			end
			if Distance < SpellData.R.range and myHero.mana > rEnergy and SpellData.R.ready and rStacks > 0 then --If we are on RRange
				if Health < (rKSDmg + OnHitDmg) + TotalCastDmg then
					__castItems(unit)
					CastSpell(_R, unit) --If we can kill with a simple R, it does it.
					return true
				elseif Health < (rKSDmg + OnHitDmg) + eKSDmg + q2KSDmg + TotalCastDmg and myHero.mana > eEnergy + rEnergy and SpellData.E.ready then
					__castItems(unit)
					CastSpell(_R, unit) --If we can kill with 
					return true
				elseif Health < (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > rEnergy*2 and rStacks > 1 then
					__castItems(unit)
					CastSpell(_R, unit) --Using 2 Rs. It jumps back to the first function.
					return true
				end
			end
		else --If the unit doesn't have the mark
			if myHero.mana > eEnergy and Distance < SpellData.E.range and SpellData.E.ready then --If we are on Erange
				if Health < eKSDmg + TotalCastDmg then
					__castItems(unit)
					CastSpell(_E) --Simple E Kill.
					return true
				elseif SpellData.R.ready and rStacks > 0 then --If we have R and are on ERange
					if Health < eKSDmg + (rKSDmg + OnHitDmg) + TotalCastDmg and myHero.mana > eEnergy + rEnergy then
						__castItems(unit)
						CastSpell(_E) --E + R without mark. It jumps to R function.
						return true
					elseif Health < eKSDmg + (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > eEnergy + rEnergy*2 and rStacks > 1 then
						__castItems(unit)
						CastSpell(_E) --E + R*2 without mark. It jumps to R function.
						return true
					end
				end
			end
			if myHero.mana > qEnergy and Distance < SpellData.Q.range and SpellData.Q.ready then --If we are on Qrange
				if Health < q1KSDmg + TotalCastDmg then
					__castItems(unit)
					CastSpell(_Q, unit) --Simple Q kill
					return true
				elseif Health < q3KSDmg + TotalCastDmg and Distance < __trueRange(unit) then
					__castItems(unit)
					CastSpell(_Q, unit) --Q + Attack
					return true
				elseif SpellData.R.ready and rStacks > 0 then --I check for R here to be sure I can get the enemy if I'm not near him
					if SpellData.E.ready then --If we have E ready
						if Health < q3KSDmg + eKSDmg + (rKSDmg + OnHitDmg) + TotalCastDmg and myHero.mana > qEnergy + eEnergy + rEnergy then
							__castItems(unit)
							CastSpell(_Q, unit) --Q+E+R, it jumps back to E function if we are near, and R if we are far.
							return true
						elseif Health < q3KSDmg + eKSDmg + (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > qEnergy + eEnergy + rEnergy*2 and rStacks > 1 then
							__castItems(unit)
							CastSpell(_Q, unit) --Q+E+R*2, as above.
							return true
						end
					else --If we don't have E ready
						if Health < q3KSDmg + TotalCastDmg and myHero.mana > qEnergy + rEnergy then
							__castItems(unit)
							CastSpell(_Q, unit) --Q + R (To Autoattack) (Not sure if good coded)
							return true
						elseif Health < q3KSDmg + (rKSDmg + OnHitDmg) + TotalCastDmg and myHero.mana > qEnergy + rEnergy then
							__castItems(unit)
							CastSpell(_Q, unit) --Q + R(Needed) + Auto
							return true
						elseif Health < q3KSDmg + (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > qEnergy + rEnergy*2 and rStacks > 1 then
							__castItems(unit)
							CastSpell(_Q, unit) --Q+R*2 + Auto
							return true
						end
					end
				end
			end
			if myHero.mana > rEnergy and Distance < SpellData.R.range and SpellData.R.ready and rStacks > 0 then --If in RRange
				if Health < (rKSDmg + OnHitDmg) + TotalCastDmg then
					__castItems(unit)
					CastSpell(_R, unit) --Simple R without mark
					return true
				elseif Health < (rKSDmg + OnHitDmg)*2 + TotalCastDmg and myHero.mana > rEnergy*2 and rStacks > 1 then
					__castItems(unit)
					CastSpell(_R, unit) --R*2
					return true
				elseif Health < (rKSDmg + OnHitDmg)*3 + TotalCastDmg and myHero.mana > rEnergy*3 and rStacks > 2 then
					__castItems(unit)
					CastSpell(_R, unit) --R*3
					return true
				elseif SpellData.E.ready then --If we have E Ready
					if Health < (rKSDmg + OnHitDmg) + eKSDmg + TotalCastDmg and myHero.mana > rEnergy + eEnergy then
						__castItems(unit)
						CastSpell(_R) --R + E without mark
						return true
					elseif Health < (rKSDmg + OnHitDmg) + eKSDmg + q3KSDmg + TotalCastDmg and myHero.mana > rEnergy + eEnergy + qEnergy and SpellData.Q.ready then
						__castItems(unit)
						CastSpell(_R) --R + Q + E
						return true
					elseif Health < (rKSDmg + OnHitDmg)*2 + eKSDmg + q3KSDmg + TotalCastDmg and myHero.mana > rEnergy*2 + eEnergy + qEnergy and SpellData.Q.ready and rStacks > 1 then
						__castItems(unit)
						CastSpell(_R) --R*2 + Q + E 
						return true
					end
				end
			end
		end
	end
	return false
end

function __validTarget(unit, distance)
	return (ValidTarget(unit, distance) and unit.bTargetable and not immuneTable[unit.networkID])
end

function __trueRange(unit)
	if ValidTarget(unit) then
		return GetDistance(myHero.minBBox) + myHero.range + GetDistance(unit.minBBox, unit.maxBBox)/2
	else
		return nil
	end
end

function __safeBalance(unit, distance, balance)
	distance = distance * distance
	local enemysInRange = 0
	local alliesInRange = 0

	for _, ally in pairs(allyTable) do
		if GetDistanceSqr(unit, ally) <= distance then
			alliesInRange = alliesInRange + 1
		end
	end

	for _, enemy in pairs(enemyTable) do
		if GetDistanceSqr(unit, enemy) <= distance and unit.networkID ~= enemy.networkID then
			enemysInRange = enemysInRange + 1
		end
	end

	return (balance >= (enemysInRange - alliesInRange))
end

function __castItems(unit)
	if __validTarget(unit) then
		local Distance = GetDistance(unit, myHero)
		local ItemArray = {
			["HXG"] = {id = 3146, range = 700},
			["DFG"] = {id = 3128, range = 750},
			["BLACKFIRE"] = {id = 3188, range = 750},
			["BWC"] = {id = 3144, range = 450},
			["TIAMAT"] = {id = 3077, range = 350},
			["HYDRA"] = {id = 3074, range = 350}
		}

		for _, item in pairs(ItemArray) do
			if GetInventoryItemIsCastable(item.id) and Distance <= item.range then
				CastItem(item.id, unit)
			end
		end
	end
end


function OnRecvPacket(packet)
	if packet.header == 0xFE then
		packet.pos = 1
		local unitNetworkId = packet:DecodeF()
		packet.pos = 12
		local stackCount = packet:Decode1()
			
		if unitNetworkId == myHero.networkID and (stackCount == 1 or stackCount == 2 or stackCount == 3) then
			rStacks = stackCount
		end
	end
end

function OnProcessSpell(unit, spell)
	if unit.isMe then
		if spell.name == "AkaliShadowDance" then
			rStacks = rStacks - 1
		elseif spell.name:lower():find("attack") then
			lastAttack = os.clock()
			lastWindUpTime = spell.windUpTime*1000
			lastAttackCD = spell.animationTime*1000
		end
	end
end


function OnGainBuff(unit, buff)
	if unit.team ~= myHero.team and buff.name == "AkaliMota" then
		SpellData.Q.applied = os.clock()
	end
end

function OnLoseBuff(unit, buff)
	if unit.team ~= myHero.team and buff.name == "AkaliMota" then
		SpellData.Q.applied = 0
	end
end

function OnTowerFocus(tower, unit)
	if tower and unit and unit.team ~= myHero.team and unit.type:lower() == "obj_ai_hero" then
		table.insert(towerFocusTable, unit)
	end
end

function OnCreateObj(object)
	for _, effect in pairs(immuneEffects) do
		if effect[1] == object.name then
			local nearestHero = nil

			for _, hero in pairs(heroTable) do
				if nearestHero and nearestHero.valid and hero and hero.valid then
					if GetDistanceSqr(hero, object) < GetDistanceSqr(nearestHero, object) then
						nearestHero = hero
					end
				else
					nearestHero = hero
				end
			end

			immuneTable[nearestHero.networkID] = os.clock() + effect[2]
		end
	end
end

function __clearImmuneTable()
	for networkID, duration in pairs(immuneTable) do
		if os.clock() > duration then
			immuneTable[networkID] = nil
		end
	end
end

function __clearTowerFocus()
	for i, unit in ipairs(towerFocusTable) do
		if not UnderTurret(unit, false) then
			table.remove(towerFocusTable, i)
		end
	end
end

function __qMarkApplied()
	return (SpellData.Q.applied ~= 0 and os.clock() < SpellData.Q.applied + SpellData.Q.duration)
end