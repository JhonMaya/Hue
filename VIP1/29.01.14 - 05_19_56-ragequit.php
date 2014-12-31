<?php exit() ?>--by ragequit 71.207.146.17
function OnLoad()
	PrintChat("Nyans Correct S_MOVE Differentiation LOADED!")
end
	
function OnSendPacket(p)
	local packetnormalized = nil
	local packettype = nil
	packetype = 0
	packetnormal = nil
    if p.header == 0x71 then
    	p.pos = 2
       	result = {
        dwArg1 = p.dwArg1,
        dwArg2 = p.dwArg2,
		type = p:DecodeF(),
        sourceNetworkId = p:DecodeF(),
        spellId = p:Decode1(),
        fromX = p:DecodeF(),
        fromY = p:DecodeF(),
        toX = p:DecodeF(),
        toY = p:DecodeF(),
        targetNetworkId = p:DecodeF()
        }
		packettype = tostring(result.type)
		packettype = packettype:gsub('%-','')
		packettype = packettype:gsub('%e','')
		packetnormalized = string.format("%.2f", packettype)
		packetnormalized = tonumber(packetnormalized)
		if packetnormalized == 1.41 then PrintChat("We sent a normal move command") end
		if packetnormalized == 1.44 then PrintChat("We sent an attack command with no target") end
		if packetnormalized == 5.64 then PrintChat("We send an attack at someone!") end
    end	
end