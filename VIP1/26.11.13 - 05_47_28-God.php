<?php exit() ?>--by God 66.25.28.149
------#################################################################################------  ------###########################     Ashe won't miss!     ############################------ ------###########################         by Toy           ############################------ ------#################################################################################------

--> Version: 1.3.2

--> Features:
--> Prodictions in every skill, also taking their hitboxes in consideration.
--> Cast options for W in both, autocarry and mixed mode (Works separately).
--> Auto-aim Enchanted Crystal Arrow activated with a separated Hotkey (Default is A, can be changed on the menu, don't set it to R if you have Smartcast enabled for R) so you can use it when you think it's better, and it will still aim for you.
--> KS with Enchanted Crystal Arrow, will use Ultimate if the enemy is killable, as long as the target is within 1200 range (can be turned on/off).
--> Drawing option for W.
--> Options to use Muramana.
--> Option to use Frost Shot when attacking an enemy champion, and deactivate when attacking a non-champion unit.
--> Option to not use Frost Shot if way too low on mana (waaaay too low on mana -maybe not early game).
--> Option to enable FrostShot Exploit, won't use mana to slow targets. (Disable "Use - FrostShot", it's not necessary while using this, however enabling the exploit will auto-replace the way of using Q to not ever waste mana again, but just in case you can turn off the "Use - Forst Shot" too).
--> Honestly, the "option to use Frost Shot while attacking an enemy champion" and "option to not use Frost Shot if mana is low" are completly useless now, because using the exploit is waaay better, but I'll leave them there just in case someone wanna be "politically correct".
--> Option to use frost shot exploit to slow EVERYTHING, and won't use mana at all.
--> Option to only use the frost shot exploit on enemy champions, just in case your team goes "wtf ashe is using Q on minions".

if myHero.charName ~= "Ashe" then return end

require "Collision"
require "Prodiction"

-- Constants
local wRange = 1200
local rRange = 1200

local QAble, WAble, RAble = false, false, false
 
local Prodict = ProdictManager.GetInstance()
local ProdictW, ProdictWCol
local ProdictR

local lastAttack

-- PROdiction
function PluginOnLoad()
        AutoCarry.SkillsCrosshair.range = 1200
        Menu()
       
        ProdictW = Prodict:AddProdictionObject(_W, wRange, 2000, 0.120, 85)
        ProdictWCol = Collision(wRange, 2000, 0.120, 85)
        ProdictR = Prodict:AddProdictionObject(_R, rRange, 1600, 0.5, 0)
end
 
-- Drawings
function PluginOnDraw()
        if not myHero.dead then
                if WAble and AutoCarry.PluginMenu.drawW then
                        DrawCircle(myHero.x, myHero.y, myHero.z, wRange, 0xFFFFFF)
                end
        end
end
 
 -- KS
 function KS()
	for i, enemy in ipairs(GetEnemyHeroes()) do
		local rDmg = getDmg("R", enemy, myHero)
		if Target and not Target.dead and Target.health < rDmg and GetDistance(enemy) < rRange then
			ProdictR:GetPredictionCallBack(Target, CastR)
		end
	end
end
 
local frostOn = false
local HKR = string.byte("A")

function Menu()
AutoCarry.PluginMenu:addParam("sep", "-- Misc Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("AutoQ", "Use - Frost Shot", SCRIPT_PARAM_ONOFF, false)
AutoCarry.PluginMenu:addParam("MMana", "Use - Muramana", SCRIPT_PARAM_ONOFF, false) 
AutoCarry.PluginMenu:addParam("Qexploit", "Use - Q Exploit (Aways)", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("Qexploit2", "Use - Q Exploit (Enemy Near)", SCRIPT_PARAM_ONOFF, false)  
AutoCarry.PluginMenu:addParam("ManaCheck", "Deactivate - Q if low on mana", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("sep1", "-- Ultimate Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("useR", "Use - Enchanted Crystal Arrow", SCRIPT_PARAM_ONKEYDOWN, false, HKR)
AutoCarry.PluginMenu:addParam("KS", "KS - Enchanted Crystal Arrow", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("sep2", "-- Autocarry Options --", SCRIPT_PARAM_INFO, "") 
AutoCarry.PluginMenu:addParam("useW", "Use - Volley", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("sep3", "-- Mixed Mode Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("useW2", "Use - Volley", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("sep4", "-- Drawing Options --", SCRIPT_PARAM_INFO, "")
AutoCarry.PluginMenu:addParam("drawW", "Draw - Volley", SCRIPT_PARAM_ONOFF, true)
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
								if RAble and Target and AutoCarry.PluginMenu.useR then
								  ProdictR:GetPredictionCallBack(Target, CastR)
                end
								if AutoCarry.PluginMenu.KS and RAble then KS()
								end
				if AutoCarry.PluginMenu.Qexploit2 then
				Exploit2()
				AutoCarry.PluginMenu.AutoQ = true
				AutoCarry.PluginMenu.Qexploit = false
		end
				end
		if AutoCarry.PluginMenu.Qexploit then
			Exploit()
			AutoCarry.PluginMenu.AutoQ = false
			AutoCarry.PluginMenu.Qexploit2 = false
		end
end

function Checks()
        QAble = (myHero:CanUseSpell(_Q) == READY)
        WAble = (myHero:CanUseSpell(_W) == READY)
        EAble = (myHero:CanUseSpell(_E) == READY)
        RAble = (myHero:CanUseSpell(_R) == READY)
        Target = AutoCarry.GetAttackTarget()
end

function CustomAttackEnemy(enemy)
        if enemy.dead or not enemy.valid or not AutoCarry.CanAttack then return end

        if AutoCarry.PluginMenu.AutoQ or AutoCarry.PluginMenu.Qexploit2 then
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

 
function PluginOnCreateObj(obj)
        if GetDistance(obj) < 100 and obj.name:lower():find("icesparkle") then
                frostOn = true
        end
end
 
function PluginOnDeleteObj(obj)
        if GetDistance(obj) < 100 and obj.name:lower():find("icesparkle") then
                frostOn = false
        end
end

function Volley()
        if WAble and AutoCarry.PluginMenu.useW then ProdictW:GetPredictionCallBack(Target, CastW)
        end
end    
 
function Volley2()
        if WAble and AutoCarry.PluginMenu.useW2 then ProdictW:GetPredictionCallBack(Target, CastW)
        end
end
 
local function getHitBoxRadius(target)
        return GetDistance(target, target.minBBox)
end
 
function CastW(unit, pos, spell)
        if GetDistance(pos) - getHitBoxRadius(unit)/2 < wRange then
                local willCollide = ProdictWCol:GetMinionCollision(pos, myHero)
                if not willCollide then CastSpell(_W, pos.x, pos.z) end
        end
end

function CastR(unit, pos, spell)
        if GetDistance(pos) - getHitBoxRadius(unit)/2 < rRange then
          CastSpell(_R, pos.x, pos.z)
        end
end

function Exploit()
	if not frostOn then
	CastSpell(_Q)
	end
	if frostOn and AutoCarry.CurrentlyShooting then
	CastSpell(_Q)
	end
end

function Exploit2()
	if GetDistance(Target) <= 650 and not frostOn then
	CastSpell(_Q)
	end
	if frostOn and AutoCarry.CurrentlyShooting then
	CastSpell(_Q)
	end
end