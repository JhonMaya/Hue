<?php exit() ?>--by Manciuszz 141.101.99.190
if myHero.charName ~= "Ezreal" and myHero.charName ~= "Ashe" and myHero.charName ~= "Draven" and myHero.charName ~= "Jinx" then return end

local SnipersConfig, EzrealArcaneShift, DravenStandAside
local BaseSpots = {}
local ultiProjectileSpeed, castDelay = 0, 0
local aim = myHero.team == TEAM_BLUE and 2 or 1

local enemyTable = GetEnemyHeroes()

local RecallEndTime = {}
local RecallState = {}
local defaultRecallDuration
local channelTable = {Recall = 8000, RecallImproved = 7000, OdinRecall = 4500, OdinRecallImproved = 4000}

local BlitzCrank, Thresh, Leona
local grab = nil

for i, enemy in ipairs(enemyTable) do
    BlitzCrank = enemy.charName == "Blitzcrank" and enemy or nil
    Thresh = enemy.charName == "Thresh" and enemy or nil
    Leona = enemy.charName == "Leona" and enemy or nil
    RecallEndTime[i] = 0
    RecallState[i] = 0
end
local enemyInGameCount = #enemyTable

function OnLoad()
    local GameMap = GetGame().map
    if GameMap.name == "Summoner's Rift" then
        --SummonersRift
        BaseSpots = {
            -- Recall-to-Base Locations
            { x = 28.58,    y = 184.62, z = 267.16  }, -- BOT LEFT BASE
            { x = 13936.64, y = 184.97, z = 14174.86}  -- TOP RIGHT BASE
        }
        defaultRecallDuration = 8000
    elseif GameMap.name == "The Crystal Scar" then
        --Dominion
        BaseSpots = {
            -- Recall-to-Base Locations
            { x = 514.287109375,  y = -35.081577301025, z = 4149.9916992188  }, -- LEFT BASE
            { x = 13311.96484375, y = -37.369071960449, z = 4161.232421875   }  -- RIGHT BASE
        }
        defaultRecallDuration = 4500
    end

    local championsArray = {
        Ezreal = { projSpeed = 2,   castDelay = 1000},
        Ashe   = { projSpeed = 1.6, castDelay = 250 },
        Draven = { projSpeed = 2,   castDelay = 500 },
        Jinx   = { projSpeed = 2.2, castDelay = 600 }
        -- Jinx Ult Starts with a speed of 1700 unit/s until it passes 1350 unit zone away from myHero, then it goes full speed which is 2200 unit/s until collision or out of map(25000 units)
    }

    if championsArray[myHero.charName] ~= nil then
        ultiProjectileSpeed = championsArray[myHero.charName].projSpeed
        castDelay 		    = championsArray[myHero.charName].castDelay
    end

    --AllClass Menu
    SnipersConfig = scriptConfig("Swag Shot", "autoUlti")
    SnipersConfig:addParam("autoUlt", "Automatic Ultimate: ", SCRIPT_PARAM_ONOFF, true)
    SnipersConfig:addParam("bypassHealthCheck", "Shoot without HealthCheck: ", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("M"))
    SnipersConfig:addParam("displayTravelTime", "Show Travel-Time: ", SCRIPT_PARAM_ONOFF, false)
    SnipersConfig:addParam("blankSPACE", "              FOCUS ONLY TARGETS ", SCRIPT_PARAM_INFO, "")
--    SnipersConfig:addParam("drawCircles", "Draw Circles", SCRIPT_PARAM_ONOFF, true)
    SnipersConfig:permaShow("bypassHealthCheck")

    for i, enemy in ipairs(enemyTable) do
        SnipersConfig:addParam("enableR"..i, "Enable sniping on "..enemy.charName, SCRIPT_PARAM_ONOFF, true)
        SnipersConfig["enableR"..i] = true
    end

    --Override OnLoad() commands
    SnipersConfig.bypassHealthCheck = false

    if BlitzCrank ~= nil or Thresh ~= nil or Leona ~= nil then
        if myHero.charName == "Ezreal" then
            EzrealArcaneShift = scriptConfig("Ezreal Auto-E", "autoE")
            if BlitzCrank ~= nil then EzrealArcaneShift:addParam("cancelBlitzGrabs",  "Jump-out Blitzcrank: ", SCRIPT_PARAM_ONOFF, false)
            elseif Thresh ~= nil then EzrealArcaneShift:addParam("cancelThreshGrabs", "Jump-out Thresh: ", SCRIPT_PARAM_ONOFF, false)
            elseif Leona ~= nil then  EzrealArcaneShift:addParam("cancelLeonaGrabs",  "Jump-out Leona: ", SCRIPT_PARAM_ONOFF, false)
            end
        elseif myHero.charName == "Draven" then
            DravenStandAside = scriptConfig("Draven Auto-E", "autoE")
            if BlitzCrank ~= nil then DravenStandAside:addParam("cancelBlitzGrabs",  "Cancel-out Blitzcrank Q: ", SCRIPT_PARAM_ONOFF, false)
            elseif Thresh ~= nil then DravenStandAside:addParam("cancelThreshGrabs", "Cancel-out Thresh Q: ", SCRIPT_PARAM_ONOFF, false)
            elseif Leona ~= nil then  DravenStandAside:addParam("cancelLeonaGrabs",  "Cancel-out Leona E: ", SCRIPT_PARAM_ONOFF, false)
            end
        end
    end

    print(" >> ".. myHero.charName .." Swag Shot")
