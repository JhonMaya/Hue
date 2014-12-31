<?php exit() ?>--by ahmedzarga23 197.0.92.200
--((Auto Download Required LIBS))--
local REQUIRED_LIBS = {
        ["VPrediction"] = "https://raw.github.com/honda7/BoL/master/Common/VPrediction.lua",
        ["SOW"] = "https://raw.github.com/honda7/BoL/master/Common/SOW.lua",
        ["SourceLib"] = "https://raw.github.com/TheRealSource/public/master/common/SourceLib.lua",

    }
local DOWNLOADING_LIBS, DOWNLOAD_COUNT = false, 0
local SELF_NAME = GetCurrentEnv() and GetCurrentEnv().FILE_NAME or ""
function AfterDownload()
    DOWNLOAD_COUNT = DOWNLOAD_COUNT - 1
    if DOWNLOAD_COUNT == 0 then
        DOWNLOADING_LIBS = false
        print("<b>[Irelia]: Required libraries downloaded successfully, please reload (double F9).</b>")
    end
end
for DOWNLOAD_LIB_NAME, DOWNLOAD_LIB_URL in pairs(REQUIRED_LIBS) do
    if FileExist(LIB_PATH .. DOWNLOAD_LIB_NAME .. ".lua") then
        require(DOWNLOAD_LIB_NAME)
    else
        DOWNLOADING_LIBS = true
        DOWNLOAD_COUNT = DOWNLOAD_COUNT + 1
        DownloadFile(DOWNLOAD_LIB_URL, LIB_PATH .. DOWNLOAD_LIB_NAME..".lua", AfterDownload)
    end
end
if DOWNLOADING_LIBS then return end
--((Combos))--
local FullCombo = {_Q,_AA,_E,_AA,_R,_Q,_AA,_IGNITE}
local Combofull = {_Q}
--((Required Libs))--
require 'VPrediction'
require 'SourceLib'
require 'SOW'
--((Spells))--
local Config = nil
local VP = VPrediction()
local SpellQ = {Range =650}
local SpellW = {Range =125}
local SpellE = {Range =425}
local SpellR = {Range = 1000}
local AA = {Range= 125}
local Ranges = {[_Q] = 650,[_W] = 125,[_E] = 425,[_R] = 1000}
--((New things))--
local informationTable = {}
local spellExpired = true
--((OnLoad Function))--
function OnLoad()
Init()
ScriptSetUp()
PrintChat("<font color=\"#81BEF7\">AwA Irelia loaded Succesfully</font>")
end
function Init()
--((Spells))--
Q = Spell(_Q, SpellQ.Range)
W = Spell(_W, SpellW.Range)
E = Spell(_E, SpellE.Range)
R = Spell(_R, SpellR.Range)
--((Skillshots))--

