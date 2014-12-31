<?php exit() ?>--by pqmailer 217.82.27.210
local Version = 0.1
local Config
local ts
local SpellData = {}
local pd, tpQ, tpE, tpR
local PSAuth, PSUpdate, PSCombat
local lastKSAttempt = 0
local DebuffTable = {}

function OnLoad()
	Config = scriptConfig("[PS] Varus v."..tostring(Version), "psvarus")

	Config:addSubMenu("Combo", "combo")
	Config.combo:addParam("teamfight", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("A"))
	Config.combo:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, false)
	Config.combo:addParam("useQstacks", "Min. stacks to use Q", SCRIPT_PARAM_SLICE, 3, 1, 3, 0)
	Config.combo:addParam("useQrange", "Minimum distance to use Q", SCRIPT_PARAM_SLICE, myHero.range + 65, 1, 1475, 0)
	Config.combo:addParam("resetQrange", "Reset Q range", SCRIPT_PARAM_ONOFF, false)
	Config.combo:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useEstacks", "Min. stacks to use E", SCRIPT_PARAM_SLICE, 3, 1, 3, 0)

	Config:addSubMenu("Harass", "harass")
	Config.harass:addParam("poke", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))
	Config.harass:addParam("pokeToggle", "Harass (Toggle)", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("Y"))
	Config.harass:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("useQstacks", "Min. stacks to use Q", SCRIPT_PARAM_SLICE, 2, 1, 3, 0)
	Config.harass:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, false)
	Config.harass:addParam("useEstacks", "Min. stacks to use E", SCRIPT_PARAM_SLICE, 2, 1, 3, 0)
	Config.harass:addParam("manaSlider", "Min. mana percent to use skills", SCRIPT_PARAM_SLICE, 30, 1, 100, 0)

	Config:addSubMenu("Ultimate", "ult")
	Config.ult:addParam("useRinRange", "Use R in AA range", SCRIPT_PARAM_ONOFF, true)
	Config.ult:addParam("useRoutRange", "Use R out of AA range", SCRIPT_PARAM_ONOFF, true)
	Config.ult:addParam("bounceCount", "Min. enemies to use R", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)

	Config:addSubMenu("Killsteal", "ks")
	Config.ks:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.ks:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
	Config.ks:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, false)

	Config:addSubMenu("Additionals", "extra")
	Config.extra:addParam("aimQtarget", "Aim to nearest mouse target", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("Q"))
	Config.extra:addParam("aimQmouse", "Aim to mouse pos", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("W"))

	SpellData = {
		Q = {minRange = 875, Range = 1475, Speed = 1850, Delay = 0, Width = 60, Chargetime = 2, ChargetimeMax = 4},
		E = {Range = 925, Speed = 1500, Delay = 0.25, Width = 225},
		R = {Range = 1075, Speed = 1200, Delay = 0.25, Width = 80, Bounce = 550}
	}

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, SpellData.Q.Range, DAMAGE_PHYSICAL)
	ts.name = "Varus"
	Config:addTS(ts)

	pd = ProdictManager.GetInstance()
	tpQ = pd:AddProdictionObject(_Q, SpellData.Q.Range, SpellData.Q.Speed, SpellData.Q.Delay, SpellData.Q.Width, myHero)
	tpE = pd:AddProdictionObject(_E, SpellData.E.Range, SpellData.E.Speed, SpellData.E.Delay, SpellData.E.Width, myHero)
	tpR = pd:AddProdictionObject(_R, SpellData.R.Range, SpellData.R.Speed, SpellData.R.Delay, SpellData.R.Width, myHero)

	PSCombat = ProSeriesCombatHandler(ts)
	PSAuth = ProSeriesAuth("VarusAuth")

	PrintChat("Pro Series "..myHero.charName.. " Edition v."..tostring(Version).." loaded")
end