end

if BlitzCrank ~= nil or Thresh ~= nil or Leona ~= nil then
    function OnCreateObj(object)
        if BlitzCrank ~= nil and object.name == "FistGrab_mis.troy" then grab = object end
        if Thresh ~= nil and object.name == "Thresh_Q_whip_beam.troy" then grab = object end
        if Leona ~= nil and object.name == "Leona_ZenithBlade_mis.troy" then grab = object end
    end

    function OnDeleteObj(object)
        if BlitzCrank ~= nil and object.name == "FistGrab_mis.troy" then grab = nil end
        if Thresh ~= nil and object.name == "Thresh_Q_whip_beam.troy" then grab = nil end
        if Leona ~= nil and object.name == "Leona_ZenithBlade_mis.troy" then grab = nil end
    end
end

function OnRecvPacket(packet)
    if packet.header == 0xD8 then
        packet.pos = 5
        local sourceNetworkId = packet:DecodeF()
        packet.pos = 24
        local number1 = packet:Decode2()
        packet.pos = 27
        local number2 = packet:Decode1()

        for i, enemy in ipairs(enemyTable) do
            if enemy.networkID == sourceNetworkId then
                if SnipersConfig["enableR"..i] then
                    if myHero:GetSpellData(_R).level > 0 and myHero:CanUseSpell(_R) == READY then
                        local rDamage = (myHero.charName == "Draven" and getDmg("R", enemy, myHero)*2) or (myHero.charName == "Jinx" and getDmg("R", enemy, myHero, 1)) or getDmg("R", enemy, myHero)
                        print("Target: "..enemy.charName.." UltDmg: "..rDamage.." HP: "..enemy.health)
                        if (enemy.health > 0 and rDamage > (enemy.health + enemy.hpRegen)) or SnipersConfig.bypassHealthCheck then
                            if number2 ~= 0 then
                                packet.pos = 75
                                local number3 = packet:Decode1()
                                if number3 == 82 then
                                    RecallEndTime[i] = GetTickCount() + (channelTable[enemy:GetSpellData(RECALL).name] or defaultRecallDuration)
                                    RecallState[i] = "Recall_Start"
                                elseif number3 == 84 then
--                                    RecallEndTime[i] = GetTickCount() + 4000
                                    RecallState[i] = "Teleport_Start"
                                elseif number3 == 0 then
                                    RecallState[i] = "Teleport_Finish"
                                end
                            elseif number1 < 60000 then
                                RecallState[i] = "Recall_Abort"
                            else
                                RecallState[i] = "Recall_Finish"
                            end
                        end
                    end
                end
            end
        end
    end
end

function OnTick()
    if myHero.dead or not SnipersConfig.autoUlt then return end

    for i=1, enemyInGameCount do
        if RecallState[i] == "Recall_Start" and RecallState[i] ~= "Teleport_Start" then
            local TravelTime = GetTickCount() + castDelay + GetDistance(BaseSpots[aim])/ultiProjectileSpeed + GetLatency()/2
                TravelTime = TravelTime + (myHero.charName == "Jinx" and (-castDelay + (1350/1.7) - GetLatency()*1.5) or 0)
            if TravelTime > RecallEndTime[i] and (TravelTime - GetTickCount()) <= defaultRecallDuration then
                local ultimateName = myHero:GetSpellData(_R).name
                if ultimateName == "DravenRCast" then
                    CastSpell(_R, BaseSpots[aim].x, BaseSpots[aim].z)
                elseif ultimateName == "dravenrdoublecast" then
                    if GetTickCount() > (RecallEndTime[i] - castDelay/2) then
                        CastSpell(_R)
                        RecallState[i] = 0
                    end
                else
                    CastSpell(_R, BaseSpots[aim].x, BaseSpots[aim].z)
                    RecallState[i] = 0
                end
            end
        elseif RecallState[i] == "Recall_Abort" or RecallState[i] == "Recall_Finish" or RecallState[i] == "Teleport_Finish" then
            RecallEndTime[i] = 0
            RecallState[i] = 0 -- stop thinking that the enemy is recalling.
        end
    end
