<?php exit() ?>--by ferrarino1com 88.8.51.176
_G.Packet.definition.R_WAYPOINT = {
                init = function()
                    return {
                        additionalInfo = {0, 0, 0, 0, 0, 0},
                        sequenceNumber = 0,
                        networkId = 0,
                        wayPoints = {}
                    }
                end,

                decode = function(p)
                    p.pos = 1
                    local packetResult = {
                        additionalInfo = {},
                        networkId = p:DecodeF(),
                        wayPoints = {}
                    }
                    p.pos = p.pos + 6 + p:Decode2()
                    packetResult.additionalNetworkId = p:DecodeF()
                    --Greedy search through the rest of the packet (usually pretty small anyways)
                    p.pos = p:Decode1()==0 and p.pos+14 or p.pos+12
                    
                    for i = p.size, p.pos, -1 do
                    	p.pos = i
                    	if p:DecodeF() == packetResult.networkId then
                    		print("yep"..p.pos)
                    		local pos = p.pos
                    		p.pos = pos - 9
                    		packetResult.sequenceNumber = p:Decode4()
                    		packetResult.waypointCount = p:Decode1()/2
                    		p.pos = p.pos + 4
                    		packetResult.wayPoints = Packet.decodeWayPoints(p,packetResult.waypointCount)
                    		break
                    	end
                    end

                    return packetResult
                end,

                encode = function(packet)
                    p = CLoLPacket(Packet.headers.R_WAYPOINT)

                    p:EncodeF(packet.values.networkId)
                    p:Encode2(#packet.values.additionalInfo - 6)

                    for k, v in ipairs(packet.values.additionalInfo) do
                        p:Encode1(v)
                    end

                    p:Encode1(3)
                    p:Encode4(packet.values.sequenceNumber)
                    p:EncodeF(packet.values.wayPoints[1].x)
                    p:EncodeF(packet.values.wayPoints[1].y)
                    p:EncodeF(packet.values.wayPoints[2].x)
                    p:EncodeF(packet.values.wayPoints[2].y)

                    return p
                end
            }