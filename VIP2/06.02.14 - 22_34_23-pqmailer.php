<?php exit() ?>--by pqmailer 217.82.35.206
local Version = 0.1
local Config
local ts
local Target
local SpellData = {}
local pd, tpQ, tpW, tpR, qCol
local PSAuth, PSUpdate, PSCombat
local EnemyTable = GetEnemyHeroes()

function OnLoad()
	Config = scriptConfig("[PS] Ezreal v."..tostring(Version), "pqsezreal")

	Config:addSubMenu("Combo", "combo")
	Config.combo:addParam("teamfight", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("A"))
	Config.combo:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, false)
	Config.combo:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Harass", "harass")
	Config.harass:addParam("poke", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("C"))
	Config.harass:addParam("pokeToggle", "Harass (Toggle)", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("Y"))
	Config.harass:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.harass:addParam("useW", "Use E", SCRIPT_PARAM_ONOFF, false)
	Config.harass:addParam("manaSlider", "Min. mana percent to use skills", SCRIPT_PARAM_SLICE, 30, 1, 100, 0)

	Config:addSubMenu("Killsteal", "ks")
	Config.ks:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	Config.ks:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
	Config.ks:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, false)

	Config:addSubMenu("Ultimate", "ult")
	Config.ult:addParam("manualAim", "Aim to nearest mouse target", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("Y"))

	SpellData = {
		Q = {Range = 1100, Speed = 2000, Delay = 0.25, Width = 80},
		W = {Range = 1050, Speed = 1600, Delay = 0.25, Width = 90},
		R = {Range = math.huge, Speed = 2000, Delay = 1, Width = 150}
	}

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, SpellData.W.Range, DAMAGE_PHYSICAL)
	ts.name = "Jinx"
	Config:addTS(ts)

	pd = ProdictManager.GetInstance()
	tpQ = pd:AddProdictionObject(_Q, SpellData.Q.Range, SpellData.Q.Speed, SpellData.Q.Delay, SpellData.Q.Width, myHero)
	tpW = pd:AddProdictionObject(_W, SpellData.W.Range, SpellData.W.Speed, SpellData.W.Delay, SpellData.W.Width, myHero)
	tpR = pd:AddProdictionObject(_R, SpellData.R.Range, SpellData.R.Speed, SpellData.R.Delay, SpellData.R.Width, myHero)

	qCol = Collision(SpellData.Q.Range, SpellData.Q.Speed, SpellData.Q.Delay, SpellData.Q.Width)

	PSHelper = ProSeriesHelper("EzrealHelper")
	PSCombat = ProSeriesCombatHandler(ts)
	PSAuth = ProSeriesAuth("EzrealAuth")
	--[[
	PSUpdate = ProSeriesUpdate("EzrealUpdate")
	PSUpdate.LocalVersion = Version
	PSUpdate.SCRIPT_NAME = "PSJinx"
	PSUpdate.SCRIPT_URL = "http://pqbol.de/scripts/release/proseries/adc/jinx/PSJinx.lua"
	PSUpdate.PATH = BOL_PATH.."Scripts\\".."PSJinx.lua"
	PSUpdate.HOST = "pqbol.de"
	PSUpdate.URL_PATH = "/scripts/release/proseries/adc/jinx/revision.lua"
	PSUpdate:Run()
	]]--

	PrintChat("Pro Series "..myHero.charName.. " Edition v."..tostring(Version).." loaded")
end

