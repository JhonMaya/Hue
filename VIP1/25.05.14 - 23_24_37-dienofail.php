<?php exit() ?>--by dienofail 68.48.159.9
local version = "0.09"
--[[
Changelog

v0.01 - release

v0.02 - jungle farm and manual Q added

v0.03 - Q cancel added (default button = 'C')

v0.04 - Ward casting bug fixed

v0.05 - Revamped support added

v0.06 - Changed manual Q logic slightly. MAKE SURE YOU HOLD THE BUTTON FOR AIM.

v0.07 - Added failsafes for enemy going out of range, should now cast if enemy goes back into range while Q is charging (if they were initially out of range)

v0.08 - now will use E when killable and not wait for a reset.

v0.09 - autoupdater
]]--


--[[if myHero.charName ~= "Vi" then return end
local AUTOUPDATE = true
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/Dienofail/BoL/master/common/SidasAutoCarryPlugin%20-%20Vi.lua".."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = LIB_PATH.."SidasAutoCarryPlugin - Vi.lua"
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

function AutoupdaterMsg(msg) print("<font color=\"#6699ff\"><b>SAC VI:</b></font> <font color=\"#FFFFFF\">"..msg..".</font>") end
if AUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, UPDATE_PATH)
    if ServerData then
        local ServerVersion = string.match(ServerData, "local version = \"%d+.%d+\"")
        ServerVersion = string.match(ServerVersion and ServerVersion or "", "%d+.%d+")
        if ServerVersion then
            ServerVersion = tonumber(ServerVersion)
            if tonumber(version) < ServerVersion then
                AutoupdaterMsg("New version available"..ServerVersion)
                AutoupdaterMsg("Updating, please don't press F9")
                DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version.") end)  
            else
                AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
            end
        end
    else
        AutoupdaterMsg("Error downloading version info")
    end
end
]]
require "VPrediction"
if myHero.charName ~= "Vi" then return end
local VP = VPrediction()
local version = 1.0
local autoUpdate = true 
local scriptName = "ExtrinsicVi"
local sourceLibFound = true
local VP = VPrediction()
local IsSowLoaded = false
local InterruptTarget = nil
local initDone = false
local Target = nil
local IsSelectorLoaded = false
local Menu = nil
local ts = nil
local IsMMALoaded = false
local IsSACLoaded = false
--[[if FileExist(LIB_PATH .. "SourceLib.lua") then
   -- require "SourceLib"
--else
 --   sourceLibFound = false
 --   DownloadFile("https://raw.github.com/TheRealSource/public/master/common/SourceLib.lua", LIB_PATH .. "SourceLib.lua", function() print("<font color=\"#6699ff\"><b>" .. scriptName .. ":</b></font> <font color=\"#FFFFFF\">SourceLib downloaded! Please reload!</font>") end)
--end
--if not sourceLibFound then return end
]]
local ToInterrupt = {
    { charName = "Caitlyn", spellName = "CaitlynAceintheHole"},
    { charName = "FiddleSticks", spellName = "Crowstorm"},
    { charName = "FiddleSticks", spellName = "DrainChannel"},
    { charName = "Galio", spellName = "GalioIdolOfDurand"},
    { charName = "Karthus", spellName = "FallenOne"},
    { charName = "Katarina", spellName = "KatarinaR"},
    { charName = "Lucian", spellName = "LucianR"},
    { charName = "Malzahar", spellName = "AlZaharNetherGrasp"},
    { charName = "MissFortune", spellName = "MissFortuneBulletTime"},
    { charName = "Nunu", spellName = "AbsoluteZero"},
    { charName = "Pantheon", spellName = "Pantheon_GrandSkyfall_Jump"},
    { charName = "Shen", spellName = "ShenStandUnited"},
    { charName = "Urgot", spellName = "UrgotSwap2"},
    { charName = "Varus", spellName = "VarusQ"},
    { charName = "Warwick", spellName = "InfiniteDuress"}
}
    
function OnLoad()
    --Menu
    checkOrbwalker()
    mainLoad()
    mainMenu()
end
    
