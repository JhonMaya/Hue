<?php exit() ?>--by GKraft 178.27.43.185
--[[
    ClientSide 1.2 by Husky
    ========================================================================

    This library povides a way to trigger client side events manually.

    Since these kind of events usually have an effect on the drawing of
    objects on your screen, you can customize the visualization of objects
    (i.e. show invisible objects and so on).

    Hint: These effects are completely client side, so nobody else but you can
    notice the changes.


    Methods
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    -- makes a unit look like it's moving somewhere (destination: <Point or Path>)
    ClientSide:MoveUnit(unit, destination)

    -- updates the duration of time based unit (grey bar above the unit)
    ClientSide:UpdateDuration(unit, durationInMs)

    -- makes the unit look like it's focusing you (orange outline around that unit)
    ClientSide:GainAggro(unit)

    -- makes the unit look like it's not focusing you (no outline around that unit)
    ClientSide:LoseAggro(unit)

    -- makes the tower look like it's focusing a unit (red laser pointer to unit)
    ClientSide:TowerFocus(tower, unit)

    -- makes the tower look like it's not focusing a unit (no laser pointer)
    ClientSide:TowerIdle(tower)

    -- makes your client think it has vision on unit (healthbars visible)
    ClientSide:GainVision(unit)

    -- makes your client think it has no vision on unit (healthbars invisible)
    ClientSide:LoseVision(unit)

    -- makes your client hide a visible unit (unit is invisible on map and minimap)
    ClientSide:HideUnit(unit)

    -- makes your client show an invisible unit (unit is visible on map and minimap)
    ClientSide:ShowUnit(unit)

    -- makes your client show a netural camp as cleared (no icon on minimap)
    ClientSide:ClearNeutralCamp(campId)


    Changelog
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    1.0     - initial release with the most important features

    1.1     - added ClientSide:ClearNeutralCamp(campId)

    1.2     - added ClientSide:MoveUnit(unit, destination)
]]

class 'ClientSide' -- {
    function ClientSide:__init()
        self.lastUpdateSequenceNumber = 0

        AddRecvPacketCallback(function(p)
            if p.header == Packet.headers.R_UPDATE then
                p.pos = 5

                self.lastUpdateSequenceNumber = p:Decode4()
            end
        end)
    end

    function ClientSide:MoveUnit(unit, destination)
        Packet('R_WAYPOINTS', {wayPoints = {[unit.networkID] = destination.points}}):receive()
    end

    function ClientSide:UpdateDuration(unit, duration)
        DelayAction(function(unit, duration)
            if unit and unit.valid then
                p = CLoLPacket(Packet.headers.R_UPDATE)

                p:Encode4(0)
                p:Encode4(self.lastUpdateSequenceNumber + 1)
                p:Encode1(1)
                p:Encode1(0x02)
                p:EncodeF(unit.networkID)
                p:Encode4(64)
                p:Encode1(4)
                p:EncodeF(duration)

                RecvPacket(p)
            end
        end, 0, {unit, duration})

        return true
    end

    function ClientSide:GainAggro(unit)
        DelayAction(function(unit)
            if unit and unit.valid then
                Packet('PKT_S2C_Aggro', {
                    sourceNetworkId = unit.networkID,
                    targetNetworkId = myHero.networkID
                }):receive()
            end
        end, 0, {unit})

        return true
    end

    function ClientSide:LoseAggro(unit)
        DelayAction(function(unit)
            if unit and unit.valid then
                Packet('PKT_S2C_Aggro', {
                    sourceNetworkId = unit.networkID,
                    targetNetworkId = 0
                }):receive()
            end
        end, 0, {unit})

        return true
    end

    function ClientSide:TowerFocus(tower, unit)
        DelayAction(function(tower, unit)
            if tower and tower.valid and unit and unit.valid then
                Packet('PKT_S2C_TowerAggro', {
                    sourceNetworkId = tower.networkID,
                    targetNetworkId = unit.networkID
                }):receive()
            end
        end, 0, {tower, unit})

        return true
    end

    function ClientSide:TowerIdle(tower)
        DelayAction(function(tower)
            if tower and tower.valid then
                Packet('PKT_S2C_TowerAggro', {
                    sourceNetworkId = tower.networkID,
                    targetNetworkId = 0
                }):receive()
            end
        end, 0, {tower})

        return true
    end

    function ClientSide:GainVision(unit)
        DelayAction(function(unit)
            if unit and unit.valid then
                Packet('PKT_S2C_GainVision', {
                    networkId = unit.networkID
                }):receive()
            end
        end, 0, {unit})

        return true
    end

    function ClientSide:LoseVision(unit)
        DelayAction(function(unit)
            if unit and unit.valid then
                Packet('PKT_S2C_LoseVision', {
                    networkId = unit.networkID
                }):receive()
            end
        end, 0, {unit})

        return true
    end

    function ClientSide:HideUnit(unit)
        DelayAction(function(unit)
            if unit and unit.valid then
                Packet('PKT_S2C_HideUnit', {
                    networkId = unit.networkID
                }):receive()
            end
        end, 0, {unit})

        return true
    end

    function ClientSide:ShowUnit(unit)
        DelayAction(function(unit)
            if unit and unit.valid then
                Packet('R_WAYPOINT', {
                    networkId = unit.networkID,
                    wayPoints = {
                        Point(unit.pos.x, unit.pos.z),
                        Point(unit.pos.x, unit.pos.z)
                    }
                }):receive()
            end
        end, 0, {unit})

        return true
    end

    function ClientSide:ClearNeutralCamp(campId)
        DelayAction(function(campId)
            Packet('PKT_S2C_Neutral_Camp_Empty', {campId = campId}):receive()
        end, 0, {campId})

        return true
    end
--}

_G.ClientSide = ClientSide()