<?php exit() ?>--by iuser99 173.55.184.160
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
	    ["sida"] = true,
	    ["16hex16"] = true,
	    ["light"] = true,
	    ["ires"] = true,
	    ["3rasus"] = true,
	    --> Paid Users
	    ["raz"] = true,
	    ["blm95"] = true,
	    ["xxgowxx "] = true,
	    ["kain"] = true,
	    ["dryice"] = true,
	    ["mewkyy"] = true,
	    ["paradoxel"] = true,
	    ["pyrophenix"] = true,
	}

	if namez[_G.GetUser():lower()] == nil or namez[_G.GetUser():lower()] == false then PrintAlert(">> Unauthorized User #1", 30, 255, 255, 255)  return end	
--}

Menu.instance = Menu("uLissandra", "uLissandra")
Menu.Get():addParam("title", "				uLissandra",  SCRIPT_PARAM_INFO, "")
	--> Combo
	Menu.Get():addSubMenu("Combo", "Combo")
		Menu.Get().Combo:addParam("Active", "TeamFight (Spacebar)", SCRIPT_PARAM_ONKEYDOWN, false, 32)
		Menu.Get().Combo:addParam("DOT", "Use Ignite", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().Combo:addParam("UseQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().Combo:addParam("UseW", "Use W", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().Combo:addParam("UseE", "Use E", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().Combo:addParam("UseR", "Use R", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().Combo:addParam("UseROpen", "Use R as Opener", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().Combo:addSubMenu("Automatic Ultimate", "AutoUltimate") 
			--> AutoUlt
			Menu.Get().Combo.AutoUltimate:addParam("amount", "Amount", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
	--> Harass
	Menu.Get():addSubMenu("Harass", "Harass")
		Menu.Get().Harass:addParam("Active", "Harass (T)", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
		Menu.Get().Harass:addParam("UseQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().Harass:addParam("UseW", "Use W", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().Harass:addParam("UseE", "Use E", SCRIPT_PARAM_ONOFF, true)
	--> Farm
	Menu.Get():addSubMenu("Farm", "Farm")
		Menu.Get().Farm:addSubMenu("Lane Clear", "LaneClear")
			--> LaneClear
			Menu.Get().Farm.LaneClear:addParam("Active", "LaneClear (C)", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
			Menu.Get().Farm.LaneClear:addParam("UseQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
			Menu.Get().Farm.LaneClear:addParam("UseW", "Use W", SCRIPT_PARAM_ONOFF, true)
			Menu.Get().Farm.LaneClear:addParam("MEC", "Use MEC", SCRIPT_PARAM_ONOFF, true)
			Menu.Get().Farm.LaneClear:addParam("UseE", "Use E", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().Farm:addParam("Active", "LastHit (R)", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("R"))
	--> KillSteal
	Menu.Get():addSubMenu("KillSteal", "KillSteal") 
		Menu.Get().KillSteal:addParam("Active", "KillSteal (Z)", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte("Z"))
		Menu.Get().KillSteal:addParam("UseQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().KillSteal:addParam("UseW", "Use W", SCRIPT_PARAM_ONOFF, true)
		Menu.Get().KillSteal:addParam("UseE", "Use E", SCRIPT_PARAM_ONOFF, true)
	Menu.Get():addSubMenu("Interrupt", "Interrupt")	
		Menu.Get().Interrupt:addParam("Interrupt", "Use Interrupt", SCRIPT_PARAM_ONOFF, true)

Menu.Get():permaShow("title")
Menu.Get("Combo"):permaShow("Active")
Menu.Get("Farm"):permaShow("Active")
Menu.Get("Farm").LaneClear:permaShow("Active")
Menu.Get("KillSteal"):permaShow("Active")

local c = createClass(Script)
c:Register("Lissandra", DAMAGE_MAGIC, function() end)

local version = "0.0.1"

_G.SkillQ = Caster(_Q, 700, SPELL_LINEAR, 2250, 0.250, 100, true) 
_G.SkillW = Caster(_W, 450, SPELL_SELF)
_G.SkillE = Caster(_E, 1025, SPELL_LINEAR, 853, 0.250, 100, true) 
_G.SkillR = Caster(_R, 700, SPELL_TARGETED) 

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

local eCast = false
local eClaw = nil 

class 'LaneClear' -- {
	
	LaneClear.instance = ""

	function LaneClear.Instance()
		if LaneClear.instance == "" then LaneClear.instance = LaneClear() end return LaneClear.instance 
	end 
	
	function LaneClear:__init()
		AddTickCallback(function() self:OnTick() end)
	end 

	function LaneClear:OnTick()
		if Menu.Get("Farm").LaneClear.Active then 
			if Menu.Get("Farm").LaneClear.UseE then 
				if SkillE:Ready() and not eClaw and eCast == false then 
					local p = Calculation.MinionMEC(c:GetMinions(), SkillE.range, SkillE.width)
					if p then 
						CastSpell(_E, p.x, p.z)
					end 
				end
			end 
			if Menu.Get("Farm").LaneClear.UseQ then 
				if SkillQ:Ready() then 
					local nearest = Calculation.GetNearest(myHero, SkillQ.range, c:GetMinions())
					if nearest then 
						SkillQ:Cast(nearest)
					end 
				end 
			end 
			if Menu.Get("Farm").LaneClear.UseW then 
				if SkillW:Ready() then 
					if Menu.Get("Farm").LaneClear.MEC then 
						local p = Calculation.MinionMEC(c:GetMinions(), SkillW.range, SkillW.range)
						if p then 
							if GetDistance(p) < 200 then 
								CastSpell(_W)
							end 
						end 
					else
					  	local nearest = Calculation.GetNearest(myHero, SkillQ.range, c:GetMinions())
					  	if nearest then 
					  		if GetDistance(nearest) < SkillW.range then 
					  			CastSpell(_W)
					  		end 
					  	end 
					end  
				end 
			end 
		end 
	end 
-- }

class 'KillSteal' -- {

	KillSteal.instance = ""

	function KillSteal.Instance()
		if KillSteal.instance == "" then KillSteal.instance = KillSteal() end return KillSteal.instance 
	end 
	
	function KillSteal:__init()
		AddTickCallback(function() self:OnTick() end)
	end 

	function KillSteal:OnTick()
		if Menu.Get("KillSteal").Active then 
			if SkillQ:Ready() and Menu.Get("KillSteal").UseQ then 
				local t = self:GetKillable(SkillQ)
				if t then 
					SkillQ:Cast(t)
				end 
			elseif SkillW:Ready() and Menu.Get("KillSteal").UseW then 
				local t = self:GetKillable(SkillW)
				if t then 
					SkillW:Cast(Target)
				end 
			elseif SkillE:Ready() and eCast == false and Menu.Get("KillSteal").UseE then 
				local t = self:GetKillable(SkillE)
				if t then
					SkillE:Cast(Target)
				end 
			elseif SkillR:Ready() and Menu.Get("KillSteal").UseR then 
				local t = self:GetKillable(SkillR)
				if t then
					SkillR:Cast(Target)
				end 
			end 
		end 
	end 

	function KillSteal:GetKillable(spell)
		for _, e in pairs(GetEnemyHeroes()) do 
			if e and e.valid and ValidTarget(e, spell.range) then
				if getDmg(SpellStrings[spell.spell], e, myHero) - 50 > e.health then 
					return e 
				end 
			end 
		end 
		return nil 
	end 

-- }

function OnTick()
	c:OnTick()
	Target = c:GetTarget()
	if Menu.Get().Combo.Active or Menu.Get().Farm.LaneClear.Active or Menu.Get().Farm.Active then 
		OrbWalking.Enable(true)
	else 
		OrbWalking.Enable(false)
	end 
	if Menu.Get().Farm.Active or Menu.Get().Farm.LaneClear.Active then
		c:GetLastHit():Enable(true)
	else 
		c:GetLastHit():Enable(false)
	end  

	if Target then 
		if DamageCalculation.Instance().ignite ~= nil and myHero:CanUseSpell(DamageCalculation.Instance().ignite) == READY and Menu.Get("Combo").DOT and getDmg("IGNITE", Target, myHero) > Target.health then 
				CastSpell(DamageCalculation.Instance().ignite, Target) 
		elseif SkillR:Ready() and Calculation.CountSurrounding(Target, 500, GetEnemyHeroes()) >= Menu.Get("Combo").AutoUltimate.amount then 
			SkillR:Cast(Target) 
		elseif Menu.Get("Combo").Active then 
			Combo.Instance():Perform(Target, COMBO_KILL)
		elseif Menu.Get("Harass").Active then 
			Combo.Instance():Perform(Target, COMBO_HARASS)
		end 
	end 
end 

function OnLoad()
	c:OnLoad()
	LaneClear.Instance()
	KillSteal.Instance()

	PrintAlert(">> uLissandra Loaded (V:" .. version ..")", 30, 0, 255, 255)
    PrintAlert(">> Thank you for donating! - iuser99 <<", 30, 0, 255, 255)
end 

function OnProcessSpell(unit, spell)
	if unit and spell and InterruptSpells[unit.name] then 
		if InterruptSpells[unit.name] == spell.name then 
			if SkillR:Ready() and Menu.Get("Interrupt").Enabled then 
				SkillR:Cast(unit)
			end 
		end 
	end 
end 

function OnCreateObj(object)
	if object.name:find("Lissandra_E_Missile.troy") then
		eClaw = object
	elseif object.name:find("Lissandra_E_Cast.troy") then 
		eCast = true 
	end 
end 

function OnDeleteObj(object)
	if object.name:find("Lissandra_E_Missile.troy") then
		eClaw = nil
	elseif object.name:find("Lissandra_E_Cast.troy") then 
		eCast = false
	end 
end 

class 'Combo' -- {

	COMBO_KILL = 0
	COMBO_HARASS = 1 
	COMBO_ULTIMATE = 2 
	
	Combo.instance = ""

	function Combo.Instance()
		if Combo.instance == "" then Combo.instance = Combo() end return Combo.instance 
	end 

	function Combo:__init()
	end 

	function Combo:Perform(Target, mode)
		if not Target then return end 
		if mode == COMBO_KILL then 
			if Menu.Get("Combo").UseE then 
				if not eClaw and eCast == false then 
					if SkillE:Ready() then 
						SkillE:Cast(Target)
					end 
				elseif eClaw and eCast == true then 
					if GetDistance(eClaw, Target) <= 80 then 
						CastSpell(_E)
					end  
				end
			end
			if Menu.Get("Combo").UseR and Menu.Get("Combo").UseROpen and SkillR:Ready() then 
				SkillR:Cast(Target)
			end 
			if Menu.Get("Combo").UseW and SkillW:Ready() and ValidTarget(Target, SkillW.range) then 
				SkillW:Cast(Target) 
			end
			if Menu.Get("Combo").UseQ and SkillQ:Ready() and ValidTarget(Target, SkillQ.range) then 
				SkillQ:Cast(Target)
			end 
			if Menu.Get("Combo").UseR and SkillR:Ready() and getDmg("R", Target, myHero) > Target.health then 
				SkillR:Cast(Target)
			end 
		elseif mode == COMBO_HARASS then 
			if Menu.Get("Harass").UseQ and SkillQ:Ready() then 
				SkillQ:Cast(Target)
			end 
			if Menu.Get("Harass").UseW and SkillW:Ready() and ValidTarget(Target, SkillW.range) then 
				SkillW:Cast(Target) 
			end
			if Menu.Get("Harass").UseE and SkillE:Ready() then 
				if not eClaw and eCast == false then 
					SkillE:Cast(Target) 
				end 
			end 
		end
	end
-- }