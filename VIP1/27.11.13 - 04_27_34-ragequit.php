<?php exit() ?>--by ragequit 174.53.87.155
--[[ madlife zed ]]


local Names = {
 "MrSithSquirrel",
 "iRes",
 "MrSkii",
 "macrosd",
}

local f = false
for _, Name in pairs(Names) do
 if GetUser() == Name then
  f = true
 end
end

if f then PrintChat("Welcome Alpha Testers") end


if myHero.charName ~= "Zed" then return end

class 'Plugin'

local Target
local SkillQ, SkillW
local NextClone = 0
local CLONE_W, CLONE_R = 1, 2
local CloneW, CloneR = nil, nil
local WBuff, RBuff = nil, nil


function Plugin:__init()
	SkillQ = AutoCarry.Skills:NewSkill(false, _Q, 900, "Razor Shuriken", AutoCarry.SPELL_LINEAR, 0, false, false, 1.742, 235, 100, false)
	SkillW = AutoCarry.Skills:NewSkill(false, _W, 550, "Living Shadow", AutoCarry.SPELL_LINEAR, 0, false, false, 1.5, 200, 100, false)
	SkillWHarass = AutoCarry.Skills:NewSkill(false, _W, 900, "Living Shadow", AutoCarry.SPELL_LINEAR, 0, false, false, 1.5, 200, 100, false)
	AutoCarry.Crosshair:SetSkillCrosshairRange(900)

	AdvancedCallback:bind("OnGainBuff", OnGainBuff)
	AdvancedCallback:bind("OnLoseBuff", OnLoseBuff)

	for _, Item in pairs(AutoCarry.Items.ItemList) do
		if Item.ID == 3153 then
			Item.Enabled = false
		end
	end
end

function Plugin:OnTick()
	Target = AutoCarry.Crosshair:GetTarget()
	QREADY = (myHero:CanUseSpell(_Q) == READY)
	WREADY = (myHero:CanUseSpell(_W) == READY)
	EREADY = (myHero:CanUseSpell(_E) == READY)
	RREADY = (myHero:CanUseSpell(_R) == READY)

	if Target then
		if AutoCarry.Keys.AutoCarry and Menu.Combo then
			Combo(Target)
		elseif (AutoCarry.Keys.MixedMode and Menu.HarassMM) or (AutoCarry.Keys.LastHit and Menu.HarassLH) or (AutoCarry.Keys.LaneClear and Menu.HarassLC) then
			Harass(Target)
		end
	if Menu.autoE then CheckE(Target) end
	end
end

function Combo(Target)
	if not CloneR and not RBuff and myHero:GetSpellData(_R).name ~= "ZedR2" and myHero:CanUseSpell(_R) == READY then
		CastSpell(_R, Target)
		return
	end

	if CloneR or myHero:CanUseSpell(_R) ~= READY then
		local Botrk = GetInventorySlotItem(3153)
		if Botrk then
			CastSpell(Botrk, Target)
		end
	end

	DoW(Target)

	if CloneW or myHero:CanUseSpell(_W) ~= READY then
		SkillQ:Cast(Target)
	end

	CheckE(Target)

	if CloneR and GetDistance(Target) > GetDistance(Target, CloneR) then
		CastSpell(_R)
	end
end

function Harass(Target)
	if not CloneW and not WBuff and not myHero:GetSpellData(_W).name:find("zedw2") and myHero:CanUseSpell(_Q) == READY then
		SkillWHarass:Cast(Target)
	end
	if CloneW or myHero:CanUseSpell(_W) ~= READY then
		SkillQ:Cast(Target)
	end
end

function DoW(Target)
	if not CloneW then
		SkillW:Cast(Target)
		CastSpell(_W, Target.x, Target.z)
	elseif GetDistance(Target) > GetDistance(CloneW, Target) then
		CastSpell(_W)
	end
end

function CheckE(Target)
	if GetDistance(Target) <= 290 then
		CastSpell(_E)
	elseif CloneW and GetDistance(Target, CloneW) <= 290 then
		CastSpell(_E)
	elseif CloneR and GetDistance(Target, CloneR) <= 290 then
		CastSpell(_E)
	end
end

function OnGainBuff(Unit, Buff)
	if Unit.isMe then
		if Buff.name == "zedwhandler" then
			NextClone = CLONE_W
			WBuff = true
		elseif Buff.name == "ZedR2" then
			NextClone = CLONE_R
			RBuff = true
		end
	end
end

function OnLoseBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "zedwhandler" then
		WBuff = nil
	elseif Unit.isMe and Buff.name == "ZedR2" then
		RBuff = nil
	end
end

function Plugin:OnCreateObj(Object)
	if Object.name:find("Zed_Clone_idle.troy") then
		if NextClone == CLONE_W and WBuff then
			CloneW = Object
			WBuff = nil
		elseif NextClone == CLONE_R and RBuff then
			CloneR = Object
			RBuff = nil
		end
	end
end

function Plugin:OnDeleteObj(Object)
	if Object and Object == CloneW then
		CloneW = nil
	elseif Object and Object == CloneR then
		CloneR = nil
	end
end

function Plugin:OnDraw()
	if CloneW then
		AutoCarry.Helper:DrawCircleObject(CloneW, 100, AutoCarry.Helper.Colour.Green, 3)
	end
	if CloneR then
		AutoCarry.Helper:DrawCircleObject(CloneR, 100, AutoCarry.Helper.Colour.Green, 3)
	end
end

Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "The Wonderful Zed")

Menu:addParam("Combo", "Combo", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("HarassLC", "Harass in LaneClear", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("HarassLH", "Harass in LastHit", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("HarassMM", "Harass in MixedMode", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("AutoE", "Auto E", SCRIPT_PARAM_ONOFF, true)