function OnWndMsg(msg,key)
    if key == string.byte("W") and msg == KEY_UP then
        -- mark Q key is release
        if Target ~= nil and ValidTarget(Target) and isPressedQ then
            --print('Manual q part 2 getting called')
            local CastPosition, HitChance, Position = VP:GetLineCastPosition(Target, 0.250, 60, CurrentRange, qSpeed, myHero, false)
            if GetDistance(CastPosition) < CurrentRange and HitChance > 0 then
                CastSpellQ2(CastPosition.x, CastPosition.z)
            end 
        elseif isPressedQ then
            CastSpellQ2(mousePos.x, mousePos.z)
        end
    end
end


function CastSpellQ1(posx, posz)
    if myHero:CanUseSpell(_Q) == READY then
        if not isPressedQ then
            --CastSpell(_Q, x, z)
            Packet("S_CAST", {spellId = _Q, x = posx, y = posz}):send()
            qTick = GetTickCount()
            --PrintChat("Cast phase 1")
        end
    end
end


function CastSpellQ2(x, y)
    if isPressedQ then
        qTick = GetTickCount()
        --PrintChat("2nd phase called")
        Send2ndQPacket(x, y)
    end
end

function CheckQstatus()
    -- if isPressedQ then
    --     PrintChat('Q is pressed Q current casttime is ' .. tostring(Qcasttime) .. ' Qstart time is ' .. tostring(Qstartcasttime))
    -- end
end

function CheckQCastTime()
    if os.clock() - Qstartcasttime > 1.250 then
        Qcasttime = 1.250
    end
    if os.clock() - Qstartcasttime > 5 then
        Qcasttime = 0
        isPressedQ = false
    end
end

function ConvertQCastTime()
    if isPressedQ then 
        --PrintChat("Q is being updated!")
        range = 250
        if Qcasttime < 0.250 then
            range = 250
        end
        if Qcasttime > 1.250 then
            range = 715
		end
        if Qcasttime > 0.250 and Qcasttime < 1.250 then
            --PrintChat("Middle calculation being done!")
            range = (Qcasttime - 0.250 / 1.250)*465 + 250
            --PrintChat("middle calculation result " .. tostring(range))
        end
        return range
    end
end

function ConvertRangeToCastTime(Range)
    CastTime = ((Range-250)/465)*1.250 + 0.200
    return CastTime
end

function UpdateQCasttime()
    if isPressedQ then
        Qcasttime = os.clock() - Qstartcasttime
    end
    if not isPressedQ then
        Qcasttime = 0
    end
end

function CalculateCurrentQDamage(Target)
    if Target ~= nil and GetDistance(Target) < 1200 and ValidTarget(Target) then
        if Qcasttime ~= nil or 0 then 
            return 0
        end
        local TotalDiff = getDmg("Q", Target, myHero, 3) - getDmg("Q", Target, myHero, 1) 
        if Qcasttime > 1.250 then
            Qcasttime = 1.250 
        end
    end
end

