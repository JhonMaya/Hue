<?php exit() ?>--by uglyoldguy 68.48.159.9
if (myHero.charName ~= "Jinx" and myHero.charName ~= "Ezreal" and myHero.charName ~= "Draven" and myHero.charName ~= "Ashe" and myHero.charName ~= "Lux") then return end
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
    if myHero.charName == "Ashe" then
        SpellData = {Speed = 1600, Delay = 0.125, Width = 130}
    elseif myHero.charName == "Ezreal" then
        SpellData = {Speed = 2000, Delay = 1.0, Width = 160}
    elseif myHero.charName == "Draven" then
        SpellData = {Speed = 2000, Delay = 0.4, Width = 160}
    elseif myHero.charName == "Jinx" then
        SpellData = {Delay = 0.5, Width = 150}
    elseif myHero.charName == "Lux" then
        SpellData = {Delay = 1.35, Speed = math.huge, Width = 190}
    end
    init = true
    print("<font color='#FF4000'>FREE SURPRISE BASEULT v0.02 by dienofail loaded</font>")
end

function Menu()
    Config = scriptConfig("Baseult", "Baseult")
    Config:addParam("On", "Use Baseult when possible", SCRIPT_PARAM_ONOFF, true)
end

function OnTick()
    if init and Config.On then
        if _G.SpawnKill ~= nil and GetTickCount() > _G.SpawnKill + SpellData.Delay*1000 + GetLatency() then
            _G.SpawnKill = nil
        end
        for _, Enemy in pairs(RecallTable) do
            Enemy.rTime = Enemy.rTime - (GetTickCount() - Enemy.time)
            Enemy.time = GetTickCount()
            local Unit = GetEnemyUnit(Enemy.source)
            local TravelTime = GetTravelTime()
                    
            if TravelTime >= Enemy.rTime and TravelTime < Enemy.rTime + 20 and (getDmg("R", Unit, myHero)+(Unit.health*.20)) >= Unit.health then
                if myHero.charName == "Lux" and GetDistance(Spawn) < 3350 then
                    _G.SpawnKill = GetTickCount()
                    CastSpell(_R, Spawn.x, Spawn.z)
                elseif myHero.charName ~= "Lux" then
                    _G.SpawnKill = GetTickCount()
                    CastSpell(_R, Spawn.x, Spawn.z)
                end
            end
        end
        RestoreMovement()
    end
end

function OnRecvPacket(p)
    if p.header == 0xD7 or p.header == 0xD8 or p.header == 0xD9 then
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
            if TimerRecDet == nil then
                    TimerRecDet = os.time()
                    --print("<font color='#04B431'>Enemy </font><font color='#FF4000'>" .. Unit.charName .. "</font> <font color='#04B431'>started Recall with </font><font color='#FF4000'>" .. math.round(Unit.health,0) .. " HP. </font>")
            elseif  os.difftime(os.time(),TimerRecDet) >= 1 and Config.Print then
                    --print("<font color='#04B431'>Enemy </font><font color='#FF4000'>" .. Unit.charName .. "</font> <font color='#04B431'>started Recall with </font><font color='#FF4000'>" .. math.round(Unit.health,0) .. " HP. </font>")
                    TimerRecDet = nil
            end
        end
    end
end

function OnSendPacket(p)
    local packet = Packet(p)

    if _G.SpawnKill ~= nil and packet:get('name') == 'S_MOVE' then
        if packet:get('sourceNetworkId') == myHero.networkID then
            lastDestination = Point(packet:get('x'), packet:get('y'))
            packet:block()
        end
    end
end

function RestoreMovement()
    if _G.SpawnKill == nil and LastDestination ~= nil then
        myHero:MoveTo(LastDestination.x, LastDestination.y)
        LastDestination = nil
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

function GetTravelTime()
    return GetDistance(Spawn) / (GetSpeed()/1000) + SpellData.Delay*1000 + GetLatency()
end

function GetSpeed()
    if myHero.charName == "Jinx" then
        local Distance = GetDistance(Spawn)
        return (Distance > 1350 and (1350*1700+((Distance-1350)*2200))/Distance or 1700)
    else
        return SpellData.Speed
    end
end