<?php exit() ?>--by pqmailer 217.82.42.56
local Version = 0.12
local Config
local ts
local SpellData = {}
local pd, tpQ
local PSAuth, PSUpdate, PSCombat

function OnLoad()
	SpellData = {
		Q = {Range = 1075, Speed = 1344, Delay = 0.218, Width = 120}
	}

	Config = scriptConfig("[PS] Sivir v."..tostring(Version), "pssivir")

	Config:addSubMenu("Combo", "combo")
	Config.combo:addParam("teamfight", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config.combo:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.combo:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Harass", "harass")
	Config.harass:addParam("poke", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))
	Config.harass:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addSubMenu("Q Return", "qreturn")
		Config.harass.qreturn:addParam("useQreturn","Use Q return", SCRIPT_PARAM_ONOFF, false)
		Config.harass.qreturn:addParam("minRange","Minimum range %", SCRIPT_PARAM_SLICE, 70, 65, 85, 0)
		Config.harass.qreturn:addParam("maxRange","Maximum range %", SCRIPT_PARAM_SLICE, 95, 75, 95, 0)	
	Config.harass:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, false)
	Config.harass:addParam("manaSlider", "Min. mana percent to use skills", SCRIPT_PARAM_SLICE, 30, 1, 100, 0)

	Config:addSubMenu("Killsteal", "ks")
	Config.ks:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Additionals", "extra")
	Config.extra:addParam("qMaxRange","Q max range", SCRIPT_PARAM_SLICE, SpellData.Q.Range, 1, SpellData.Q.Range, 0)

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, SpellData.Q.Range, DAMAGE_PHYSICAL)
	ts.name = "Sivir"
	Config:addTS(ts)

	pd = ProdictManager.GetInstance()
	tpQ = pd:AddProdictionObject(_Q, SpellData.Q.Range, SpellData.Q.Speed, SpellData.Q.Delay, SpellData.Q.Width, myHero)

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
			if ValidTarget(Enemy, Config.extra.qMaxRange) and getDmg("Q", Enemy, myHero) >= Enemy.health then
				tpQ:GetPredictionCallBack(Enemy, CastQ)
			end
		end
	end
	if Config.combo.teamfight then
		if ValidTarget(Target) then
			if Config.combo.useQ and myHero:CanUseSpell(_Q) == READY and GetDistance(Target) <= Config.extra.qMaxRange then
				tpQ:GetPredictionCallBack(Target, CastQ)
			end
		end
	end
	if Config.harass.poke and ManaCheck() then
		if ValidTarget(Target) then
			if Config.harass.useQ and myHero:CanUseSpell(_Q) == READY and GetDistance(Target) <= Config.extra.qMaxRange then
				if Config.harass.qreturn.useQreturn then
					if UseQReturn(Target) then
						tpQ:GetPredictionCallBack(Target, CastQ)
					end
				else
					tpQ:GetPredictionCallBack(Target, CastQ)
				end
			end
		end
	end
end

function OnProcessSpell(unit, spell)
	if unit.isMe and spell.name:lower():find("attack") then
		local Target = PSCombat:GetTarget()
		if Target then
			if (Config.combo.useW and Config.combo.teamfight) or (Config.harass.useW and Config.harass.poke and ManaCheck()) and myHero:CanUseSpell(_W) == READY and GetDistance(Target) <= PSCombat:GetTrueRange(Target) then
				DelayAction(function()
					CastSpell(_W)
				end, spell.windUpTime-GetLatency()/1000)
			end
		end
	end
end

function CastQ(unit, pos, spell)
	if ValidTarget(unit) and pos ~= nil and GetDistance(pos) <= Config.extra.qMaxRange and myHero:CanUseSpell(_Q) == READY then
		CastSpell(_Q, pos.x, pos.z)
	end
end

function UseQReturn(unit)
	if unit and GetDistance(unit) >= (Config.extra.qMaxRange*Config.harass.qreturn.minRange/100) and GetDistance(unit) <= (Config.extra.qMaxRange*Config.harass.qreturn.maxRange/100) then
		return true
	end

	return false
end

function ManaCheck()
	return (myHero.mana > (myHero.maxMana*(Config.harass.manaSlider/100)))
end