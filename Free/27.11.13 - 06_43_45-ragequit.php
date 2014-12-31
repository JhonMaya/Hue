<?php exit() ?>--by ragequit 174.53.87.155
--[[ The Wonderful Riven ]]
--Spacer
--Spacer
--Spacer
--Spacer
--Spacer

local Names = {
 "ragequit",
 "MrSithSquirrel",
 "iRes",
 "MrSkii",
 "macrosd",
 "Wtayllor",
 "420yoloswag",
 "pyrophenix",
 "dragonne",
 "xxgowxx",
 "kriksi",
 "serpicos",
 "Clamity",
 "xtony211",
 "Mercurial4991",
 "khicon",
 "igotcslol",
 "xthanhz",
 "Lienniar",
 "420yoloswag",
}

local f = false
for _, Name in pairs(Names) do
 if GetUser() == Name then
  f = true
 end
end

if not f then return end
if f then PrintChat("Welcome Alpha Testers") end



if myHero.charName ~= "Riven" then return end

class 'Plugin'

local SkillQ 
local SkillW 
local SkillE 
local SkillR 
local SkillRC
local Target
local QCount = 0
local NextQ = 0
local ShouldCancel = false
local DoQFix = false
local RetreatPos = nil
local DoCombo, DoHarass = false, false
local HasUlt = false
local REndsAt = 0

local QRange, WRange, ERange = 260, 250, 325

function Plugin:__init() -- This function is pretty much the same as OnLoad, so you can do your load stuff in here
	SkillQ = AutoCarry.Skills:NewSkill(false, _Q, 210, "Broken Wings", AutoCarry.SPELL_SELF_ATMOUSE, 0, false, false) -- register muh skrillz
	SkillW = AutoCarry.Skills:NewSkill(false, _W, 290, "Ki Burst", AutoCarry.SPELL_CIRCLE, 0, false, false, 1.40, 250, 275, false)
	SkillE = AutoCarry.Skills:NewSkill(false, _E, 325, "Valor", AutoCarry.SPELL_CIRCLE, 0, false, false, 1.40, 250, 275, false)
	SkillR = AutoCarry.Skills:NewSkill(false, _R, 0, "Blade of the Exile", AutoCarry.SPELL_SELF, 0, false, false)
	SkillRC = AutoCarry.Skills:NewSkill(false, _R, 900, "Wind Slash", AutoCarry.SPELL_CONE, 0, false, false, 1.5, 250, 100, false)

	AutoCarry.Plugins:RegisterOnAttacked(OnAttacked)
	AdvancedCallback:bind("OnGainBuff", OnGainBuff)
	AdvancedCallback:bind("OnLoseBuff", OnLoseBuff)

	AutoCarry.Crosshair:SetSkillCrosshairRange(900)
end

function Plugin:OnTick()
	Target = AutoCarry.Crosshair:GetTarget()


	Killsteal()
	--[[ do hax ]]
	if Target and Menu.AutoKS then
		if HasUlt and GetTickCount() > REndsAt then
			SkillRC:ForceCast(Target)
		end
		if DoHarass and Menu.Harass then
			Harass(Target)
		end
		if Menu.Combo then
			Combo(Target)
		end
	end
end

--[[ Combo ]]

function Combo(Target)
	if not HasUlt and GetDistance(Target) <= ERange then
		CastSpell(_R)
	end

	if (not SkillR:IsReady() or HasUlt) and AutoCarry.Orbwalker:CanOrbwalkTargetFromPosition(Target, GetEDashPos(Target)) then
		CastSpell(_E, Target.x, Target.z)
	end
	if not ShouldCancel and QCount == 0 and not SkillQ:IsReady() then
		if GetDistance(Target) <= WRange then
			CastSpell(_W)
		end
	end
	if DoQFix then
		CastSpell(_Q, Target.x, Target.z)
	end
	if ShouldCancel then
		DoAnimationCancel(Target)
	end

end

--[[ Harass ]]

