<?php exit() ?>--by Okkiru 179.223.24.153
if myHero.charName ~= "Ashe" then return end

require "Prodiction"
require "VPrediction"

-- Constants
local wRange = 1200
local rRange = 2000
local xRange = 1200

local QAble, WAble, RAble = false, false, false
 
local Prodict = ProdictManager.GetInstance()
local ProdictW, ProdictWCol, ProdictWFastCol
local ProdictR

local ProdictWCol = nil
local ProdictWFastCol = nil

local lastAttack

local wDmg

-- PROdiction
function PluginOnLoad()
        AutoCarry.SkillsCrosshair.range = xRange
        Menu()
        RebornCheck()
       
        ProdictW = Prodict:AddProdictionObject(_W, wRange, 2000, 0.120, 85)
        ProdictR = Prodict:AddProdictionObject(_R, rRange, 1600, 0.5, 0)
end
 
-- Drawings
function PluginOnDraw()
        if not myHero.dead then
                if WAble and AutoCarry.PluginMenu.drawW then
                        DrawCircle(myHero.x, myHero.y, myHero.z, AutoCarry.PluginMenu.extra.wRanger, 0x6600CC)
                end
                if RAble and AutoCarry.PluginMenu.drawR then
                        DrawCircle(myHero.x, myHero.y, myHero.z, AutoCarry.PluginMenu.extra.rRanger, 0x990000)
                end
        end
end
 
 -- KS
function KS()
    for i = 1, heroManager.iCount do
        local Enemy = heroManager:getHero(i)
        if WAble then wDmg = getDmg("W",Enemy,myHero) else wDmg = 0 end
        if RAble and ValidTarget(Enemy, 1000, true) and Enemy.health < getDmg("R",Enemy,myHero) + getDmg("AD",Enemy,myHero) + wDmg + 50 then
            CastRKS(Enemy)
            Volley()
        end
    end
end
 
local frostOn = false
local HKR = string.byte("A")

