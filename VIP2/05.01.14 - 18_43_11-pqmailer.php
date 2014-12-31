<?php exit() ?>--by pqmailer 217.82.4.36
local Version = 0.21
local Config
local ts
local SpellData = {}
local pd, tpE, tpR
local AxeTable = {}
local QData = {Stacks = 0, TS = 0}
local wUp = false
local enemyMinions
local PSAuth, PSUpdate, PSCombat

function OnLoad()
	Config = scriptConfig("[PS] Draven v."..tostring(Version), "psdraven")

	Config:addSubMenu("Combo", "combo")
	Config.combo:addParam("teamfight", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config.combo:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("LastHit", "lasthit")
	Config.lasthit:addParam("hit", "Last Hit", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("X"))
	Config.lasthit:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Harass", "harass")
	Config.harass:addParam("poke", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))
	Config.harass:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, false)
	Config.harass:addParam("manaSlider", "Min. mana percent to use skills", SCRIPT_PARAM_SLICE, 30, 1, 100, 0)

	Config:addSubMenu("LaneClear", "laneclear")
	Config.laneclear:addParam("clear", "Lane Clear", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("V"))
	Config.laneclear:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.laneclear:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
	Config.laneclear:addParam("useEcount", "Minions to hit with E", SCRIPT_PARAM_SLICE, 3, 1, 10, 0)

	Config:addSubMenu("Ultimate", "ult")
	Config.ult:addParam("useRinRange", "Use R in AA range to execute", SCRIPT_PARAM_ONOFF, true)
	Config.ult:addParam("useRoutRange", "Use R out of AA range to execute", SCRIPT_PARAM_ONOFF, true)
	Config.ult:addParam("useRoutRangeDistance", "Distance to execute out of AA range", SCRIPT_PARAM_SLICE, 1500, 1, 3000, 0)

	Config:addSubMenu("Killsteal", "ks")
	Config.ks:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Axe options", "axe")
	Config.axe:addParam("forceCatch", "Ignore any other movement", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("Y"))
	Config.axe:addParam("catchRange", "Catch range", SCRIPT_PARAM_SLICE, 1200, 200, 1500, 0)
	Config.axe:addParam("safeZone", "Safe zone", SCRIPT_PARAM_SLICE, 100, 1, 200, 0)

	Config.axe:permaShow("forceCatch")

	SpellData = {
		Q = {Radius = 90, Cap = 2},
		E = {Range = 1050, Speed = 1400, Delay = 0.280, Width = 90},
		R = {Range = math.huge, Speed = 464, Delay = 0.500, Width = 120}
	}

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, SpellData.E.Range, DAMAGE_PHYSICAL)
	ts.name = "Draven"
	Config:addTS(ts)

	enemyMinions = minionManager(MINION_ENEMY, SpellData.E.Range, myHero, MINION_SORT_HEALTH_ASC)

	pd = ProdictManager.GetInstance()
	tpE = pd:AddProdictionObject(_E, SpellData.E.Range, SpellData.E.Speed, SpellData.E.Delay, SpellData.E.Width, myHero)
	tpR = pd:AddProdictionObject(_R, SpellData.R.Range, SpellData.R.Speed, SpellData.R.Delay, SpellData.R.Width, myHero)

	PSCombat = ProSeriesCombatHandler(ts)
	PSAuth = ProSeriesAuth("DravenAuth")
	PSUpdate = ProSeriesUpdate("DravenUpdate")
	PSUpdate.LocalVersion = Version
	PSUpdate.SCRIPT_NAME = "PSDraven"
	PSUpdate.SCRIPT_URL = "http://pqbol.de/scripts/release/proseries/adc/draven/PSDraven.lua"
	PSUpdate.PATH = BOL_PATH.."Scripts\\"..debug.getinfo(2).source
	PSUpdate.HOST = "pqbol.de"
	PSUpdate.URL_PATH = "/scripts/release/proseries/adc/draven/revision.lua"
	PSUpdate:Run()


	PrintChat("Pro Series "..myHero.charName.. " Edition v."..tostring(Version).." loaded")
