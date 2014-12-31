<?php exit() ?>--by pqmailer 217.82.40.172
local Version = 0.1
local Config
local ts
local SpellData = {}
local pd, tpQ
local PSAuth, PSUpdate, PSCombat

function OnLoad()
	Config = scriptConfig("[PS] Sivir v."..tostring(Version), "pssivir")

	Config:addSubMenu("Combo", "combo")
	Config.combo:addParam("teamfight", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config.combo:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Harass", "harass")
	Config.harass:addParam("poke", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))
	Config.harass:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, false)
	Config.harass:addParam("manaSlider", "Min. mana percent to use skills", SCRIPT_PARAM_SLICE, 30, 1, 100, 0)

	Config:addSubMenu("Killsteal", "ks")
	Config.ks:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)

	SpellData = {
		Q = {Range = 1075, Speed = 1344, Delay = 0.218, Width = 120}
	}

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, SpellData.Q.Range, DAMAGE_PHYSICAL)
	ts.name = "Sivir"
	Config:addTS(ts)

	pd = ProdictManager.GetInstance()
	tpQ = pd:AddProdictionObject(_W, SpellData.Q.Range, SpellData.Q.Speed, SpellData.Q.Delay, SpellData.Q.Width, myHero)

	PSCombat = ProSeriesCombatHandler(ts)
	PSAuth = ProSeriesAuth("SivirAuth")
	PSUpdate = ProSeriesUpdate("SivirAuth")
	PSUpdate.LocalVersion = Version
	PSUpdate.SCRIPT_NAME = "PSSivir"
	PSUpdate.SCRIPT_URL = "http://pqbol.de/scripts/release/proseries/adc/sivir/PSSivir.lua"
	PSUpdate.PATH = BOL_PATH.."Scripts\\".."PSSivir.lua"
	PSUpdate.HOST = "pqbol.de"
	PSUpdate.URL_PATH = "/scripts/release/proseries/adc/sivir/revision.lua"
	PSUpdate:Run()

	PrintChat("Pro Series "..myHero.charName.. " Edition v."..tostring(Version).." loaded")
end

function OnTick()
	if PSAuth:IsAuthed() == false then
		return
	end
	local Target = PSCombat:GetTarget()
	if Config.ks.useQ and myHero:CanUseSpell(_Q) == READY then
		for _, Enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(Enemy, SpellData.Q.Range) and getDmg("Q", Enemy, myHero) >= Enemy.health then
				tpQ:GetPredictionCallBack(Enemy, CastQ)
			end
		end
	end
	if Config.combo.teamfight then
		if ValidTarget(Target) then
			local Distance = myHero:GetDistance(Target)

			if Config.combo.useQ and myHero:CanUseSpell(_Q) == READY and Distance <= SpellData.Q.Range then
				tpQ:GetPredictionCallBack(Target, CastQ)
			end
			if Config.combo.useW and myHero:CanUseSpell(_W) == READY and Distance <= PSCombat:GetTrueRange(Target) then
				CastSpell(_W)
			end
		end
	end
	if Config.harass.poke and ManaCheck() then
		if ValidTarget(Target) then
			local Distance = myHero:GetDistance(Target)

			if Config.harass.useQ and myHero:CanUseSpell(_Q) == READY and Distance <= SpellData.Q.Range then
				tpQ:GetPredictionCallBack(Target, CastQ)
			end
			if Config.harass.useW and myHero:CanUseSpell(_W) == READY and Distance <= PSCombat:GetTrueRange(Target) then
				CastSpell(_W)
			end
		end
	end
end

function CastQ(unit, pos, spell)
	if ValidTarget(unit, SpellData.Q.Range) and myHero:CanUseSpell(_Q) == READY and pos ~= nil then
		CastSpell(_Q, pos.x, pos.z)
	end
end

function ManaCheck()
	return (myHero.mana > (myHero.maxMana*(Config.harass.manaSlider/100)))
end