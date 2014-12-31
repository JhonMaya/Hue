<?php exit() ?>--by uglyoldguy 68.48.159.9
local RecallTable = {}
local RecallTypes = {}
local Spawn
local SpellData = {}
local BlockedMovement = false
local LastDestination = nil
local TimerCollision = nil
local TimerRecDet = nil
_G.SpawnKill = nil
local init = false


function OnLoad()
    Spawn = GetEnemySpawnPos()
    RecallTypes = {[1] = 8000, [8] = 4500, [10] = 8000}
    Menu()
    init = true
    print("<font color='#FF4000'> SURPRISE RECALL PRINTER</font>")
end

function Menu()
    Config = scriptConfig("Printer", "Printer")
    Config:addParam("Print", "Print back messages in chat", SCRIPT_PARAM_ONOFF, true)
end

function OnRecvPacket(p)
    if p.header == 0xD7 then
        p.pos = 5
        local __source = p:DecodeF()
        p.pos = 112
        local __type = p:Decode1()

        if IsAlly(__source) or myHero.networkID == __source then return end
        --RecallPlayer = objManager:GetObjectByNetworkId(__source)
        local Unit = GetEnemyUnit(__source)
        if RecallTable[__source] and __type == 4 then
            RecallTable[__source] = nil
        elseif RecallTable[__source] == nil and __type == 6 then
            RecallTable[__source] = {source = __source, time = GetTickCount(), finish = GetTickCount() + RecallTypes[GetGame().map.index], rTime = RecallTypes[GetGame().map.index]}
            if TimerRecDet == nil and Config.Print then
                    TimerRecDet = os.time()
                    print("<font color='#04B431'>Enemy </font><font color='#FF4000'>" .. Unit.charName .. "</font> <font color='#04B431'>started Recall with </font><font color='#FF4000'>" .. math.round(Unit.health,0) .. " HP. </font>")
            elseif  os.difftime(os.time(),TimerRecDet) >= 1 and Config.Print then
                    print("<font color='#04B431'>Enemy </font><font color='#FF4000'>" .. Unit.charName .. "</font> <font color='#04B431'>started Recall with </font><font color='#FF4000'>" .. math.round(Unit.health,0) .. " HP. </font>")
                    TimerRecDet = nil
            end
        end
    end
end

function IsAlly(nId)
    for _, Ally in pairs(GetAllyHeroes()) do
        if Ally.networkID == nId then
            return true
        end
    end
    return false
end

function GetEnemyUnit(nId)
    for _, Enemy in pairs(GetEnemyHeroes()) do
        if Enemy.networkID == nId then
            return Enemy
        end
    end
end