--((Minion Manger))--
EnemyMinions = minionManager(MINION_ENEMY, 1100, myHero, MINION_SORT_MAXHEALTH_DEC)
JungleMinions = minionManager(MINION_JUNGLE, 1100, myHero, MINION_SORT_MAXHEALTH_DEC)
Loaded = true
end
--((Script Menu))--
function ScriptSetUp()
VP = VPrediction()
TS = SimpleTS(STS_LESS_CAST_PHYSICAL)
Orbwalker = SOW(VP)
DrawHandler = DrawManager()
DamageCalculator= DamageLib()
--((Damage Calclator))--
DamageCalculator:RegisterDamageSource(_Q, _PHYSICAL, 10, 30, _PHYSICAL, _AD, 1, function() return (player:CanUseSpell(_Q) == READY) end)
DamageCalculator:RegisterDamageSource(_E, _MAGIC, 30, 50, _MAGIC, _AP, 0.5, function() return (player:CanUseSpell(_E) == READY) end)
DamageCalculator:RegisterDamageSource(_R, _PHYSICAL, 40, 40, _PHYSICAL, _AD, 0.6, function() return (player:CanUseSpell(_R) == READY) end)
Config = scriptConfig("Irelia", "Irelia")
Config:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
Config:addParam("Harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("T"))
Config:addParam("Laneclear", "Laneclear", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("V"))
Config:addParam("Flee", "Flee", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
--((Orbwalker))--
Config:addSubMenu("Orbwalk", "Orbwalk")
Orbwalker:LoadToMenu(Config.Orbwalk)
--((Target Selector))--
Config:addSubMenu("Target Selector", "TS")
TS:AddToMenu(Config.TS)
--((Combo options))--
Config:addSubMenu("Combo options", "ComboSub")
Config.ComboSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
Config.ComboSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
Config.ComboSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
Config.ComboSub:addParam("useR", "Use R if killable", SCRIPT_PARAM_ONOFF, true)
Config.ComboSub:addParam("useQ2", "Use Q to Gapclose", SCRIPT_PARAM_ONOFF, true)
Config.ComboSub:addParam("SetGRange", "Set GapClosing Range", SCRIPT_PARAM_SLICE, 0, 0, 1500)
--((Harass options))--
Config:addSubMenu("Harass options", "HarassSub")
Config.HarassSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
Config.HarassSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
Config.HarassSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
Config.HarassSub:addParam("ManaC", "Block Harass When Mana is under %", SCRIPT_PARAM_SLICE, 0, 0, 100)
--((Ultimate))--
Config:addSubMenu("Ultimate", "Ultimate")
Config.Ultimate:addParam("useR", "Force R Cast", SCRIPT_PARAM_ONKEYDOWN, false,string.byte("A"))
--((Farm options))--
Config:addSubMenu("Laneclear options", "FSub")
Config.FSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
Config.FSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
Config.FSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
Config.FSub:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, true)
--((Jfarm options))--
Config:addSubMenu("Jungle Farm options", "Jfarm")
Config.Jfarm:addParam("Enabled", "Jungle Farm ", SCRIPT_PARAM_ONKEYDOWN, true,string.byte("V"))
Config.Jfarm:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
Config.Jfarm:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
Config.Jfarm:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
--((Advanced options))
Config:addSubMenu("Advanced Options", "AdvOpt")
Config.AdvOpt:addParam("useQ4", "Use Q to Dodge", SCRIPT_PARAM_ONOFF, true)
Config.AdvOpt:addParam("useR", "Use R when low", SCRIPT_PARAM_ONOFF, true)
Config.AdvOpt:addParam("useR2", "On minions if there is no enemies", SCRIPT_PARAM_ONOFF, true)
Config.AdvOpt:addParam("useR3", "On enemies", SCRIPT_PARAM_ONOFF, true)
Config.AdvOpt:addParam("AutoRLow", "Min Health % for Auto R", SCRIPT_PARAM_SLICE, 15, 0, 100, -1)
--((Draw))--
Config:addSubMenu("Draw", "Draw")
for spell, range in pairs(Ranges) do
DrawHandler:CreateCircle(myHero, range, 1, {255, 255, 255, 255}):AddToMenu(Config.Draw, SpellToString(spell).." Range", true, true, true)
end
DamageCalculator:AddToMenu(Config.Draw, FullCombo)
--((Permashow))--
Config:permaShow("Combo")
Config:permaShow("Harass")
Config:permaShow("Laneclear")
Config:permaShow("Flee")
end
--((Combo))--
function Combo() 
local Qfound = TS:GetTarget(SpellQ.Range)
local Wfound = TS:GetTarget(SpellW.Range)
local Efound = TS:GetTarget(SpellE.Range)
local Rfound = TS:GetTarget(SpellR.Range)
local Gfound = TS:GetTarget(Config.ComboSub.SetGRange)
if Qfound and Q:IsReady() and Config.ComboSub.useQ then 
    Q:Cast(Qfound)
end 
if Wfound and W:IsReady() and Config.ComboSub.useW then 
    W:Cast()
end 
if Efound and E:IsReady() and Config.ComboSub.useE then 
    E:Cast(Efound)
end
if Rfound and R:IsReady() and Config.ComboSub.useR then 
    if DamageCalculator:IsKillable(Rfound,FullCombo) then
         R:Cast(Rfound.x,Rfound.z)
     end 
end  
if Gfound and Q:IsReady() and Config.ComboSub.useQ2 then 
    if GetDistance(Gfound,myHero) > SpellQ.Range then 
        local ToGapMinion = GetBestMinion(Gfound)
        if ToGapMinion then 
            Q:Cast(ToGapMinion)
        end 
    end 
end 
end 
--((Harass))--
function Harass()
if Config.HarassSub.ManaC > (myHero.mana / myHero.maxMana) * 100 then return end 
local Qfound = TS:GetTarget(SpellQ.Range)
local Wfound = TS:GetTarget(SpellW.Range)
local Efound = TS:GetTarget(SpellE.Range)
local Rfound = TS:GetTarget(SpellR.Range)
if Qfound and Q:IsReady() and Config.ComboSub.useQ then 
    Q:Cast(Qfound)
end 
if Wfound and W:IsReady() and Config.ComboSub.useW then
    W:Cast()
end 
if Efound and E:IsReady() and Config.ComboSub.useE then 
    E:Cast(Efound)
end 
end
--((Farm))--
function Farm()
    EnemyMinions:update()
    MinionObj2 = EnemyMinions.objects[1]
    for i, MinionObj in pairs(EnemyMinions.objects) do
        if MinionObj or MinionObj2 then 
    if DamageCalculator:IsKillable(MinionObj,Combofull) then 
        if Q:IsReady() and  Config.FSub.useQ  then 
            Q:Cast(MinionObj)
        end 
     end 
        if W:IsReady() and Config.FSub.useW then 
            W:Cast() 
        end 
        if  E:IsReady() and Config.FSub.useE then
            E:Cast(MinionObj)
        end 
        if R:IsReady() and Config.FSub.useR then 
        R:Cast(MinionObj2.x,MinionObj2.z)
        end 
      end
    end 
  end 

--((Jungle Farm))--
function JFarm() 
    JungleMinions:update()
    local JungleObj = JungleMinions.objects[1]
    if JungleObj then 
        if Q:IsReady() and  Config.Jfarm.useQ then 
            Q:Cast(JungleObj)
        end 
        if W:IsReady() and Config.Jfarm.useW then 
            W:Cast() 
        end 
        if E:IsReady() and Config.Jfarm.useE then 
            E:Cast(JungleObj)
        end 
    end 
end 
--((Flee))--
function flee() 
    EnemyMinions:update()
    for i, minion in pairs(EnemyMinions.objects) do
        if DamageCalculator:IsKillable(minion,Combofull) then 
            Q:Cast(minion)
        end 
    end 
end 
--((Get Minion to gapClose))--
function GetBestMinion(Target)
EnemyMinions:update()
for i, minion in pairs(EnemyMinions.objects) do
    if Target ~= nil and minion ~= nil then 
        if GetDistance(minion,Target) < GetDistance(minion,myHero) then 
            if DamageCalculator:IsKillable(minion,Combofull) then 
                return minion
            end 
        end 
    end 
end 
end 
--((Auto R when Low))
function AutoRLow() 
    local Rfound = TS:GetTarget(SpellR.Range)
    EnemyMinions:update() 
		MinionObj2 = EnemyMinions.objects[1]
     if (myHero.health / myHero.maxHealth) <= (Config.AdvOpt.AutoRLow / 100) then
         
        for i, minion in pairs(EnemyMinions.objects) do
            if minion ~= nil  and  not Rfound and Config.AdvOpt.useR2  then
                R:Cast(minion.x,minion.z)
                                end 
                                                                end 
             if Rfound and Config.AdvOpt.useR3 and MinionObj then 
                R:Cast(MinionObj2.x,MinionObj2.z)
                                end 
            end 
        end 

--((Utility))--
function GetPredictedPositionsTableLinear(unit, delay, radius, range, speed, from)
    local result = {}
    for i, target in ipairs(t) do
        local CastPosition, Hitchance, Position = GetLineAOECastPosition(unit, delay, radius, range, speed, from) 
        table.insert(result, Position)
    end
    return result

end
--((OnTick))--
function OnTick() 
    if Loaded then 
        if Config.Ultimate.useR then 
            local Rfound = TS:GetTarget(SpellR.Range)
            if Rfound and R:IsReady() then 
                R:Cast(Rfound.x,Rfound.z)
            end 
        end 
        if Config.Combo then 
            Combo()
        end 
        if Config.Harass then 
            Harass()
        end 
        if Config.Laneclear then 
            Farm () 
        end 
        if Config.Flee then 
            flee()
        end 
        if Config.AdvOpt.useR  then 
            AutoRLow()
        end 
     end 
end 