<?php exit() ?>--by Incognito 70.78.192.224


--[[ Emulator Code ]]

require "FastCollision"
require 'Prodiction'
_G.Collision = Collision
Prodict = ProdictManager.GetInstance()
function readAll(file)
    local f = io.open(file, "rb")
    local content = f:read("*all")
    f:close()
    return content
end
function file_exists(name)
    local f=io.open(name,"r")
    if f~=nil then io.close(f) return true else return false end
end

function Convert(script)

    script = script:gsub("function Plugin:","function ")
    script = script:gsub("function Plugin","function ")
    script = script:gsub("AutoCarry%.PluginMenu","MainMenu")
    script = script:gsub("AutoCarry%.","")
    script = script:gsub("Helper%.","")
    script = script:gsub("Orbwalker:","")
    script = script:gsub("self:","")
    script = script:gsub("local lastDistance = GetEnemyLastDistance%(enemy%)","local lastDistance = GetEnemyLastDistance(enemy) if not lastDistance then return end")
    script = script:gsub("Plugin%(%), ","")
    script = script:gsub("local Skills, Keys, Items, Data, Jungle, Helper, MyHero, Minions, Crosshair, Orbwalker = Helper:GetClasses%(%)","")

    -- script = script:gsub("Keys%.","MainMenu.")
    file = io.open(SCRIPT_PATH.."ConvertedPlugins\\"..myHero.charName..".lua","w")
    file:write(script)
    file:close()
    LoadScript("ConvertedPlugins//"..myHero.charName..".lua")
end
local PluginFile
function OnLoad()

    RunCmdCommand('mkdir "' .. string.gsub(SCRIPT_PATH..'/ConvertedPlugins"', [[/]], [[\]]))
    if file_exists(SCRIPT_PATH.."ConvertedPlugins\\"..myHero.charName..".lua") then
        PluginFile = readAll(SCRIPT_PATH.."ConvertedPlugins\\"..myHero.charName..".lua")
        LoadScript("ConvertedPlugins//"..myHero.charName..".lua")
    elseif file_exists(LIB_PATH.."SidasAutoCarryPlugin - "..myHero.charName..".lua") then
        PluginFile = readAll(LIB_PATH.."SidasAutoCarryPlugin - "..myHero.charName..".lua")
        Convert(PluginFile)
    end

end
_G.IsAfterAttack = function() return not MMA_AttackAvailable end
_G.EnemyTable = GetEnemyHeroes()
_G.GetNextAttackTime = function() return GetLatency() + 700 end -- Can't really emulate as simply as I wanted ;d
_G.GetAttackTarget = function()
    local targetId = _G.attackedUnit
    if MMA_Target then return MMA_Target
    elseif targetId ~= nil and targetId > 0 then
        local targetMMA = objManager:GetObjectByNetworkId(targetId)
        if targetMMA ~= nil and targetMMA.type:lower() == "obj_ai_hero" then return targetMMA end
    else ts:update() return ts.target
    end