function OnTick()
    if initDone then
        Target = Checks()
        UpdateQCasttime()
        CheckQCastTime()
        CheckQstatus()
        CurrentRange = ConvertQCastTime(Qcasttime)
        if Menu.JungleFarm then
            JungleClear()
        end
        if Menu.Farm then
            LaneClear()
        end

        if Menu.manualQ and QREADY and not isPressedQ then
            --PrintChat('Current range is ' .. tostring(CurrentRange))
            CastSpellQ1(myHero.x+550, myHero.z+550)
        end

        if Menu.Extras.CancelQ and QREADY and isPressedQ then
            if Target == nil then
                CastSpellQ2(mousePos.x, mousePos.z)
            end

            if Target ~= nil and GetDistance(Target) + 50 < CurrentRange and ValidTarget(Target) then
                local CastPosition1, HitChance1, Position1 = VP:GetLineCastPosition(Target, 0.253, 55, CurrentRange, qSpeed, myHero, false)
                if GetDistance(CastPosition1) < 730 then
                    CastSpellQ2(CastPosition1.x, CastPosition1.z)
                end
            end
        end
        
        if Target == nil then return end
        if Target ~= nil and Menu.manualQ and ValidTarget(Target) and isPressedQ and not QPredictionHold then
            --print('Manual q part 2 getting called')
            local CastPosition, HitChance, Position = VP:GetLineCastPosition(Target, 0.483, 55, CurrentRange, qSpeed, myHero, false)
            if GetDistance(CastPosition) < CurrentRange and HitChance > 0 then
                CastSpellQ2(CastPosition.x, CastPosition.z)
            end 

            local CastPosition1, HitChance1, Position1 = VP:GetLineCastPosition(Target, 0.253, 55, CurrentRange, qSpeed, myHero, false)
            if CurrentRange == 715 and Qcasttime > 1.150 then
                if GetDistance(CastPosition1) < 730 then
                    CastSpellQ2(CastPosition1.x, CastPosition1.z)
                end
            end
        end
    
        --PrintChat(tostring(isPressedQ))
        if Menu.CancelQ and Target ~= nil then
            local CastPosition, HitChance, Position = nil
            if ValidTarget(Target) then
                CastPosition, HitChance, Position = VP:GetLineCastPosition(Target, 0.250, 55, CurrentRange, qSpeed, myHero, false)
            end
            if CastPosition ~= nil and GetDistance(CastPosition) < CurrentRange and ValidTarget(Target) then
                CastSpellQ2(CastPosition.x, CastPosition.z)
            else
                CastSpellQ2(mousePos.x, mousePos.z)
            end
        end
    
    
        if Target ~= nil and Menu.Combo then
            if getDmg("E", Target, myHero) > Target.health and EREADY and GetDistance(Target) < 250 then
                CastSpell(_E, Target)
            end
    
            if not isPressedQ then
                local CastPosition, HitChance, Position = VP:GetLineCastPosition(Target, 0.550, 55, 725, qSpeed, myHero, false)
                if QREADY and GetDistance(CastPosition) < 750 and GetDistance(Target) > Menu.Extras.qdistance and HitChance >= 1 then
                    CastSpellQ1(CastPosition.x, CastPosition.z)
                end
            end
            if isPressedQ then
                --PrintChat('2nd part ready')
                local CastPosition, HitChance, Position = VP:GetLineCastPosition(Target, 0.200, 65, CurrentRange, qSpeed, myHero, false)
                --PrintChat(tostring(HitChance) .. ' ' .. tostring(CastPosition.x) .. ' ' .. tostring(CastPosition.z))
                if GetDistance(CastPosition) < CurrentRange and HitChance > 0 then
                    CastSpellQ2(CastPosition.x, CastPosition.z)
                end

                if GetDistance(CastPosition) > CurrentRange + 290 and CurrentRange == 715 and Qcasttime >= 1.250 then
                    local ApproachVector = Vector(Vector(CastPosition) - Vector(myHero)):normalized()
                    local ToCastVector = Vector(myHero.visionPos) + ApproachVector*700
                    CastSpellQ2(ToCastVector.x, ToCastVector.z)
                end
            end



            --if QREADY and Menu.useQ and GetDistance(Target) < 800 and GetDistance(Target) > 300 and not isPressedQ and AutoCarry.MainMenu.AutoCarry and Qcasttime == 0 then
                --print("Debug")
                --PrintChat('Calculating Q')
                --calculating delay = sample every value from 0 --> 500 ms
                --[[for i=0, qtotalcasttime, 10 do
                    current_range = (qmaxRange - qminRange)/ (i/qtotalcasttime) 
                    current_range = current_range + qminRange
                    current_cast_time = i + 250 / 1000
                    CastPosition, HitChance, Position = VP:GetLineCastPosition(Target, current_cast_time, 55, current_range, qSpeed)
                    if HitChance > min_hit_chance then
                        CastSpell(_Q, CastPosition.x, CastPosition.z)
                        CastSpell(10)
                    end
                end]]
                --CastPosition, HitChance, Position = VP:GetLineCastPosition(Target, current_cast_time, 55, current_range, qSpeed)
                --CastSpell(_Q, Target.x, Target.z)
                --isPressedQ = true
            --end
    
            --if isPressedQ then
                --print("Debug2")
                --[[PrintChat(tostring(Qcasttime))
                --check to see if current cast time and position intersects with enemy vector
                if Qcasttime > qtotalcasttime then
                    Qcasttime = qtotalcasttime
                else 
                    Qcasttime = os.clock() - Qstarttime
                end
                print("Debug3")
                current_range = qminRange + Qcasttime*(qmaxRange-qminRange/qtotalcasttime)
                CastPosition, HitChance, Position = VP:GetLineCastPosition(Target, 0.250, 55, current_range, qSpeed)
                if HitChance > min_hit_chance then
                    Qshouldcast = true
                    PrintChat("Sending packet")
                    Send2ndQPacket(myHero.networkID, CastPosition.x, CastPosition.z)
                end]]
            --else
                --Qshouldcast = false
            --end
            if RREADY and Menu.useR and GetDistance(Target) < rRange and Menu.Combo and Menu.useRfirst then
                CastSpell(_R, Target)
            end 
            if RREADY and GetDistance(Target) < rRange and Menu.KS.killsteal and getDmg("R", Target, myHero) > Target.health then
                CastSpell(_R, Target)
            end 
            if RREADY and CheckHitNumber(Target) >= Menu.ComboSub.minR and GetDistance(Target) < rRange and Menu.useR then
                CastSpell(_R, Target)
            end
            if Menu.Combo and EREADY and GetDistance(Target) < 450 and Menu.ComboSub.useE then
                CastSpellE(Target)
            end 
        end
    
        -- if Target ~= nil and isPressedQ and not Menu.Combo and not Menu.manualQ and ValidTarget(Target, 800) and not IsKeyDown(KeyQ) then
        --     local CastPosition, HitChance, Position = nil
        --     if ValidTarget(Target) then
        --         CastPosition, HitChance, Position = VP:GetLineCastPosition(Target, 0.483, 55, CurrentRange, qSpeed, myHero)
        --     end
        --     if CastPosition ~= nil and GetDistance(CastPosition) < CurrentRange and ValidTarget(Target) then
        --         CastSpellQ2(CastPosition.x, CastPosition.z)
        --     else
        --         CastSpellQ2(mousePos.x, mousePos.z)
        --     end
        -- end
    end
