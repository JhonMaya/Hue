<?php exit() ?>--by apple 141.101.99.198
if myHero.charName ~= "Ashe" then return end 
function PluginOnLoad()
print("Revamped Plugin: Ashe, loaded")
wRange, wSpeed, wDelay, wWidth = 1200, 1.550, 250, 55
SkillW = {spellKey = _W, range = wRange, speed = wSpeed, delay = wDelay, width = wWidth, minions = true}
eRange, eSpeed, eDelay, eWidth = 2500, 1.350, 250, 200
rRange, rSpeed, rDelay, rWidth = 99999, 1.6, 250, 100
SkillR = {spellKey = _R, range = rRange, speed = rSpeed, delay = rDelay, width = rWidth}
delay = 0
AsheMenu()
end
function PluginOnTick()
-- Check E range --
if myHero:GetSpellData(_E).level == 1 then eRange = 2500 
elseif myHero:GetSpellData(_Q).level == 2 then eRange = 3250 
elseif myHero:GetSpellData(_Q).level == 3 then eRange = 4000 
elseif myHero:GetSpellData(_Q).level == 4 then eRange = 4750 
elseif myHero:GetSpellData(_Q).level == 5 then eRange = 5500 end
if GetTickCount() - delay > 1000 then
if eRange ~= nil then
SkillE = {spellKey = _E, range = eRange, speed = eSpeed, delay = eDelay, width = eWidth}
end
delay = GetTickCount()
end
Target = AutoCarry.GetAttackTarget()
AutoCarry.SkillsCrosshair.range = (eRange / 2)
-- Free Q
if AutoCarry.PluginMenu.AutoQ and VIP_USER then if frosted == false and GetTickCount() > lasttick + 300 then CastSpell(_Q) lasttick = GetTickCount() end end
if Target ~= nil and (AutoCarry.MainMenu.AutoCarry) then if AutoCarry.PluginMenu.AutoW and (myHero:CanUseSpell(_W) == READY) then AutoCarry.CastSkillshot(SkillW, Target) end
if AutoCarry.PluginMenu.AutoE and (myHero:CanUseSpell(_E) == READY) and GetDistance(Target) > 1200 then AutoCarry.CastSkillshot(SkillE, Target) end
if AutoCarry.PluginMenu.AutoR and not AutoCarry.PluginMenu.AutoRKS then AutoCarry.CastSkillshot(SkillR, Target) else if (getDmg("R", Target, myHero) > Target.health and GetDistance(Target,myHero) > 700)then AutoCarry.CastSkillshot(SkillR, Target) end end end
    --[[
	-- Check for Crit Buff
	for i = 1, myHero.buffCount do
        local tBuff = myHero:getBuff(i)
        if BuffIsValid(tBuff) then
            if tBuff.name == "ashecritchanceready" then
					Focus = true
					else
					Focus = false
				end
			end 
		end]]
	
end
lasttick = GetTickCount() 
function PluginOnProcessSpell(object,spell) 
if spell.name == "frostarrow" then CastSpell(_Q) lasttick = GetTickCount() end 
end 
function PluginOnCreateObj(obj) 
if GetDistance(obj) < 100 and obj.name == "iceSparkle.troy" then frosted = true end 
end 
function PluginOnDeleteObj(obj) 
if GetDistance(obj) < 100 and obj.name == "iceSparkle.troy" then frosted = false end 
end
function AsheMenu()
if VIP_USER then
AutoCarry.PluginMenu:addParam("AutoQ", "Free Frost Shot", SCRIPT_PARAM_ONOFF, true)
end
AutoCarry.PluginMenu:addParam("AutoW", "Volley", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("AutoE", "Hawkshot (Vision)", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("AutoR", "Auto Ultimate", SCRIPT_PARAM_ONOFF, true)
AutoCarry.PluginMenu:addParam("AutoRKS", "Save Ultimate for KS", SCRIPT_PARAM_ONOFF, true)
end