function Menu()
AutoCarry.PluginMenu:addSubMenu("-- [Range & Prediction Settings] --", "extra")
AutoCarry.PluginMenu.extra:addParam("useRanger", "Use - Custom Ranges", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu.extra:addParam("xRanger", "Range - Skill Crosshair", SCRIPT_PARAM_SLICE, 1200, 600, 2000, 0)
AutoCarry.PluginMenu.extra:addParam("wRanger", "Range - Volley", SCRIPT_PARAM_SLICE, 1200, 600, 1200, 0)
AutoCarry.PluginMenu.extra:addParam("rRanger", "Range - Enchanted Crystal Arrow", SCRIPT_PARAM_SLICE, 2000, 325, 2000, 0)
AutoCarry.PluginMenu.extra:addParam("UseVP", "Use - VPrediction", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu.extra:addParam("HitChance", "VP - Hitchance", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
AutoCarry.PluginMenu.extra:addParam("HitChanceInfo", "Info - Hitchance", SCRIPT_PARAM_ONOFF, false)
AutoCarry.PluginMenu:addParam("sep", "-- Misc Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("AutoQ", "Use - Frost Shot", SCRIPT_PARAM_ONOFF, false)
AutoCarry.PluginMenu:addParam("MMana", "Use - Muramana", SCRIPT_PARAM_ONOFF, false) 
AutoCarry.PluginMenu:addParam("Qexploit", "Use - Q Exploit (Aways)", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("Qexploit2", "Use - Q Exploit (Enemy Only)", SCRIPT_PARAM_ONOFF, false)  
AutoCarry.PluginMenu:addParam("ManaCheck", "Deactivate - Q if low on mana", SCRIPT_PARAM_ONOFF, false)
AutoCarry.PluginMenu:addParam("sep1", "-- Ultimate Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("useR", "Use - Enchanted Crystal Arrow", SCRIPT_PARAM_ONKEYDOWN, false, HKR)
AutoCarry.PluginMenu:addParam("KS", "KS - Enchanted Crystal Arrow", SCRIPT_PARAM_ONOFF, false)
AutoCarry.PluginMenu:addParam("sep2", "-- Autocarry Options --", SCRIPT_PARAM_INFO, "") 
AutoCarry.PluginMenu:addParam("useW", "Use - Volley", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("sep3", "-- Mixed Mode Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("useW2", "Use - Volley", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("sep4", "-- Drawing Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("drawW", "Draw - Volley", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("drawR", "Draw - Enchanted Crystal Arrow", SCRIPT_PARAM_ONOFF, true)
end

function PluginOnTick()
        Checks()
			if Target then
            	if Target and (AutoCarry.MainMenu.AutoCarry) then
				Volley()
            	end
            	if Target and (AutoCarry.MainMenu.MixedMode) then
				Volley2()
            	end
			end
			if AutoCarry.PluginMenu.KS and RAble then KS()
				if AutoCarry.PluginMenu.Qexploit2 then
				Exploit2()
				AutoCarry.PluginMenu.AutoQ = false
				AutoCarry.PluginMenu.Qexploit = false
				end
			end
		if AutoCarry.PluginMenu.Qexploit then
			Exploit()
			AutoCarry.PluginMenu.AutoQ = false
			AutoCarry.PluginMenu.Qexploit2 = false
		end
		if AutoCarry.PluginMenu.useR then
			 EnchantedArrow()
			 AutoCarry.SkillsCrosshair.range = AutoCarry.PluginMenu.extra.rRanger else AutoCarry.SkillsCrosshair.range = AutoCarry.PluginMenu.extra.xRanger
        end
		--
	if not AutoCarry.PluginMenu.extra.useRanger then
	AutoCarry.PluginMenu.extra.xRanger = 1200
	AutoCarry.PluginMenu.extra.wRanger = 1200
	AutoCarry.PluginMenu.extra.rRanger = 2000
	end
	if AutoCarry.PluginMenu.extra.HitChanceInfo then
		PrintChat ("<font color='#FFFFFF'>Hitchance 0: No waypoints found for the target, returning target current position</font>")
		PrintChat ("<font color='#FFFFFF'>Hitchance 1: Low hitchance to hit the target</font>")
		PrintChat ("<font color='#FFFFFF'>Hitchance 2: High hitchance to hit the target</font>")
		PrintChat ("<font color='#FFFFFF'>Hitchance 3: Target too slowed or/and too close(~100% hit chance)</font>")
		PrintChat ("<font color='#FFFFFF'>Hitchance 4: Target inmmobile(~100% hit chace)</font>")
		PrintChat ("<font color='#FFFFFF'>Hitchance 5: Target dashing(~100% hit chance)</font>")
		AutoCarry.PluginMenu.ranges.HitChanceInfo = false
	end
end

function Checks()
        QAble = (myHero:CanUseSpell(_Q) == READY)
        WAble = (myHero:CanUseSpell(_W) == READY)
        EAble = (myHero:CanUseSpell(_E) == READY)
        RAble = (myHero:CanUseSpell(_R) == READY)
        Target = AutoCarry.GetAttackTarget()
        if myHero.dead then frostOn = false end
end

function CustomAttackEnemy(enemy)
        if enemy.dead or not enemy.valid or not AutoCarry.CanAttack then return end

        if (AutoCarry.PluginMenu.AutoQ or AutoCarry.PluginMenu.Qexploit2) then
                if ValidTarget(enemy) and enemy.type == "obj_AI_Hero" and not frostOn and ((AutoCarry.PluginMenu.ManaCheck and myHero.mana > 100) or not AutoCarry.PluginMenu.ManaCheck) then
                        CastSpell(_Q)
                elseif ValidTarget(enemy) and enemy.type ~= "obj_AI_Hero" and frostOn then
                        CastSpell(_Q)
                end
        end

        if AutoCarry.PluginMenu.MMana then
                if ValidTarget(enemy) and enemy.type == "obj_AI_Hero" and ((AutoCarry.PluginMenu.ManaCheck and myHero.mana > 100) or not AutoCarry.PluginMenu.ManaCheck) and not MuramanaIsActive() then
                        MuramanaOn()
                elseif ValidTarget(enemy) and enemy.type ~= "obj_AI_Hero" and MuramanaIsActive() then
                        MuramanaOff()
                end
        end

        myHero:Attack(enemy)
        AutoCarry.shotFired = true
end

 
function PluginOnProcessSpell(unit, spell)
	if unit.isMe and spell.name == myHero:GetSpellData(_Q).name then
	if not frostOn then frostOn = true else frostOn = false end
	end
end

function Volley()
        if AutoCarry.PluginMenu.extra.useVP then
        for i, target in pairs(GetEnemyHeroes()) do
        CastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, 0.120, 85, wRange, 2000, myHero)
        if IsSACReborn and WAble and AutoCarry.PluginMenu.useW and not AutoCarry.Orbwalker:IsShooting() and HitChance >= AutoCarry.PluginMenu.extra.HitChance and GetDistance(CastPosition) < AutoCarry.PluginMenu.extra.wRanger then CastSpell(_W, CastPosition.x, CastPosition.z)
        elseif WAble and AutoCarry.PluginMenu.useW and HitChance >= AutoCarry.PluginMenu.extra.HitChance and GetDistance(CastPosition) < AutoCarry.PluginMenu.extra.wRanger then CastSpell(_W, CastPosition.x, CastPosition.z) end
        end
        else
        if IsSACReborn and WAble and AutoCarry.PluginMenu.useW and not AutoCarry.Orbwalker:IsShooting() then ProdictW:GetPredictionCallBack(Target, CastW)
        elseif WAble and AutoCarry.PluginMenu.useW then ProdictW:GetPredictionCallBack(Target, CastW) end
        end
end    
 
function Volley2()
        if AutoCarry.PluginMenu.extra.useVP then
        for i, target in pairs(GetEnemyHeroes()) do
        CastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, 0.120, 85, wRange, 2000, myHero)
        if IsSACReborn and WAble and AutoCarry.PluginMenu.useW2 and not AutoCarry.Orbwalker:IsShooting() and HitChance >= AutoCarry.PluginMenu.extra.HitChance and GetDistance(CastPosition) < AutoCarry.PluginMenu.extra.wRanger then CastSpell(_W, CastPosition.x, CastPosition.z)
        elseif WAble and AutoCarry.PluginMenu.useW2 and HitChance >= AutoCarry.PluginMenu.extra.HitChance and GetDistance(CastPosition) < AutoCarry.PluginMenu.extra.wRanger then CastSpell(_W, CastPosition.x, CastPosition.z) end
        end
        else
        if IsSACReborn and WAble and AutoCarry.PluginMenu.useW2 and not AutoCarry.Orbwalker:IsShooting() then ProdictW:GetPredictionCallBack(Target, CastW)
        elseif WAble and AutoCarry.PluginMenu.useW2 then ProdictW:GetPredictionCallBack(Target, CastW) end
        end
end 

function EnchantedArrow()
        if AutoCarry.PluginMenu.ranges.useVP then
          for i, target in pairs(GetEnemyHeroes()) do
          CastPosition,  HitChance,  Position = VP:GetLineCastPosition(Target, 0.5, 0, rRange, 1600, myHero)
          if Target and RAble and HitChance >= AutoCarry.PluginMenu.ranges.HitChance and GetDistance(CastPosition) < AutoCarry.PluginMenu.ranges.rRanger then CastSpell(_R, CastPosition.x, CastPosition.z) end
          end
        elseif Target and RAble then ProdictR:GetPredictionCallBack(Target, CastR) end
end   
 
function GetHitBoxRadius(target)
        return GetDistance(target, target.minBBox)
end
 
function CastW(unit, pos, spell)
        if GetDistance(pos) - getHitBoxRadius(unit)/2 < AutoCarry.PluginMenu.extra.wRanger and AutoCarry.PluginMenu.extra.ColSwap then
                local willCollide = ProdictWFastCol:GetMinionCollision(pos, myHero)
                if not willCollide then CastSpell(_W, pos.x, pos.z) end
        elseif GetDistance(pos) - getHitBoxRadius(unit)/2 < AutoCarry.PluginMenu.extra.wRanger then
                local willCollide = ProdictWCol:GetMinionCollision(pos, myHero)
                if not willCollide then CastSpell(_W, pos.x, pos.z) end
        end
end

function CastR(unit, pos, spell)       
        if GetDistance(pos) - getHitBoxRadius(unit)/2 < AutoCarry.PluginMenu.extra.rRanger then
          CastSpell(_R, pos.x, pos.z)
        end
end

function CastRKS(Unit)
    if GetDistance(Unit) - GetHitBoxRadius(Unit)*0.5 < rRange and ValidTarget(Unit) then
        RPos = ProdictR:GetPrediction(Unit)
        CastSpell(_R, RPos.x, RPos.z)
    end
end

function Exploit()
	if not frostOn then
	CastSpell(_Q)
	end
	if IsSACReborn and frostOn and AutoCarry.Orbwalker:IsShooting() then
	CastSpell(_Q)
	elseif not IsSACReborn and frostOn and AutoCarry.CurrentlyShooting then
	CastSpell(_Q)
	end
end

function Exploit2()
	if GetDistance(Target) <= 650 and not frostOn then
	CastSpell(_Q)
	end
	if IsSACReborn and frostOn and AutoCarry.Orbwalker:IsShooting() then
	CastSpell(_Q)
	elseif not IsSACReborn and frostOn and AutoCarry.CurrentlyShooting then
	CastSpell(_Q)
	end
end

function RebornCheck()
	if AutoCarry.Skills then IsSACReborn = true else IsSACReborn = false end
	if AutoCarry.PluginMenu.extra.ColSwap then
		require "FastCollision"
		ProdictWFastCol = FastCol(ProdictW)
		PrintChat("<font color='#FFFFFF'>>> Ashe: Fast Collision Loaded!</font>")
	else
		require "Collision"
		ProdictWCol = Collision(wRange, 2000, 0.120, 85)
		PrintChat("<font color='#FFFFFF'>>> Ashe: Normal Collision Loaded!</font>") end
end