end

function LaneClear()
    EnemyMinions:update()
    local minion = EnemyMinions.objects[1]
    if minion == nil then return end
    if Menu.Farm and Menu.FarmSub.useQ and minion ~= nil then
        if ValidTarget(minion) then
            if QREADY and #EnemyMinions.objects > 0 then
                local QPos = GetBestQPositionFarm()
                if QPos and not isPressedQ then
                    CastSpellQ1(QPos.x, QPos.z)
                end
                
                if QPos ~= nil and isPressedQ and GetDistance(QPos) < CurrentRange then
                    CastSpellQ2(QPos.x, QPos.z)
                end
            end
        end
    end

    if minion ~= nil and ValidTarget(minion) and Menu.Farm and Menu.FarmSub.useE then
        if EREADY and ValidTarget(minion) and GetDistance(minion) < 400 then
            CastSpellE(minion)
        end
    end
end

function JungleClear()
    JungleMinions:update()
    --local UseQ = Menu.Farm.JungleKey
    local minion = JungleMinions.objects[1]
    if minion == nil then return end
    if minion ~= nil  and ValidTarget(minion) and Menu.JungleFarm and Menu.FarmSub.useQ then
        if ValidTarget(minion) then
            if QREADY and #JungleMinions.objects > 0 then
                local QPos = GetBestQPositionFarmJungle()
                if QPos and not isPressedQ then
                    CastSpellQ1(QPos.x, QPos.z)
                end
                
                if isPressedQ and GetDistance(QPos) < CurrentRange then
                    CastSpellQ2(QPos.x, QPos.z)
                end
            end
        end
    end

    if minion ~= nil and ValidTarget(minion) and Menu.JungleFarm and Menu.FarmSub.useE then
        if EREADY and ValidTarget(minion) and GetDistance(minion) < 400 then
            CastSpellE(minion)
        end
    end
end
            
function AutoAttack(Target)
    if Target == nil or not ValidTarget(Target) or Target.dead or GetDistance(Target) > 850 then return end
    if _G.MMA_Loaded then
        _G.MMA_ForceTarget = Target
    elseif _G.AutoCarry.Orbwalker then
        _G.AutoCarry.Orbwalker.Orbwalk(Target)
    elseif IsSowLoaded then
        SOWi:Attack(Target)
    end
end

function CastSpellE(Target)
    if Target == nil or not ValidTarget(Target) or Target.dead or GetDistance(Target) > 850 then return end
    if GetDistance(Target) > 400 then return end
    if _G.MMA_Loaded then
        if _G.MMA_NextAttackAvailability < 0.5 then
            CastSpell(_E) 
        end
    elseif _G.AutoCarry then
        if (_G.AutoCarry.shotFired or _G.AutoCarry.Orbwalker:IsAfterAttack()) then
            CastSpell(_E) 
        end
    elseif IsSowLoaded then
        -- if not SOWi:CanAttack() then
        --     CastSpell(_E) 
        -- end
    end
