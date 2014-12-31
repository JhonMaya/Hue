<?php exit() ?>--by pqmailer 217.82.42.56
local Version = 0.11
local Config
local ts
local SpellData = {}
local pd, tpQ, tpW, wCol
local pUp = false
local rUp = os.clock()
local enrageBuff = {up = false, expire = os.clock()}
local enemyMinions
local PSAuth, PSUpdate, PSCombat

function OnLoad()
	Config = scriptConfig("[PS] Lucian v."..tostring(Version), "pslucian")

	Config:addSubMenu("Combo", "combo")
	Config.combo:addParam("teamfight", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config.combo:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Harass", "harass")
	Config.harass:addParam("poke", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))
	Config.harass:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("manaSlider", "Min. mana percent to use skills", SCRIPT_PARAM_SLICE, 30, 1, 100, 0)

	Config:addSubMenu("Ultimate", "ult")
	Config.ult:addParam("manualAim", "Aim to nearest mouse target", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("Y"))

	Config:addSubMenu("Killsteal", "ks")
	Config.ks:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.ks:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Additionals", "extra")
	Config.extra:addParam("weaveTriForce", "Weave Trinity Force/Sheen", SCRIPT_PARAM_ONOFF, true)
	Config.extra:addParam("extendQ", "Try to use minion->Q (Beta)", SCRIPT_PARAM_ONOFF, true)

	SpellData = {
		AA = {Range = 525},
		Q = {Range = 550, Range2 = 1100, Speed = math.huge, Delay = 0.2, Width = 65},
		W = {Range = 1000, Speed = 1600, Delay = 0.312, Width = 50},
		R = {Range = 1425, Speed = 2850, Delay = 0.249, Width = 90}
	}

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, SpellData.R.Range, DAMAGE_PHYSICAL)
	ts.name = "Lucian"
	Config:addTS(ts)

	pd = ProdictManager.GetInstance()
	tpQ = pd:AddProdictionObject(_Q, SpellData.Q.Range, SpellData.Q.Speed, SpellData.Q.Delay, SpellData.Q.Width, myHero)
	tpW = pd:AddProdictionObject(_W, SpellData.W.Range, SpellData.W.Speed, SpellData.W.Delay, SpellData.W.Width, myHero)
	tpR = pd:AddProdictionObject(_R, SpellData.R.Range, SpellData.R.Speed, SpellData.R.Delay, SpellData.R.Width, myHero)

	wCol = Collision(SpellData.W.Range, SpellData.W.Speed, SpellData.W.Delay, SpellData.W.Width)

	enemyMinions = minionManager(MINION_ENEMY, SpellData.AA.Range, myHero, MINION_SORT_HEALTH_ASC)

	PSCombat = ProSeriesCombatHandler(ts)
	PSAuth = ProSeriesAuth("LucianAuth")
	PSUpdate = ProSeriesUpdate("LucianUpdate")
	PSUpdate.LocalVersion = Version
	PSUpdate.SCRIPT_NAME = "PSLucian"
	PSUpdate.SCRIPT_URL = "http://pqbol.de/scripts/release/proseries/adc/lucian/PSLucian.lua"
	PSUpdate.PATH = BOL_PATH.."Scripts\\".."PSLucian.lua"
	PSUpdate.HOST = "pqbol.de"
	PSUpdate.URL_PATH = "/scripts/release/proseries/adc/lucian/revision.lua"
	PSUpdate:Run()

	PrintChat("Pro Series "..myHero.charName.. " Edition v."..tostring(Version).." loaded")
end