function OnTick()
	if PSAuth:IsAuthed() == false then return end
	if Config.extra.aimQtarget then _AimQToEnemy(mousePos) end
	if Config.extra.aimQmouse then _AimQToPos(mousePos) end
	_ResetSettings()
	local target = PSCombat:GetTarget()
	if ((Config.ks.useQ and myHero:CanUseSpell(_Q) == READY) or (Config.ks.useE and myHero:CanUseSpell(_E) == READY) or (Config.ks.useR and myHero:CanUseSpell(_R) == READY)) and os.clock() > lastKSAttempt + 2 then
		for _, enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(enemy) then
				local distance = GetDistance(enemy)

				if Config.ks.useQ and distance <= SpellData.Q.Range and _GetDmg("Q", enemy) >= enemy.health and myHero:CanUseSpell(_Q) == READY then
					_CastQ(enemy)
					lastKSAttempt = os.clock()
				elseif Config.ks.useE and distance <= SpellData.E.Range and _GetDmg("E", enemy) >= enemy.health and myHero:CanUseSpell(_E) == READY then
					tpE:GetPredictionCallBack(enemy, _CastE)
					lastKSAttempt = os.clock()
				elseif Config.ks.useR and distance <= SpellData.R.Range and distance > PSCombat:GetTrueRange(enemy) and _GetDmg("R", enemy) >= enemy.health and myHero:CanUseSpell(_R) == READY then
					tpR:GetPredictionCallBack(enemy, _CastR)
					lastKSAttempt = os.clock()
				end
			end
		end
	end
	if ValidTarget(target) then
		local trueRange = PSCombat:GetTrueRange(target)
		local distance = GetDistance(target)

		if Config.combo.teamfight then
			if myHero:CanUseSpell(_R) == READY then
				local enemy, count = _GetREnemy()

				if enemy ~= nil and count ~= nil then
					local distance = GetDistance(enemy)
					local trueRange = PSCombat:GetTrueRange(enemy)

					if ((Config.ult.useRinRange and distance <= trueRange) or (Config.ult.useRoutRange and distance > trueRange)) and count >= Config.ult.bounceCount then
						tpR:GetPredictionCallBack(target, _CastR)
					end
				end
			elseif distance > trueRange and DebuffTable[target.networkID] ~= nil then
				if Config.combo.useQ and myHero:CanUseSpell(_Q) == READY and DebuffTable[target.networkID] >= Config.combo.useQstacks then
					local predPos = tpQ:GetPrediction(target)
					local predDistance = GetDistance(predPos)

					if predPos ~= nil and predDistance <= SpellData.Q.Range and predDistance >= Config.combo.useQrange then
						_CastQ(target)
					end
				elseif Config.combo.useE and myHero:CanUseSpell(_E) == READY and DebuffTable[target.networkID] >= Config.combo.useEstacks then
					tpE:GetPredictionCallBack(target, _CastE)
				end
			end
		elseif Config.harass.poke or Config.harass.pokeToggle then
			if distance > trueRange and DebuffTable[target.networkID] ~= nil then
				if Config.harass.useQ and myHero:CanUseSpell(_Q) == READY and DebuffTable[target.networkID] >= Config.harass.useQstacks then
					local predPos = tpQ:GetPrediction(target)

					if predPos ~= nil and GetDistance(predPos) <= SpellData.Q.Range then
						_CastQ(target)
					end
				elseif Config.harass.useE and myHero:CanUseSpell(_E) == READY and DebuffTable[target.networkID] >= Config.harass.useEstacks then
					tpE:GetPredictionCallBack(target, _CastE)
				end
			end
		end
	end
end

function OnProcessSpell(unit, spell)
	if unit.isMe and spell.name:lower():find("attack") and spell.target.type:lower() == "obj_ai_hero" and DebuffTable[spell.target.networkID] ~= nil then
		local qPos = tpQ:GetPrediction(spell.target)

		if ((Config.combo.teamfight and Config.combo.useQ and DebuffTable[spell.target.networkID] >= Config.combo.useQstacks - 1 and (qPos ~= nil and GetDistance(qPos) >= Config.combo.useQrange)) or ((Config.harass.poke or Config.harass.pokeToggle) and Config.harass.useQ and DebuffTable[spell.target.networkID] >= Config.harass.useQstacks - 1 and myHero.mana > myHero.maxMana * (Config.harass.manaSlider / 100))) and myHero:CanUseSpell(_Q) == READY then
			DelayAction(function()
				_CastQ(spell.target)
			end, spell.windUpTime - GetLatency() / 1000)
		elseif ((Config.combo.teamfight and Config.combo.useE and DebuffTable[spell.target.networkID] >= Config.combo.useEstacks - 1) or ((Config.harass.poke or Config.harass.pokeToggle) and Config.harass.useE and DebuffTable[spell.target.networkID] >= Config.harass.useEstacks - 1 and myHero.mana > myHero.maxMana * (Config.harass.manaSlider / 100))) and myHero:CanUseSpell(_E) == READY then
			DelayAction(function()
				tpE:GetPredictionCallBack(spell.target, _CastE)
			end, spell.windUpTime - GetLatency() / 1000)
		end
	end
end

function OnSendPacket(p)
	if p.header == 0xE5 then
		p.pos = 1
		
		local sourceNetworkId = p:DecodeF()
		local spellId = p:Decode1()
		
		if sourceNetworkId == myHero.networkID and spellId == 128 then
			p:Block()
		end
	end
end

function OnGainBuff(unit, buff)
	if buff.source and buff.source.isMe and buff.name == "varuswdebuff" then
		DebuffTable[unit.networkID] = 1
	end
end

