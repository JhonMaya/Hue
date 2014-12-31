<?php exit() ?>--by iuser99 108.162.246.215
--[[AUTH]]
--class 'Auth' --{
	function GetUser2()
	    return "vadash"
	end
	--_G.GetUser = GetUser2 --uncomment to overload global function GetUser with our realisation]]

	--overload check (CastSpell GetUser GetLoLPath addresses should be almost equal)
	local a1 = tonumber(string.sub(tostring(_G.CastSpell), 11), 16)
	local a2 = tonumber(string.sub(tostring(_G.GetUser), 11), 16)
	local a3 = tonumber(string.sub(tostring(_G.GetLoLPath), 11), 16)
	if math.abs(a2-a1) > 75000 and math.abs(a3-a2) > 75000 then  PrintAlert(">> Unauthorized User #2", 30, 255, 255, 255) return end

	local namez = {
		--> Default
	    ["iuser99"] = true,
	    ["Sida"] = true,
	    --> Paid Users
	    ["Raz"] = true,
	}

	if namez[_G.GetUser():lower()] == nil or namez[_G.GetUser():lower()] == false then PrintAlert(">> Unauthorized User #1", 30, 255, 255, 255)  return end	
--}

Menu.instance = Menu("uBrand", "uBrand")
	Menu.Get():addSubMenu("Combo Settings", "Combo")
		--> Combo
		Menu.Get().Combo:addParam("Active", "Active (Spacebar)", SCRIPT_PARAM_ONKEYDOWN, false, 32)
		Menu.Get().Combo:addSubMenu("Burst Settings", "Burst")
			--> Burst
			Menu.Get().Combo.Burst:addParam("OnKillable", "Perform on Killable Target", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().Combo:addSubMenu("Ultimate Settings", "Ultimate")
			--> Ultimate
			Menu.Get().Combo.Ultimate:addParam("surrounding", "# of grouped enemies", SCRIPT_PARAM_SLICE, 3, 0, 5, 0)
			Menu.Get().Combo.Ultimate:addParam("killable", "# of killable enemies", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
		Menu.Get().Combo:addSubMenu("Stun Settings", "Stun")
			--> Stun 
			Menu.Get().Combo.Stun:addParam("OnAway", "Perform walking away enemy", SCRIPT_PARAM_ONOFF, false)
		Menu.Get().Combo:addSubMenu("AOE Settings", "AOE")
			--> AOE
			Menu.Get().Combo.AOE:addParam("amount", "# of grouped", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
		Menu.Get():addSubMenu("Farm Settings", "Farm")
			--> Farm
			Menu.Get().Farm:addSubMenu("LaneClear", "LaneClear")
				--> Laneclear
				Menu.Get().Farm.LaneClear:addParam("Active", "Active (C)", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
				Menu.Get().Farm.LaneClear:addParam("Spread", "Spread ablaze", SCRIPT_PARAM_ONOFF, true)
				Menu.Get().Farm.LaneClear:addParam("UseW", "Use W", SCRIPT_PARAM_ONOFF, true)
				Menu.Get().Farm.LaneClear:addParam("UseE", "Use E", SCRIPT_PARAM_ONOFF, true)
			Menu.Get().Farm:addSubMenu("LastHit", "LastHit")
				--> LastHit
				Menu.Get().Farm.LastHit:addParam("Active", "Active (R)", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("R"))

local c = createClass(Script)
c:Register("Brand", DAMAGE_MAGIC, function() end)

local version = "0.0.1" 

local InterruptSpells = {
	["FiddleSticks"] =  {
        ["fearmonger_marker"] = { important = 1}
    },
    ["Galio"] =  {
        ["GalioIdolOfDurand"] = { important = 0}
    },
    ["Katarina"] =  {
        ["katarinarsound"] = { important = 0}
    },
    ["Nunu"] =  {
        ["AbsoluteZero"] = { important = 0}
    },
    ["Shen"] =  {
        ["ShenStandUnited"] = { important = 0}
    }
}

local SkillQ = Caster(_Q, 1000, SPELL_LINEAR_COL, 1603, 0.187, 110, true)
local SkillW = Caster(_W, 900, SPELL_CIRCLE, 900, 0.25, 100, true)
local SkillE = Caster(_E, 625, SPELL_TARGETED)
local SkillR = Caster(_R, 750, SPELL_TARGETED)


local combo = ComboLibrary()

local enemies = {}

class 'LaneClear' -- {

	LaneClear.instance = ""

	function LaneClear.Instance()
		if LaneClear.instance == "" then LaneClear.instance = LaneClear() end return LaneClear.instance 
	end 
	
	function LaneClear:__init()
		AddTickCallback(function() self:OnTick() end)
	end 

	function LaneClear:OnTick() 
		if Menu.Get().Farm.LaneClear.Active then
			c:GetLastHit():Enable(true)
			if SkillW:Ready() and Menu.Get().Farm.LaneClear.UseW then 
				self:CastW()
			end 

			local m = Calculation.GetNearest(myHero, SkillE.range, c:GetMinions()) 
			if m and Menu.Get().Farm.LaneClear.UseE then 
				Combo.Instance():SpreadAblaze(m)
			end 
		else 
			c:GetLastHit():Enable(false)
		end 
	end 

	function LaneClear:CastW()
		local minions = {}
		for _, minion in pairs(c:GetMinions()) do
			if ValidTarget(minion, SkillW.range) and SkillW:Ready() then
				table.insert(minions, minion)
			end
		end

		local minionClusters = {}

		local closeMinion = 300
		for _, minion in pairs(minions) do
			local foundCluster = false
			for i, mc in ipairs(minionClusters) do
				if GetDistance(mc, minion) < closeMinion then
					mc.x = ((mc.x * mc.count) + minion.x) / (mc.count + 1)
					mc.z = ((mc.z * mc.count) + minion.z) / (mc.count + 1)
					mc.count = mc.count + 1
					foundCluster = true
					break
				end
			end
	 
			if not foundCluster then
				local mc = {x=0, z=0, count=0}
				mc.x = minion.x
				mc.z = minion.z
				mc.count = 1
				table.insert(minionClusters, mc)
			end
		end

		if #minionClusters < 1 then return end

		local largestCluster = 0
		local largestClusterSize = 0
		for i, mc in ipairs(minionClusters) do
			if mc.count > largestClusterSize then
				largestCluster = i
				largestClusterSize = mc.count
			end
		end

		minionCluster = minionClusters[largestCluster]
		if minionCluster then
			CastSpell(_W, minionCluster.x, minionCluster.z)
		end

		minions = nil
		minionClusters = nil
	end 
-- }

class 'Plugin' -- {
	
	function OnTick() 
		c:OnTick() 
		Target = c:GetTarget()

		if Menu.Get().Combo.Active or Menu.Get().Farm.LaneClear.Active or Menu.Get().Farm.LastHit.Active then 
			OrbWalking.Enable(true)
		else 
			OrbWalking.Enable(false)
		end 

		if Menu.Get().Farm.LastHit.Active or Menu.Get().Farm.LaneClear.Active then
			c:GetLastHit():Enable(true)
		else 
			c:GetLastHit():Enable(false)
		end  

		if Target and Menu.Get().Combo.Active then
			if Menu.Get().Combo.Stun.OnAway and MovementPrediction.GetDirection(Target) == DIRECTION_AWAY and GetDistance(Target) < SkillW.range and DamageCalculation.CalculateRealDamage(Target) >= Target.health then 
				Combo.Instance():CastCombo(Target, COMBO_STUN) 
			elseif DamageCalculation.CalculateRealDamage(Target) >= Target.health and Menu.Get().Combo.Burst.OnKillable then 
				Combo.Instance():CastCombo(Target, COMBO_BURST)
			elseif SkillR:Ready() and (Calculation.CountSurrounding(Target, 300, GetEnemyHeroes()) >= Menu.Get().Combo.Ultimate.surrounding or Combo.Instance():GetKillable(Target) >= Menu.Get().Combo.Ultimate.killable) then 
				Combo.Instance():CastCombo(Target, COMBO_ULTIMATE) 
			elseif Calculation.CountSurrounding(myHero, 1000, GetEnemyHeroes()) > Menu.Get().Combo.AOE.amount then 
				Combo.Instance():CastCombo(Target, COMBO_AOE)
			else 
				Combo.Instance():CastCombo(Target, COMBO_BURST)
			end
		end 
	end 

	function OnLoad() 
		c:OnLoad()

		LaneClear.Instance()

		AdvancedCallback:bind('OnGainBuff', function(unit, buff) OnGainBuff(unit, buff) end)
		AdvancedCallback:bind('OnLoseBuff', function(unit, buff) OnLoseBuff(unit, buff) end)
		AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) OnUpdateBuff(unit, buff) end)	

		PrintChat("<font color='#400000'> Loaded >> iBrand - Set the World Ablaze (V: " .. version .. ") </font>")

		PrintAlert(">> uBrand Loaded (V:" .. version ..") <<", 30, 0, 255, 255)
   		PrintAlert(">> Thank you for donating! - iuser99 <<", 30, 0, 255, 255)

		combo:AddCasters({SkillW, SkillQ, SkillE, SkillR})
		combo:AddCustomCast(_R, function(Target) return (getDmg("R", Target, myHero) > Target.health) end)
	end 

	function OnProcessSpell(unit, spell)
		if unit and spell and InterruptSpells[unit.name] then 
			if InterruptSpells[unit.name] == spell.name then 
				Combo.Instance():CastInterrupt(unit)
			end 
		end 
	end 
-- }

class 'Combo' -- {
	
	COMBO_STUN = 0 
	COMBO_ULTIMATE = 1
	COMBO_AOE = 2
	COMBO_BURST = 3

	Combo.instance = ""

	function Combo.Instance()
		if Combo.instance == "" then Combo.instance = Combo() end return Combo.instance 
	end 

	function Combo:__init()
	end 

	function Combo:CastCombo(Target, comboType)
		if comboType == COMBO_STUN then 
			--PrintChat("STUN")
			if ValidTarget(Target, SkillQ.range) then 
				if SkillW:Ready() then 
					SkillW:Cast(Target)
				end 
				if not HasAblaze(Target) then 
					self:SpreadAblaze(Target)
				end 
				if SkillQ:Ready() then 
					SkillQ:Cast(Target)
				end 
			end 
		elseif comboType == COMBO_ULTIMATE then 
			--PrintChat("ULTIMATE")
			if ValidTarget(Target, SkillR.range) then 
				if not HasAblaze(Target) then 
					if SkillQ:Ready() then 
						SkillQ:Cast(Target)
					end 
					if SkillW:Ready() then 
						SkillW:Cast(Target)
					end  
					self:SpreadAblaze(Target)
				end 
				if HasAblaze(Target) or getDmg("R", Target, myHero) > Target.health then 
					if SkillR:Ready() then 
						CastSpell(_R, Target) -- override iFoundation 
					end 
				end 
			end 
		elseif comboType == COMBO_AOE then 
			--PrintChat("AOE")
			if ValidTarget(Target, SkillQ.range) then 
				if not HasAblaze(Target) then 
					if SkillQ:Ready() then 
						SkillQ:Cast(Target)
					elseif SkillW:Ready() then 
						SkillW:Cast(Target) 
					end 
					if not HasAblaze(Target) then 
						self:SpreadAblaze(Target)
					end 
				end 
				if HasAblaze(Target) then 
					self:SpreadAblaze(Target)
					if SkillW:Ready() then 
						SkillW:Cast(Target)
					end 
					local best = self:GetBestStunTarget(SkillQ.range) 
					if best then 
						if SkillQ:Ready() then 
							SkillQ:Cast(best)
						end 
					end 
					if HasAblaze(Target) and SkillE:Ready() then 
						SkillE:Cast(Target)
					end 
				end 
			end 
		elseif comboType == COMBO_BURST then 
			--PrintChat("BURST")
			combo:CastCombo(Target)
			self:SpreadAblaze(Target)
		end 
	end 

	function Combo:SpreadAblaze(Target)
		if not SkillE:Ready() then return false end 
		for _, minion in pairs(c:GetMinions()) do 
			if minion and not minion.dead then 
				if HasAblaze(minion) then 
					if GetDistance(minion, Target) <= 300 then 
						SkillE:Cast(minion) 
						if HasAblaze(Target) then
							return true 
						end 
					end 
				end 
			end 
		end 
		for _, player in pairs(GetEnemyHeroes()) do 
			if player and not player.dead then 
				if HasAblaze(player) then 
					if GetDistance(player, Target) <= 300 and ValidTarget(player, SkillE.range) then 
						SkillE:Cast(player) 
						if HasAblaze(Target) then 
							return true
						end 
					end 
				end 
			end 
		end 
	end 

	function Combo:GetKillable(Target) 
		local count = 0
		for _, player in pairs(GetEnemyHeroes()) do 
			if player and not player.dead then 
				if GetDistance(player, Target) <= 500 and getDmg("R", player, myHero, 3) > player.health then 
					count = count + 1
				end 
			end 
		end 
		return count
	end 

	function Combo:GetBestStunTarget(range) 
		local enemies = GetEnemyHeroes() 
		for _, enemy in pairs(enemies) do 
			if enemy.dead or not ValidTarget(enemy, range) then
				table.remove(enemies, _)
			end 
		end 
		table.sort(enemies, function(a, b)
				return a.totalDamage > b.totalDamage and a.health < b.health 		
			end)
		for _, enemy in pairs(enemies) do 
			if enemy and not enemy.dead and ValidTarget(enemy, range) then 
				return enemy 
			end 
		end 
	end 

	function Combo:CastInterrupt(Target)
		if not Target or Target.dead then return end 
		if GetDistance(Target) <= SkillQ.range then 
			if not HasAblaze(Target) then 
				self:SpreadAblaze(Target)
				if not HasAblaze(Target) then 
					if SkillW:Ready() then 
						SkillW:Cast(Target)
					elseif SkillE:Ready() then 
						SkillE:Cast(Target)
					end 
				end 
			end
			if SkillQ:Ready() then 
				SkillQ:Cast(Target)
			end 
		end 
	end 
-- }

function OnGainBuff(unit, buff) 
	if unit == nil or buff == nil then return end 
	if unit.team ~= myHero.team then 
		if buff.name == "brandablaze" then 
			table.insert(enemies, unit) 
			PrintFloatText(unit, 5, "(+) Ablaze")
		end 
	end 
end 

function OnLoseBuff(unit, buff) 
	if unit == nil or buff == nil then return end 
	if unit.team ~= myHero.team then 
		if buff.name == "brandablaze" then 
			for i=1, #enemies do 
				if enemies[i] == unit then 
					table.remove(enemies, i)
				end 
			end 
			PrintFloatText(unit, 21, "(-) Ablaze")
		end 
	end 
end 

function OnUpdateBuff(unit, buff) 
	if unit == nil or buff == nil then return end 
	if unit.team ~= myHero.team then 
		if buff.name == "brandablaze" then 
			table.insert(enemies, unit) 
			PrintFloatText(unit, 5, "(+) Ablaze Spread")
		end 
	end 
end 

function HasAblaze(Target)
	for i=1, #enemies, 1 do 
		if enemies[i].networkID == Target.networkID then 
			return true 
		end 
	end 
end 
