<?php exit() ?>--by GKraft 178.27.43.185
class 'Movement' -- {
    -- PRIVATE METHODS --------------------------------------------------------

    local simulatedMovement = nil

    local visitedWaypoints = 0

    local myLastReceivedPath = nil

    local myLastSentPath = nil

    local automaticMovement = false

    local function ExtractMovementPathFromPacket(rawPacket)
        if rawPacket.header == Packet.headers.S_MOVE then
            local wayPoints = Packet(rawPacket):get('wayPoints')

            if wayPoints[1] == wayPoints[#wayPoints] then
                simulatedMovement = nil
            else
                simulatedMovement = Path(table.unpack(wayPoints))
            end

            rawPacket:Block()
            rawPacket:Hide()
        end
    end

    local function ReceivePacket(rawPacket)
        if rawPacket.header == Packet.headers.R_WAYPOINTS then
            local decodedPacket = Packet(rawPacket)
            local wayPoints = decodedPacket:get('wayPoints')[myHero.networkID]

            if wayPoints then
                myLastReceivedPath = {
                    time = GetInGameTimer() - GetLatency() / 2000,
                    path = Path(table.unpack(wayPoints))
                }
            end
        end
    end

    local function SendPacket(rawPacket)
        if rawPacket.header == Packet.headers.S_MOVE then
            local wayPoints = Packet(rawPacket):get('wayPoints')

            if wayPoints then
                if not automaticMovement then
                    _G.Movement.currentFollowPath = nil
                else
                    rawPacket:Hide()
                end

                myLastSentPath = {
                    time = GetInGameTimer(),
                    path = Path(table.unpack(wayPoints))
                }
            end
        end
    end

    local function FollowPathTick()
        if _G.Movement.currentFollowPath and #_G.Movement.currentFollowPath.points >= 2 then
            local heroPos = Point(myHero.x, myHero.z)

            if visitedWaypoints == 0 then
                local wayPoint = _G.Movement.currentFollowPath.points[visitedWaypoints + 1]

                automaticMovement = true
                myHero:MoveTo(wayPoint.x, wayPoint.y)
                automaticMovement = false

                visitedWaypoints = visitedWaypoints + 1
            end

            if _G.Movement.currentFollowPath.points[visitedWaypoints + 1] then
                if Movement:GetArrivalTime() <= (GetLatency() / 1000) then
                    automaticMovement = true
                    myHero:MoveTo(_G.Movement.currentFollowPath.points[visitedWaypoints + 1].x, _G.Movement.currentFollowPath.points[visitedWaypoints + 1].y)
                    automaticMovement = false

                    visitedWaypoints = visitedWaypoints + 1
                end
            end
        end
    end

    -- PUBLIC METHODS ---------------------------------------------------------

    function Movement:__init()
        AddRecvPacketCallback(ReceivePacket)
        AddSendPacketCallback(SendPacket)
        AddTickCallback(FollowPathTick)
    end

    function Movement:GetLastReceivedPath()
        if myLastReceivedPath then
            return myLastReceivedPath.path, myLastReceivedPath.time
        else
            return nil, 0
        end
    end

    function Movement:GetLastSentPath()
        if myLastSentPath then
            return myLastSentPath.path, myLastSentPath.time
        else
            return nil, 0
        end
    end

    function Movement:GetCurrentPath()
        local lastReceivedPath, lastReceivedTime = self:GetLastReceivedPath()
        local lastSentPath, lastSentTime = self:GetLastSentPath()
        local path = lastReceivedPath and (
            lastSentPath and (
                (lastReceivedTime > lastSentTime) and lastReceivedPath or lastSentPath
            ) or nil
        ) or lastSentPath
        local heroPos = Point(myHero.x, myHero.z)

        if path then
            local closestDistance = math.huge
            local closestSegmentNumber = 1
            local lineSegments = path:getLineSegments()

            for segmentNumber, lineSegment in ipairs(lineSegments) do
                local currentDistance = lineSegment:distance(heroPos)

                if currentDistance < closestDistance then
                    closestDistance = currentDistance
                    closestSegmentNumber = segmentNumber
                end
            end

            local pathPoints = {heroPos}
            for i = closestSegmentNumber + 1, #path.points do
                pathPoints[i - closestSegmentNumber + 1] = path.points[i]
            end

            return Path(table.unpack(pathPoints))
        else
            return heroPos
        end
    end

    function Movement:GetArrivalTime()
        local currentPath = self:GetCurrentPath()

        return currentPath:__type() ~= "Point" and math.max((currentPath:len() - 30) / myHero.ms - GetLatency() / 2000, 0) or 0
    end

    function Movement:FollowPath(path)
        self.currentFollowPath = path

        visitedWaypoints = 0
    end

    function Movement:CalculatePath(destination)
        if destination then
            if destination:__type() == 'Point' then
                AddSendPacketCallback1(ExtractMovementPathFromPacket)
                myHero:MoveTo(destination.x, destination.y)
                RemoveSendPacketCallback(ExtractMovementPathFromPacket)

                return simulatedMovement
            elseif destination:__type() == 'Path' then
                if #destination.points == 1 then
                    return self:CalculatePath(destination.points[1])
                elseif #destination.points > 1 then
                    local result = nil

                    for i = 1, #destination.points - 1 do
                        ClientSide:MoveUnit(myHero, Point(destination.points[i].x, destination.points[i].y))

                        local pathResult = self:CalculatePath(destination.points[i + 1])

                        if pathResult then
                            if result then
                                result = result + pathResult
                            else
                                result = pathResult
                            end
                        end
                    end

                    ClientSide:MoveUnit(myHero, Movement:GetCurrentPath())

                    return result
                end
            end
        end
    end
-- }

_G.Movement = Movement()