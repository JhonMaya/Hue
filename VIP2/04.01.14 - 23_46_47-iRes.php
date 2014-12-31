<?php exit() ?>--by iRes 92.30.36.27
 local ts = nil
local Target = false
local Prodict = nil
local PredW, PredQ = nil, nil
local DeathfireGraspBought, BlackFireTorch = false, false
local Q, W, E, R = false, false, false, false -- Variables for if Skill ready or not (True/False)
local IgniteSlot = nil
local IgniteDmg = nil
local IgniteReady = false
local lastAttack, lastWindUpTime, lastAttackCD = 0, 0, 0
 
function OnLoad()
        Prodict = ProdictManager.GetInstance()
        PredictionW()
        PredictionQ()
    menu()
        getIgniteSlot()
        ts = TargetSelector(TARGET_LESS_CAST, 900,DAMAGE_MAGIC)
        ts.name = "Brand"
        BN:addTS(ts)
        QCol = Collision(1000, 1600, 0.625, 90)
        PrintChat("Team GermanAutoWin's Brand loaded.")
end
 
function OnTick()
        ts:update()
        CheckSpells()
        checkDeathFireGrasp()
        EverythingCombined()
        AutoStunIfClose()
        KillStealE()
        KillStealW()
        KillStealQ()
        AutoHarrasWPerm()
        AutoHarrasEPerm()
        AutoHarrasQPerm()
end
 
-- functions for Orbwalk Ingame
 
function EverythingCombined()
        if BN.GSettings.HarassToggle then
        if BN.OSettings.Orby then
            OrbWalk()
        elseif BN.OSettings.movetoMouse then
            moveToCursor()
        end
                           
        if ValidTarget(ts.target) and ts.target ~= nil then
            Harass()
        end
    end
                   
    if BN.GSettings.ComboToggle then
        if BN.OSettings.Orby then
            OrbWalk()
        elseif BN.OSettings.ToMouse then
            moveToCursor()
        end
                           
        if ValidTarget(ts.target) and ts.target ~= nil then
            ComboNTf()
        end
    end
 
end
 
function checkDeathFireGrasp() --Check if DFG bought and if Ready--
    DeathfireGraspBought = GetInventorySlotItem(3128)
    DFGREADY = GetInventoryItemIsCastable(3128,myHero)
end
 
-- Menu Stuff
 
