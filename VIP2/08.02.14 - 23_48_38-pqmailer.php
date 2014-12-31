<?php exit() ?>--by pqmailer 217.82.39.166
local Version = 0.13
local Config
local ts
local SpellData = {}
local pd, tpW, tpR, rCol
local qUp = false
local ExploitTick = GetTickCount()
local PSUpdate, PSCombat
local PSAuth = ProSeriesAuth("AsheAuth")
local LastTargetHero = false
local initDone = false

function Init()
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
	Config.ult:addParam("chainCC", "Use R to chain CC (only on Target)", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Killsteal", "ks")
	Config.ks:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)

	Config:addSubMenu("Additionals", "extra")
	Config.extra:addParam("useExploit", "Use Q exploit", SCRIPT_PARAM_ONOFF, true)
	Config.extra:addParam("ExploitOnlyHeros", "Enable it only against heros", SCRIPT_PARAM_ONOFF, true)

	SpellData = {
		W = {Range = 1150, Speed = 1500, Delay = 0.250, Width = 120, Angle = (80-20)*math.pi/180},
		R = {Range = math.huge, Speed = 1000, Delay = 0.243, Width = 120}
	}

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, SpellData.W.Range+200, DAMAGE_PHYSICAL)
	ts.name = "Ashe"
	Config:addTS(ts)

	pd = ProdictManager.GetInstance()
	tpW = pd:AddProdictionObject(_W, SpellData.W.Range, SpellData.W.Speed, SpellData.W.Delay, SpellData.W.Width, myHero)
	tpR = pd:AddProdictionObject(_R, SpellData.R.Range, SpellData.R.Speed, SpellData.R.Delay, SpellData.R.Width, myHero)

	rCol = Collision(SpellData.R.Range, SpellData.R.Speed, SpellData.R.Delay, SpellData.R.Width)

	PSCombat = ProSeriesCombatHandler(ts)
	PSUpdate = ProSeriesUpdate("AsheAuth")
	PSUpdate.LocalVersion = Version
	PSUpdate.SCRIPT_NAME = "PSAshe"
	PSUpdate.SCRIPT_URL = "http://pqbol.de/scripts/release/proseries/adc/ashe/PSAshe.lua"
	PSUpdate.PATH = BOL_PATH.."Scripts\\".."PSAshe.lua"
	PSUpdate.HOST = "pqbol.de"
	PSUpdate.URL_PATH = "/scripts/release/proseries/adc/ashe/revision.lua"
	PSUpdate:Run()

	initDone = true
	PrintChat("Pro Series "..myHero.charName.. " Edition v."..tostring(Version).." loaded")
end

function OnTick()
	if not initDone then
		if PSAuth:IsAuthed() then
			Init()
		else
			return
		end
	end
	local Target = PSCombat:GetTarget()
	if Config.ult.chainCC and Config.combo.teamfight and ValidTarget(Target) then
		tpR:GetPredictionOnImmobile(Target, CastR)
	end
	if Config.extra.useExploit and not qUp and GetTickCount() > ExploitTick + 300 then
		if Config.extra.ExploitOnlyHeros then
			if LastTargetHero then
				CastSpell(_Q)
				ExploitTick = GetTickCount()
			end
		else
			CastSpell(_Q)
			ExploitTick = GetTickCount()
		end
	end
	if Config.ult.manualAim and myHero:CanUseSpell(_R) == READY then
		local NearestTarget = NearestTarget(mousePos)
		if ValidTarget(NearestTarget, 2000) then
			tpR:GetPredictionCallBack(NearestTarget, CastR)
		end
	end
	if (Config.ks.useW and myHero:CanUseSpell(_W) == READY) or ((Config.ult.useRinRange or Config.ult.useRoutRange) and myHero:CanUseSpell(_R) == READY) then
		for _, Enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(Enemy) then
				local Distance = GetDistanceSqr(myHero, Enemy)
				local TrueRange = PSCombat:GetTrueRange(Enemy)

				if Config.ks.useW and Distance <= SpellData.W.Range*SpellData.W.Range and getDmg("W", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_W) == READY then
					tpW:GetPredictionCallBack(Enemy, CastW)
				elseif Config.ult.useRinRange and Distance <= TrueRange*TrueRange and getDmg("R", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_R) == READY then
					tpR:GetPredictionCallBack(Enemy, CastR)
				elseif Config.ult.useRoutRange and Distance > TrueRange*TrueRange and Distance <= Config.ult.useRoutRangeDistance*Config.ult.useRoutRangeDistance and getDmg("R", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_R) == READY then
					tpR:GetPredictionCallBack(Enemy, CastR)
				end
			end
		end
	end
	if (Config.combo.teamfight and Config.combo.useW) or (Config.harass.poke and Config.harass.useW and ManaCheck()) and myHero:CanUseSpell(_W) == READY then
		local _, Pos = GetBestCone(SpellData.W.Range, SpellData.W.Angle)
		if Pos then
			CastSpell(_W, Pos.x, Pos.z)
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
	if unit.isMe and spell.name:lower():find("attack") then
		LastTargetHero = (spell.target.type:lower() == "obj_ai_hero" and true or false)
	end
	if unit.isMe and spell.name == "frostarrow" then
		if Config.extra.ExploitOnlyHeros then
			if spell.target.type:lower() == "obj_ai_hero" then
				LastTargetHero = true
				CastSpell(_Q)
				ExploitTick = GetTickCount()
			else
				LastTargetHero = false
				CastSpell(_Q)
			end
		else
			CastSpell(_Q)
			ExploitTick = GetTickCount()
		end
	end