end
--[[
_G.ValidTarget2 = rawget(_G, 'ValidTarget')
_G.ValidTarget = function(tg,rg)
    res = ValidTarget2(tg,rg)
    if res then
        if MainMenu.AutoCarry or MainMenu.MixedMode then
            if tg.type == myHero.type and MMA_Target  then
                _G.lastTg = MMA_Target
                return res
            end
        elseif tg and tg.type == myHero.type and not MMA_Target then
            ts:update()
            _G.lastTg = tg
            end
    end
    return res
end]]
_G.MainMenu = scriptConfig("SAC Emulator","Emulator Settings")
ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1200, DAMAGE_PHYSICAL, false) -- PRO TIP: Priority 1 is the highest, 5 is the lowest.
ts.name = "Skills TS"
MainMenu:addTS(ts)
MainMenu:addParam("AutoCarry","SAC AutoCarry", SCRIPT_PARAM_ONKEYDOWN, false, string.byte(" "))
MainMenu:addParam("MixedMode","SAC MixedMode", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
MainMenu:addParam("LaneClear","SAC LaneClear", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
_G.ConfigMenu = scriptConfig("Sida's Auto Carry: Configuration", "sidasacconfig")
ConfigMenu:addParam("HitChance", "Ability Hitchance", SCRIPT_PARAM_SLICE, 60, 0, 100, 0)
_G.GetMinionTarget = function()
    return nil
end
_G.SkillsCrosshair = {range = myHero.range}
_G.EnemyMinions = function()
    if not minions then minions = minionManager(MINION_ENEMY, myHero.range + 65, myHero, MINION_SORT_HEALTH_ASC)
    else minions:update() end
    return minions
end
_G.getHitBoxRadius = function (unit) return 80 end
local ProQ,ProQCol,ProW,ProWCol,ProE,ProECol,ProR,ProRCol
function _G.SetProdction(skill)
    if skill.spellKey == _Q and not ProQ then
        ProQ = Prodict:AddProdictionObject(skill,skill.range,skill.speed*1000,(skill.delay/1000),skill.width)
        ProQCol = FastCol(ProQ)
        return {ProQ,ProQCol}
    elseif skill.spellKey == _Q and ProQ then return {ProQ,ProQCol}
    elseif skill.spellKey == _W and not ProW then ProW = Prodict:AddProdictionObject(skill,skill.range,skill.speed*1000,(skill.delay/1000),skill.width)
    ProWCol = FastCol(ProW)
    return {ProW,ProWCol}
    elseif skill.spellKey == _W and ProW then return {ProW,ProWCol}
    elseif skill.spellKey == _E and not ProE then ProE = Prodict:AddProdictionObject(skill,skill.range,skill.speed*1000,(skill.delay/1000),skill.width)
    ProECol = FastCol(ProE)
    return {ProE,ProECol}
    elseif skill.spellKey == _E and ProE then return {ProE,ProECol}
    elseif skill.spellKey == _R and not ProR then ProR = Prodict:AddProdictionObject(skill,skill.range,skill.speed*1000,(skill.delay/1000),skill.width)
    ProRCol = FastCol(ProR)
    return {ProR,ProRCol}
    elseif skill.spellKey == _R and ProR then return {ProR,ProRCol}
    end
    if skill.range > ts.range then ts.range = skill.range end
end
_G.CastSkillshot = function (skill, target)
    ProdictSkill = SetProdction(skill)
    if not ProdictSkill then return end
    ProdictSkill = ProdictSkill[1]
    ProdictSkill:GetPredictionCallBack(target, CastCall)
end

function CastCall(unit,pos,skill)
    if pos and GetDistance(pos) <= skill.range then
        if VIP_USER then
            if not skill.minions or not GetCollision(skill, myHero, pos) then
                CastSpell(skill.Name.spellKey, pos.x, pos.z)
            end

        end
    end
end
_G.GetCollision = function (skill, source, destination)
    local ProdictSkill = SetProdction(skill)
    local col = ProdictSkill[2]
    return col:GetMinionCollision(destination, source)
end
KeysC = {}
KeysC.__index = KeysC

function KeysC.init()
    local acc = {}
    setmetatable(acc,KeysC)
    acc.LastHit = MainMenu.LastHit
    acc.LaneClear = MainMenu.LaneClear
    acc.AutoCarry = MainMenu.AutoCarry
    return acc
end
PluginsC = {}
PluginsC.__index = PluginsC

function PluginsC.init()
    local acc = {}
    setmetatable(acc,PluginsC)
    return acc
end
function PluginsC:RegisterPlugin(f,f2)
    return MainMenu
end
function PluginsC:RegisterPlugin(f)
    return MainMenu
end
CrossHairC = {}
CrossHairC.__index = CrossHairC

function CrossHairC.init()
    local acc = {}
    setmetatable(acc,CrossHairC)
    return acc
end
function CrossHairC:SetSkillCrosshairRange(range)
    ts.range = range
end
function CrossHairC:GetTarget()
    return GetAttackTarget()
end
AutoCarryC = {}
AutoCarryC.__index = AutoCarryC
_G.GetPrediction = function(skill, target)
		if VIP_USER then
			pred = TargetPredictionVIP(skill.range, skill.speed*1000, skill.delay/1000, skill.width)
		elseif not VIP_USER then
			pred = TargetPrediction(skill.range, skill.speed, skill.delay, skill.width)
		end
		return pred:GetPrediction(target)
	end
function AutoCarryC.init()
    local acc = {}
    setmetatable(acc,AutoCarryC)
    acc.Crosshair = Crosshair
    acc.Keys = MainMenu
    acc.Plugins = Plugins
    return acc
end

-- create and use an AccountAutoCarry.Crosshair:GetTarget()
_G.Plugin = function() return "Reborn Plugin" end
_G.Crosshair = CrossHairC.init()
_G.Plugins = PluginsC.init()
_G.AutoCarry = AutoCarryC.init()
_G.Keys = MainMenu
--[[ End of emulator Code ]]