function menu()
        BN = scriptConfig ("TeamGermanAutoWinBrand", "NapalmBomb")
        BN:addSubMenu("General Settings", "GSettings")
        BN.GSettings:addParam("PermHarassW", "AutoHarass W if in range", SCRIPT_PARAM_ONOFF, false)
        BN.GSettings:addParam("PermHarassE", "AutoHarass E if in range", SCRIPT_PARAM_ONOFF, false)
        BN.GSettings:addParam("PermHarassQ", "AutoHarass Q if in range and burning", SCRIPT_PARAM_ONOFF, false)
        BN.GSettings:addParam("HarassToggle", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
        BN.GSettings:addParam("ComboToggle", "Combo no teamfight", SCRIPT_PARAM_ONKEYDOWN, false, 32)
        BN.GSettings:addParam("LifeSaver", "Autostun if enemy to close", SCRIPT_PARAM_ONOFF, true)
        BN:addSubMenu("Killsteal Settings", "KSettings")
        BN.KSettings:addParam("AutoKSE", "KS WITH E", SCRIPT_PARAM_ONOFF, true)
        BN.KSettings:addParam("AutoKSQ", "KS WITH Q", SCRIPT_PARAM_ONOFF, true)
        BN.KSettings:addParam("AutoKSW", "KS WITH W", SCRIPT_PARAM_ONOFF, true)
        BN:addSubMenu("Draw Settings", "DSettings")
        BN.DSettings:addParam("DrawStuff", "Drawings ", SCRIPT_PARAM_ONOFF, true)
        BN.DSettings:addParam("DrawQ", "Draw Q range if ready ", SCRIPT_PARAM_ONOFF, true)
        BN.DSettings:addParam("DrawW", "Draw W range if ready ", SCRIPT_PARAM_ONOFF, true)
        BN.DSettings:addParam("DrawE", "Draw E range if ready ", SCRIPT_PARAM_ONOFF, true)
        BN.DSettings:addParam("DrawR", "Draw R range if ready ", SCRIPT_PARAM_ONOFF, true)
        BN:addSubMenu("Orbwalk Settings", "OSettings")
    BN.OSettings:addParam("ToMouse", "MoveToMouse", SCRIPT_PARAM_ONKEYDOWN, false, 32)
        BN.OSettings:addParam("Orby", "Orbwalk", SCRIPT_PARAM_ONOFF, true)
end
 
 
function OnDraw()
        DrawQ()
        DrawW()
        DrawE()
        DrawR()
        DrawComboKillerOrHarass()
end
 
--PredictionStuff
function PredictionW()
                PredW = Prodict:AddProdictionObject(_W, 900, 900, 0.250, wWidth, myHero)
end
 
function PredictionQ()
                PredQ = Prodict:AddProdictionObject(_Q, 1000, 1600, 0.250, 0, myHero)
end
 
-- Auto Harass
function AutoHarrasWPerm() -- Auto E when enemy in range
    if (BN.GSettings.PermHarassW and ValidTarget(ts.target, 900)) and W then
                local predictPos,_ = PredW:GetPrediction(ts.target)
        CastSpell(_W, predictPos.x, predictPos.z)
    end    
end
 
function AutoHarrasEPerm() -- Auto E when enemy in range
    if (BN.GSettings.PermHarassE and ValidTarget(ts.target, 625)) and E then
        CastSpell(_E, ts.target)
    end    
end
 
function AutoHarrasQPerm() -- Auto Q when enemy in range
    if (BN.GSettings.PermHarassQ and ValidTarget(ts.target, 1000)) and GetDistance(ts.target) <= 1000 and not QCol:GetMinionCollision(myHero, ts.target) and Q then
                        local predictPos,_ = PredQ:GetPrediction(ts.target)
                        CastSpell(_Q, predictPos.x, predictPos.z)
    end    
end
 
-- Harass Function
function Harass()
        if BN.GSettings.HarassToggle and ValidTarget(ts.target, 900) then
                if  GetDistance(ts.target) <= 625 then
                        CastSpell(_E, ts.target)
                end
                if  GetDistance(ts.target) <= 900 then
                        local predictPos,_ = PredW:GetPrediction(ts.target)
                        CastSpell(_W, predictPos.x, predictPos.z)
                end
                if  GetDistance(ts.target) <= 1000 and not QCol:GetMinionCollision(myHero, ts.target) and Q then
                        local predictPos,_ = PredQ:GetPrediction(ts.target)
                        CastSpell(_Q, predictPos.x, predictPos.z)
                end
        end
end
--StunCombos
 
function AutoStunIfClose()
        for i, killTarget in pairs(GetEnemyHeroes()) do
        if (GetDistance(killTarget) <= 400) and ValidTarget(killTarget) and not QCol:GetMinionCollision(myHero, ts.target) and BN.GSettings.LifeSaver and E and Q and not BN.ComboToggle then
             CastSpell(_E, killTarget)    
                        local predictPos,_ = PredQ:GetPrediction(killTarget)
                         CastSpell(_Q, predictPos.x, predictPos.z)  
        end
    end
end
 
 
--KillSteals
 
function KillStealE()
        for i, killTarget in pairs(GetEnemyHeroes()) do
        if (GetDistance(killTarget) <= 625) and ValidTarget(killTarget) and (killTarget.health < getDmg("E", killTarget, myHero)) and BN.KSettings.AutoKSE then
             CastSpell(_E, killTarget)                      
        end
    end
end
 
function KillStealW()
        for i, killTarget in pairs(GetEnemyHeroes()) do
        if (GetDistance(killTarget) <= 900) and ValidTarget(killTarget) and (killTarget.health < getDmg("W", killTarget, myHero)) and BN.KSettings.AutoKSW then
             local predictPos,_ = PredW:GetPrediction(ts.target)
                         CastSpell(_W, predictPos.x, predictPos.z)            
        end
    end
end
 
function KillStealQ()
        for i, killTarget in pairs(GetEnemyHeroes()) do
        if (GetDistance(killTarget) <= 1000) and ValidTarget(killTarget) and (killTarget.health < getDmg("Q", killTarget, myHero)) and BN.KSettings.AutoKSQ and not QCol:GetMinionCollision(myHero, ts.target) and Q  then
             local predictPos,_ = PredQ:GetPrediction(ts.target)
                         CastSpell(_Q, predictPos.x, predictPos.z)                  
        end
    end
end
 
--ComboNoTeamfight
function ComboNTf()
        if  GetDistance(ts.target) <= 750 and DeathfireGraspBought ~= nil and GetInventoryItemIsCastable(3128,myHero) and BN.GSettings.ComboToggle then
        CastSpell(DeathfireGraspBought, ts.target)
        end
        if BN.GSettings.ComboToggle and ValidTarget(ts.target, 900) then
                if  GetDistance(ts.target) <= 625 then
                        CastSpell(_E, ts.target)
                end
                if  GetDistance(ts.target) <= 900 then
                        local predictPos,_ = PredW:GetPrediction(ts.target)
                        CastSpell(_W, predictPos.x, predictPos.z)
                end
                if  GetDistance(ts.target) <= 1000 and not QCol:GetMinionCollision(myHero, ts.target) and Q then
                        local predictPos,_ = PredQ:GetPrediction(ts.target)
                        CastSpell(_Q, predictPos.x, predictPos.z)
                end
                if  GetDistance(ts.target) <= 750 then
                        CastSpell(_R, ts.target)
                end
        end
end
 
 
function CheckSpells() -- Updates Skills--
            Q = (myHero:CanUseSpell(_Q) == READY)
            W = (myHero:CanUseSpell(_W) == READY)
            E = (myHero:CanUseSpell(_E) == READY)
            R = (myHero:CanUseSpell(_R) == READY)
            IgniteReady = (IgniteSlot ~= nil) and (myHero:CanUseSpell(IgniteSlot) == READY)
end
 
function getIgniteSlot()
            IgniteSlot = ((myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") and SUMMONER_1) or (myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") and SUMMONER_2) or nil)
