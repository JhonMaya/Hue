<?php exit() ?>--by pqmailer 217.82.6.109
class 'Plugin'
if myHero.charName ~= "Riven" or not VIP_USER then return end

local QData = {Count = 0, Next = 0, Last = 0}
local RData = {Start = 0, Up = false}
local PassiveCount = 0
local Skills, Keys, Items, Data, Jungle, Helper, MyHero, Minions, Crosshair, Orbwalker = AutoCarry.Helper:GetClasses()

function Plugin:__init()
	PrintChat("PQRiven loaded!")
end

function Plugin:OnTick()
	Crosshair:SetSkillCrosshairRange(myHero.range + GetDistance(myHero.minBBox) + (self:GetQRadius()*2))
	if myHero:CanUseSpell(_Q) ~= READY and os.clock() > QData.Last + 1 then QData.Count = 0 end
	if Menu.ksQ then self:ksQ() end
	if Menu.ksW then self:ksW() end
	if Menu.ksR then self:ksR() end
	self:CastRRnd()
	if Keys.AutoCarry then self:Combo() end
	if Menu.harass and Keys.MixedMode then self:Harass() end
	if Keys.LaneClear and Menu.lcSkills then self:LaneClear() end
end
-- test
function Plugin:OnProcessSpell(unit, spell)
	if unit.isMe then
		local Target = Crosshair:GetTarget()

		if spell.name == "RivenTriCleave" and Menu.cancelQ and ValidTarget(Target) then
			local Pos = Target + (Vector(myHero) - Target):normalized()*(GetDistance(Target)+50)
			if Pos then
				Packet('S_MOVE', {x = Pos.x, y = Pos.z}):send()
			end
		elseif spell.name == "RivenFengShuiEngine" or spell.name == "rivenizunablade" then
			if Menu.cancelRE and myHero:CanUseSpell(_E) == READY then
				CastSpell(_E, mousePos.x, mousePos.z)
			elseif Menu.cancelRQ and myHero:CanUseSpell(_Q) == READY then
				CastSpell(_Q, mousePos.x, mousePos.z)
			end
		elseif spell.name == "RivenKiBurst" and Menu.cancelW then
			if GetInventoryItemIsCastable(3077) then
				CastItem(3077)
			elseif GetInventoryItemIsCastable(3074) then
				CastItem(3074)
			end
		elseif spell.name:lower():find("attack") then
			DelayAction(function ()
				if Keys.AutoCarry and ValidTarget(Target) then
					local Distance = myHero:GetDistance(Target)
					if Distance <= self:GetQRadius() and myHero:CanUseSpell(_Q) == READY then
						CastSpell(_Q, Target.x, Target.z)
					end
					if Distance <= self:GetWRadius() and myHero:CanUseSpell(_W) == READY then
						CastSpell(_W)
					end
				end
			end, spell.windUpTime-GetLatency()/2000)
		end
	end
end

function Plugin:GetQRadius()
	if RData.Up then
		if QData.Count == 2 then
			return 200+260
		else
			return 162.5+260
		end
	else
		if QData.Count == 2 then
			return 112.5+260
		else
			return 150+260
		end
	end
end

function Plugin:GetWRadius()
	if RData.Up then
		return 135
	else
		return 125
	end
end

function Plugin:Combo()
	local Target = Crosshair:GetTarget()

	if ValidTarget(Target) then
		local Distance = myHero:GetDistance(Target)
		local Radius = self:GetQRadius()
		local Position = GetAoESpellPosition(Radius, Target, 0)

		if Menu.useRcombo then
			if not RData.Up and (Target.health/Target.maxHealth)*100 <= Menu.useRHealth and PassiveCount < 3 then
				CastSpell(_R)
			end
			if RData.Up and myHero:CanUseSpell(_R) == READY and Distance <= 870 and getDmg("R", Target, myHero) >= Target.health then
				CastSpell(_R, Target.x, Target.z)
			end
		end
		local GCDistance = 0
		if myHero:CanUseSpell(_Q) == READY then
			GCDistance = self:GetQRadius()+125
		elseif myHero:CanUseSpell(_W) == READY then
			GCDistance = self:GetWRadius()+125
		end
		if myHero:CanUseSpell(_E) == READY and Menu.useEcombo and Distance > 125 and Distance < 325 + GCDistance then
			CastSpell(_E, Target.x, Target.z)
		end
		if myHero:CanUseSpell(_W) == READY and Distance <= self:GetWRadius() then
			CastSpell(_W)
		end
		if not Menu.forceAA then
			CastSpell(_Q, Position.x, Position.z)
		end
	end
