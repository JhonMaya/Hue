<?php exit() ?>--by pqmailer 217.82.40.172
local Version = 0.1
local Config
local ts
local SpellData = {}
local pd, tpW, tpE, tpR
local PSAuth, PSUpdate, PSCombat

function OnLoad()
	Config = scriptConfig("[PS] Jinx v."..tostring(Version), "psjinx")

	Config:addSubMenu("Combo", "combo")
	Config.combo:addParam("teamfight", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config.combo:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useWaa", "Use W in AA range", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useEcount", "Min. enemies to use E", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)

	Config:addSubMenu("Harass", "harass")
	Config.harass:addParam("poke", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))
	Config.harass:addParam("pokeToggle", "Harass (Toggle)", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("Y"))
	Config.harass:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, false)
	Config.harass:addParam("useEcount", "Min. enemies to use E", SCRIPT_PARAM_SLICE, 2, 1, 5, 0)
	Config.harass:addParam("manaSlider", "Min. mana percent to use skills", SCRIPT_PARAM_SLICE, 30, 1, 100, 0)

	Config:addSubMenu("Ultimate", "ult")
	Config.ult:addParam("useRinRange", "Use R in AA range to execute", SCRIPT_PARAM_ONOFF, true)
	Config.ult:addParam("useRoutRange", "Use R out of AA range to execute", SCRIPT_PARAM_ONOFF, true)
	Config.ult:addParam("useRoutRangeDistance", "Distance to execute out of AA range", SCRIPT_PARAM_SLICE, 1500, 1, 3000, 0)

	Config:addSubMenu("Killsteal", "ks")
	Config.ks:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)

	SpellData = {
		W = {Range = 1450, Speed = 3430, Delay = 0.609, Width = 100},
		E = {Range = 900, Speed = 900, Delay = 0.500, Width = 200},
		R = {Range = math.huge, Speed = 2500, Delay = 0.600, Width = 200}
	}

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, SpellData.E.Range, DAMAGE_PHYSICAL)
	ts.name = "Jinx"
	Config:addTS(ts)

	pd = ProdictManager.GetInstance()
	tpW = pd:AddProdictionObject(_W, SpellData.W.Range, SpellData.W.Speed, SpellData.W.Delay, SpellData.W.Width, myHero)
	tpE = pd:AddProdictionObject(_E, SpellData.E.Range, SpellData.E.Speed, SpellData.E.Delay, SpellData.E.Width, myHero)
	tpR = pd:AddProdictionObject(_R, SpellData.R.Range, SpellData.R.Speed, SpellData.R.Delay, SpellData.R.Width, myHero)

	PSCombat = ProSeriesCombatHandler(ts)
	PSAuth = ProSeriesAuth("JinxAuth")
	PSUpdate = ProSeriesUpdate("JinxUpdate")
	PSUpdate.LocalVersion = Version
	PSUpdate.SCRIPT_NAME = "PSJinx"
	PSUpdate.SCRIPT_URL = "http://pqbol.de/scripts/release/proseries/adc/jinx/PSJinx.lua"
	PSUpdate.PATH = BOL_PATH.."Scripts\\".."PSJinx.lua"
	PSUpdate.HOST = "pqbol.de"
	PSUpdate.URL_PATH = "/scripts/release/proseries/adc/jinx/revision.lua"
	PSUpdate:Run()

	PrintChat("Pro Series "..myHero.charName.. " Edition v."..tostring(Version).." loaded")
end

function OnTick()
	if PSAuth:IsAuthed() == false then
		return
	end
	local Target = PSCombat:GetTarget()
	if (Config.ks.useW and myHero:CanUseSpell(_W) == READY) or ((Config.ult.useRinRange or Config.ult.useRoutRange) and myHero:CanUseSpell(_R) == READY) then
		for _, Enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(Enemy) then
				local Distance = myHero:GetDistance(Enemy)
				tpR.Speed = GetRSpeed(Enemy)

				if Config.ks.useW and Distance <= SpellData.W.Range and getDmg("W", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_W) == READY then
					tpW:GetPredictionCallBack(Enemy, CastW)
				elseif Config.ult.useRinRange and Distance <= PSCombat:GetTrueRange(Enemy) and getDmg("R", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_R) == READY then
					tpR:GetPredictionCallBack(Enemy, CastR)
				elseif Config.ult.useRoutRange and Distance <= Config.ult.useRoutRangeDistance and getDmg("R", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_R) == READY then
					tpR:GetPredictionCallBack(Enemy, CastR)
				end
			end
		end
	end
	if Config.combo.teamfight then
		Config.harass.pokeToggle = false
		if ValidTarget(Target) then
			local Distance = myHero:GetDistance(Target)

			if Config.combo.useE and CountEnemyHeroInRange(myHero.range) >= Config.combo.useEcount and myHero:CanUseSpell(_E) == READY and Distance <= SpellData.E.Range then
				tpE:GetPredictionCallBack(Target, CastE)
			end
			if Config.combo.useW and myHero:CanUseSpell(_W) == READY and Distance <= SpellData.W.Range and (Config.combo.useWaa or Distance > PSCombat:GetTrueRange(Target)) then
				tpW:GetPredictionCallBack(Target, CastW)
			end
		end
	end
	if (Config.harass.poke or Config.harass.pokeToggle) and ManaCheck() then
		if ValidTarget(Target) then
			local Distance = myHero:GetDistance(Target)

			if Config.harass.useW and myHero:CanUseSpell(_W) == READY and Distance <= SpellData.W.Range then
				tpW:GetPredictionCallBack(Target, CastW)
			end
			if Config.harass.useE and CountEnemyHeroInRange(myHero.range) >= Config.harass.useEcount and myHero:CanUseSpell(_E) == READY and Distance <= SpellData.E.Range then
				tpE:GetPredictionCallBack(Target, CastE)
			end
		end
	end
end

function GetRSpeed(pos)
	local Distance = GetDistance(pos)
	return (Distance > 1350 and (1350*1700+((Distance-1350)*2200))/Distance or 1700)
end

function CastW(unit, pos, spell)
	local Col = Collision(SpellData.W.Range, SpellData.W.Speed, SpellData.W.Delay, SpellData.W.Width)

	if ValidTarget(unit, SpellData.W.Range) and myHero:CanUseSpell(_W) == READY and not Col:GetMinionCollision(pos, myHero) then
		CastSpell(_W, pos.x, pos.z)
	end
end

function CastE(unit, pos, spell)
	if ValidTarget(unit, SpellData.E.Range) and myHero:CanUseSpell(_E) == READY then
		CastSpell(_E, pos.x, pos.z)
	end
end

function CastR(unit, pos, spell)
	if ValidTarget(unit, SpellData.R.Range) and myHero:CanUseSpell(_R) == READY then
		CastSpell(_R, pos.x, pos.z)
	end
end

function ManaCheck()
	return (myHero.mana > (myHero.maxMana*(Config.harass.manaSlider/100)))
end