<?php exit() ?>--by ragequit 174.53.87.155
--[[ The Wonderful Karthus
	by The Wonderful Nyan
]] -- :P

local Names = {
 "Sida",
 "mrsithsquirrel",
 "ragequit",
 "iRes",
 "MrSkii",
 "marcosd",
 "WTayllor",
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
 "tacD",
 "zderekzz",
 "EZGAMER",
 "xpliclt",
 "omfgabriel",
 "hellking298",
 "Ex0tic123",
 "xxlarsyxx",
 "eway86",
 "UglyOldGuy",
}

local f = false
for _, Name in pairs(Names) do
 if GetUser() == Name then
  f = true
 end
end

if f then PrintChat("Welcome Alpha Testers") end


if myHero.charName ~= "Karthus" then return end

class 'Plugin'

local qPred
local wPred
local enemies = {}
local defile = false
local disableMovement = false
local lastQ = 0
local Target

function Plugin:__init()
	AutoCarry.Skills:DisableAll() -- Disable the default SAC skills
    SkillQ = AutoCarry.Skills:NewSkill(false, _Q, 875, "Lay Waste", AutoCarry.SPELL_CIRCLE, 0, false, false, 1.5, 500, 100, false) -- register muh skrillz
    SkillW = AutoCarry.Skills:NewSkill(false, _W, 950, "Wall of Pain", AutoCarry.SPELL_LINEAR, 0, false, false, 1.40, 250, 275, false)
    SkillR = AutoCarry.Skills:NewSkill(false, _R, 0, "Requiem", AutoCarry.SPELL_SELF, 0, false, false)
    AutoCarry.Crosshair:SetSkillCrosshairRange(950)
    AutoCarry.Minions.EnemyMinions = minionManager(MINION_ENEMY, 950, myHero, MINION_SORT_HEALTH_ASC)

    AdvancedCallback:bind("OnGainBuff", OnGainBuff)
    AdvancedCallback:bind("OnLoseBuff", OnLoseBuff)

    PrintChat("<font color='#00adee'> >> Nyan's Karthas - PROdiction<</font>")
end	

function Plugin:OnTick()
	Target = AutoCarry.Crosshair:GetTarget()
	if Target then
		if KarthConfig.Combo then Combo(Target) end
		if KarthConfig.Harass then Harass(Target) end
		if KarthConfig.Spam then AutoE(Target) end
		if KarthConfig.Tear then Tear() end
		CheckR()
	end
	if KarthConfig.Farm then Farm() end

	if (KarthConfig.Combo or KarthConfig.Farm) and defile then
		for _, enemy in pairs(AutoCarry.Helper.EnemyTable) do
			if ValidTarget(enemy) and GetDistance(enemy) <= 425 then
				return
			end
		end
		CastSpell(_E)
	end
end

function OnGainBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "Defile" then
		defile = true
	end
end

function OnLoseBuff(Unit, Buff)
	if Unit.isMe and Buff.name == "Defile" then
		defile = false
	end
end

function Plugin:OnProcessSpell(unit, spell)
	if unit.isMe and spell.name == "LayWaste" then
		lastQ = GetTickCount()
	end
end

function Combo(Target)
	SkillW:Cast(Target)
	SkillQ:Cast(Target)
end

function Harass()
	SkillQ:Cast(Target)
end

function AutoE(Target)
	for _, enemy in pairs(AutoCarry.Helper.EnemyTable) do
		if ValidTarget(enemy) and GetDistance(enemy) <= 425 then
			CastSpell(_E)
			return
		end
	end

	if (KarthConfig.Combo or KarthConfig.Harass) and not defile and GetDistance(Target) <= 425 then CastSpell(_E) return end
end

local LastMsg = 0
function CheckR()
	if myHero:CanUseSpell(_R) ~= READY then return end
	if GetTickCount() > LastMsg + 1000 then
		for _, Enemy in pairs(AutoCarry.Helper.EnemyTable) do
			if Enemy.health < getDmg("R", Enemy, myHero) then
				PrintFloatText(myHero, 10, "Press R to kill "..Enemy.charName.."!!")
				LastMsg = GetTickCount()
			end
		end
	end
end

function Tear()
	if GetTickCount() > lastQ + 2900 and GetDistance(mousePos) <= 875 then
		CastSpell(_Q, mousePos.x, mousePos.z)	
	elseif GetTickCount() > lastQ + 2900 then
		CastSpell(_Q, myHero.x, myHero.z)
	end
end

function Farm()
	LastHit()
end