function OnUpdateBuff(unit, buff)
	if buff.name == "varuswdebuff" and DebuffTable[unit.networkID] then
		DebuffTable[unit.networkID] = buff.stack
	end
end

function OnLoseBuff(unit, buff)
	if buff.name == "varuswdebuff" and DebuffTable[unit.networkID] then
		DebuffTable[unit.networkID] = nil
	end
end

function _CastQ(unit)
	if ValidTarget(unit) and myHero:CanUseSpell(_Q) == READY then
		local pos = tpQ:GetPrediction(unit)

		if pos ~= nil then
			_StartQ()

			DelayAction(function()
				_ReleaseQ(pos)
			end, _GetQHoldTime(GetDistance(pos)))
		end
	end
end

function _CastE(unit, pos, spell)
	if ValidTarget(unit) and pos ~= nil and GetDistance(pos) <= SpellData.E.Range and myHero:CanUseSpell(_E) == READY then
		CastSpell(_E, pos.x, pos.z)
	end
end

function _CastR(unit, pos, spell)
	if ValidTarget(unit) and pos ~= nil and GetDistance(pos) <= SpellData.R.Range and myHero:CanUseSpell(_R) == READY then
		CastSpell(_R, pos.x, pos.z)
	end
end

function _AimQToEnemy(pos)
	local bestT = nil

	for _, enemy in pairs(GetEnemyHeroes()) do
		if bestT and bestT.valid and enemy and enemy.valid then
			if GetDistanceSqr(enemy) < GetDistanceSqr(bestT) then
				bestT = enemy
			end
		else
			bestT = enemy
		end
	end

	if ValidTarget(bestT, SpellData.Q.Range) then
		_CastQ(bestT)
	end
end

function _AimQToPos(pos)
	if pos ~= nil then
		pos = (GetDistance(pos) <= SpellData.Q.Range and pos or (myHero + (Vector(pos) - myHero):normalized() * SpellData.Q.Range))
		_StartQ()

		DelayAction(function()
			_ReleaseQ(pos)
		end, _GetQHoldTime(GetDistance(pos)))
	end
end

function _StartQ()
	if myHero:CanUseSpell(_Q) == READY then
		CastSpell(_Q, mousePos.x, mousePos.z)
		return true
	end
	
	return false
end

function _ReleaseQ(pos)
	if pos and pos.x ~= nil and pos.y ~= nil and pos.z ~= nil then
		local p = CLoLPacket(0xE5)
		p:EncodeF(myHero.networkID)
		p:Encode1(128)
		p:EncodeF(pos.x)
		p:EncodeF(pos.y)
		p:EncodeF(pos.z)
		p.dwArg1 = 1
		p.dwArg2 = 0
		SendPacket(p)
	end
end

function _GetQTravelTime(pos)
	return GetDistance(pos) / (SpellData.Q.Speed/1000) + SpellData.Q.Delay*1000 + GetLatency()
end

function _GetQHoldTime(range)
	range = (range <= SpellData.Q.Range and range or SpellData.Q.Range)
	return (2 * range - SpellData.Q.Chargetime * SpellData.Q.minRange) / (SpellData.Q.Range - SpellData.Q.minRange)
end

function _GetREnemy()
	local bestE, bestC = nil, 0

	for _, enemy in pairs(GetEnemyHeroes()) do
		if bestE and bestE.valid and enemy and enemy.valid then
			local tempC = CountEnemyHeroInRange(SpellData.Q.Bounce, enemy)

			if tempC > bestC then
				bestE = enemy
				bestC = tempC
			end
		else
			bestE = enemy
			bestC = CountEnemyHeroInRange(SpellData.Q.Bounce, enemy)
		end
	end

	return bestE, bestC
end

function _GetDmg(skill, unit)
	local wDamage = getDmg("W", unit, myHero, 2) * (DebuffTable[unit.networkID] ~= nil and DebuffTable[unit.networkID] or 1)
	if skill == "Q" then
		local minDmg = 0.625 * (55 * myHero:GetSpellData(_Q).level - 40 + 1.6 * myHero.totalDamage)
		local maxDmg = (55 * myHero:GetSpellData(_Q).level - 40 + 1.6 * myHero.totalDamage)
		local holdTime = _GetQHoldTime(GetDistance(unit))
		local realDmg = ((2 - holdTime) * minDmg + holdTime * maxDmg) / 2

		return myHero:CalcDamage(unit, realDmg) + wDamage
	elseif skill == "E" then
		return getDmg("E", unit, myHero) + wDamage
	elseif skill == "R" then
		return getDmg("R", unit, myHero) + wDamage
	end
end

function _ResetSettings()
	if Config.combo.resetQrange then
		Config.combo.useQrange = myHero.range + 65
		Config.combo.resetQrange = false
	end
end