end
 
 
 
-- Orbwalshiat
function timeToShoot()
    return (GetTickCount() + GetLatency()/2 > lastAttack + lastAttackCD)
end
     
function moveToCursor()
     myHero:MoveTo(mousePos.x,mousePos.z)
end
     
function heroCanMove()
    return (GetTickCount() + GetLatency()/2 > lastAttack + lastWindUpTime + 20)
end
     
function OrbWalk()
    if ValidTarget(ts.target) and GetDistance(ts.target) <= 500 then
        if timeToShoot()  then
             myHero:Attack(ts.target)
        elseif heroCanMove()  then
            moveToCursor()
        end
        else
            moveToCursor()        
        end
     
end
 
--callbacks
function OnProcessSpell(object,spell)
    if object == myHero then
        if spell.name:lower():find("attack") then
            lastAttack = GetTickCount() - GetLatency()/2
            lastWindUpTime = spell.windUpTime*1000
            lastAttackCD = spell.animationTime*1000
        end
           
    end
end
 
 
 
-- Drawings
function DrawQ()
            if BN.DSettings.DrawStuff and BN.DrawQ and Q then
                    DrawCircle(myHero.x, myHero.y, myHero.z, 1100, 0xCC9999)
            end
end
 
function DrawW()
            if BN.DSettings.DrawStuff and BN.DrawW and W then
                    DrawCircle(myHero.x, myHero.y, myHero.z, 900, 0xCC9999)
            end
