<?php exit() ?>--by iRes 141.101.99.197
     TEXT BELOW IS SELECTED. PLEASE PRESS CTRL+C TO COPY TO YOUR CLIPBOARD. (&#8984;+C ON MAC)
if myHero.charName ~= "Ryze" or not VIP_USER then return end
 
local Q = {
        Range = 600,
        Damage = 25 * GetSpellData(_Q).level + 35 + .4 * myHero.ap + .065 * myHero.maxMana,
        BonusCR = 2 * GetSpellData(_Q).level, --2 4 6 8 10
        CastTime = 265,
        Cooldown = 3500 * (1 + myHero.cdr),
        Level = 0
}
local W = {
        Range = 600,
        Damage = 35 * GetSpellData(_W).level + 25 + .6 * myHero.ap + .045 * myHero.maxMana,
        RootDuration = .5 + .25 * GetSpellData(_W).level, -- 0,75 1 1,25 1,5 1,75
        CastTime = 250,
        Cooldown = 14000 * (1 + myHero.cdr),
        Level = 0
       
}
local E = {
        Range = 600,
        Damage = 20 * GetSpellData(_E).level + 30 + .35 * myHero.ap + .01 * myHero.maxMana,
        Bounces = 5,
        MResDebuff = 9 + 3 * GetSpellData(_E).level, --12 15 18 21 24
        CastTime = 280,
        Cooldown = 14000 * (1 + myHero.cdr),
        Level = 0
}
local R = {
        Duration = 4 + GetSpellData(_R).level,
        spellVamp = .10 + GetSpellData(_R).level * .05,
        MovementSpacketed = 80,
        Splash = .5,
        SplashRange = 200,
        Level = 0
}
 
local Ignite = {
        Damage = 50 + 20 * myHero.level,
        Slot = nil
}
 
local enemies = {}
 
local activeItems = {
        DFG = 3128,
        HXG = 3146,
        BWC = 3144,
        SHEEN = 3057,
        TRINITY = 3078,
        ICEBORN = 3025,
        LICHBANE = 3100,
        MURAMANA = 3042,
}
 
 
function OnLoad()
        PrintChat("<font color='#00FFEE'>>> Legendary Ryze loaded</font>")
       
        --Config
        Config = scriptConfig("Legendary Ryze: Basic Settings", "Config")
        Config:addParam("Harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, 84)
        Config:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
        Config:addParam("Farm", "Farm", SCRIPT_PARAM_ONKEYTOGGLE, false, 73)
        Config:addParam("SafeDistance", "Distance to feel safe", SCRIPT_PARAM_SLICE, 400, 0, Q.Range, 0)
        Config:addParam("Ignite", "Automatically use ignite", SCRIPT_PARAM_ONOFF, false)
        Config:addParam("Ult", "Use ult in combo", SCRIPT_PARAM_ONOFF, false)
        Config:addParam("SingleUlt", "Use ult against a single target", SCRIPT_PARAM_ONOFF, false)
        Config:addParam("stutter", "Stutterwalk", SCRIPT_PARAM_ONOFF, false)
        Config:permaShow("Ignite")
        Config:permaShow("Harass")
        Config:permaShow("Combo")
        Config:permaShow("Farm")
       
        MTM = scriptConfig("Legendary Ryze: Move to Mouse Settings", "Items")
        MTM:addParam("MTMHarass", "Move to Mouse while harassing", SCRIPT_PARAM_ONOFF, false)
        MTM:addParam("MTMCombo", "Move to Mouse while using Combo", SCRIPT_PARAM_ONOFF, false)
       
        Items = scriptConfig("Legendary Ryze: Item Settings", "Items")
        Items:addParam("qfarm", "Stack Tear/QFarm", SCRIPT_PARAM_ONOFF, false)
        Items:addParam("lb", "Lich Bane/Trinity Force/Iceborn Gauntlet", SCRIPT_PARAM_ONOFF, true)
        Items:addParam("dfg", "Deathfire Grasp", SCRIPT_PARAM_ONOFF, false)
        Items:addParam("hxg", "Hextech Gunblade/Bilgewater Cutlass", SCRIPT_PARAM_ONOFF, false)
        Items:addParam("muramana", "Muramana", SCRIPT_PARAM_ONOFF, false)
        Items:addParam("minmanamura", "Min Mana Muramana", SCRIPT_PARAM_SLICE, 25, 0, 100, 2)
       
        Exploits = scriptConfig("Legendary Ryze: Exploits", "Exploits")
        Exploits:addParam("NFD", "No Face-Direction", SCRIPT_PARAM_ONOFF, true)
        Exploits:addParam("PassiveExploit", "Passive Exploit", SCRIPT_PARAM_ONOFF, true)
       
        Drawing = scriptConfig("Legendary Ryze: Drawing", "Drawing")
        Drawing:addParam("DrawAARange", "Draw AA Range", SCRIPT_PARAM_ONOFF, true)
        Drawing:addParam("DrawSkillRange", "Draw Skill Range", SCRIPT_PARAM_ONOFF, true)
        Drawing:addParam("DrawSplashRange", "Draw R Splash Range", SCRIPT_PARAM_ONOFF, true)
        Drawing:addParam("DrawAA", "Draw Autoattacks", SCRIPT_PARAM_ONOFF, true)
        Drawing:addParam("DrawAATarget", "Draw Autoattack Target", SCRIPT_PARAM_ONOFF, true)
        Drawing:addParam("DrawTarget", "Draw Target", SCRIPT_PARAM_ONOFF, true)
        Drawing:addParam("DrawText", "Draw Text", SCRIPT_PARAM_ONOFF, true)
       
        --Target Selector
        ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, Q.Range, DAMAGE_MAGIC, false)
        ts.name = "Ryze"
        Config:addTS(ts)
       
        --get enemies
        for i = 1, heroManager.iCount do
                local hero = heroManager:getHero(i)
                if hero.team ~= myHero.team then
                        enemies[i] = {champ = hero.charName, timetokill = 0, count = i}
                end
        end
       
        --get ignite
        if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
                Ignite.Slot = SUMMONER_1
        elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
                Ignite.Slot = SUMMONER_2
        else
                Ignite.Slot = nil
        end    
end
       
function OnUnload()
        PrintChat("<font color='#FFAA00'>>> Legendary Ryze unloaded</font>")
end
 
_CastSpell = CastSpell
function CastSpell(spell)
        if spell ~= _R then
                if ts.target ~= nil then
                        CastSpell(spell, ts.target)
                end
        else
                _CastSpell(_R)
        end
end
function CastSpell(spell, target)
        if myHero:CanUseSpell(spell) then
                if Exploits.NFD then
                        packet = CLoLPacket(0x9A)
                                packet:EncodeF(myHero.networkID)
                                packet:Encode1(spell)
                                packet:EncodeF(myHero.x)
                                packet:EncodeF(myHero.z)
                                packet:EncodeF(0)
                                packet:EncodeF(0)
                                if target ~= nil and target.networkID ~= nil then
                                        packet:EncodeF(target.networkID)
                                end
                                packet.dwArg1 = 1
                                packet.dwArg2 = 0
                        SendPacket(packet)
                else
                        _CastSpell(spell, target)
                end
        end
        if Config.stutter then
                myHero:MoveTo(mousePos.x, mousePos.z)
        end
end
 
function OnProcessSpell(Object, Spell)
        if Object.isMe then
                attackTarget = Spell.target
                if Spell.name:find("Attack") then
                        aaNWID = Spell.projectileID
                elseif Spell.name == GetSpellData(_Q).name then
                        qNWID = Spell.projectileID
                end
        end
end
 
function UpdateSpells()
        Q.Level = GetSpellData(_Q).level
        Q.Damage = 25 * Q.Level + 35 + .4 * myHero.ap + .065 * myHero.maxMana
        Q.BonusCR = 2 * Q.Level
        Q.Cooldown = 3500 * (1 + myHero.cdr)
       
        W.Level = GetSpellData(_W).level
        W.Damage = 35 * GetSpellData(_W).level + 25 + .6 * myHero.ap + .045 * myHero.maxMana
        W.RootDuration = .5 + .25 * GetSpellData(_W).level
        W.Cooldown = 14000 * (1 + myHero.cdr)
       
        E.Level = GetSpellData(_E).level
        E.Damage = 20 * E.Level + 30 + .35 * myHero.ap + .01 * myHero.maxMana
        E.MResDebuff = 9 + 3 * E.Level
        E.Cooldown = 14000 * (1 + myHero.cdr)
       
        R.Level = GetSpellData(_R).level
        R.Duration = 4 + R.Level
        R.spellVamp = .10 + R.Level * .05
       
        Ignite.Damage = 50 + 20 * myHero.level
end
 
function underTurret()
        for _, turret in pairs(GetTurrets()) do
                if turret.team == player.team then
                        if GetDistance(ts.target, turret) < 950 then
                                return true
                        end
                end
        end
        return false
end
 
--dot qwqeq 20%+cr
function DoT()
        if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
        if myHero:CanUseSpell(_W) == READY then CastSpell(_W, ts.target) end
        if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
        if myHero:CanUseSpell(_E) == READY then CastSpell(_E, ts.target) end
        if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
end
 
--burst qweq
function Burst()
        if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
        if myHero:CanUseSpell(_W) == READY then CastSpell(_W, ts.target) end
        if myHero:CanUseSpell(_E) == READY then CastSpell(_E, ts.target) end
        if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
end
function BurstDamage()
        QDmg = getDmg("Q", ts.target, myHero, 3)
        WDmg = getDmg("W", ts.target, myHero, 3)
        EDmg = getDmg("E", ts.target, myHero, 3)
        return (QDmg + WDmg + EDmg + QDmg)
end
 
--fleeing wqeq
function FleeingCombo()
        if myHero:CanUseSpell(_W) == READY then CastSpell(_W, ts.target) end
        if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
        if myHero:CanUseSpell(_E) == READY then CastSpell(_E, ts.target) end
        if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
end
 
function CountEnemys(range)
        local enemyInRange = 0
    for i = 1, heroManager.iCount, 1 do
        local hero = heroManager:getHero(i)
        if GetDistance(ts.target, hero) <= range then
            enemyInRange = enemyInRange + 1
        end
    end
    return enemyInRange
end
 
function PassiveExploit()
        if aaNWID and objManager:GetObjectByNetworkId(aaNWID) then
                aaObject = objManager:GetObjectByNetworkId(aaNWID)
                aaNWID = nil
        end
        if qNWID and objManager:GetObjectByNetworkId(qNWID) then
                qObject = objManager:GetObjectByNetworkId(qNWID)
                qNWID = nil
        end
        if attackTarget and attackTarget.valid then
                if aaObject and aaObject.valid and GetDistance(attackTarget, aaObject) < 300  and attackTarget.health < myHero.totalDamage then
                        CastSpell(_Q, attackTarget)
                        CastSpell(_W, attackTarget)
                end
                if qObject and qObject.valid and GetDistance(attackTarget, qObject) < 200 and attackTarget.health < Q.Damage then
                        CastSpell(_E, attackTarget)
                        CastSpell(_W, attackTarget)
                        qObject = nil
                        attackTarget = nil
                end    
        end
end
 
function UpdateTimers()
        if enemies ~= nil then
                for _, enemy in ipairs(enemies) do
                        local hero = heroManager:getHero(enemy.count)
                        if not hero.dead and hero.visible and hero.health and hero ~= nil then
                                QDmg = getDmg("Q", hero, myHero, 3)
                                WDmg = getDmg("W", hero, myHero, 3)
                                EDmg = getDmg("E", hero, myHero, 3)
                                if hero.health <= QDmg then
                                        enemy.timetokill = Q.CastTime
                                else
                                        if myHero:CanUseSpell(_W) == READY then
                                                if hero.health <= WDmg then
                                                        enemy.timetokill = W.CastTime
                                                elseif hero.health <= QDmg + WDmg then
                                                        enemy.timetokill = Q.CastTime + W.CastTime
                                                end
                                                if myHero:CanUseSpell(_E) == READY then
                                                        if hero.health <= QDmg + WDmg then
                                                                enemy.timetokill = Q.CastTime + W.CastTime
                                                        elseif hero.health <= QDmg + WDmg + EDmg then
                                                                enemy.timetokill = Q.CastTime + W.CastTime + E.CastTime
                                                        elseif hero.health <= (QDmg + WDmg + EDmg + QDmg) then
                                                                enemy.timetokill = Q.CastTime + W.CastTime + E.CastTime + Q.Cooldown - 2000 + Q.CastTime
                                                        elseif hero.health <= (QDmg + WDmg + QDmg + EDmg + QDmg) then
                                                                enemy.timetokill = Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime + E.CastTime + Q.Cooldown - 1000 + Q.CastTime
                                                        else
                                                                enemy.timetokill = (Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime + E.CastTime + Q.Cooldown - 1000 + Q.CastTime) * math.floor(hero.health/(QDmg + WDmg + QDmg + EDmg + QDmg))
                                                        end
                                                else
                                                        if hero.health <= QDmg + WDmg then
                                                                enemy.timetokill = Q.CastTime + W.CastTime
                                                        elseif hero.health <= QDmg + WDmg + QDmg then
                                                                enemy.timetokill = Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime
                                                        else
                                                                enemy.timetokill = (Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime) + ((Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime + E.CastTime + Q.Cooldown - 1000 + Q.CastTime) * math.floor(hero.health/(QDmg + WDmg + QDmg + EDmg + QDmg)))
                                                        end
                                                end
                                        else
                                                enemy.timetokill = (Q.CastTime + E.CastTime + Q.Cooldown - 1000 + Q.CastTime) + ((Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime + E.CastTime + Q.Cooldown - 1000 + Q.CastTime) * math.floor(hero.health/(QDmg + WDmg + QDmg + EDmg + QDmg)))
                                        end
                                end
                        end
                end
        end
end
 
function Harass()
        if MTM.MTMHarass then
                myHero:MoveTo(mousePos.x, mousePos.z)
        end
        if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
end
 
function getBestCombo()
        if (CountEnemys(R.SplashRange)  >= 2 and Config.Ult) or Config.SingleUlt then
                if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
                if myHero:CanUseSpell(_R) == READY then CastSpell(_R) end
        end
        if GetDistance(ts.target, myHero) > Config.SafeDistance and not underTurret() then
                if (myHero.cdr * -1) > 0.2 and ts.target.health > BurstDamage() then
                        DoT()
                else
                        Burst()
                end
        else
                FleeingCombo()
        end
end
 
function autoIgnite()
        for _, enemy in ipairs(enemies) do
                local hero = heroManager:getHero(enemy.count)
                if not hero.dead and hero.visible and hero.health and GetDistance(myHero, hero) <= 600 and ValidTarget(hero) then
                        if hero.health <= Ignite.Damage then
                                CastSpell(Ignite.Slot, hero)
                        end
                end
        end
end
 
function useActiveItems()
        if ts.target ~= nil then
                if Items.dfg and GetInventorySlotItem(activeItems.DFG) ~= nil then
                        CastSpell(GetInventorySlotItem(activeItems.DFG), ts.target)
                end
                if Items.hxg then
                        if GetInventorySlotItem(activeItems.HXG) ~= nil then
                                CastSpell(GetInventorySlotItem(activeItems.HXG), ts.target)
                        elseif GetInventorySlotItem(activeItems.BWC) ~= nil then
                                CastSpell(GetInventorySlotItem(activeItems.BWC), ts.target)
                        end
                end
        end
end
 
function useActiveProc()
        if ts.target ~= nil then
                if Items.lb and (GetInventorySlotItem(activeItems.LICHBANE) ~= nil or GetInventorySlotItem(activeItems.SHEEN) ~= nil or GetInventorySlotItem(activeItems.ICEBORN) or GetInventorySlotItem(activeItems.TRINITY) ~= nil) then
                        if GetDistance(ts.target, myHero) <= (myHero.range + GetDistance(myHero.minBBox)-10) then
                                myHero:Attack(ts.target)
                        end
                end
                if Items.muramana and GetInventorySlotItem(activeItems.MURAMANA) ~= nil then
                        MuramanaToggle(1000, ((myHero.mana / myHero.maxMana) > (Items.minmanamura / 100)))
                end
        end
end
 
function QFarm()
        if myHero:CanUseSpell(_Q) == READY then
                for _, minion in ipairs(minionManager(MINION_ENEMY, 600 , myHero, MINION_SORT_HEALTH_ASC).objects) do
                        if ValidTarget(minion, QRange) and minion.health < getDmg("Q", minion, myHero) then
                                CastSpell(_Q, minion)
                        end
                end
        end
end
 
function Farm()
        for _, minion in ipairs(minionManager(MINION_ENEMY, 600 , myHero, MINION_SORT_HEALTH_ASC).objects) do
                if ValidTarget(minion, QRange) then
                        if myHero:CanUseSpell(_Q) == READY and minion.health < getDmg("Q", minion, myHero) then
                                CastSpell(_Q, minion)
                        elseif myHero:CanUseSpell(_W) == READY and minion.health < getDmg("W", minion, myHero) then
                                CastSpell(_W, minion)
                        elseif myHero:CanUseSpell(_E) == READY and minion.health < getDmg("E", minion, myHero) then
                                CastSpell(_E, minion)
                        end
                end
        end
end
 
function OnTick()
        if not myHero.dead then
                ts:update()    
                UpdateSpells()
                if Exploits.PassiveExploit then
                        PassiveExploit()
                end
               
                UpdateTimers()
                if ts.target ~= nil then
                        if Config.Harass then
                                Harass()
                                useActiveProc()
                        elseif Config.Combo then
                                useActiveItems()
                                getBestCombo()
                                if MTM.MTMCombo then
                                        myHero:MoveTo(mousePos.x, mousePos.z)
                                end
                                useActiveProc()
                        end
                end
                if Config.Ignite then
                        autoIgnite()
                end
                if Items.qfarm then
                        QFarm()
                end
                if Config.Farm then
                        Farm()
                end
        end
end
 
function OnDraw()
        if Drawing.DrawText then
                for _, enemy in ipairs(enemies) do
                        local hero = heroManager:getHero(enemy.count)
                        if hero.visible and not hero.dead then
                                herodrawx, herodrawy = get2DFrom3D(hero.x, hero.y, hero.z)
                       
                                if hero ~= nil and enemy.timetokill ~= 0 then
                                        timer = string.format("%.1f", (enemy.timetokill)/1000)
                                        DrawText(timer .. "s", 16, herodrawx, herodrawy, 0xFF80FF00)
                                end
                        end
                end
        end
       
        if Drawing.DrawAATarget and attackTarget and attackTarget.valid then
                DrawCircle(attackTarget.x, attackTarget.y, attackTarget.z, 70, 0x000066FF)
        end
       
        if Drawing.DrawAA and aaObject and aaObject.valid then
                DrawCircle(aaObject.x, aaObject.y, aaObject.z, 60, 0xFF3D4F3D)
        end
       
        if ts.target ~= nil then
                if Drawing.DrawTarget then
                        DrawCircle(ts.target.x, ts.target.y, ts.target.z, 70, 0x00FF0000)
                end
                if Drawing.DrawSplashRange then
                        DrawCircle(ts.target.x, ts.target.y, ts.target.z, R.SplashRange, 0x0066A3FF)
                end
        end
       
        if Drawing.DrawSkillRange then
                if Q.Range == W.Range and W.Range == E.Range then
                        DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, Q.Range, 0x000066FF)
                elseif Q.Range == E.Range or W.Range == E.Range then
                        DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, Q.Range, 0x000066FF)
                        DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, W.Range, 0x000066FF)
                elseif Q.Range == W.Range then
                        DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, Q.Range, 0x000066FF)
                        DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, E.Range, 0x000066FF)
                else
                        DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, Q.Range, 0x000066FF)
                        DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, W.Range, 0x000066FF)
                        DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, E.Range, 0x000066FF)
                end
        end
       
        if Drawing.DrawAARange then
                DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, myHero.range + GetDistance(myHero.minBBox)-10, 0x000066FF)
        end
end