end

function round(num, idp)
    return string.format("%." .. (idp or 0) .. "f", num)
end

function OnDraw()
    local mydmg = 0
        if QREADY then
            DrawCircle3D(myHero.x, myHero.y, myHero.z, qmaxRange,  1, ARGB(255, 0, 255, 255))
            DrawCircle3D(myHero.x, myHero.y, myHero.z, CurrentRange,  1, ARGB(255, 0, 0, 255))
        end
        if EREADY then 
            DrawCircle3D(myHero.x, myHero.y, myHero.z, eRange, 1,  ARGB(255, 0, 255, 255))
        end
        if RREADY then
            DrawCircle3D(myHero.x, myHero.y, myHero.z, rRange, 1,  ARGB(255, 0, 255, 255))
        end
        
        local Enemies = GetEnemyHeroes()
        for idx, enemy in ipairs(Enemies) do
            if QREADY then
                mydmg = getDmg("Q", enemy, myHero, 3)+mydmg
            end 
            if EREADY then 
                mydmg = 2*getDmg("E", enemy, myHero)+mydmg
            end
            if RREADY then
                mydmg = getDmg("R", enemy, myHero)+mydmg
            end
        if ValidTarget(enemy) and not enemy.dead and GetDistance(enemy) < 2000 and (QREADY and getDmg("Q", enemy, myHero) > enemy.health) then
            DrawText3D("Short Q", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)               
        elseif ValidTarget(enemy) and not enemy.dead and GetDistance(enemy) < 2000 and (QREADY and getDmg("Q", enemy, myHero, 3) > enemy.health) then
            DrawText3D("Full Q", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
        elseif ValidTarget(enemy) and not enemy.dead and GetDistance(enemy) < 2000 and (RREADY and getDmg("R", enemy, myHero) > enemy.health) then
            DrawText3D("R", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
        elseif ValidTarget(enemy) and not enemy.dead  and GetDistance(enemy) < 2000 and (EREADY and 2*getDmg("E", enemy, myHero) > enemy.health) then
            DrawText3D("2E", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
        elseif ValidTarget(enemy) and not enemy.dead  and GetDistance(enemy) < 2000 and (EREADY and QREADY) and 2*getDmg("E", enemy, myHero) + getDmg("Q", enemy, myHero,  3) > enemy.health then
            DrawText3D("Q+2E", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
        elseif ValidTarget(enemy) and not enemy.dead  and GetDistance(enemy) < 2000 and (RREADY and QREADY) and getDmg("Q", enemy, myHero) + getDmg("R", enemy, myHero, 3) > enemy.health then
            DrawText3D("Q+R", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
        elseif ValidTarget(enemy) and not enemy.dead  and GetDistance(enemy) < 2000 and  mydmg > enemy.health then
            DrawText3D("All in!", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
        end
    end
end
function OnProcessSpell(unit, spell)
    if not initDone then return end
    if unit == nil then return end 
    if QREADY and Menu.Extras.InterruptQ then
        for idx, ability in ipairs(ToInterrupt) do
            if spell.name == ability.spellName  and unit.team ~= myHero.team and ConvertRangeToCastTime(GetDistance(unit)) < spell.windupTime + spell.animationTime and ValidTarget(unit) and GetDistance(unit) < qmaxRange then
                InterruptTarget = unit
                if not isPressedQ then
                    CastSpellQ1(unit.x, unit.z)
                    local DelayActionTime = ConvertRangeToCastTime(GetDistance(unit))
                    DelayAction(function () Send2ndQPacket(unit.x, unit.z) end, DelayActionTime+0.02)
                elseif isPressedQ then
                    if GetDistance(unit) > CurrentRange then
                        Send2ndQPacket(unit.x, unit.z)
                    end
                end
            end
        end
    end

    if RREADY and Menu.Extras.InterruptR then
        for _, ability in ipairs(ToInterrupt) do
            if spell.name == ability.spellName and unit.team ~= myHero.team and GetDistance(unit) < rRange and ValidTarget(unit) then
                CastSpell(_R, unit)
            end
        end
    end

    if unit == myHero then
        if spell.name:lower():find("attack") then
            lastAttack = GetTickCount() - GetLatency()/2
            lastWindUpTime = spell.windUpTime*1000
            lastAttackCD = spell.animationTime*1000
        end
    end

    if unit.isMe and spell.name == 'ViE' and IsSowLoaded then
        SOWi:resetAA()
    end
end

function GetBestQPositionFarm()
    local MaxQ = 0 
    local MaxQPos 
    for i, minion in pairs(EnemyMinions.objects) do
        local hitQ = countminionshitQ(minion)
        if hitQ > MaxQ or MaxQPos == nil then
            MaxQPos = minion
            MaxQ = hitQ
        end
    end

    if MaxQPos then
        return MaxQPos
    else
        return nil
    end
end

function GetBestQPositionFarmJungle()
    local MaxQ = 0 
    local MaxQPos 
    for i, minion in pairs(JungleMinions.objects) do
        local hitQ = countminionshitQJungle(minion)
        if hitQ > MaxQ or MaxQPos == nil then
            MaxQPos = minion
            MaxQ = hitQ
        end
    end

    if MaxQPos then
        return MaxQPos
    else
        return nil
    end
end

function countminionshitQJungle(pos)
    local n = 0
    local ExtendedVector = Vector(myHero) + Vector(Vector(pos) - Vector(myHero)):normalized()*qmaxRange
    local EndPoint = Vector(myHero) + ExtendedVector
    for i, minion in ipairs(JungleMinions.objects) do
        local MinionPointSegment, MinionPointLine, MinionIsOnSegment =  VectorPointProjectionOnLineSegment(Vector(myHero), Vector(EndPoint), Vector(minion)) 
        local MinionPointSegment3D = {x=MinionPointSegment.x, y=pos.y, z=MinionPointSegment.y}
        if MinionIsOnSegment and GetDistance(MinionPointSegment3D, pos) < qWidth then
            n = n +1
            -- if Config.Extras.Debug then
            --  print('count minions W returend ' .. tostring(n))
            -- end
        end
    end
    return n
end

function countminionshitQ(pos)
    local n = 0
    local ExtendedVector = Vector(myHero) + Vector(Vector(pos) - Vector(myHero)):normalized()*qmaxRange
    local EndPoint = Vector(myHero) + ExtendedVector
    for i, minion in ipairs(EnemyMinions.objects) do
        local MinionPointSegment, MinionPointLine, MinionIsOnSegment =  VectorPointProjectionOnLineSegment(Vector(myHero), Vector(EndPoint), Vector(minion)) 
        local MinionPointSegment3D = {x=MinionPointSegment.x, y=pos.y, z=MinionPointSegment.y}
        if MinionIsOnSegment and GetDistance(MinionPointSegment3D, pos) < qWidth then
            n = n +1
            -- if Config.Extras.Debug then
            --  print('count minions W returend ' .. tostring(n))
            -- end
        end
    end
    return n
end

function mainLoad()
    EnemyMinions = minionManager(MINION_ENEMY, 750, myHero, MINION_SORT_MAXHEALTH_DEC)
    JungleMinions = minionManager(MINION_JUNGLE, 750, myHero, MINION_SORT_MAXHEALTH_DEC)
    min_hit_chance = 1
    qtotalcasttime = 400 --not sure about this value yet
    qmaxRange = 715
    qminRange = 250
    qWidth = 55
    qSpeed = 1500
    eRange = 300
    rRange = 800
    QREADY = false
    EREADY = false
    RREADY = false
    isPressedQ = false
    Qcasttime = 0 
    Qstartcasttime = 0 
    Qbeingcast = false
    Qshouldcast = false
    invisibleTime   = 300
    Qstarttime = 0
    buffer = 0
    qTick = 0
    ignite = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
    PrintChat("Vixen Vi Loaded!")
    initDone = true
end

function checkOrbwalker()
    if _G.MMA_Loaded ~= nil and _G.MMA_Loaded then
        IsMMALoaded = true
        print('MMA detected, using MMA compatibility')
    elseif _G.AutoCarry then
        IsSACLoaded = true
        print('SAC detected, using SAC compatibility')
    elseif FileExist(LIB_PATH .."SOW.lua") then
        require "SOW"
        SOWi = SOW(VP)
        IsSowLoaded = true
        SOWi:RegisterAfterAttackCallback(AutoAttackReset)
        print('SOW loaded, using SOW compatibility')
    else
        print('Please use SAC, MMA, or SOW for your orbwalker')
    end
end

function AutoAttackReset() 
    if Menu.Combo then 
        if ValidTarget(Target) and Target ~= nil and GetDistance(Target) < 375 then
            CastSpell(_E)
        end
    end

    if Menu.JungleFarm then
        JungleMinions:update()
        --local UseQ = Menu.Farm.JungleKey
        
        local minion = JungleMinions.objects[1]
        if minion ~= nil then
            if GetDistance(minion) < 400 and ValidTarget(minion) then
                CastSpell(_E)
            end
        end
    end

    if Menu.Farm then
        EnemyMinions:update()
        --local UseQ = Menu.Farm.JungleKey
        local minion = EnemyMinions.objects[1]
        if minion ~= nil then
            if GetDistance(minion) < 400 and ValidTarget(minion) then
                CastSpell(_E)
            end
        end
    end
end

function CheckHitNumber(Target)
    local numhit = 0
    if Target ~= nil and GetDistance(Target) < rRange and not Target.dead then 
        local PredictedPos, hitchance, _ = VP:GetPredictedPos(Target, 0.25, 1400, myHero, false)
        local Enemies = GetEnemyHeroes()
        for idx, enemy in ipairs(Enemies) do
            if GetDistance(enemy) < 1500 and GetDistance(enemy, Target) < 800 then
                local PredictedPos2, hitchance2, _ = VP:GetPredictedPos(enemy, 0.25, 1400, myHero, false)
                if hitchance >= 1 and hitchance2 >= 1 then
                    local pointSegment, pointLine, isOnSegment = VectorPointProjectionOnLineSegment(Vector(myHero.visionPos), Vector(PredictedPos), Vector(PredictedPos2))
                    if isOnSegment and GetDistance(pointSegment, pointLine) < 100 + VP:GetHitBox(enemy) then 
                        numhit = numhit + 1
                    end
                end
            end
        end
    end
    return numhit
end

function mainMenu()
    Menu = scriptConfig("ExtrinsicVi","ExtrinsicVi")
    ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 750, DAMAGE_PHYSICAL)
    ts.name = "Vi TS"
    Menu:addTS(ts)
    HKC = string.byte("T")
    HKX = string.byte("X")
    HKV = string.byte("C")
    KeyQ = string.byte("Q")
    Menu:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
    Menu:addParam("Farm", "Farm", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('X'))
    Menu:addParam("JungleFarm", "Jungle Farm", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('V'))
    Menu:addParam("manualQ", "Q PREDICTION KEY", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('W'))
    --Menu:addParam("Jungle Farm", "JFarm", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('V'))
    Menu:addSubMenu("Combo options", "ComboSub")
    Menu:addSubMenu("Farm", "FarmSub")
    Menu:addSubMenu("KS", "KS")
    Menu:addSubMenu("Extras Configurations", "Extras")
    Menu:addSubMenu("Draw", "Draw")
    Menu.ComboSub:addParam("useQ", "Use Q in combo", SCRIPT_PARAM_ONOFF, true)
    Menu.ComboSub:addParam("useE", "Use E in combo", SCRIPT_PARAM_ONOFF, true)
    Menu.ComboSub:addParam("useR", "Use Ult in combo", SCRIPT_PARAM_ONOFF, true)
    Menu.ComboSub:addParam("useRfirst", "Begin Combo with Ult", SCRIPT_PARAM_ONOFF, false)
    Menu.ComboSub:addParam("minR", "Minimum R number for Autoult", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
    Menu.KS:addParam("killsteal", "Ult Killsteal", SCRIPT_PARAM_ONOFF, false)
    Menu.Draw:addParam("drawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
    Menu.Draw:addParam("drawR", "Draw R Range", SCRIPT_PARAM_ONOFF, true)
    Menu.FarmSub:addParam("useQ","use Q in jungle clear", SCRIPT_PARAM_ONOFF, true)
    Menu.FarmSub:addParam("useE","use E in jungle clear", SCRIPT_PARAM_ONOFF, true)
    Menu.Extras:addParam("CancelQ","Stop Q!", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('T'))
    Menu.Extras:addParam("InterruptQ", "Use Q to Interrupt Spells!", SCRIPT_PARAM_ONOFF, true) 
    Menu.Extras:addParam("InterruptR", "Use R to Interrupt Spells!", SCRIPT_PARAM_ONOFF, true) 
    Menu.Extras:addParam("qdistance", "Chase with Q distance", SCRIPT_PARAM_SLICE, 150, 0, 715, 150)
    Menu.Extras:addParam("QPredictionHold", "Manual Q charge until release", SCRIPT_PARAM_ONOFF, true)
    if IsSowLoaded then
        Menu:addSubMenu("Orbwalker", "SOWiorb")
        SOWi:LoadToMenu(Menu.SOWiorb)
        Menu.SOWiorb.Mode0 = false
    end
    Menu:permaShow("Combo")
    Menu:permaShow("Farm")
    Menu:permaShow("JungleFarm")
end

function OnGainBuff(unit, buff)
    if unit.isMe and buff.name == 'ViQ' then
        --PrintChat("Gained")
        isPressedQ = true
        Qstartcasttime = os.clock()
    end
    if unit.isMe and buff.type == 5 or buff.type == 7 or buff.type == 11 or buff.type == 21 or buff.type == 24 or buff.type == 30 then
        isPressedQ = false
        Qcasttime = 0
    end
end

function OnLoseBuff(unit, buff)
    if unit.isMe and buff.name == 'ViQ' then
        --PrintChat("Lost")
        isPressedQ  = false
        Qcasttime = 0

    end
end


--[[function OnCreateObj(object)
    if object.name == "Vi_Q_Channel_L.troy" then 
        iPressedQ = true 
        PrintChat("Object Gained")
        Qstarttime = os.clock()
        Time = 0
    end
end]]

function OnDeleteObj(object)
    if object.name == "Vi_Q_Channel_L.troy" then 
        iPressedQ = false 
        Qcasttime = 0
    end
end
    
--Copied from  
function Send2ndQPacket(xpos, zpos)
    --PrintChat("Packet Called!")
    packet = CLoLPacket(0xE5)
    packet:EncodeF(myHero.networkID)
    packet:Encode1(128)
    packet:EncodeF(xpos)
    packet:EncodeF(myHero.y)
    packet:EncodeF(zpos)
    packet.dwArg1 = 1
    packet.dwArg2 = 0
    SendPacket(packet)
    --PrintChat("Packet Sent!")
--nID, spell, x, y, z
end


function OnSendPacket(packet)
    if not initDone then return end
    -- New handler for SAC: Reborn
    local p = Packet(packet)
    --if p:get("spellId") == SkillE.spellKey and not (AutoCarry.MainMenu.AutoCarry or AutoCarry.MainMenu.LaneClear or AutoCarry.MainMenu.MixedMode or AutoCarry.PluginMenu.SlowE) then
        --p:block()
    --end
    if Menu.Extras.CancelQ == false then 
        if packet.header == 0xE5 and Menu.Combo or Menu.Farm or Menu.JungleFarm then --and Cast then -- 2nd cast of channel spells packet2
            packet.pos = 5
            spelltype = packet:Decode1()
            if spelltype == 0x80 then -- 0x80 == Q
                packet.pos = 1
                packet:Block()
                --PrintChat("Packet blocked")
            end
        end


        if packet.header == 0xE5 and Menu.manualQ then --and Cast then -- 2nd cast of channel spells packet2
            packet.pos = 5
            spelltype = packet:Decode1()
            if spelltype == 0x80 then -- 0x80 == Q
                packet.pos = 1
                packet:Block()
            end
        end
    end
end

function Checks()
    if not initDone then return end
    QREADY = (myHero:CanUseSpell(_Q) == READY)
    WREADY = (myHero:CanUseSpell(_W) == READY)
    EREADY = (myHero:CanUseSpell(_E) == READY)
    RREADY = (myHero:CanUseSpell(_R) == READY)
    if not QREADY and not RREADY then
        ts.range = 450
    else
        ts.range = 850
    end
    if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then ignite = SUMMONER_1
    elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then ignite = SUMMONER_2 end
    IGNITEReady = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
    if myHero.dead then Target = nil end
    if IsSowLoaded and ts ~= nil then
        ts:update()
        Target = ts.target
    elseif _G.MMA_Loaded ~= nil and _G.MMA_Loaded then
        Target = _G.MMA_Target
    elseif _G.AutoCarry ~= nil and _G.AutoCarry then
        Target = _G.AutoCarry.Crosshair:GetTarget()
    end
    return Target
end

load(Base64Decode("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"), nil, "b", _ENV)()