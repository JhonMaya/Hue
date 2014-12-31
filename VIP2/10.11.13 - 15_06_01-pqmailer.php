<?php exit() ?>--by pqmailer 217.82.19.223
class 'Plugin'
if myHero.charName ~= "Jayce" then return end

local SkillQ1, SkillQ2
local GateData = nil
local IsMeele, IsRange = true, false
local LastStance = os.clock()
local Hyperload = false

function Plugin:__init()
	SkillQ1 = AutoCarry.Skills:NewSkill(true, _Q, 1050, "Shock Blast", AutoCarry.SPELL_LINEAR_COL, 0, false, false, 1.6, 285, 50, true)
	SkillQ2 = AutoCarry.Skills:NewSkill(true, _Q, 1625, "Shock Blast", AutoCarry.SPELL_LINEAR_COL, 0, false, false, 1.6, 285, 50, true)
	PrintChat("this is a BETA")
	--AutoCarry.Crosshair:SetSkillCrosshairRange(1625)
end

function Plugin:OnTick()
	DisableDefaultSkillUse()
	CheckGate()
	if Menu.placeMovementGate then PlaceMovementGate() end
	if Menu.manualEQ then EQtoMouse() end
	if Menu.wBurst then wBurst() end
	if Menu.harass then Harass() end
	if AutoCarry.Keys.AutoCarry then Combo() end
end

function Plugin:OnProcessSpell(unit, spell)
	if unit.isMe then
		if spell.name:find("jayceaccelerationgate") then
			GateData = {timestamp = os.clock()}
		end
	else
		return
	end
end

AdvancedCallback:bind('OnGainBuff', function(unit, buff)
	if unit.isMe then
		if buff.name == "jaycestancehammer" then
			IsMeele = true
			IsRange = false
		elseif buff.name == "jaycestancegun" then
			IsMeele = false
			IsRange = true
		elseif buff.name == "jaycehypercharge" then
			Hyperload = true
		end
	end
end)

AdvancedCallback:bind('OnLoseBuff', function(unit, buff)
	if unit.isMe then
		if buff.name == "jaycehypercharge" then
			Hyperload = false
		end
	end
end)

function DisableDefaultSkillUse()
	AutoCarry.Skills:GetSkill(_Q).Enabled = false
end

function Combo()
	local Target = AutoCarry.Crosshair:GetTarget()

	if ValidTarget(Target) then
		local Distance = myHero:GetDistance(Target)

		if IsRange then
			if myHero:CanUseSpell(_E) == READY and myHero:CanUseSpell(_Q) == READY and Distance <= 1650 and not SkillQ2:GetCollision(Target) then
				local Pos = GatePosition(Target)
				CastSpell(_E, Pos.x, Pos.z)
			end

			if GateData then
				SkillQ2:ForceCast(Target)
			elseif getDmg("Q", Target, myHero) > Target.health then
				SkillQ1:ForceCast(Target)
			end

			if myHero:CanUseSpell(_Q) ~= READY and myHero:CanUseSpell(_E) ~= READY then
				SwitchStance("meele")
			end
		end

		if IsMeele then
			if myHero:CanUseSpell(_Q) == READY and Distance <= 600 then
				CastSpell(_Q, Target)
			end

			if myHero:CanUseSpell(_W) == READY then
				local InRange = CountEnemyHeroInRange(285)

				if InRange > 0 then
					CastSpell(_W)
				end
			end

			if myHero:CanUseSpell(_E) == READY and Distance <= 240 then
				CastSpell(_E, Target)
			end

			if myHero:CanUseSpell(_Q) ~= READY and myHero:CanUseSpell(_W) ~= READY and myHero:CanUseSpell(_E) ~= READY then
				SwitchStance("range")
			end
		end

		if IsRange and os.clock() <= LastStance + 4 and myHero:CanUseSpell(_Q) ~= READY and myHero:CanUseSpell(_E) ~= READY then
			CastSpell(_W)
		end
	end
end

function Harass()
	local Target = AutoCarry.Crosshair:GetTarget()

	if ValidTarget(Target) then
		if IsMeele then
			SwitchStance("range")
		end

		if IsRange then
			if myHero:CanUseSpell(_E) == READY and myHero:CanUseSpell(_Q) == READY and myHero:GetDistance(Target) <= 1650 and not SkillQ2:GetCollision(Target) then
				local Pos = GatePosition(Target)
				CastSpell(_E, Pos.x, Pos.z)
			end

			if GateData then
				SkillQ2:ForceCast(Target)
			elseif GateData == nil and myHero:CanUseSpell(_E) ~= READY then
				SkillQ1:ForceCast(Target)
			end
		end
	end
end

function EQtoMouse()
	if IsMeele then
		SwitchStance("range")
	end

	if IsRange then
		if myHero:CanUseSpell(_E) == READY and myHero:CanUseSpell(_Q) == READY then
			local Pos = GatePosition(mousePos)
			CastSpell(_E, Pos.x, Pos.z)
		end

		local MyPos = Vector(myHero.x, myHero.y, myHero.z)
		local MousePos = Vector(mousePos.x, mousePos.y, mousePos.z)
		local EndPoint = nil

		if GateData then
			EndPoint = MyPos - (MyPos - MousePos):normalized() * 1625
		elseif GateData == nil and myHero:CanUseSpell(_E) ~= READY then
			EndPoint = MyPos - (MyPos - MousePos):normalized() * 1050
		end

		if EndPoint and myHero:CanUseSpell(_Q) == READY then
			CastSpell(_Q, EndPoint.x, EndPoint.z)
		end
	end
end

function wBurst()
	local Target = AutoCarry.Crosshair:GetTarget()

	if ValidTarget(Target, 500) then
		if IsMeele then
			SwitchStance("range")
		end

		if IsRange then
			if myHero:CanUseSpell(_W) == READY then
				CastSpell(_W)
			end
		end
	else
		return
	end
end

function SwitchStance(stance)
	if not myHero:CanUseSpell(_R) == READY then return end

	if (stance == "range" and IsMeele) or (stance == "meele" and IsRange) then
		CastSpell(_R)
		LastStance = os.clock()
		return
	end
end

function GatePosition(Position)
	if Position then
		local TargetPos = Vector(Position.x, Position.y, Position.z)
		local MyHeroPos = Vector(myHero.x, myHero.y, myHero.z)
		local GatePos = MyHeroPos+(MyHeroPos-TargetPos)*(-Menu.gatePuffer/myHero:GetDistance(mousePos))

		return GatePos
	else
		return
	end
end

function CheckGate()
	if GateData ~= nil then
		if os.clock() > GateData.timestamp + 4 then
			GateData = nil
		else
			return
		end
	else
		return
	end
end

function PlaceMovementGate()
	if IsMeele then
		SwitchStance("range")
	end

	if myHero:CanUseSpell(_E) == READY then
		local Pos = GatePosition(mousePos)
		CastSpell(_E, Pos.x, Pos.z)
	else
		return
	end
end

Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "PQJayce")
Menu:addParam("placeMovementGate", "Place Movement Gate", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("Y"))
Menu:addParam("manualEQ", "EQ to Mouse Pos", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("A"))
Menu:addParam("harass", "Harass Target", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("T"))
Menu:addParam("wBurst", "W Burst", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("S"))
Menu:addParam("gatePuffer", "Distance between Gate and Hero", SCRIPT_PARAM_SLICE, 100, 10, 100, 0)