end

--[[function OnCreateObj(obj)
    if obj.name:find("Jinx_R_Mis") then
        ourRocket = obj
    end
end

function OnDeleteObj(obj)
    if obj.name:find("Jinx_R_Mis") then
        ourRocket = nil
    end
end]]

function OnDraw()
    if myHero.dead then return end

    if (EzrealArcaneShift ~= nil or DravenStandAside ~= nil) and grab ~= nil and GetDistance(grab) < 500 then
        local dangerousHook = BlitzCrank ~= nil and BlitzCrank or Thresh ~= nil and Thresh or Leona ~= nil and Leona or nil
        if dangerousHook ~= nil and math.abs((myHero.x - dangerousHook.x)*(grab.z - dangerousHook.z) - (myHero.z - dangerousHook.z)*(grab.x - dangerousHook.x)) < 39000 then
            if EzrealArcaneShift ~= nil and DravenStandAside == nil then
                local destX = myHero.x*4 - dangerousHook.x*3
                local destZ = myHero.z*4 - dangerousHook.z*3
                CastSpell(_E, destX, destZ)
            elseif EzrealArcaneShift == nil and DravenStandAside ~= nil then
                CastSpell(_E, dangerousHook.x, dangerousHook.z)
            end
        end
    end

--[[
    if SnipersConfig.drawCircles then
        for i,BaseSpotz in pairs(BaseSpots) do
            local circleColor = GetDistance(BaseSpotz, mousePos) <= 250 and 0x00FF00 or 0xFFFFFF
            DrawCircle(BaseSpotz.x, BaseSpotz.y, BaseSpotz.z, 28,  circleColor)
            DrawCircle(BaseSpotz.x, BaseSpotz.y, BaseSpotz.z, 29,  circleColor)
            DrawCircle(BaseSpotz.x, BaseSpotz.y, BaseSpotz.z, 30,  circleColor)
            DrawCircle(BaseSpotz.x, BaseSpotz.y, BaseSpotz.z, 31,  circleColor)
            DrawCircle(BaseSpotz.x, BaseSpotz.y, BaseSpotz.z, 32,  circleColor)
            DrawCircle(BaseSpotz.x, BaseSpotz.y, BaseSpotz.z, 100, circleColor)
        end
    end
]]

    --[[if IsKeyPressed(GetKey("J")) then
        CastSpell(_R, BaseSpots[aim].x, BaseSpots[aim].z)
        local latency = GetLatency()/2
        shotFired = GetTickCount() + castDelay - latency

        startSpeed = 1.7
        flightSpeed = 2.2
        TravelTimez = GetTickCount() + castDelay + GetDistance(BaseSpots[aim])/flightSpeed + latency
        TravelTimez = TravelTimez + (myHero.charName == "Jinx" and (-castDelay + (1350/startSpeed) - latency*1.5) or 0)
    end

    if true and shotFired ~= nil then
        if GetTickCount() <= TravelTimez then
            DrawLines3D({myHero, BaseSpots[aim]}, 1, ARGB(255,255,0,0))
            local time = GetTickCount() - shotFired
            local traveledDistance = time * flightSpeed
            poscircle = myHero + (Vector(BaseSpots[aim]) - myHero):normalized()*traveledDistance
            if ourRocket ~= nil then
                print(GetDistance(poscircle, ourRocket))
                DrawCircle(ourRocket.x, ourRocket.y, ourRocket.z, 140, 0xFFFFFF00)
            end
            DrawLines3D({poscircle, BaseSpots[aim]}, 2, ARGB(255,0,255,0))
            DrawCircle(BaseSpots[aim].x, BaseSpots[aim].y, BaseSpots[aim].z, 65, 0xFFFFFF00)
        end
    end]]

    if SnipersConfig.displayTravelTime then
        local Distance = GetTarget() ~= nil and GetDistance(GetTarget()) or GetDistance(mousePos)
        local TravelTime = castDelay + math.floor(Distance) / ultiProjectileSpeed + GetLatency()/2
        DrawText("Time: "..TravelTime.. "ms", 16, GetCursorPos().x, GetCursorPos().y + 50, 0xFF00FF00)
        DrawCircle(mousePos.x, mousePos.y, mousePos.z, 50, 0xFFFFFF00)
    end

end