function Harass(Target)

	if not RetreatPos then
		RetreatPos = GetClickPos(Target)
	end
	if SkillW:IsReady() then
		AutoCarry.MyHero:AttacksEnabled(false)
	else
		AutoCarry.MyHero:AttacksEnabled(true)
	end

	if not ShouldCancel and QCount == 0 then
		if GetDistance(Target) <= WRange then
			CastSpell(_W)
		end
	end
	if DoQFix then
		CastSpell(_Q, Target.x, Target.z)
	end
	if ShouldCancel then
		DoAnimationCancel(Target)
	end
	if not SkillQ:IsReady() and not SkillW:IsReady() and QCount == 0 and NextQ - GetTickCount() < 2000 then
		if RetreatPos then
			CastSpell(_E, RetreatPos.x, RetreatPos.z)
		end
	end
end

function Killsteal()
	if myHero:CanUseSpell(_R) == READY then
		for _, enemy in pairs(AutoCarry.Helper.EnemyTable) do
			if ValidTarget(enemy) and GetDistance(enemy) < 870 and enemy.health < getDmg("R", enemy, myHero) then
				if HasUlt then
					CastSpell(_R, enemy.x, enemy.z)
				else
					CastSpell(_R)
				end
			end
		end
	end
end

function GetEDashPos(Target)
	MyPos = Vector(myHero.x, myHero.y, myHero.z)
	MousePos = Vector(Target.x, Target.y, Target.z)
	return MyPos - (MyPos - MousePos):normalized() * ERange
end

--[[ Farm? ]]

--[[ Exploit Q thingy ]]

--[[ Menu etc ]]

function Plugin:OnAnimation(Unit, Animation)
	if Unit.isMe and Animation:find("Spell1a") then 
		QCount = 1
		ShouldCancel = true
		DoQFix = false
	elseif Unit.isMe and Animation:find("Spell1b") then 
		QCount = 2
		ShouldCancel = true
		DoQFix = false
	elseif Unit.isMe and Animation:find("Spell1c") then 
		QCount = 3
		ShouldCancel = true
		DoQFix = false
	elseif Unit.isMe and Animation:find("Run") or Animation:find("Idle1") and ShouldCancel then
		ShouldCancel = false
		AutoCarry.MyHero:MovementEnabled(true)
		AutoCarry.MyHero:AttacksEnabled(true)
		AutoCarry.Orbwalker:ResetAttackTimer()
	end
end

function Plugin:OnProcessSpell(Unit, Spell)
	if Unit.isMe and Spell.name == "RivenFeint" then
		RetreatPos = nil
	end
end

function DoAnimationCancel(Target)
	if QCount > 0 then
		AutoCarry.MyHero:MovementEnabled(false)
		AutoCarry.MyHero:AttacksEnabled(false)
		local movePos = GetClickPos(Target)
		if movePos then
			myHero:MoveTo(movePos.x, movePos.z)
		end
	end
end

function OnAttacked()
	if Menu.Harass or Menu.Combo then
		if Target and GetTickCount() > NextQ and SkillQ:IsReady() then 
			DoQFix = true
			CastSpell(_Q, Target.x, Target.z)
			NextQ = AutoCarry.Orbwalker:GetNextAttackTime()
		end
	end
end

function OnGainBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "RivenFengShuiEngine" then
		IncreaseRanges()
		HasUlt = true
		REndsAt = GetTickCount() + 14000
	end
end

function OnLoseBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "RivenTriCleave" then
		QCount = 0
	elseif Unit.isMe and Buff.name == "RivenFengShuiEngine" then
		DecreaseRanges()
		HasUlt = false
	end
end

function GetClickPos(Target)
	if Target then
		MyPos = Vector(myHero.x, myHero.y, myHero.z)
		MousePos = Vector(Target.x, Target.y, Target.z)
		return MyPos - (MyPos - MousePos):normalized() * -200
	end
end

function IncreaseRanges()
	WRange = 260
end

function DecreaseRanges()
	WRange = 250
end

Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "The Wonderful Riven")
Menu:addParam("Combo", "Combo In Autocarry", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("Harass", "Harass in MixedMode", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("AutoKS", "Auto KS with R", SCRIPT_PARAM_ONOFF, true)
