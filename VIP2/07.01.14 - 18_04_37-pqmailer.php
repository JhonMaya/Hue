<?php exit() ?>--by pqmailer 217.82.37.119
local Version = 0.11
local Config
local ts
local SpellData = {}
local pd, tpW, tpE, tpR, wCol
local PSAuth, PSUpdate, PSCombat

function OnLoad()
	Config = scriptConfig("[PS] Jinx v."..tostring(Version), "psjinx")

	Config:addSubMenu("Combo", "combo")
	Config.combo:addParam("teamfight", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config.combo:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useWrange", "Minimum distance to use W", SCRIPT_PARAM_SLICE, myHero.range, 1, 1450, 0)
	Config.combo:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useEcount", "Min. enemies to use E", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)

	Config:addSubMenu("Harass", "harass")
	Config.harass:addParam("poke", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))
	Config.harass:addParam("pokeToggle", "Harass (Toggle)", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("Y"))
	Config.harass:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, false)
	Config.harass:addParam("useEcount", "Min. enemies to use E", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
	Config.harass:addParam("manaSlider", "Min. mana percent to use skills", SCRIPT_PARAM_SLICE, 30, 1, 100, 0)

	Config:addSubMenu("Ultimate", "ult")
	Config.ult:addParam("useRinRange", "Use R in AA range to execute", SCRIPT_PARAM_ONOFF, true)
	Config.ult:addParam("useRoutRange", "Use R out of AA range to execute", SCRIPT_PARAM_ONOFF, true)
	Config.ult:addParam("useRoutRangeDistance", "Distance to execute out of AA range", SCRIPT_PARAM_SLICE, 1500, 1, 3000, 0)
	Config.ult:addParam("useAOE", "Kill enemies with the AOE", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Killsteal", "ks")
	Config.ks:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)

	SpellData = {
		W = {Range = 1450, Speed = 3430, Delay = 0.609, Width = 100},
		E = {Range = 900, Speed = 900, Delay = 0.500, Width = 200},
		R = {Range = math.huge, Speed = 2500, Delay = 0.600, Width = 200, Radius = 225}
	}

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, SpellData.E.Range, DAMAGE_PHYSICAL)
	ts.name = "Jinx"
	Config:addTS(ts)

	pd = ProdictManager.GetInstance()
	tpW = pd:AddProdictionObject(_W, SpellData.W.Range, SpellData.W.Speed, SpellData.W.Delay, SpellData.W.Width, myHero)
	tpE = pd:AddProdictionObject(_E, SpellData.E.Range, SpellData.E.Speed, SpellData.E.Delay, SpellData.E.Width, myHero)
	tpR = pd:AddProdictionObject(_R, SpellData.R.Range, SpellData.R.Speed, SpellData.R.Delay, SpellData.R.Width, myHero)

	wCol = Collision(SpellData.W.Range, SpellData.W.Speed, SpellData.W.Delay, SpellData.W.Width)

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
				local Distance = GetDistance(Enemy)
				local TrueRange = PSCombat:GetTrueRange(Enemy)
				tpR.Speed = GetRSpeed(Enemy)

				if Config.ks.useW and Distance <= SpellData.W.Range and getDmg("W", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_W) == READY then
					tpW:GetPredictionCallBack(Enemy, CastW)
				elseif Config.ult.useRinRange and Distance <= TrueRange and getDmg("R", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_R) == READY then
					tpR:GetPredictionCallBack(Enemy, CastR)
				elseif Config.ult.useRoutRange and Distance > TrueRange and Distance <= Config.ult.useRoutRangeDistance and getDmg("R", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_R) == READY then
					tpR:GetPredictionCallBack(Enemy, CastR)
				end
			end
		end
	end
	if Config.combo.teamfight then
		Config.harass.pokeToggle = false
		if ValidTarget(Target) then
			local Distance = GetDistance(Target)

			if Config.combo.useE and CountEnemyHeroInRange(myHero.range) >= Config.combo.useEcount and myHero:CanUseSpell(_E) == READY and Distance <= SpellData.E.Range then
				tpE:GetPredictionCallBack(Target, CastE)
			end
			if Config.combo.useW and myHero:CanUseSpell(_W) == READY and Distance <= SpellData.W.Range and Distance >= Config.combo.useWrange then
				tpW:GetPredictionCallBack(Target, CastW)
			end
		end
	end
	if (Config.harass.poke or Config.harass.pokeToggle) and ManaCheck() then
		if ValidTarget(Target) then
			local Distance = GetDistance(Target)

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
	if ValidTarget(unit) and pos ~= nil and GetDistance(pos) <= SpellData.W.Range and myHero:CanUseSpell(_W) == READY and not wCol:GetMinionCollision(pos, myHero) then
		CastSpell(_W, pos.x, pos.z)
	end
end

function CastE(unit, pos, spell)
	if ValidTarget(unit) and pos ~= nil and GetDistance(pos) <= SpellData.E.Range and myHero:CanUseSpell(_E) == READY then
		CastSpell(_E, pos.x, pos.z)
	end
end

function CastR(unit, pos, spell)
	local rCol = Collision(SpellData.R.Range, GetRSpeed(unit), SpellData.R.Delay, SpellData.R.Width)

	if ValidTarget(unit) and pos ~= nil and GetDistance(pos) <= SpellData.R.Range and myHero:CanUseSpell(_R) == READY then
		local Col, ColHeros = rCol:GetHeroCollision(pos, myHero)
		if Col and Config.ult.useAOE then
			for _, Hero in pairs(ColHeros) do
				if GetDistance(Hero, pos) <= SpellData.R.Radius and getDmg("R", Hero, myHero)*0.8 >= unit.health then
					tpR.Speed = GetRSpeed(Hero)
					local newPos = tpR:GetPrediction(Hero)
					CastSpell(_R, newPos.x, newPos.z)
				end
			end
		else
			CastSpell(_R, pos.x, pos.z)
		end
	end
end

function ManaCheck()
	return (myHero.mana > (myHero.maxMana*(Config.harass.manaSlider/100)))
end