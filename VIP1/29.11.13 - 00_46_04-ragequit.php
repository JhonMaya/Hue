<?php exit() ?>--by ragequit 174.53.87.155
--[[ The Wonderful Riven ]]


--[[ The Wonderful Riven ]]
--Spacer
--Spacer
--Spacer
--Spacer
--Spacer

local Names = {
 "mrsithsquirrel",
 "ragequit",
 "iRes",
 "MrSkii",
 "marcosd",
 "WTayllor",
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
--local needult = false
--local combos1 = true
--local combos2 = false
--local combos3 = false

local QRange, WRange, ERange = 260, 250, 325

function Plugin:__init() -- This function is pretty much the same as OnLoad, so you can do your load stuff in here
	SkillQ = AutoCarry.Skills:NewSkill(false, _Q, 260, "Broken Wings", AutoCarry.SPELL_SELF_ATMOUSE, 0, false, false) -- register muh skrillz
	SkillW = AutoCarry.Skills:NewSkill(false, _W, 250, "Ki Burst", AutoCarry.SPELL_CIRCLE, 0, false, false, 1.40, 250, 275, false)
	SkillE = AutoCarry.Skills:NewSkill(false, _E, 325, "Valor", AutoCarry.SPELL_CIRCLE, 0, false, false, 1.40, 250, 275, false)
	SkillR = AutoCarry.Skills:NewSkill(false, _R, 0, "Blade of the Exile", AutoCarry.SPELL_SELF, 0, false, false)
	SkillRC = AutoCarry.Skills:NewSkill(false, _R, 900, "Wind Slash", AutoCarry.SPELL_CONE, 0, false, false, 1.5, 1130, 30, false)

	AutoCarry.Plugins:RegisterOnAttacked(OnAttacked)
	AdvancedCallback:bind("OnGainBuff", OnGainBuff)
	AdvancedCallback:bind("OnLoseBuff", OnLoseBuff)

	AutoCarry.Crosshair:SetSkillCrosshairRange(900)
	combos1 = true
	combos2 = false
	combos3 = false
	PrintChat("<font color='#00adec'> >>The Wonderful Riven LOADED!!<</font>")
	PrintChat("<font color='#00adee'> >>Combo 2/3 and Harass2/3 Disabed for now do not use<</font>")
	PrintChat("<font color='#00addd'> >>Quick Swap Disabled For now until new combos and harasses are unlocked<</font>")
	PrintChat("<font color='#00addd'> >>Anti over kill disabled until damage lib update<</font>")
end

function Plugin:OnTick()
	Target = AutoCarry.Crosshair:GetTarget()
	if AutoCarry.Keys.AutoCarry then DoCombo = true else DoCombo = false end
	if AutoCarry.Keys.MixedMode then DoHarass = true else DoHarass = false end
	if AutoCarry.Keys.LastHit then DoCombo = false DoHarass = false end
	if AutoCarry.Keys.LaneClear then DoCombo = false DoHarass = false end
	antimultiharass()
	antimulticombo()
	debugger()
	if Menu.Select then 
		Selecter()
		makeselection()
		--Menu.Select = false
		--Menu.SelectH = false
	end
 if Menu.Run then Escape() end

	if Target and Menu.AutoKS then Killsteal() end
	--[[ do hax ]]
	if Target and Menu.AutoKS then
		if HasUlt and GetTickCount() > REndsAt then
			SkillRC:ForceCast(Target)
		end
	end	
	--[[hax end]]	
	--[[harass 1]]
		if Target and DoHarass and Menu.Harass then
			Harass(Target)
		end
	--[[harass 1]]
	--[[combo 1]]
		if Target and DoCombo and Menu.Combo then
			Combo(Target)
		end
	--[[combo 1]]
	--[[farm]]
	if Menu.FarmQ and AutoCarry.Keys.LaneClear then
	spellfarm()
end
if Menu.FarmQx and AutoCarry.Keys.LastHit then
	spellfarm()
	end

end

--[[ Combo ]]

function Combo(Target) -- ult is needed combo
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

function Combo2(Target) -- combo without ult to do if ult is not needed
end	

function ComboNoC(Target)
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

function Harass2(Target)

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

function HarassNoC(Target)
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

function AntiOverkill()  -- do anti overkill calcs here
	--calc if true then local need ult == true
end	

function GetEDashPos(Target)
	MyPos = Vector(myHero.x, myHero.y, myHero.z)
	MousePos = Vector(Target.x, Target.y, Target.z)
	return MyPos - (MyPos - MousePos):normalized() * ERange
end

--[[ Farm? ]]

function spellfarm() -- hmm q farms maybes
if AutoCarry.Minions.KillableMinion and AutoCarry.Orbwalker:AttackReady() then
    	local BlacklistMinion = AutoCarry.Minions.KillableMinion
  	end
  		if not AutoCarry.Orbwalker:AttackReady() and (myHero:CanUseSpell(_Q) == READY or myHero:CanUseSpell(_E) == READY) then
    		for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
      		if Minion ~= BlacklistMinion then
        		if GetDistance(Minion) < 625 then
          				if myHero:CanUseSpell(_Q) == READY and Menu.FarmQ then
           	 				if Minion.health <= getDmg("Q", Minion, myHero) / 3 then
              					CastSpell(_Q, Minion.x, Minion.z)
              			end		
            		end
          		end
  			end
		end	
	end
end	

--[[ Exploit Q thingy ]]

--[[ Menu etc ]]

function Plugin:OnAnimation(Unit, Animation)
	if DoHarass and Menu.Harass or DoCombo and Menu.Combo then 	
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
end	

function Plugin:OnProcessSpell(Unit, Spell)
	if Unit.isMe and Spell.name == "RivenFeint" then
		RetreatPos = nil
	end
end

function DoAnimationCancel(Target)
	if DoHarass and Menu.Harass or DoCombo and Menu.Combo then 	
		if QCount > 0 then
			AutoCarry.MyHero:MovementEnabled(false)
			AutoCarry.MyHero:AttacksEnabled(false)
			local movePos = GetClickPos(Target)
			if movePos then
			myHero:MoveTo(movePos.x, movePos.z)
			end
		end
	end	
end	

function OnAttacked()
	if DoHarass and Menu.Harass or DoCombo and Menu.Combo then 
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

function Plugin:OnDraw()
if Menu.Combo then DrawText("Combo 1 Active",16,100, 100, 0xFF00FF00) end
if Menu.Combo2 then DrawText("Combo 2 Active",16,100, 100, 0xFF00FF00) end
if Menu.Combo3 then DrawText("Combo 3 Non-Animation Cancel Active",16,100, 100, 0xFF00FF00) end
if Menu.Harass then DrawText("Harass 1 Active",16,100, 120, 0xFF00FF00) end
if Menu.Harass2 then DrawText("Harass 2 Active",16,100, 120, 0xFF00FF00) end
if Menu.Harass3 then DrawText("Harass 3 Non-Animation Cancel Active",16,100, 120, 0xFF00FF00) end
DrawText("Swap Combo Key is T",16,100, 140, 0xffffff00)	
end	

function IncreaseRanges()
	WRange = 260
end

function DecreaseRanges()
	WRange = 250
end

function Escape()
	local Distance = GetDistance(mousePos)
	local MoveSqr = math.sqrt((mousePos.x - myHero.x) ^ 2 + (mousePos.z - myHero.z) ^ 2)
	local MoveX = myHero.x + Distance * ((mousePos.x - myHero.x) / MoveSqr)
	local MoveZ = myHero.z + Distance * ((mousePos.z - myHero.z) / MoveSqr)
	myHero:MoveTo(MoveX, MoveZ)
	CastSpell(_Q, MoveX, MoveZ)
	CastSpell(_E, MoveX, MoveZ)
end	

function antimulticombo()
if Menu.Combo then
Menu.Combo2 = false
Menu.Combo3 = false
end
if Menu.Combo2 then
Menu.Combo = false
Menu.Combo3 = false
end	
if Menu.Combo3 then
Menu.Combo = false
Menu.Combo2 = false
end		
end

function antimultiharass()
if Menu.Harass then
Menu.Harass2 = false
Menu.Harass3 = false
end
if Menu.Harass2 then
Menu.Harass = false
Menu.Harass3 = false
end	
if Menu.Harass3 then
Menu.Harass = false
Menu.Harass2 = false
end
end

function debugger()
--[[if Menu.Combo then AutoCarry.Helper:Debug("Combo 1 active") end
if Menu.Combo2 then AutoCarry.Helper:Debug("Combo 2 active") end
if Menu.Combo3 then AutoCarry.Helper:Debug("Combo 3 active Non-Animation") end
if Menu.Harass then AutoCarry.Helper:Debug("Harass 1 active") end
if Menu.Harass2 then AutoCarry.Helper:Debug("Harass 2 active") end
if Menu.Harass3 then AutoCarry.Helper:Debug("Harass 3 active Non-Animation") end
AutoCarry.Helper:Debug("Swap key is T")]]			
end

function Selecter()
if combos == true then 
		combos = false
		combos2 = true
		combos3 = false
	end	
	if combos2 == true then 
		combos = false
		combos2 = false
		combos3 = true
	end	
	if combos3 == true then 
		combos = true
		combos2 = false
		combos3 = false
	end	
end	

function makeselection()
	if combos == true then
	Menu.Combo = true	
	Menu.Combo2 = false
	Menu.Combo3 = false
	end
	if combos2 == true then
	Menu.Combo2 = true	
	Menu.Combo3 = false
	Menu.Combo = false	
	end
	if combos3 == true then
	Menu.Combo3 = true	
	Menu.Combo2 = false
	Menu.Combo = false	
	end
end	



Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "The Wonderful Riven")
Menu:addParam("sep", "-- AutoCarry Combos--", SCRIPT_PARAM_INFO, "")
Menu:addParam("Combo", "Combo one In Autocarry", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("Combo2", "Combo 2 in Autocarry", SCRIPT_PARAM_ONOFF, false)
Menu:addParam("Combo3", "Non-Animation Cancel Combo 3", SCRIPT_PARAM_ONOFF, false)
Menu:addParam("sep", "-- Choose only 1 of the above combos or errors!--", SCRIPT_PARAM_INFO, "")
Menu:addParam("sep", "", SCRIPT_PARAM_INFO, "")

Menu:addParam("sep", "-- Mixmode Harasses--", SCRIPT_PARAM_INFO, "")
Menu:addParam("Harass", "Harass one in MixedMode", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("Harass2", "Harass 2 in MixedMode", SCRIPT_PARAM_ONOFF, false)
Menu:addParam("Harass3", "Non-Animation Cancel Harass 3", SCRIPT_PARAM_ONOFF, false)
Menu:addParam("sep", "-- Choose only 1 of the above harasses or errors!--", SCRIPT_PARAM_INFO, "")
Menu:addParam("sep", "", SCRIPT_PARAM_INFO, "")

Menu:addParam("sep", "-- Farming --", SCRIPT_PARAM_INFO, "")
Menu:addParam("FarmQ", "Farm With Q in LaneClear", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("FarmQx", "Farm With Q in LastHit", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("sep", "", SCRIPT_PARAM_INFO, "")

Menu:addParam("sep", "-- Escape --", SCRIPT_PARAM_INFO, "")
Menu:addParam("Run", "Escape Combo", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
Menu:addParam("sep", "", SCRIPT_PARAM_INFO, "")

Menu:addParam("sep", "-- Misc Functions --", SCRIPT_PARAM_INFO, "")
Menu:addParam("AutoKS", "Auto KS with R", SCRIPT_PARAM_ONOFF, true)
Menu:addParam("AntiOK", "Use AntiOverkill", SCRIPT_PARAM_ONOFF, false)
Menu:addParam("Select", "Swap Combos", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
Menu:addParam("SelectH", "Swap Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
Menu:addParam("sep", "", SCRIPT_PARAM_INFO, "")