end

function OnTick()
	if PSAuth:IsAuthed() == false then
		return
	end
	local Target = PSCombat:GetTarget()
	if (Config.ks.useE and myHero:CanUseSpell(_E) == READY) or ((Config.ult.useRinRange or Config.ult.useRoutRange) and myHero:CanUseSpell(_R) == READY) then
		for _, Enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(Enemy) then
				local Distance = GetDistanceSqr(Enemy)
				local TrueRange = PSCombat:GetTrueRange(Enemy)

				if Config.ks.useE and Distance <= SpellData.E.Range*SpellData.E.Range and getDmg("E", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_E) == READY then
					tpE:GetPredictionCallBack(Enemy, CastE)
				elseif Config.ult.useRinRange and Distance <= TrueRange*TrueRange and getDmg("R", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_R) == READY then
					tpR:GetPredictionCallBack(Enemy, CastR)
				elseif Config.ult.useRoutRange and Distance <= Config.ult.useRoutRangeDistance*Config.ult.useRoutRangeDistance and Distance >= TrueRange*TrueRange and getDmg("R", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_R) == READY then
					tpR:GetPredictionCallBack(Enemy, CastR)
				end
			end
		end
	end
	if Config.laneclear.clear then
		if Config.laneclear.useQ and QData.Stacks < SpellData.Q.Cap and myHero:CanUseSpell(_Q) == READY then
			CastSpell(_Q)
		elseif Config.laneclear.useE and myHero:CanUseSpell(_E) == READY then
			local bestM, bestC = GetBestMinion()
			if bestM and bestM ~= nil and bestC >= Config.laneclear.useEcount then
				CastSpell(_E, bestM.x, bestM.z)
			end
		end
		CatchAxes()
	end
	if Config.lasthit.hit then
		if Config.lasthit.useQ and QData.Stacks < SpellData.Q.Cap and myHero:CanUseSpell(_Q) == READY then
			CastSpell(_Q)
		end
		CatchAxes()
	end
	if Target then
		if (Config.combo.teamfight and Config.combo.useE) or (Config.harass.poke and Config.harass.useE and ManaCheck()) and myHero:CanUseSpell(_E) == READY and ValidTarget(Target, SpellData.E.Range) then
			tpE:GetPredictionCallBack(Target, CastE)
		end
		if (Config.combo.teamfight and Config.combo.useW) or (Config.harass.poke and Config.harass.useW and ManaCheck()) and myHero:CanUseSpell(_W) == READY and not wUp and ValidTarget(Target, PSCombat:GetTrueRange(Target)) then
			CastSpell(_W)
		end
		if (Config.combo.teamfight and Config.combo.useQ) or (Config.harass.poke and Config.harass.useQ and ManaCheck()) or (Config.laneclear.clear and Config.laneclear.useQ) then
			if QData.Stacks < SpellData.Q.Cap and myHero:CanUseSpell(_Q) == READY and ValidTarget(Target, PSCombat:GetTrueRange(Target)) then
				CastSpell(_Q)
			end
			CatchAxes()
		end
	end
end

function OnGainBuff(unit, buff)
	if unit.isMe then
		if buff.name == "dravenspinningattack" then
			QData.Stacks = buff.stack
			QData.TS = os.clock()
		elseif buff.name == "DravenFury" then
			wUp = true
		end
	end
end

function OnUpdateBuff(unit, buff)
	if unit.isMe then
		if buff.name == "dravenspinningattack" then
			QData.Stacks = buff.stack
			QData.TS = os.clock()
		end
	end
end

function OnLoseBuff(unit, buff)
	if unit.isMe then
		if buff.name == "dravenspinningattack" then
			QData.Stacks = 0
			QData.TS = os.clock()
		elseif buff.name == "DravenFury" then
			wUp = false
		end
	end
