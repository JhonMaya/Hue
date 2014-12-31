<?php exit() ?>--by Kain 97.90.203.108
--[[Sida's Autocarry Plugin - Nasus By Configz 
Credits / Bothappy / Hellking / Kain / 

v0.1 - Nasus
]]


if myHero.charName ~= "Nasus" then return end

local qRange = 120
local wRange = 650 
local eRange = 700
local QREADY, WREADY, EREADY, RREADY = false, false, false, false
local eSpeed = 20.0
local eDelay = 250
local eWidth = 200
local QStack = 0
local bHasBuff = false

SkillE = AutoCarry.Skills:NewSkill(false, _E, eRange, "Spirit Fire", AutoCarry.SPELL_LINEAR, 0, false, false, eSpeed, eDelay, eWidth, false)

function PluginOnLoad()
	AutoCarry.Crosshair:SetSkillCrosshairRange(700)
	Menu()
	
	 AdvancedCallback:bind('OnGainBuff', function(unit, buff) OnGainBuff(unit, buff) end)
     AdvancedCallback:bind('OnLoseBuff', function(unit, buff) OnLoseBuff(unit, buff) end)
       
	
	PrintChat("Configz Nasus Version 0.1 - Credits EntrywayHellking  Tandu for PRO LAST HIT!")

	        SheenSlot = GetInventorySlotItem(3057)
        TrinitySlot = GetInventorySlotItem(3078)
        LichBaneSlot = GetInventorySlotItem(3100)
        IceBornSlot = GetInventorySlotItem(3025)
end


function OnGainBuff(unit,buff)
        if unit.isMe then
                if buff.name == "NasusQ" then
                        bHasBuff = true
                end
        end
end
function OnLoseBuff(unit,buff)
        if unit.isMe then
                if buff.name == "NasusQ" then
                        bHasBuff = false
                end
        end
end
function PluginOnRecvPacket(p)
        if p.header == 0xFE and p.size == 0xC then
                p.pos = 1
                pNetworkID = p:DecodeF()
                unk01 = p:Decode2()
                unk02 = p:Decode1()
                stack = p:Decode4()
 
                if pNetworkID == myHero.networkID then
                        QStack = stack
                end
        end
end
function GetQDamage(target)
        local ADDmg = getDmg("AD", target, myHero)
        local extra = (SheenSlot and ADDmg or 0) + (TrinitySlot and ADDmg*1.5 or 0) + (LichBaneSlot and getDmg("LICHBANE",target,myHero) or 0) + (IceBornSlot and ADDmg*1.25 or 0)
        return getDmg("Q", target, myHero) + myHero:CalcDamage(target,QStack) + extra
end

function PluginOnTick()
	Checks()
	
        if AutoCarry.MainMenu.LastHit then
                if QREADY or bHasBuff then
                        AutoCarry.CanAttack = false
                        for _, minion in pairs(AutoCarry.EnemyMinions().objects)do
                                if minion and not minion.dead and minion.health < GetQDamage(minion) then
                                        CastSpell(_Q, minion.x, minion.z)
                                        myHero:Attack(minion)
                                        AutoCarry.shotFired = true
                                end
                        end
                        AutoCarry.CanAttack = true
                end
        end
	
	if AutoCarry.MainMenu.AutoCarry and Target then
		if QREADY then CastSpell(_Q) end
		if Menu.UseW and WREADY then CastSpell(_W, Target) end
		if Menu.UseE and EREADY and GetDistance(Target) <= eRange then SkillE:Cast(Target) end
	end 
end


function Menu()
	Menu = AutoCarry.PluginMenu
		Menu:addParam("FarmwQ","Farm with Q (AutoCarry LastHit)", SCRIPT_PARAM_ONOFF, true)
        Menu:addParam("DrawQStack","Draw QStack", SCRIPT_PARAM_ONOFF, true)
		Menu:addParam("UseE", "Use E", SCRIPT_PARAM_ONKEYDOWN, false, 69)
		Menu:addParam("UseW", "Use W", SCRIPT_PARAM_ONKEYDOWN, false, 87)
		Menu:addParam("DrawWrange","Draw W RANGE", SCRIPT_PARAM_ONOFF, true)
		Menu:addParam("DrawErange","Draw E RANGE", SCRIPT_PARAM_ONOFF, true)
end		
	

function Checks()
	QREADY = (myHero:CanUseSpell(_Q) == READY)
    WREADY = (myHero:CanUseSpell(_W) == READY)
    EREADY = (myHero:CanUseSpell(_E) == READY)
    RREADY = (myHero:CanUseSpell(_R) == READY)
	Target = AutoCarry.Crosshair:GetTarget()
end

local function getHitBoxRadius(target)
	return GetDistance(target, target.minBBox)
end


function PluginOnDraw()
        if Menu.DrawQStack then
                DrawText("QStack "..QStack, 20, 100, 100, 0xFFFFFF00)
        end
		if Menu.DrawWrange and WREADY then DrawCircle(myHero.x, myHero.y, myHero.z, wRange, 0xFF00FF) end
		if Menu.DrawErange and EREADY then DrawCircle(myHero.x, myHero.y, myHero.z, eRange, 0x00CC33) end	
end