function OnTick()
	if PSAuth:IsAuthed() == false then
		return
	end
	local Target = PSCombat:GetTarget()
	if Config.ult.manualAim and myHero:CanUseSpell(_R) == READY and os.clock() > rUp + 2 then
		local NearestTarget = NearestTarget(mousePos)
		if ValidTarget(NearestTarget, SpellData.R.Range) then
			local Pos = tpR:GetPrediction(NearestTarget)
			CastSpell(_R, NearestTarget.x, NearestTarget.z)
		end
	end
	if (Config.ks.useQ and myHero:CanUseSpell(_Q) == READY) or (Config.ks.useW and myHero:CanUseSpell(_W) == READY) then
		for _, Enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(Enemy) then
				local Distance = GetDistance(myHero, Enemy)

				if Config.ks.useW and Distance <= SpellData.W.Range and getDmg("W", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_W) == READY then
					CastW(Enemy)
				elseif Config.ks.useQ and Distance <= SpellData.Q.Range2 and getDmg("Q", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_Q) == READY then
					CastQCustom(Enemy)
				end
			end
		end
	end
	if Config.combo.teamfight then
		if ValidTarget(Target) and CanTrinityWeave() then
			local Distance = GetDistance(myHero, Target)
			local TrueRange = PSCombat:GetTrueRange(Target)

			if Config.combo.useQ and myHero:CanUseSpell(_Q) == READY and (Distance <= SpellData.Q.Range or (Distance <= SpellData.Q.Range2 and CastQFar(Target, true))) then
				if (Distance < TrueRange and not pUp) or (Distance > TrueRange) then
					CastQCustom(Target)
				end
			elseif Config.combo.useW and myHero:CanUseSpell(_W) == READY and Distance <= SpellData.W.Range then
				if (Distance < TrueRange and not pUp) or (Distance > TrueRange) then
					CastW(Target)
				end
			end
		end
	end
	if Config.harass.poke and ManaCheck() then
		if ValidTarget(Target) and CanTrinityWeave() then
			local Distance = GetDistance(myHero, Target)
			local TrueRange = PSCombat:GetTrueRange(Target)

			if Config.harass.useQ and myHero:CanUseSpell(_Q) == READY and (Distance <= SpellData.Q.Range or (Distance <= SpellData.Q.Range2 and CastQFar(Target, true))) then
				if (Distance < TrueRange and not pUp) or (Distance > TrueRange) then
					CastQCustom(Target)
				end
			elseif Config.harass.useW and myHero:CanUseSpell(_W) == READY and Distance <= SpellData.W.Range then
				if (Distance < TrueRange and not pUp) or (Distance > TrueRange) then
					CastW(Target)
				end
			end
		end
	end
end

function OnGainBuff(unit, buff)
	if unit.isMe then
		if buff.name == "lucianpassivebuff" then
			pUp = true
		elseif buff.name == "sheen" then
			enrageBuff.up = true
		end
	end
end

function OnLoseBuff(unit, buff)
	if unit.isMe then
		if buff.name == "lucianpassivebuff" then
			pUp = true
		elseif buff.name == "sheen" then
			enrageBuff.up = false
			enrageBuff.expire = os.clock()
		end
	end
end

function OnCreateObj(obj)
	if GetDistance(obj) < 100 and obj.name == "Lucian_R_mis.troy" then
		rUp = os.clock()
	end
end

function CanTrinityWeave()
	if Config.extra.weaveTriForce and (GetInventoryHaveItem(3057) or GetInventoryHaveItem(3078)) then
		return (not enrageBuff.up and (os.clock() > enrageBuff.expire + 2))
	else
		return true
	end
end

function CastQCustom(unit)
	if ValidTarget(unit) then
		local Distance = GetDistance(myHero, unit)

		if Distance <= SpellData.Q.Range then
			CastSpell(_Q, unit)
		elseif Config.extra.extendQ then
			CastQFar(unit, false)
		end
	end
end

function CastQFar(unit, check)
	check = check or false
	enemyMinions:update()
	local PredPos = tpQ:GetPrediction(unit)
	if PredPos then
		local spellPos = Vector(myHero.x, myHero.y, myHero.z) - (Vector(myHero.x, myHero.y, myHero.z) - Vector(PredPos.x, PredPos.y, PredPos.z)):normalized() * SpellData.Q.Range2
		--local spellPos = Vector(myHero) + (Vector(PredPos) - Vector(myHero)):normalized() * SpellData.Q.Range2
		local qCol = Collision(SpellData.Q.Range, SpellData.Q.Speed, SpellData.Q.Delay, SpellData.Q.Width)

		for _, minion in pairs(enemyMinions.objects) do
			if qCol:GetHeroCollision(minion, spellPos) then
				if check then
					return true
				else
					CastSpell(_Q, minion)
				end
			end
		end
	end
end

function CastW(unit)
	if ValidTarget(unit) then
		local PredPos = tpW:GetPrediction(unit)

		if PredPos ~= nil and GetDistance(PredPos) <= SpellData.W.Range and myHero:CanUseSpell(_W) == READY and not wCol:GetMinionCollision(PredPos, myHero) then
			CastSpell(_W, PredPos.x, PredPos.z)
		end
	end
end

function ManaCheck()
	return (myHero.mana > (myHero.maxMana*(Config.harass.manaSlider/100)))
end

function NearestTarget(pos)
	local bestT = nil

	for _, Enemy in pairs(GetEnemyHeroes()) do
		if bestT and bestT.valid and Enemy and Enemy.valid then
			if GetDistanceSqr(Enemy) < GetDistanceSqr(bestT) then
				bestT = Enemy
			end
		else
			bestT = Enemy
		end
	end

	return bestT
end