function OnTick()
	if PSAuth:IsAuthed() == false then return end
	Target = PSCombat:GetTarget()
	if ValidTarget(Target) then
		Target.qPredPos = tpQ:GetPrediction(Target)
		Target.wPredPos = tpW:GetPrediction(Target)
		Target.rPredPos = tpR:GetPrediction(Target)
		Target.trueRange = PSCombat:GetTrueRange(Target)
		Target.distance = GetDistance(myHero, Target)
	end
	if Config.ult.manualAim and myHero:CanUseSpell(_R) == READY then
		local aimTarget = _NearestTarget(mousePos)
		if ValidTarget(aimTarget) then
			local aimTargetPredPos = tpR:GetPrediction(aimTarget)
			CastSpell(_R, aimTargetPredPos.x, aimTargetPredPos.z)
		end
	end
	if (Config.ks.useQ and myHero:CanUseSpell(_Q) == READY) or (Config.ks.useW and myHero:CanUseSpell(_W) == READY) then
		for _, Enemy in pairs(EnemyTable) do
			if ValidTarget(Enemy) then
				local qPredPos = tpQ:GetPrediction(Enemy)
				local wPredPos = tpW:GetPrediction(Enemy)
				local rPredPos = tpR:GetPrediction(Enemy)

				if Config.ks.useQ and qPredPos ~= nil and myHero:CanUseSpell(_Q) == READY and qPredPos ~= nil and GetDistance(qPredPos) <= SpellData.Q.Range and not qCol:GetMinionCollision(myHero, qPredPos) and getDmg("Q", Enemy, myHero) >= Enemy.health then
					CastSpell(_Q, qPredPos.x, qPredPos.z)
				elseif Config.ks.useW and wPredPos ~= nil and myHero:CanUseSpell(_W) == READY and wPredPos ~= nil and GetDistance(wPredPos) <= SpellData.W.Range and getDmg("W", Enemy, myHero) >= Enemy.health then
					CastSpell(_W, wPredPos.x, wPredPos.z)
				elseif Config.ks.useR and rPredPos ~= nil and myHero:CanUseSpell(_R) == READY and rPredPos ~= nil and GetDistance(Enemy) > PSCombat:GetTrueRange(Enemy) and GetDistance(rPredPos) <= SpellData.R.Range and getDmg("R", Enemy, myHero) >= Enemy.health then
					CastSpell(_R, rPredPos.x, rPredPos.z)
				end
			end
		end
	end
	if Config.combo.teamfight and Target ~= nil and Target.distance > Target.trueRange then
		Config.harass.pokeToggle = false
		if ValidTarget(Target) then
			if Config.combo.useQ and myHero:CanUseSpell(_Q) == READY and Target.qPredPos ~= nil and GetDistance(Target.qPredPos) <= SpellData.Q.Range and not qCol:GetMinionCollision(myHero, Target.qPredPos) then
				CastSpell(_Q, Target.qPredPos.x, Target.qPredPos.z)
			end
			if Config.combo.useW and myHero:CanUseSpell(_W) == READY and Target.wPredPos ~= nil and GetDistance(Target.wPredPos) <= SpellData.W.Range then
				CastSpell(_W, Target.wPredPos.x, Target.wPredPos.z)
			end
		end
	end
	if (Config.harass.poke or Config.harass.pokeToggle) and myHero.mana > (myHero.maxMana * (Config.harass.manaSlider / 100)) then
		if ValidTarget(Target) then
			if Config.harass.useQ and myHero:CanUseSpell(_Q) == READY and Target.qPredPos ~= nil and GetDistance(Target.qPredPos) <= SpellData.Q.Range and not qCol:GetMinionCollision(myHero, Target.qPredPos) then
				CastSpell(_Q, Target.qPredPos.x, Target.qPredPos.z)
			end
			if Config.harass.useW and myHero:CanUseSpell(_W) == READY and Target.wPredPos ~= nil and GetDistance(Target.wPredPos) <= SpellData.W.Range then
				CastSpell(_W, Target.wPredPos.x, Target.wPredPos.z)
			end
		end
	end
end

function OnProcessSpell(unit, spell)
	if unit.isMe and spell.name:lower():find("attack") and Config.combo.teamfight then
		local qPredPos = tpQ:GetPrediction(spell.target)
		local wPredPos = tpW:GetPrediction(spell.target)

		if Config.combo.useQ and myHero:CanUseSpell(_Q) == READY and qPredPos ~= nil and not qCol:GetMinionCollision(myHero, qPredPos) then
			DelayAction(function()
				if not qCol:GetMinionCollision(myHero, qPredPos) then
					CastSpell(_Q, qPredPos.x, qPredPos.z)
				end
			end, spell.windUpTime-GetLatency() / 1000)
		elseif Config.combo.useW and myHero:CanUseSpell(_W) == READY and wPredPos ~= nil then
			DelayAction(function()
				CastSpell(_W, qPredPos.x, qPredPos.z)
			end, spell.windUpTime-GetLatency() / 1000)	
		end
	end
end

function _NearestTarget(pos)
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