function LastHit()
			--spacer 
						--spacer 
									--spacer 
												--spacer 
															--spacer 
																		--spacer 
																					--spacer 
																								--spacer 
	for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
		if GetDistance(Minion) < 875 then
			local PredictedDamage =  AutoCarry.Minions:GetPredictedDamageOnTarget(Minion, GetTickCount() + 500)
			--spacer 
						--spacer 
									--spacer 
												--spacer 
															--spacer 
																		--spacer 

																					--spacer 


			local QDamage = PredictedDamage > 0 and getDmg("Q", Minion, myHero) or getDmg("Q", Minion, myHero) - 15

			local Health = Minion.health - PredictedDamage

			--spacer 
						--spacer 
									--spacer 
												--spacer 
															--spacer 
																		--spacer 
																					--spacer 
																								--spacer 
			if myHero:CanUseSpell(_Q) == READY then
			--spacer 
						--spacer 
									--spacer 
												--spacer 
															--spacer 
																		--spacer 
																					--spacer 
																								--spacer 

				if Health < QDamage / 2 then -- Just Q this minion, he'll die
					CastSpell(_Q, Minion.x, Minion.z)
				elseif Health < QDamage then -- This minion will die from full damage, let's see if we can hit him alone
					local QSpot = FindSingleSpot(Minion)
								--spacer 
											--spacer 
														--spacer 
																	--spacer 
																				--spacer 
																							--spacer 
																										--spacer 
																													--spacer 
					if QSpot and QSpot.single then -- We can hit alone
						CastSpell(_Q, QSpot.x, QSpot.z)
					end
				end
			end
			if GetDistance(Minion) < 425 then
				if Minion.health < getDmg("E", Minion, myHero) then
					CastSpell(_E)
								--spacer 
											--spacer 
														--spacer 
																	--spacer 
																				--spacer 
																							--spacer 
																										--spacer 
																													--spacer 
																																--spacer 
					return
				end
			end
		end
	end
end

function WillHitMultiple(Location, Target)
	for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
		if Location and Target and Minion and Minion ~= Target and GetDistance(Location, Minion) <= 210 then
			return true
		end
	end
	return false
end

function FindSingleSpot(Target)
	local QSpot = {}
	local Spot = { x = 0, y = 0, z = 0 }
    local Angle = 0
    local spotFound = false
    for i=0, 360, 10 do
        local rads = Angle * (math.pi / 180)
        Spot.x = Target.x + 70 * math.cos(rads)
        Spot.y = Target.y
        Spot.z = Target.z + 70 * math.sin(rads)
        if not WillHitMultiple(Spot, Target) then
            QSpot.x = Spot.x
            QSpot.y = Spot.y
            QSpot.z = Spot.z
            QSpot.single = true
            spotFound = true

            break
        end
        Angle = Angle - 10
    end
    if not spotFound then
        QSpot.x = Target.x
        QSpot.y = Target.y
        QSpot.z = Target.z
        QSpot.single = false
    end
    return QSpot
end

function FindSingleQSpot(Target)
	local Area = {} -- Minions that are in range to get hit too
	local QSpot = {}
	for _, Minion in pairs(AutoCarry.Minions.EnemyMinions.objects) do
		if Minion and Minion ~= Target and GetDistance(Target, Minion) <= 210 then
			table.insert(Area, Minion)


		end
	end

	-- if #Area then -- If minions can get hit
	-- 	local Space = { x = 0, y = 0, z = 0 } -- Create a fake larger circle containing all the extra space of the extra minions around target
	-- 	table.insert(Area, Target)
	-- 	local Count = #Area
	-- 	for _, Minion in pairs(Area) do -- For each minion that can get hit
	-- 		Space.x = Space.x + Minion.x
	-- 		Space.y = Space.y + Minion.y
	-- 		Space.z = Space.z + Minion.z
	-- 	end
	-- 	QSpot.x = Space.x / Count
 --        QSpot.y = Space.y / Count
 --        QSpot.z = Space.z / Count
    -- else
    	local Spot = { x = 0, y = 0, z = 0 }
        local Angle = 0
        local spotFound = false
        for i=0, 360, 10 do
            local rads = Angle * (math.pi / 180)
            Spot.x = Target.x + 50 * math.cos(rads)
            Spot.y = Target.y
            Spot.z = Target.z + 50 * math.sin(rads)
            local spotClear = true
            if FindMultiQSpot(Spot) == false then
                QSpot.x = Spot.x
                QSpot.y = Spot.y
                QSpot.z = Spot.z
                QSpot.single = 1
                spotFound = true
                break
            end
            Angle = Angle - 10
        end
        if spotFound ~= true then
            QSpot.x = Target.x
            QSpot.y = Target.y
            QSpot.z = Target.z
            QSpot.single = 0
        end
	--end
	return QSpot
end


KarthConfig = AutoCarry.Plugins:RegisterPlugin(Plugin(), "The Wonderful Karthus")
KarthConfig:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
KarthConfig:addParam("Harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("D"))
KarthConfig:addParam("Farm", "Farm", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("A"))
KarthConfig:addParam("Tear", "Stack Tear With Q", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("C"))
KarthConfig:addParam("Spam", "Auto Defile Close Enemies", SCRIPT_PARAM_ONOFF, true)
KarthConfig:addParam("Movement", "Move To Mouse", SCRIPT_PARAM_ONOFF, true)
KarthConfig:addParam("sep", "-- Farming --", SCRIPT_PARAM_INFO, "")
KarthConfig:addParam("FarmQ", "Farm: Last Hit With Q", SCRIPT_PARAM_ONOFF, true)