end

function CastR(unit, pos, spell)
	if ValidTarget(unit) and pos ~= nil and myHero:CanUseSpell(_R) == READY and not rCol:GetHeroCollision(pos, myHero) then
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

function CountVectorsBetween(V1, V2, Vectors)
	local result = 0	 
	for i, test in ipairs(Vectors) do
		local NVector = V1:crossP(test)
		local NVector2 = test:crossP(V2)
		if NVector.z >= 0 and NVector2.z >= 0 then
			result = result + 1
		end
	end

	return result
end

function MidPointBetween(V1, V2) 
	return Vector((V1.x + V2.x)/2,0, (V1.z + V2.z)/2)
end

function GetBestCone(Radius, Angle)
	local Targets = {}
	local PosibleCastPoints = {}

	for i, enemy in ipairs(GetEnemyHeroes()) do
		if ValidTarget(enemy) then
			local Position = tpW:GetPrediction(enemy)
			if Position and (GetDistance(Position) <= Radius) then
				table.insert(Targets, Vector(Position.x - myHero.x, 0, Position.z - myHero.z))
			elseif (GetDistance(enemy) <= Radius) and not Position then
				table.insert(Targets, Vector(enemy.x - myHero.x, 0, enemy.z - myHero.z))
			end
		end
	end
	
	local Best = 0
	local BestCastPos = nil

	if #Targets == 1 then
		Best = 1
		BestCastPos =Radius*Vector(Targets[1].x,0,Targets[1].z):normalized()
	elseif #Targets > 1  then
		for i, edge in ipairs(Targets) do
			local Edge1 = Radius*Vector(edge.x,0,edge.z):normalized()
			local Edge2 = Edge1:rotated(0, Angle, 0)
			local Edge3 = Edge1:rotated(0, -Angle, 0)
			
			Count1 = CountVectorsBetween(Edge1, Edge2, Targets)
			Count2 = CountVectorsBetween(Edge3, Edge1, Targets)
			
			if Count1 >= Best then
				Best = Count1
				BestCastPos = MidPointBetween(Edge1, Edge2)
			end
			if Count2 >= Best then
				Best = Count2
				BestCastPos = MidPointBetween(Edge3, Edge1)
			end
		end
	end
	

	if BestCastPos then
		BestCastPos =  Vector(myHero.x + BestCastPos.x, 0, myHero.z+BestCastPos.z)
	end

	return Best, BestCastPos
end	

function CountEnemiesInCone(CastPoint, Radius, Angle)
	local Direction = Radius * (-Vector(myHero.x, 0, myHero.z) + Vector(CastPoint.x,0,CastPoint.z)):normalized()
	local Vector1 = Direction:rotated(0, Angle/2, 0) 
	local Vector2 = Direction:rotated(0, -Angle/2, 0)
	local Targets = {}

	for i, enemy in ipairs(GetEnemyHeroes()) do
		if ValidTarget(enemy) then
			local Position = tpW:GetPrediction(enemy)
			if Position and (GetDistance(Position) <= Radius) then
				table.insert(Targets, Vector(Position.x - myHero.x, 0, Position.z - myHero.z))
			elseif (GetDistance(enemy) <= Radius) and not Position then
				table.insert(Targets, Vector(enemy.x - myHero.x, 0, enemy.z - myHero.z))
			end
		end
	end

	return CountVectorsBetween(Vector2, Vector1, Targets)
end