<?php exit() ?>--by pqmailer 217.82.37.119
local QData = {Count = 0, Next = 0, Last = 0}
local RData = {Start = 0, Up = false}
local Orbwalk = {lastAttack = 0, lastWindUp = 0, lastAttackCD = 0, lastAnimation = nil, hitBoxSize = 65, range = 0, walkDistance = 300}
local PassiveCount = 0
local ts
local MinionManager = {Enemy = minionManager(MINION_ENEMY, Orbwalk.range, myHero, MINION_SORT_HEALTH_ASC), Jungle = minionManager(MINION_JUNGLE, Orbwalk.range, myHero, MINION_SORT_HEALTH_ASC)}
local EnemyTable = table.copy(GetEnemyHeroes())
--local lastAttack = 0
local rp

function OnLoad()
	Menu = scriptConfig("PQRiven", "pqriven")
	Menu:addParam("focusSelect", "Focus selected target", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	--Menu:addParam("laneClear", "Lane clear", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("V"))
	Menu:addParam("harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("Y"))
	Menu:addParam("forceAA", "Force AA in Combo", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("T"))
	Menu:addParam("useEcombo", "Use E in Combo", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("A"))
	Menu:addParam("useRcombo", "Use R in Combo", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("useEharass", "Use E while Harass", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("useRHealth", "Activate R at % enemy hp", SCRIPT_PARAM_SLICE, 60, 0, 100, 0)
	Menu:addParam("smartCombos", "Use smart kill combos", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("ksQ", "KS with Q", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("ksW", "KS with W", SCRIPT_PARAM_ONOFF, false)
	Menu:addParam("ksR", "KS with R", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("ksRws", "KS only if Wind Slash is active", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("cancelQlaugh", "Cancel Q animation with a laugh", SCRIPT_PARAM_ONOFF, false)
	Menu:addParam("cancelQ", "Cancel Q animation with movement", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("cancelW", "Cancel W animation with Tiamat/Hydra", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("cancelWQ", "Cancel W animation with Q", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("cancelRQ", "Cancel R animation with Q", SCRIPT_PARAM_ONOFF, true)
	Menu:addParam("cancelRE", "Cancel R animation with E", SCRIPT_PARAM_ONOFF, true)
	--Menu:addParam("lcSkills", "Use Skills while Lane Clear/Jungle", SCRIPT_PARAM_ONOFF, true)
	Menu:permaShow("harass")
	Menu:permaShow("forceAA")
	Menu:permaShow("useEcombo")

	Orbwalk.range = myHero.range+Orbwalk.hitBoxSize

	ts = TargetSelector(TARGET_LOW_HP_PRIORITY, Orbwalk.range, DAMAGE_PHYSICAL)
	ts.name = "Riven"
	Menu:addTS(ts)

	jungleMinions = minionManager(MINION_JUNGLE, Orbwalk.range, myHero, MINION_SORT_HEALTH_ASC)

	rp = ProdictManager.GetInstance():AddProdictionObject(_R, 900, 1200, 0.25, 125, myHero)

	PrintChat("PQRiven ALPHA loaded!")
end

function OnTick()
	ts.range = myHero.range+GetDistance(myHero.minBBox)+((GetQRadius()+260)*2)
	ts.targetSelected = Menu.focusSelect
	ts:update()
	Orbwalk.range = myHero.range+GetDistance(myHero.minBBox)+((GetQRadius()+260)*2)
	MinionManager.Enemy:update()
	MinionManager.Jungle:update()
	if myHero:CanUseSpell(_Q) ~= READY and os.clock() > QData.Last + 1 then QData.Count = 0 end
	if Menu.ksQ then ksQ() end
	if Menu.ksW then ksW() end
	if Menu.ksR then ksR() end
	CastRRnd()
	if Menu.smartCombos and Menu.combo then SmartCombos() end
	if Menu.combo then
		OrbWalk(ts.target)
		Combo()
	end
	if Menu.harass then Harass() end
	if Menu.laneClear then LaneClear() end
end

function OnProcessSpell(unit, spell)
	if unit.isMe then
		local Target = ts.target

		if spell.name == "RivenTriCleave" and (Menu.cancelQ or Menu.cancelQlaugh) and ValidTarget(Target) then
			local Pos = Target + (Vector(myHero) - Target):normalized()*(GetDistance(Target)+50)
			if Menu.cancelQ and Pos then
				Packet('S_MOVE', {x = Pos.x, y = Pos.z}):send()
			end
			if Menu.cancelQlaugh then
				Laugh()
			end
		elseif spell.name == "RivenFengShuiEngine" or spell.name == "rivenizunablade" then
			if Menu.cancelRE and myHero:CanUseSpell(_E) == READY then
				CastSpell(_E, mousePos.x, mousePos.z)
			elseif Menu.cancelRQ and myHero:CanUseSpell(_Q) == READY then
				local Position = GetAoESpellPosition(GetQRadius(), Target, 0)
				CastSpell(_Q, Position.x, Position.z)
			end
		elseif spell.name == "RivenKiBurst" and Menu.cancelW then
			if GetInventoryItemIsCastable(3077) then
				CastItem(3077)
			elseif GetInventoryItemIsCastable(3074) then
				CastItem(3074)
			elseif Menu.cancelWQ and myHero:CanUseSpell(_Q) == READY then
				local Position = GetAoESpellPosition(GetQRadius(), Target, 0)
				CastSpell(_Q, Position.x, Position.z)
			end
		elseif spell.name:lower():find("attack") then
			Orbwalk.lastWindUp = spell.windUpTime*1000
			Orbwalk.lastAttackCD = spell.animationTime*1000
			DelayAction(function ()
				if Menu.combo and ValidTarget(Target) then
					local Distance = myHero:GetDistance(Target)
					if Distance <= GetQRadius()+260 and myHero:CanUseSpell(_Q) == READY then
						local Position = GetAoESpellPosition(GetQRadius(), Target, 0)
						CastSpell(_Q, Position.x, Position.z)
					end
					if Distance <= GetWRadius() and myHero:CanUseSpell(_W) == READY then
						CastSpell(_W)
					end
				end
			end, spell.windUpTime-GetLatency()/2000)
		end
	end
end

function OnAnimation(unit, animationName)
	if unit.isMe and Orbwalk.lastAnimation ~= animationName then Orbwalk.lastAnimation = animationName end
end

function OnAttack(unit)
	if unit.isMe then Orbwalk.lastAttack = GetTickCount() end
end

function GetQRadius()
	if RData.Up then
		if QData.Count == 2 then
			return 200
		else
			return 162.5
		end
	else
		if QData.Count == 2 then
			return 112.5
		else
			return 150
		end
	end
end

function GetWRadius()
	if RData.Up then
		return 325
	else
		return 250
	end
end

function Combo()
	local Target = ts.target

	if ValidTarget(Target) then
		local Distance = myHero:GetDistance(Target)
		local Radius = GetQRadius()
		local Position = GetAoESpellPosition(Radius, Target, 0)

		if Menu.useRcombo then
			if not RData.Up and (Target.health/Target.maxHealth)*100 <= Menu.useRHealth and PassiveCount < 3 then
				CastSpell(_R)
			end
			if RData.Up and myHero:CanUseSpell(_R) == READY and Distance <= 900 and getDmg("R", Target, myHero) >= Target.health then
				rp:GetPredictionCallBack(Target, CastR)
			end
		end
		local GCDistance = 0
		if myHero:CanUseSpell(_Q) == READY then
			GCDistance = GetQRadius()+125
		elseif myHero:CanUseSpell(_W) == READY then
			GCDistance = GetWRadius()+125
		end
		if myHero:CanUseSpell(_E) == READY and Menu.useEcombo and Distance > 125 and Distance < 325 + GCDistance then
			CastSpell(_E, Target.x, Target.z)
		end
		if myHero:CanUseSpell(_W) == READY and Distance <= GetWRadius() then
			CastSpell(_W)
		end
		if not Menu.forceAA and Distance <= GetQRadius()+260 then
			CastSpell(_Q, Position.x, Position.z)
		end
	end
end

function SmartCombos()
	local Target = ts.target

	if ValidTarget(Target) then
		local qDmg = (myHero:CanUseSpell(_Q) == READY and getDmg("Q", Target, myHero) or 0)
		local wDmg = (myHero:CanUseSpell(_W) == READY and getDmg("Q", Target, myHero) or 0)
		local rDmg = (myHero:CanUseSpell(_R) == READY and getDmg("R", Target, myHero) or 0)
		local Position = GetAoESpellPosition(GetQRadius(), Target, 0)

		if myHero:CanUseSpell(_Q) == READY and qDmg >= Target.health then
			if myHero:GetDistance(Target) <= GetQRadius()+260 then
				CastSpell(_Q, Target.x, Target.z)
			elseif myHero:CanUseSpell(_E) == READY and myHero:GetDistance(Target) <= GetQRadius()+260+325 then
				CastSpell(_E, Target.x, Target.z)
				CastSpell(_Q, Position.x, Position.z)
			end
		elseif myHero:CanUseSpell(_W) == READY and wDmg >= Target.health then
			if myHero:GetDistance(Target) <= GetWRadius() then
				CastSpell(_W)
			elseif myHero:CanUseSpell(_E) == READY and myHero:GetDistance(Target) <= getWRadius()+325 then
				CastSpell(_E, Target.x, Target.z)
				CastSpell(_W)
			end
		elseif myHero:CanUseSpell(_Q) == READY and myHero:CanUseSpell(_W) == READY and qDmg+wDmg >= Target.health then
			if myHero:GetDistance(Target) <= GetQRadius()+260 then
				CastSpell(_Q, Position.x, Position.z)
				CastSpell(_W)
			elseif myHero:CanUseSpell(_E) == READY and myHero:GetDistance(Target) <= GetQRadius()+260+325 then
				CastSpell(_E, Target.x, Target.z)
				CastSpell(_Q, Position.x, Position.z)
				CastSpell(_W)
			end
		elseif RData.Up and myHero:CanUseSpell(_R) == READY and rDmg >= Target.health then
			if myHero:GetDistance(Target) <= 900 then
				rp:GetPredictionCallBack(Target, CastR)
			elseif myHero:CanUseSpell(_E) == READY and myHero:GetDistance(Target) <= 900+325 then
				CastSpell(_E, Target.x, Target.z)
				rp:GetPredictionCallBack(Target, CastR)
			end
		end
	end
end

function Harass()
	local Target = ts.target

	if ValidTarget(Target) then
		local Distance = myHero:GetDistance(Target)
		local GCDistance = (myHero:CanUseSpell(_W) == READY and 250 or 0)

		if myHero:CanUseSpell(_E) == READY and Distance > 250 and Distance < 325 + GCDistance and Menu.useEharass then
			CastSpell(_E, Target.x, Target.z)
		end
		if myHero:CanUseSpell(_W) == READY and Distance <= 250 then
			CastSpell(_W)
		end
	end
end

function LaneClear()
	local JungleMob = Jungle:GetAttackableMonster()
	local Target = nil

	if JungleMob and ValidTarget(JungleMob, GetQRadius()+260+125) then
		Target = JungleMob
	else
		for _, Minion in pairs(Minions.EnemyMinions.objects) do
			if ValidTarget(Minion, GetQRadius()+260+125) and not ((Minions.AlmostKillable and Minions.AlmostKillable.networkID == Minion.networkID) or (Minions.KillableMinion and Minions.KillableMinion.networkID == Minion.networkID)) then
				Target = Minion
				break
			end
		end
	end

	if ValidTarget(Target, GetQRadius()+260+125) then
		if myHero:CanUseSpell(_W) == READY then
			CastSpell(_W)
		end
		if myHero:CanUseSpell(_Q) == READY then
			CastSpell(_Q, Target.x, Target.z)
		end
	end
end

function ksQ()
	if myHero:CanUseSpell(_Q) == READY then
		local Radius = GetQRadius()
		for _, Enemy in pairs(EnemyTable) do
			if ValidTarget(Enemy, GetQRadius()+260+125) and getDmg("Q", Enemy, myHero) >= Enemy.health then
				local Position = GetAoESpellPosition(Radius, Enemy, 0)
				CastSpell(_Q, Position.x, Position.z)
			end
		end
	end
end

function ksW()
	if myHero:CanUseSpell(_W) == READY then
		for _, Enemy in pairs(EnemyTable) do
			if ValidTarget(Enemy, 250) and getDmg("W", Enemy, myHero) >= Enemy.health then
				CastSpell(_W)
			end
		end
	end
end

function ksR()
	if myHero:CanUseSpell(_R) == READY then
		for _, Enemy in pairs(EnemyTable) do
			if ValidTarget(Enemy, 900) and getDmg("R", Enemy, myHero) >= Enemy.health then
				if RData.Up and myHero:CanUseSpell(_R) == READY then
					rp:GetPredictionCallBack(Enemy, CastR)
				else
					if not Menu.ksRws then
						CastSpell(_R)
					end
				end
			end
		end
	end
end

function CastRRnd()
	if RData.Up and myHero:CanUseSpell(_R) == READY and os.clock() > RData.Start + 13 then
		local Lowest = nil
		for _, Enemy in pairs(EnemyTable) do
			if ValidTarget(Enemy, 900) then
				if Lowest and Lowest.valid then
					if Enemy.health < Lowest.health then
						Lowest = Enemy
					end
				else
					Lowest = Enemy
				end
			end
		end
		if ValidTarget(Lowest, 900) then
			rp:GetPredictionCallBack(Lowest, CastR)
		end
	end
end

function heroCanMove()
	return (GetTickCount() + GetLatency()/2 > Orbwalk.lastAttack + Orbwalk.lastWindUp + 20)
end

function timeToShoot()
    return (GetTickCount() > Orbwalk.lastAttack)
end

function moveToCursor()
	if GetDistance(mousePos) > 50 or Orbwalk.lastAnimation == "Idle1" then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*Orbwalk.walkDistance
		myHero:MoveTo(moveToPos.x, moveToPos.z)
	end
end

function OrbWalk(target)
	if ValidTarget(target, 500) then
		if timeToShoot() then
			myHero:Attack(target)
		elseif heroCanMove() then
			moveToCursor()
		end
	elseif heroCanMove() then
		moveToCursor()
	end
end

function Laugh()
	p = CLoLPacket(0x47)
	p:EncodeF(myHero.networkID)
	p:Encode1(2)
	p.dwArg1 = 1
	p.dwArg2 = 0
	SendPacket(p)
end

function CastR(unit, pos, spell)
	if ValidTarget(unit, 900) and myHero:CanUseSpell(_R) == READY then
		CastSpell(_R, pos.x, pos.z)
	end
end

AdvancedCallback:bind('OnGainBuff', function(unit, buff)
	if unit.isMe then
		if buff.name == "rivenpassiveaaboost" then
			PassiveCount = buff.stack
		elseif buff.name == "RivenFengShuiEngine" then
			RData.Start = os.clock()
			RData.Up = true
		elseif buff.name == "riventricleavesoundone" then
			QData.Count = 1
			QData.Last = os.clock()
		elseif buff.name == "riventricleavesoundtwo" then
			QData.Count = 2
			QData.Last = os.clock()
		elseif buff.name == "riventricleavesoundthree" then
			QData.Count = 3
			QData.Last = os.clock()
		end
	end
end)

AdvancedCallback:bind('OnUpdateBuff', function(unit, buff)
	if unit.isMe then
		if buff.name == "rivenpassiveaaboost" then
			PassiveCount = buff.stack
		end
	end
end)

AdvancedCallback:bind('OnLoseBuff', function(unit, buff)
	if unit.isMe then
		if buff.name == "rivenpassiveaaboost" then
			PassiveCount = 0
		elseif buff.name == "RivenFengShuiEngine" then
			RData.Up = false
		end
	end
end)