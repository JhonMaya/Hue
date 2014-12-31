<?php exit() ?>--by pqmailer 217.82.40.172
local Version = 0.1
local Config
local ts
local SpellData = {}
local pd, tpW, tpR
local qUp = false
local ExploitTick = GetTickCount()
local PSAuth, PSUpdate, PSCombat

function OnLoad()
	Config = scriptConfig("[PS] Ashe v."..tostring(Version), "psashe")

	Config:addSubMenu("Combo", "combo")
	Config.combo:addParam("teamfight", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config.combo:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Harass", "harass")
	Config.harass:addParam("poke", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))
	Config.harass:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("manaSlider", "Min. mana percent to use skills", SCRIPT_PARAM_SLICE, 30, 1, 100, 0)

	Config:addSubMenu("Ultimate", "ult")
	Config.ult:addParam("manualAim", "Aim to nearest mouse target", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))
	Config.ult:addParam("useRinRange", "Use R in AA range to execute", SCRIPT_PARAM_ONOFF, true)
	Config.ult:addParam("useRoutRange", "Use R out of AA range to execute", SCRIPT_PARAM_ONOFF, true)
	Config.ult:addParam("useRoutRangeDistance", "Distance to execute out of AA range", SCRIPT_PARAM_SLICE, 1500, 1, 3000, 0)

	Config:addSubMenu("Killsteal", "ks")
	Config.ks:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Additionals", "extra")
	Config.extra:addParam("useExploit", "Use Q exploit", SCRIPT_PARAM_ONOFF, true)

	SpellData = {
		W = {Range = 1200, Speed = 1500, Delay = 0.250, Width = 120},
		R = {Range = math.huge, Speed = 1000, Delay = 0.243, Width = 120}
	}

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, SpellData.W.Range+200, DAMAGE_PHYSICAL)
	ts.name = "Ashe"
	Config:addTS(ts)

	pd = ProdictManager.GetInstance()
	tpW = pd:AddProdictionObject(_W, SpellData.W.Range, SpellData.W.Speed, SpellData.W.Delay, SpellData.W.Width, myHero)
	tpR = pd:AddProdictionObject(_R, SpellData.R.Range, SpellData.R.Speed, SpellData.R.Delay, SpellData.R.Width, myHero)

	PSCombat = ProSeriesCombatHandler(ts)
	PSAuth = ProSeriesAuth("AsheAuth")
	PSUpdate = ProSeriesUpdate("AsheAuth")
	PSUpdate.LocalVersion = Version
	PSUpdate.SCRIPT_NAME = "PSAshe"
	PSUpdate.SCRIPT_URL = "http://pqbol.de/scripts/release/proseries/adc/ashe/PSAshe.lua"
	PSUpdate.PATH = BOL_PATH.."Scripts\\".."PSAshe.lua"
	PSUpdate.HOST = "pqbol.de"
	PSUpdate.URL_PATH = "/scripts/release/proseries/adc/ashe/revision.lua"
	PSUpdate:Run()

	PrintChat("Pro Series "..myHero.charName.. " Edition v."..tostring(Version).." loaded")
end

function OnTick()
	local Target = GetCustomTarget()
	if Config.extra.useExploit and not qUp and GetTickCount() > ExploitTick + 300 then
		CastSpell(_Q)
		ExploitTick = GetTickCount()
	end
	if Config.ult.manualAim and myHero:CanUseSpell(_R) == READY then
		local NearestTarget = NearestTarget(mousePos)
		if ValidTarget(NearestTarget) then
			tpR:GetPredictionCallBack(NearestTarget, CastR)
		end
	end
	if (Config.ks.useW and myHero:CanUseSpell(_W) == READY) or ((Config.ult.useRinRange or Config.ult.useRoutRange) and myHero:CanUseSpell(_R) == READY) then
		for _, Enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(Enemy) then
				local Distance = GetDistanceSqr(myHero, Enemy)
				local TrueRange = GetTrueRangeToUnit(Enemy)

				if Config.ks.useW and Distance <= SpellData.W.Range*SpellData.W.Range and getDmg("W", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_W) == READY then
					tpW:GetPredictionCallBack(Enemy, CastW)
				elseif Config.ult.useRinRange and Distance <= TrueRange*TrueRange and getDmg("R", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_R) == READY then
					tpR:GetPredictionCallBack(Enemy, CastR)
				elseif Config.ult.useRoutRange and Distance <= Config.ult.useRoutRangeDistance*Config.ult.useRoutRangeDistance and getDmg("R", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_R) == READY then
					tpR:GetPredictionCallBack(Enemy, CastR)
				end
			end
		end
	end
	if Target then
		if ValidTarget(Target, SpellData.W.Range) and (Config.combo.teamfight and Config.combo.useW) or (Config.harass.poke and Config.harass.useW and ManaCheck()) and myHero:CanUseSpell(_W) == READY then
			tpW:GetPredictionCallBack(Target, CastW)
		end
	end
end

function OnCreateObj(object)
	if GetDistance(object) < 100 and object.name == "iceSparkle.troy" then
		qUp = true
	end
end

function OnDeleteObj(object)
	if GetDistance(object) < 100 and object.name == "iceSparkle.troy" then
		qUp = false
	end
end

function OnProcessSpell(unit, spell)
	if unit.isMe and spell.name == "frostarrow" then
		CastSpell(_Q)
		ExploitTick = GetTickCount()
	end
end

function GetCustomTarget()
	if _G.MMA_Target ~= nil and _G.MMA_Target.type:lower() == "obj_ai_hero" then
		return _G.MMA_Target
	end

	if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Crosshair.Attack_Crosshair.target ~= nil and _G.AutoCarry.Crosshair.Attack_Crosshair.target.type:lower() == "obj_ai_hero" then
		return _G.AutoCarry.Crosshair.Attack_Crosshair.target
	end
	
	ts:update()

	return ts.target
end

function GetTrueRangeToUnit(unit)
	return GetDistance(myHero.minBBox) + myHero.range + GetDistance(unit.minBBox, unit.maxBBox)/2
end

function CastW(unit, pos, spell)
	if ValidTarget(unit, SpellData.W.Range) and myHero:CanUseSpell(_W) == READY and pos ~= nil then
		CastSpell(_W, pos.x, pos.z)
	end
end

function CastR(unit, pos, spell)
	if ValidTarget(unit) and myHero:CanUseSpell(_R) == READY and pos ~= nil then
		CastSpell(_R, pos.x, pos.z)
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