end
     
function DrawE()
            if BN.DSettings.DrawStuff and BN.DrawE and E then
                    DrawCircle(myHero.x, myHero.y, myHero.z, 625, 0x9900FF)
            end
end
     
function DrawR()
            if BN.DSettings.DrawStuff and BN.DrawR and R then
                    DrawCircle(myHero.x, myHero.y, myHero.z, 750, 0xFF3300)
            end
end
 
 
function DrawComboKillerOrHarass()     
     for i=1, heroManager.iCount do
                local enemy = heroManager:GetHero(i)
                        if ValidTarget(enemy) then
                                local EnemyDrawPos = WorldToScreen(D3DXVECTOR3(enemy.x, enemy.y, enemy.z))
                                local PosX = EnemyDrawPos.x - 35
                                local PosY = EnemyDrawPos.y - 50
                                                local DrawHim = heroManager:GetHero(i)
                                                local GlobalQ = getDmg("Q",DrawHim, myHero)
                                                local GlobalW = getDmg("W",DrawHim, myHero)
                                                local GlobalE = getDmg("E",DrawHim, myHero)
                                                local GlobalR = getDmg("R",DrawHim, myHero)    
                                                local GlobalIgnite = getDmg("IGNITE", DrawHim, myHero)
                                if (DrawHim).health < (GlobalQ + GlobalW + GlobalR + GlobalE+GlobalIgnite) and W and R and E and Q and IgniteReady then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                elseif (DrawHim).health < (GlobalQ + GlobalW + GlobalR + GlobalE) and W and R and E and Q then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                elseif ((DrawHim).health < (GlobalQ + GlobalW + GlobalE+GlobalIgnite) and E and W and Q and IgniteReady ) or ((DrawHim).health < ( GlobalQ + GlobalW + GlobalR + GlobalIgnite) and W and R and Q and IgniteReady) then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                elseif ((DrawHim).health < (GlobalQ + GlobalW + GlobalE) and E and W and Q) or ((DrawHim).health < (GlobalQ + GlobalW + GlobalR) and W and Q and R) then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                elseif ((DrawHim).health < (GlobalQ + GlobalW+GlobalIgnite) and W and IgniteReady ) then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                elseif ((DrawHim).health < (GlobalW) and W ) or ((DrawHim).health < (GlobalE)and E) or ((DrawHim).health < (GlobalR) and  R) or ((DrawHim).health < (GlobalQ) and  Q) then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                elseif (DrawHim).health < (GlobalW + GlobalR + GlobalE+GlobalIgnite) and W and R and E and IgniteReady then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                elseif (DrawHim).health < (GlobalW + GlobalR + GlobalE) and W and R and E then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                elseif ((DrawHim).health < (GlobalW + GlobalE+GlobalIgnite) and E and W and IgniteReady ) or ((DrawHim).health < (GlobalW + GlobalR + GlobalIgnite) and W and R and IgniteReady) then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                elseif ((DrawHim).health < (GlobalW + GlobalE) and E and W ) or ((DrawHim).health < (GlobalW + GlobalR) and W and  R) then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                elseif ((DrawHim).health < (GlobalW+GlobalIgnite) and W and IgniteReady ) then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                elseif ((DrawHim).health < (GlobalW) and W ) or ((DrawHim).health < (GlobalE)and E) or ((DrawHim).health < (GlobalR) and  R) or ((DrawHim).health < (GlobalQ) and  Q) then
                                        DrawText("COMBOKILL",25,PosX ,PosY ,ARGB(255, 255, 000, 000))
                                else
                                        DrawText("HARRAS ME",20,PosX ,PosY ,ARGB(255, 128, 128, 128))
                        end
        end
    end
end