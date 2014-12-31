<?php exit() ?>--by pqmailer 217.82.40.172
local Version = 0.1
local Config
local ts
local SpellData = {}
local pd, tpQ, tpW, WCol
local pUp = false
local enemyMinions

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

	SpellData = {
		AA = {Range = 525},
		Q = {Range = 550, Speed = math.huge, Delay = 0.2, Width = 65},
		W = {Range = 1000, Speed = 1600, Delay = 0.312, Width = 90},
		R = {Range = 1425, Speed = 2850, Delay = 0.249, Width = 90}
	}

	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, SpellData.R.Range, DAMAGE_PHYSICAL)
	ts.name = "Lucian"
	Config:addTS(ts)

	pd = ProdictManager.GetInstance()
	tpQ = pd:AddProdictionObject(_Q, SpellData.Q.Range, SpellData.Q.Speed, SpellData.Q.Delay, SpellData.Q.Width, myHero)
	tpW = pd:AddProdictionObject(_W, SpellData.W.Range, SpellData.W.Speed, SpellData.W.Delay, SpellData.W.Width, myHero)
	tpR = pd:AddProdictionObject(_R, SpellData.R.Range, SpellData.R.Speed, SpellData.R.Delay, SpellData.R.Width, myHero)

	WCol = Collision(SpellData.W.Range, SpellData.W.Speed, SpellData.W.Delay, SpellData.W.Width)

	enemyMinions = minionManager(MINION_ENEMY, SpellData.AA.Range, myHero, MINION_SORT_HEALTH_ASC)

	PSCombat = ProSeriesCombatHandler(ts)
	PSAuth = ProSeriesAuth("LucianAuth")
	PSUpdate = ProSeriesUpdate("LucianUpdate")
	PSUpdate.LocalVersion = Version
	PSUpdate.SCRIPT_NAME = "PSLucian"
	PSUpdate.SCRIPT_URL = "http://pqbol.de/scripts/release/proseries/adc/draven/PSLucian.lua"
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
	if Config.ult.manualAim and myHero:CanUseSpell(_R) == READY then
		local NearestTarget = NearestTarget(mousePos)
		if ValidTarget(NearestTarget) then
			tpR:GetPredictionCallBack(NearestTarget, CastR)
		end
	end
	if (Config.ks.useQ and myHero:CanUseSpell(_Q) == READY) or (Config.ks.useW and myHero:CanUseSpell(_W) == READY) then
		for _, Enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(Enemy) then
				local Distance = GetDistanceSqr(myHero, Enemy)

				if Config.ks.useW and Distance <= SpellData.W.Range*SpellData.W.Range and getDmg("W", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_W) == READY then
					tpW:GetPredictionCallBack(Enemy, CastW)
				elseif Config.ks.useQ and Distance <= SpellData.Q.Range*SpellData.Q.Range and getDmg("Q", Enemy, myHero) >= Enemy.health and myHero:CanUseSpell(_Q) == READY then
					CastQCustom(Enemy)
				end
			end
		end
	end
	if Config.combo.teamfight then
		if ValidTarget(Target) then
			local Distance = GetDistanceSqr(myHero, Target)
			local TrueRange = PSCombat:GetTrueRange(Target)

			if Config.combo.useQ and myHero:CanUseSpell(_Q) == READY and Distance <= SpellData.Q.Range*SpellData.Q.Range and ((Distance <= TrueRange*TrueRange and not pUp) or (Distance > TrueRange*TrueRange)) then
				CastQCustom(Target)
			end
			if Config.combo.useW and myHero:CanUseSpell(_W) == READY and Distance <= SpellData.W.Range*SpellData.W.Range and ((Distance <= TrueRange*TrueRange and not pUp) or (Distance > TrueRange*TrueRange)) then
				tpW:GetPredictionCallBack(Target, CastW)
			end
		end
	end
	if Config.harass.poke and ManaCheck() then
		if ValidTarget(Target) then
			local Distance = GetDistanceSqr(myHero, Target)
			local TrueRange = PSCombat:GetTrueRange(Target)

			if Config.harass.useQ and myHero:CanUseSpell(_Q) == READY and Distance <= SpellData.Q.Range*SpellData.Q.Range and ((Distance <= TrueRange*TrueRange and not pUp) or (Distance > TrueRange*TrueRange)) then
				CastQCustom(Target)
			end
			if Config.harass.useW and myHero:CanUseSpell(_W) == READY and Distance <= SpellData.W.Range*SpellData.W.Range and ((Distance <= TrueRange*TrueRange and not pUp) or (Distance > TrueRange*TrueRange)) then
				tpW:GetPredictionCallBack(Target, CastW)
			end
		end
	end
end

function OnProcessSpell(unit, spell)
	if unit.isMe then
		if spell.name == "LucianPassiveAttack" then
			pUp = false
		elseif spell.name == "LucianQ" or spell.name == "LucianW" or spell.name == "LucianE" or spell.name == "LucianR" then
			pUp = true
		end
	end
end

function CastQCustom(unit)
	if ValidTarget(unit) then
		local Distance = GetDistanceSqr(myHero, unit)

		if Distance <= SpellData.Q.Range*SpellData.Q.Range then
			CastSpell(_Q, unit)
		else
			CastQFar(unit)
		end
	end
end

function CastQFar(unit)
	enemyMinions:update()
	local pos = tpQ:GetPrediction(unit)
	if pos then
		V = Vector(pos) - Vector(myHero)
		
		Vn = V:normalized()
		Distance = GetDistance(pos)
		tx, ty, tz = Vn:unpack()
		TopX = pos.x - (tx * Distance)
		TopY = pos.y - (ty * Distance)
		TopZ = pos.z - (tz * Distance)
		
		Vr = V:perpendicular():normalized()
		Radius = GetDistance(unit, unit.minBBox)
		tx, ty, tz = Vr:unpack()
		
		LeftX = pos.x + (tx * Radius)
		LeftY = pos.y + (ty * Radius)
		LeftZ = pos.z + (tz * Radius)
		RightX = pos.x - (tx * Radius)
		RightY = pos.y - (ty * Radius)
		RightZ = pos.z - (tz * Radius)
		
		Left = WorldToScreen(D3DXVECTOR3(LeftX, LeftY, LeftZ))
		Right = WorldToScreen(D3DXVECTOR3(RightX, RightY, RightZ))
		Top = WorldToScreen(D3DXVECTOR3(TopX, TopY, TopZ))
		Poly = Polygon(Point(Left.x, Left.y), Point(Right.x, Right.y), Point(Top.x, Top.y))
	
		for i, minion in pairs(enemyMinions.objects) do
			toScreen = WorldToScreen(D3DXVECTOR3(minion.x, minion.y, minion.z))
			toPoint = Point(toScreen.x, toScreen.y)
			if Poly:contains(toPoint) then
				CastSpell(_Q, minion)
			end
		end
	end
end

function CastW(unit, pos, spell)
	if ValidTarget(unit, SpellData.W.Range) and myHero:CanUseSpell(_W) == READY and not WCol:GetMinionCollision(pos, myHero) and pos ~= nil then
		CastSpell(_W, pos.x, pos.z)
	end
end

function CastR(unit, pos, spell)
	if ValidTarget(unit, SpellData.R.Range) and myHero:CanUseSpell(_R) == READY and pos ~= nil then
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