end

function OnCreateObj(object)
	if object and object.valid and object.name == "Draven_Q_reticle_self.troy" and GetDistanceSqr(object) <= Config.axe.catchRange*Config.axe.catchRange then
		table.insert(AxeTable, object)
	end
end

function OnDeleteObj(object)
	if object and object.name == "Draven_Q_reticle_self.troy" then
		table.remove(AxeTable, 1)
	end
end

function OnSendPacket(p)
	local packet = Packet(p)

	if Config.axe.forceCatch and next(AxeTable) and packet:get('name') == 'S_MOVE' then
		if packet:get('sourceNetworkId') == myHero.networkID then
			packet:block()
		end
	end
end

function CatchAxes()
	if next(AxeTable) then
		if _G.AutoCarry then
			_G.AutoCarry.CanMove = ((Config.axe.forceCatch and GetDistanceSqr(AxeTable[1]) > SpellData.Q.Radius*SpellData.Q.Radius) and false or true)
		end
		if Config.axe.forceCatch and GetDistanceSqr(AxeTable[1]) > SpellData.Q.Radius*SpellData.Q.Radius then
			Packet('S_MOVE', {x = AxeTable[1].x, y = AxeTable[1].z}):send()
		elseif CanMove() and GetDistanceSqr(AxeTable[1]) > SpellData.Q.Radius*SpellData.Q.Radius and IsZoneSafe(AxeTable[1], Config.axe.safeZone) and not UnderTurret(AxeTable[1], true) then
			myHero:MoveTo(AxeTable[1].x, AxeTable[1].z)
		end
	end
end

function CastE(unit, pos, spell)
	if ValidTarget(unit, SpellData.E.Range) and myHero:CanUseSpell(_E) == READY and pos ~= nil then
		CastSpell(_E, pos.x, pos.z)
	end
end

function CastR(unit, pos, spell)
	if ValidTarget(unit) and myHero:CanUseSpell(_R) == READY and pos ~= nil then
		CastSpell(_R, pos.x, pos.z)
	end
end

function CanMove()
	if _G.Evade or _G.evade or (_G.MMA_Loaded and not _G.MMA_AbleToMove) or (_G.AutoCarry and _G.AutoCarry.Orbwalker and GetTickCount() > _G.AutoCarry.Orbwalker:GetNextAttackTime()) then
		return false
	end
	
	return true
end

function IsZoneSafe(Pos)
	local Safe = true
	local Distance = Config.axe.safeZone*Config.axe.safeZone

	for _, Enemy in pairs(GetEnemyHeroes()) do
		if Enemy and Enemy.valid and GetDistanceSqr(Pos, Enemy) < Distance then
			safe = false
			break
		end
	end

	return Safe
end

function ManaCheck()
	return (myHero.mana > (myHero.maxMana*(Config.harass.manaSlider/100)))
end

function GetBestMinion()
	enemyMinions:update()
	local bestMinion = nil
	local bestHit = 0

	for _, minion in ipairs(enemyMinions.objects) do
		local willHit = 0
		for _, minion2 in ipairs(enemyMinions.objects) do
			local ex = myHero.x
			local ez = myHero.z
			local tx = minion2.x
			local tz = minion2.z
			dx = ex - tx
				dz = ez - tz
			if dx ~= 0 then
				m = dz/dx
				c = ez - m*ex
			end
			mx = minion.x
			mz = minion.z
			distanc = (math.abs(mz - m*mx - c))/(math.sqrt(m*m+1))
			if distanc < SpellData.E.Width and math.sqrt((tx - ex)*(tx - ex) + (tz - ez)*(tz - ez)) > math.sqrt((tx - mx)*(tx - mx) + (tz - mz)*(tz - mz)) then
				willHit = willHit + 1
			end

			if willHit > bestHit then
				bestMinion = minion2
				bestHit = willHit
			end
		end
	end

	return bestMinion, bestHit
end