end

function Plugin:Harass()
	local Target = Crosshair:GetTarget()

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

function Plugin:LaneClear()
	local JungleMob = Jungle:GetAttackableMonster()
	local Target = nil

	if JungleMob and ValidTarget(JungleMob, self:GetQRadius()+125) then
		Target = JungleMob
	else
		for _, Minion in pairs(Minions.EnemyMinions.objects) do
			if ValidTarget(Minion, self:GetQRadius()+125) and not ((Minions.AlmostKillable and Minions.AlmostKillable.networkID == Minion.networkID) or (Minions.KillableMinion and Minions.KillableMinion.networkID == Minion.networkID)) then
				Target = Minion
				break
			end
		end
	end

	if ValidTarget(Target, self:GetQRadius()+125) then
		if myHero:CanUseSpell(_W) == READY then
			CastSpell(_W)
		end
		if myHero:CanUseSpell(_Q) == READY then
			CastSpell(_Q, Target.x, Target.z)
		end
	end
end

function Plugin:ksQ()
	if myHero:CanUseSpell(_Q) == READY then
		local Radius = self:GetQRadius()
		for _, Enemy in pairs(Helper.EnemyTable) do
			if ValidTarget(Enemy, self:GetQRadius()+125) and getDmg("Q", Enemy, myHero) >= Enemy.health then
				local Position = GetAoESpellPosition(Radius, Enemy, 0)
				CastSpell(_Q, Position.x, Position.z)
			end
		end
	end
end

function Plugin:ksW()
	if myHero:CanUseSpell(_W) == READY then
		for _, Enemy in pairs(Helper.EnemyTable) do
			if ValidTarget(Enemy, 250) and getDmg("W", Enemy, myHero) >= Enemy.health then
				CastSpell(_W)
			end
		end
	end
end

function Plugin:ksR()
	if myHero:CanUseSpell(_R) == READY then
		for _, Enemy in pairs(Helper.EnemyTable) do
			if ValidTarget(Enemy, 870) and getDmg("R", Enemy, myHero) >= Enemy.health then
				if RData.Up and myHero:CanUseSpell(_R) == READY then
					CastSpell(_R, Enemy.x, Enemy.z)
				else
					if not Menu.ksRws then
						CastSpell(_R)
					end
				end
			end
		end
	end
end

function Plugin:CastRRnd()
	if RData.Up and myHero:CanUseSpell(_R) == READY and os.clock() > RData.Start + 13 then
		local Lowest = nil
		for _, Enemy in pairs(Helper.EnemyTable) do
			if ValidTarget(Enemy, 870) then
				if Lowest and Lowest.valid then
					if Enemy.health < Lowest.health then
						Lowest = Enemy
					end
				else
					Lowest = Enemy
				end
			end
		end
		if ValidTarget(Lowest, 870) then
			CastSpell(_R, Lowest.x, Lowest.z)
		end
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

Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "PQRiven")
Menu:addParam("harass", "Harass in Mixed Mode", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("Y"))
Menu:addParam("forceAA", "Force AA in Combo", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("T"))
Menu:addParam("useEcombo", "Use E in Combo", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("A"))
Menu:addParam("useRcombo", "Use R in Combo", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("useEharass", "Use E while Harass", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("useRHealth", "Activate R at % enemy hp", SCRIPT_PARAM_SLICE, 60, 0, 100, 0)
Menu:addParam("ksQ", "KS with Q", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("ksW", "KS with W", SCRIPT_PARAM_ONOFF, false)
Menu:addParam("ksR", "KS with R", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("ksRws", "KS only if Wind Slash is active", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("cancelQ", "Cancel Q animation with movement", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("cancelW", "Cancel W animation with Tiamat/Hydra", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("cancelRQ", "Cancel R animation with Q", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("cancelRE", "Cancel R animation with E", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("lcSkills", "Use Skills while Lane Clear/Jungle", SCRIPT_PARAM_ONOFF, true)
Menu:permaShow("harass")
Menu:permaShow("forceAA")
Menu:permaShow("useEcombo")